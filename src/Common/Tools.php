<?php

namespace NFePHP\NFSeIPM\Common;

/**
 * Auxiar Tools Class for comunications with NFSe webserver in Nacional Standard
 *
 * @category  NFePHP
 * @package   NFePHP\NFSeIPM
 * @copyright NFePHP Copyright (c) 2008-2018
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse-ipm for the canonical source repository
 */

use NFePHP\Common\Certificate;

class Tools
{
    public $lastRequest;
    public $curlerror;
    public $curlinfo;

    protected $config;
    protected $prestador;
    protected $certificate;
    protected $wsobj;
    protected $soap;
    protected $environment;
    protected $filepath;
    protected $storage;
    protected $url = 'http://sync.nfs-e.net/datacenter/include/nfw/importa_nfw/nfw_import_upload.php?eletron=1';


    /**
     * Constructor
     * @param string $config
     * @param Certificate $cert
     */
    public function __construct($config, Certificate $cert = null)
    {
        $this->config = json_decode($config);
        $this->certificate = $cert;
        $this->wsobj = $this->loadWsobj($this->config->cmun);
        $this->config->tom = $this->wsobj->tom;
        $this->environment = 'homologacao';
        if ($this->config->tpamb === 1) {
            $this->environment = 'producao';
        }
    }

    /**
     * Remove temporary message file
     */
    public function __destruct()
    {
        if (is_file($this->filepath)) {
            unlink($this->filepath);
        }
    }

    /**
     * load webservice parameters
     * @param string $cmun
     * @return object
     * @throws \Exception
     */
    protected function loadWsobj($cmun)
    {
        $path = realpath(__DIR__ . "/../../storage/urls_webservices.json");
        $urls = json_decode(file_get_contents($path), true);
        if (empty($urls[$cmun])) {
            throw new \Exception("Não localizado parâmetros para esse municipio.");
        }
        return (object)$urls[$cmun];
    }

    /**
     * Send message to webservice
     * @param string $message
     * @param string $operation
     * @return string XML response from webservice
     */
    public function send($message, $operation)
    {
        $this->lastRequest = $message;
        if ($this->config->tpamb !== 3) {
            $response = $this->upload($message);
        } else {
            //return $message;
            $response = $this->fakeResponse($message, $operation);
        }
        return $response;
    }

    /**
     * Upload message to URL
     * @param string $message
     * @return string
     * @throws \Exception
     */
    protected function upload($message)
    {
        try {
            $this->filepath = $this->saveMessage($message);
            if (function_exists('curl_file_create')) {
                $cFile = curl_file_create($this->filepath);
            } else {
                $cFile = '@' . realpath($this->filepath);
            }
            $fields = [
                'login' => $this->config->cnpj,
                'senha' => $this->config->senha,
                'cidade' => $this->wsobj->tom,
                'f1' => $cFile //Alterado
            ];
            $userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';

            $oCurl = curl_init();
            curl_setopt($oCurl, CURLOPT_URL, $this->url);
            curl_setopt($oCurl, CURLOPT_USERAGENT, $userAgent);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($oCurl, CURLOPT_POST, true);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($oCurl, CURLOPT_TIMEOUT, 20);

            $response = curl_exec($oCurl);
            $curlerror = curl_error($oCurl);
            $ainfo = curl_getinfo($oCurl);
            if (is_array($ainfo)) {
                $this->curlinfo = $ainfo;
            }
            $headsize = curl_getinfo($oCurl, CURLINFO_HEADER_SIZE);
            $httpcode = curl_getinfo($oCurl, CURLINFO_HTTP_CODE);
            $responseHead = trim(substr($response, 0, $headsize));
            curl_close($oCurl);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        if ($curlerror != '') {
            throw new \Exception($curlerror . " [{$this->url}]");
        }
        if ($httpcode != 200) {
            throw new \Exception(
                " [{$this->url}] HTTP Error code: $httpcode - "
                . $responseHead
            );
        }
        return $response;
    }

    /**
     * Response for FAKE
     * @return string
     */
    protected function fakeResponse($message, $operation)
    {
        $message = htmlentities($message);
        $resp = [
            'url' => $this->url,
            'operation' => $operation,
            'body' => "{$message}"
        ];
        return json_encode($resp);
    }

    /**
     * Creates a temporary file for upload
     * @param string $message
     * @return string
     */
    protected function saveMessage($message)
    {
        $filename = "NFSe_" . date('ymdHis').".xml";
        $path = "{$this->storage}/{$this->config->cnpj}";
        if (!is_dir($path)) {
            mkdir($path, '0777');
        }
        $filepath = "$path/$filename";
        file_put_contents($filepath, $message);
        return $filepath;
    }
}
