<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\NFSeIPM\Tools;
use NFePHP\NFSeIPM\Rps;
use NFePHP\NFSeIPM\Common\FakePretty;

try {

    $config = [
        'cnpj'  => '83270397000592',
        'senha' => 'secreta',
        'im'    => '1733160024',
        'cmun'  => '4202909', //ira determinar as urls e outros dados
        'razao' => 'Empresa Test Ltda',
        'tpamb' => 3 //1-producao, 2-homologacao, 3-Fake
    ];

    //NOTA: se 1-produção a NFSe será enviada
    //      se 2-homologação a NFSe será enviada, mas marcada como teste
    //      se 3-Fake NFSe NÃO SERÁ enviada e o XML da solicitação será retornado

    //Consulte a prefeitura para saber se a assinatura é obrigatoria
    //se não for abra um ISSUE no repositório

    $configJson = json_encode($config);

    $content = file_get_contents('expired_certificate.pfx');
    $password = 'associacao';
    $cert = Certificate::readPfx($content, $password);

    $tools = new Tools($configJson, $cert);

    $std = new \stdClass();

    //$std->identificador = '1234'; //opcional

    //consulte a Prefeitura para determinar se o RPS é necessário
    $std->rps = new \stdClass(); //não obrigatorio
    $std->rps->nro_recibo_provisorio = 12; //obrigatorio
    $std->rps->serie_recibo_provisorio = 1; //obrigatorio
    $std->rps->data_emissao_recibo_provisorio = '18/11/2013'; //obrigatorio
    $std->rps->hora_emissao_recibo_provisorio = '13:30:05'; //obrigatorio

    //$std->pedagio = new \stdClass(); //não obrigatorio
    //$std->pedagio->cod_equipamento_automatico = 'ABX253661'; //obrigatorio

    $std->nf = new \stdClass(); //obrigatorio
    //$std->nf->numero = "123456"; //somente preencher em caso de cancelamento
    $std->nf->valor_total = 1.00; //obrigatorio
    $std->nf->valor_desconto = 0.00; //opcional
    $std->nf->valor_ir = 0.00; //opcional
    $std->nf->valor_inss = 0.00; //opcional
    $std->nf->valor_contribuicao_social = 0.00; //opcional
    $std->nf->valor_rps = 1.00; //opcional
    $std->nf->valor_pis = 0.00; //opcional
    $std->nf->valor_cofins = 0.00; //opcional
    $std->nf->observacao = 'PRESTACAO DE SERVICO\r\nTrib Aprox R$ 4,68 Federal e 1,36 Municipal Fonte: IBPT/FECOMERCIO RS-Valor Liquido da Nota Fiscal - R$ 34,68\r\nCondicoes de Pagamento: GARANTIA\r\n\r\n\r\nContato: 733518 - Depto: 04 - Vendedor: 0538-JONATHAN NAZARIO DE OLIVEIRA\r\nO.S.: 161463 - Placa: MKU8017  - Chassi: 9BWAG4123FT522156 - Modelo: VW/UP TAKE MA - KM: 21054 - \r\nDN : 1220 - Despesas de Garantia do Fabricante'; //descreve também  o motivo de cancelamento  //opcional

    $std->tomador = new \stdClass(); //obrigatorio
    //$std->tomador->endereco_informado = 'N'; //S ou N opcional
    $std->tomador->tipo = 'J'; //obrigatorio F ou J ou E
    //$std->tomador->identificador = '292929292' //opcional Número do cartão de identificação estrangeira ou passaporte.
    //$std->tomador->estado = 'New York'; //opcional Somente informar quando o tipo do tomador for E (estrangeiro)
    //$std->tomador->pais = 'EUA'; //opcional Somente informar quando o tipo do tomador for E (estrangeiro)
    $std->tomador->cpfcnpj = '59104422005704'; //opcional
    $std->tomador->ie = ''; //opcional
    $std->tomador->nome_razao_social = 'VOLKSWAGEN DO BRASIL INDUSTRIA DE VEICULOS AUTOMOTORES LTDA'; //opcional
    $std->tomador->sobrenome_nome_fantasia = 'VOLKSWAGEN DO BRASIL INDUSTRIA DE VEICULOS AUTOMOTORES LTDA'; //opcional
    $std->tomador->logradouro = 'MARGINAL DA VIA ANCHIETA SN KM'; //opcional
    $std->tomador->email = 'centralnfe@volkswagen.com.br'; //opcional
    $std->tomador->numero_residencia = '5'; //opcional
    $std->tomador->complemento = ''; //opcional
    $std->tomador->ponto_referencia = ''; //opcional
    $std->tomador->bairro = 'DEMARCHI'; //opcional
    $std->tomador->cidade = ''; //opcional
    $std->tomador->cep = '19823901'; //opcional
    $std->tomador->ddd_fone_comercial = '11'; //opcional
    $std->tomador->fone_comercial = '43474796'; //opcional
    $std->tomador->ddd_fone_residencial = '11'; //opcional
    $std->tomador->fone_residencial = '43474796'; //opcional
    $std->tomador->ddd_fax = '11'; //opcional
    $std->tomador->fone_fax = '43474796'; //opcional

    $std->itens[0] = new \stdClass(); //obrigatorio
    $std->itens[0]->tributa_municipio_prestador = 'S'; //obrigatorio S ou N
    $std->itens[0]->codigo_local_prestacao_servico = '8291'; //obrigatorio codigo TOM do municipio vide WEBTOM
    $std->itens[0]->codigo_item_lista_servico = '1401'; //obrigatorio Lei Complementar 116/2003
    $std->itens[0]->descritivo = 'AP.COMANDO REDE EL.BORDO PROGR'; //obrigatorio
    $std->itens[0]->unidade_codigo = '1'; //opcional Código sobre variações de Prefeitura para Prefeitura. O campo torna-se obrigatório a partir do momento em que o Município utiliza esta configuração.
    $std->itens[0]->unidade_quantidade = 0.13; //opcional
    $std->itens[0]->unidade_valor_unitario = 1.00; //opcional
    $std->itens[0]->aliquota_item_lista_servico = 4.00; //obrigatorio
    $std->itens[0]->situacao_tributaria = '0'; //
    //0 - Tributada Integralmente: o valor do imposto será lançado para o emissor da nota.
    //1 - Tributada Integralmente com ISSRF: o valor do imposto não será lançado, pois será recolhido pelo tomador, caso seja órgão público municipal.
    //2 - Tributada Integralmente e sujeita à Substituição Tributária: o valor do imposto não será lançado, pois será recolhido pelo tomador (substituto tributário), caso não seja um órgão público municipal.
    //3 - Tributada com redução da base de cálculo: o valor do imposto será lançado para o emissor da nota, porém, na apuração da base de cálculo, será descontado o valor da tag <valor_deducao> (esta situação tributária somente se aplica, caso o serviço consignado seja o de código 1705).
    //4 - Tributada com redução da base de cálculo com ISSRF: o valor do imposto não será lançado, pois será recolhido pelo tomador, caso seja órgão público municipal, porém na apuração da base de cálculo será descontado o valor da tag <valor_deducao> (esta situação tributária somente se aplica, caso o serviço consignado seja o de código 1705).
    //5 - Tributada com redução da base de cálculo e sujeita à Substituição Tributária: o valor do imposto não será lançado, pois será recolhido pelo tomador, caso não seja um órgão público municipal, porém na apuração da base de cálculo será descontado o valor da tag <valor_deducao> (esta situação tributária somente se aplica,caso o serviço consignado seja o de código 1705).
    //6 - Isenta: não irá gerar valor de imposto, pois o prestador é isento.
    //7 - Imune: não irá gerar valor do imposto, pois o prestador é imune.
    //8 - Não Tributada - ISS regime Fixo: não irá influenciar no cálculo do imposto, pois o valor é previamente calculado.
    //9 - Não Tributada - ISS regime Estimativa: não irá influenciar no cálculo do imposto, pois o valor é previamente estimado.
    //10 - Não Tributada - ISS Construção Civil recolhido antecipadamente: não irá gerar valor de imposto, pois foi recolhido antecipadamente (esta situação tributária somente se aplica, caso os serviços consignados sejam os de código 1701, 1702, 1703, 1705, 1719).
    //15 - Não Tributada - Ato Cooperado: não irá gerar valor do imposto, pois a prestação de serviço para cooperados não está sujeita ao ISS; porém, mesmo que cooperativa e caso o serviço seja prestado para um não cooperado, deve- se utilizar das outras situações tributárias, de acordo com o caso.

    $std->itens[0]->valor_tributavel = 1.00; //obrigatorio
    $std->itens[0]->valor_deducao = 0; //opcional
    $std->itens[0]->valor_issrf = 0.00; //opcional

    $std->genericos[0] = new \stdClass();
    $std->genericos[0]->titulo = '';
    $std->genericos[0]->descricao = '';

    $std->produtos[0] = new \stdClass();
    $std->produtos[0]->descricao = '';
    $std->produtos[0]->valor = '';

    $rps = new Rps($std);

    $response = $tools->enviar($rps);

    echo FakePretty::prettyPrint($response, '');

} catch (\Exception $e) {
    echo $e->getMessage();
}