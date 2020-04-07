<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\NFSeIPM\Tools;
use NFePHP\NFSeIPM\Common\FakePretty;

try {

    $config = [
        'cnpj'  => '99999999000191',
        'senha' => 'secreta',
        'im'    => '1733160024',
        'cmun'  => '4202909', //ira determinar as urls e outros dados
        'razao' => 'Empresa Test Ltda',
        'tpamb' => 3 //1-producao, 2-homologacao, 3-Fake
    ];

    $configJson = json_encode($config);

    $content = file_get_contents('expired_certificate.pfx');
    $password = 'associacao';
    $cert = Certificate::readPfx($content, $password);

    $tools = new Tools($configJson, $cert);

    $codigo = '01234567890123'; //codigo de autenticidade
    $numero = null; //'123'; //numero da NFSe
    $serie = null; //'1'; //serie da NFSe
    $cadastro = null; //'1733160024'; //numero do cadastro do contribuinte

    $response = $tools->consultar($codigo, $numero, $serie, $cadastro);

    echo FakePretty::prettyPrint($response, '');

} catch (\Exception $e) {
    echo $e->getMessage();
}