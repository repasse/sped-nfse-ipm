<?php

namespace NFePHP\NFSeIPM\Common;

/**
 * Class FakePretty shows event and fake comunication data
 * for analises and debugging only
 *
 * @category  API
 * @package   NFePHP\NFseIPM
 * @copyright NFePHP Copyright (c) 2017
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse-ipm for the canonical source repository
 */

class FakePretty
{
    public static function prettyPrint($response, $save = '')
    {
        if (empty($response)) {
            $html = "Sem resposta";
            return $html;
        }
        $std = json_decode($response);
        if (!empty($save)) {
            file_put_contents(
                realpath("../../tests/fixtures/xml/$save.xml"),
                $std->body
            );
        }
        $doc = new \DOMDocument('1.0', 'ISO-8859-1');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $doc->loadXML(html_entity_decode($std->body));

        $html = "<pre>";
        $html .= '<h2>url</h2>';
        $html .= $std->url;
        $html .= "<br>";
        $html .= '<h2>operation</h2>';
        $html .= "<br>";
        $html .= $std->operation;
        $html .= "<br>";
        $html .= '<h2>XML</h2>';
        $html .= str_replace(
            ['<', '>'],
            ['&lt;','&gt;'],
            str_replace(
                '<?xml version="1.0"?>',
                '<?xml version="1.0" encoding="ISO-8859-1"?>',
                $doc->saveXML()
            )
        );
        $html .= "</pre>";
        return $html;
    }
}
