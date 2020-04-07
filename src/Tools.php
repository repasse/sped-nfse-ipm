<?php

namespace NFePHP\NFSeIPM;

/**
 * Class for comunications with NFSe webserver in Nacional Standard
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

use NFePHP\NFSeIPM\Common\Tools as BaseTools;
use NFePHP\NFSeIPM\RpsInterface;
use NFePHP\NFSeIPM\Common\Signer;
use NFePHP\Common\Certificate;
use NFePHP\Common\Validator;
use stdClass;
use DateTime;

class Tools extends BaseTools
{

    protected $xsdpath;

    public function __construct($config, Certificate $cert = null)
    {
        parent::__construct($config, $cert);
        $path = realpath(__DIR__ . '/../storage/schemes');
        $this->xsdpath = $path;
    }

    /**
     * Consulta de NFSe
     * @param string $codigo
     * @param integer $numero
     * @param integer $serie
     * @param string $cadastro
     * @return string
     */
    public function consultar(
        $codigo = null,
        $numero = null,
        $serie = null,
        $cadastro = null
    ) {
        $operation = 'consultar';
        $content = "<nfse><pesquisa>";
        if (!empty($codigo)) {
            $content .= "<codigo_autenticidade>$codigo</codigo_autenticidade>";
        } elseif (!empty($numero) && !empty($serie) && !empty($cadastro)) {
            $content .= "<numero>$numero</numero>"
               . "<serie>$serie</serie>"
               . "<cadastro>$cadastro</cadastro>";
        }
        $content .= "</pesquisa></nfse>";
        return $this->send($content, $operation);
    }

    /**
     * Envio de RPS/NFSe e também usado para cancelamento
     * @param RpsInterface $rps
     * @return string
     */
    public function enviar(RpsInterface $rps): string
    {
        $operation = 'enviar';
        if ($this->config->tpamb > 1 && $this->config->tpamb < 3) {
            $rps->teste(true); //marca xml como teste não será considerado pelo servidor
        }
        $rps->config($this->config);
        $content = $rps->render();
        //assina se necessário
        if ($this->wsobj->sign) {
            $rps->sign($this->wsobj->sign);
            $content = Signer::sign(
                $this->certificate,
                $content
            );
        }
        return $this->send($content, $operation);
    }
}