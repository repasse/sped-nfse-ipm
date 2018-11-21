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
use NFePHP\Common\DOMImproved as Dom;
use NFePHP\NFSeIPM\RpsInterface;
use NFePHP\NFSeIPM\Common\Signer;
use NFePHP\NFSeIPM\Common\Soap\SoapInterface;
use NFePHP\NFSeIPM\Common\Soap\SoapCurl;

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

    protected $urls = [
        '4202909' => [
            'municipio' => 'Brusque',
            'uf' => 'SC',
            'tom' => '8055',
            'sign' => true
        ]
    ];
    
    /**
     * Constructor
     * @param string $config
     * @param Certificate $cert
     */
    public function __construct($config, Certificate $cert = null)
    {
        $this->config = json_decode($config);
        $this->certificate = $cert;
        $wsobj = $this->urls;
        if (empty($this->urls[$this->config->cmun])) {
            throw new \Exception(
                "Esse codigo [{$this->config->cmun}] não pertence a nenhum "
                . "municipio cadastrado para esse modelo."
            );
        }
        $this->wsobj = json_decode(json_encode($this->urls[$this->config->cmun]));
        $this->storage = realpath(__DIR__ . '/../../storage');
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
     * Sign XML passing in content
     * @param string $content
     * @param string $tagname
     * @param string $mark
     * @return string XML signed
     */
    public function sign($content, $tagname, $mark)
    {
        $xml = Signer::sign(
            $this->certificate,
            $content,
            $tagname,
            $mark,
            OPENSSL_ALGO_SHA1,
            [true,true,null,null],
            'nfse'
        );
        $dom = new Dom('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($xml);
        return $dom->saveXML($dom->documentElement);
    }
    
    /**
     * Send message to webservice
     * @param string $message
     * @return string XML response from webservice
     */
    public function send($message)
    {
        if ($this->config->tpamb !== 3) {
            $response = $this->upload($message);
        } else {
            $response = $this->fakeResponse();
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
            $response = '';
            $this->filepath = $this->saveMessage($message);
        
            $fields = [
                'login' => $this->config->cnpj,
                'senha' => $this->config->senha,
                'cidade' => $this->wsobj->tom,
                'file' => '@'. $this->filepath
            ];
            $url = "http://sync.nfs-e.net/datacenter/include/nfw/importa_nfw/nfw_import_upload.php?eletron=1";
            $userAgent = 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0';

            $oCurl = curl_init();
            curl_setopt($oCurl, CURLOPT_URL, $url);
            curl_setopt($oCurl, CURLOPT_USERAGENT, $userAgent);
            curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($oCurl, CURLOPT_POST, true);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($oCurl, CURLOPT_TIMEOUT, 20);
            //curl_setopt($oCurl, CURLOPT_HEADER, false);
            //curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 0);
            //curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);

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
            throw new \Exception($curlerror . " [$url]");
        }
        if ($httpcode != 200) {
            throw new \Exception(
                " [$url] HTTP Error code: $httpcode - "
                . $responseHead
            );
        }
        return $response;
    }
    
    /**
     * Response for FAKE
     * @return string
     */
    protected function fakeResponse()
    {
        $file = realpath(__DIR__ . '/../../storage');
        return file_get_contents($file .'/fake_response.xml');
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
