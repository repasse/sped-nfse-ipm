<?php

namespace NFePHP\NFSeIPM\Common;

/**
 * Class for RPS XML convertion
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

use NFePHP\Common\DOMImproved as Dom;
use stdClass;
use DOMElement;

class Factory
{

    /**
     * @var stdClass
     */
    protected $std;

    /**
     * @var Dom
     */
    protected $dom;

    /**
     * @var DOMElement
     */
    protected $rps;
    /**
     * @var \stdClass
     */
    protected $config;

    /**
     * Constructor
     * @param stdClass $std
     * @param bool $sign
     * @param bool $teste
     */
    public function __construct(stdClass $std, $sign = false, $teste = false)
    {
        $this->std = $std;
        $this->dom = new Dom('1.0', 'UTF-8');
        $this->dom->preserveWhiteSpace = true;
        $this->dom->formatOutput = false;
        $this->rps = $this->dom->createElement('nfse');
        if ($sign) {
            $att = $this->dom->createAttribute('id');
            $att->value = 'elote';
            $this->rps->appendChild($att);
        }
        if ($teste) {
            $this->dom->addChild(
                $this->rps,
                "nfse_teste",
                '1',
                true
            );
        }
    }

    /**
     * Add config
     * @param \stdClass $config
     */
    public function addConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Builder, converts stdClass Rps in XML NFSe/Rps
     * NOTE: without Prestador Tag
     * @return string NFSe/RPS in XML string format
     */
    public function render(): string
    {
        $this->dom->addChild(
            $this->rps,
            "identificador",
            !empty($this->std->identificador) ? $this->std->identificador : null,
            false
        );
        if (!empty($this->std->rps)) {
            $rps = $this->std->rps;
            $node = $this->dom->createElement('rps');
            $this->dom->addChild(
                $node,
                "nro_recibo_provisorio",
                $rps->nro_recibo_provisorio,
                true
            );
            $this->dom->addChild(
                $node,
                "serie_recibo_provisorio",
                $rps->serie_recibo_provisorio,
                true
            );
            $this->dom->addChild(
                $node,
                "data_emissao_recibo_provisorio",
                $rps->data_emissao_recibo_provisorio,
                true
            );
            $this->dom->addChild(
                $node,
                "hora_emissao_recibo_provisorio",
                $rps->hora_emissao_recibo_provisorio,
                true
            );
            $this->rps->appendChild($node);
        }

        if (!empty($this->std->pedagio)) {
            $pedagio = $this->std->pedagio;
            $node = $this->dom->createElement('pedagio');
            $this->dom->addChild(
                $node,
                "cod_equipamento_automatico",
                $pedagio->cod_equipamento_automatico,
                true
            );
            $this->rps->appendChild($node);
        }

        $nf = $this->std->nf;
        $node = $this->dom->createElement('nf');
        $this->dom->addChild(
            $node,
            "numero",
            !empty($nf->numero) ? $nf->numero : null,
            false
        );
        $this->dom->addChild(
            $node,
            "valor_total",
            number_format($nf->valor_total, 2, ',', ''),
            true
        );
        $this->dom->addChild(
            $node,
            "valor_desconto",
            isset($nf->valor_desconto) ? number_format($nf->valor_desconto, 2, ',', '') : null,
            false
        );
        $this->dom->addChild(
            $node,
            "valor_ir",
            isset($nf->valor_ir) ? number_format($nf->valor_ir, 2, ',', '') : null,
            false
        );
        $this->dom->addChild(
            $node,
            "valor_inss",
            isset($nf->valor_inss) ? number_format($nf->valor_inss, 2, ',', '') : null,
            false
        );
        $this->dom->addChild(
            $node,
            "valor_contribuicao_social",
            isset($nf->valor_contribuicao_social) ? number_format($nf->valor_contribuicao_social, 2, ',', '') : null,
            false
        );
        $this->dom->addChild(
            $node,
            "valor_rps",
            isset($nf->valor_rps) ? number_format($nf->valor_rps, 2, ',', '') : null,
            false
        );
        $this->dom->addChild(
            $node,
            "valor_pis",
            isset($nf->valor_pis) ? number_format($nf->valor_pis, 2, ',', '') : null,
            false
        );
        $this->dom->addChild(
            $node,
            "valor_cofins",
            isset($nf->valor_cofins) ? number_format($nf->valor_cofins, 2, ',', '') : null,
            false
        );
        $this->dom->addChild(
            $node,
            "observacao",
            isset($nf->observacao) ? $nf->observacao : null,
            false
        );
        $this->rps->appendChild($node);

        $node = $this->dom->createElement('prestador');
        $this->dom->addChild(
            $node,
            "cpfcnpj",
            $this->config->cnpj,
            true
        );
        $this->dom->addChild(
            $node,
            "cidade",
            !empty($this->config->tom) ? $this->config->tom : null,
            false
        );
        $this->rps->appendChild($node);

        $tom = $this->std->tomador;
        $node = $this->dom->createElement('tomador');
        $this->dom->addChild(
            $node,
            "endereco_informado",
            !empty($tom->endereco_informado) ? $tom->endereco_informado : null,
            false
        );
        $this->dom->addChild(
            $node,
            "tipo",
            $tom->tipo,
            true
        );
        $this->dom->addChild(
            $node,
            "identificador",
            !empty($tom->identificador) ? $tom->identificador : null,
            false
        );
        $this->dom->addChild(
            $node,
            "estado",
            !empty($tom->estado) ? $tom->estado : null,
            false
        );
        $this->dom->addChild(
            $node,
            "pais",
            !empty($tom->pais) ? $tom->pais : null,
            false
        );
        $this->dom->addChild(
            $node,
            "cpfcnpj",
            !empty($tom->cpfcnpj) ? $tom->cpfcnpj : null,
            false
        );
        $this->dom->addChild(
            $node,
            "ie",
            !empty($tom->ie) ? $tom->ie : '',
            true
        );
        $this->dom->addChild(
            $node,
            "nome_razao_social",
            !empty($tom->nome_razao_social) ? $tom->nome_razao_social : null,
            false
        );
        $this->dom->addChild(
            $node,
            "sobrenome_nome_fantasia",
            !empty($tom->sobrenome_nome_fantasia) ? $tom->sobrenome_nome_fantasia : null,
            false
        );
        $this->dom->addChild(
            $node,
            "logradouro",
            !empty($tom->logradouro) ? $tom->logradouro : null,
            false
        );
        $this->dom->addChild(
            $node,
            "email",
            !empty($tom->email) ? $tom->email : null,
            false
        );
        $this->dom->addChild(
            $node,
            "numero_residencia",
            !empty($tom->numero_residencia) ? $tom->numero_residencia : null,
            false
        );
        $this->dom->addChild(
            $node,
            "complemento",
            !empty($tom->complemento) ? $tom->complemento : '',
            true
        );
        $this->dom->addChild(
            $node,
            "ponto_referencia",
            !empty($tom->ponto_referencia) ? $tom->ponto_referencia : '',
            true
        );
        $this->dom->addChild(
            $node,
            "bairro",
            !empty($tom->bairro) ? $tom->bairro : '',
            true
        );
        $this->dom->addChild(
            $node,
            "cidade",
            !empty($tom->cidade) ? $tom->cidade : '',
            true
        );
        $this->dom->addChild(
            $node,
            "cep",
            !empty($tom->cep) ? $tom->cep : null,
            false
        );
        $this->dom->addChild(
            $node,
            "ddd_fone_comercial",
            !empty($tom->ddd_fone_comercial) ? $tom->ddd_fone_comercial : null,
            false
        );
        $this->dom->addChild(
            $node,
            "fone_comercial",
            !empty($tom->fone_comercial) ? $tom->fone_comercial : null,
            false
        );
        $this->dom->addChild(
            $node,
            "ddd_fone_residencial",
            !empty($tom->ddd_fone_residencial) ? $tom->ddd_fone_residencial : null,
            false
        );
        $this->dom->addChild(
            $node,
            "fone_residencial",
            !empty($tom->fone_residencial) ? $tom->fone_residencial : null,
            false
        );
        $this->dom->addChild(
            $node,
            "ddd_fax",
            !empty($tom->ddd_fax) ? $tom->ddd_fax : null,
            false
        );
        $this->dom->addChild(
            $node,
            "fone_fax",
            !empty($tom->fone_fax) ? $tom->fone_fax : null,
            false
        );
        $this->rps->appendChild($node);

        $itens = $this->dom->createElement('itens');
        foreach ($this->std->itens as $item) {
            $node = $this->dom->createElement('lista');
            $this->dom->addChild(
                $node,
                "tributa_municipio_prestador",
                $item->tributa_municipio_prestador,
                true
            );
            $this->dom->addChild(
                $node,
                "codigo_local_prestacao_servico",
                $item->codigo_local_prestacao_servico,
                true
            );
            $this->dom->addChild(
                $node,
                "codigo_item_lista_servico",
                $item->codigo_item_lista_servico,
                true
            );
            $this->dom->addChild(
                $node,
                "descritivo",
                $item->descritivo,
                true
            );
            $this->dom->addChild(
                $node,
                "unidade_codigo",
                isset($item->unidade_codigo) ? $item->unidade_codigo : null,
                false
            );
            $this->dom->addChild(
                $node,
                "unidade_quantidade",
                isset($item->unidade_quantidade) ? number_format($item->unidade_quantidade, 2, ',', '') : null,
                false
            );
            $this->dom->addChild(
                $node,
                "unidade_valor_unitario",
                isset($item->unidade_valor_unitario) ? number_format($item->unidade_valor_unitario, 2, ',', '') : null,
                false
            );

            $this->dom->addChild(
                $node,
                "aliquota_item_lista_servico",
                number_format($item->aliquota_item_lista_servico, 2, ',', ''),
                true
            );
            $this->dom->addChild(
                $node,
                "situacao_tributaria",
                $item->situacao_tributaria,
                true
            );
            $this->dom->addChild(
                $node,
                "valor_tributavel",
                number_format($item->valor_tributavel, 2, ',', ''),
                true
            );
            $this->dom->addChild(
                $node,
                "valor_deducao",
                isset($item->valor_deducao) ? $item->valor_deducao : null,
                false
            );
            $this->dom->addChild(
                $node,
                "valor_issrf",
                isset($item->valor_issrf) ? number_format($item->valor_issrf, 2, ',', '') : null,
                false
            );
            $itens->appendChild($node);
        }
        $this->rps->appendChild($itens);
        if (!empty($this->std->genericos)) {
            foreach ($this->std->genericos as $generico) {
                $genericos = $this->dom->createElement('genericos');
                $linha = $this->dom->createElement('linha');
                $this->dom->addChild(
                    $linha,
                    "titulo",
                    isset($generico->titulo) ? $generico->titulo : '',
                    true
                );
                $this->dom->addChild(
                    $linha,
                    "descricao",
                    isset($generico->descricao) ? $generico->descricao : '',
                    true
                );
                $genericos->appendChild($linha);
                $this->rps->appendChild($genericos);
            }
        }
        if (!empty($this->std->produtos)) {
            foreach ($this->std->produtos as $produto) {
                $produtos = $this->dom->createElement('produtos');
                $this->dom->addChild(
                    $produtos,
                    "descricao",
                    isset($produto->descricao) ? $produto->descricao : '',
                    true
                );
                $this->dom->addChild(
                    $produtos,
                    "valor",
                    !empty($produto->valor) ? number_format($produto->valor, 2, ',', '') : '',
                    true
                );
                $this->rps->appendChild($produtos);
            }
        }
        $this->dom->appendChild($this->rps);
        return $this->dom->saveXML($this->rps);
    }
}
