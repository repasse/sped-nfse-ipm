<?php

namespace NFePHP\NFSeIPM\Common;

use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use NFePHP\Common\Certificate;
use DOMDocument;

class Signer
{
    public static function sign(Certificate $certificate, $xml)
    {
        $doc = new DOMDocument();
        $doc->loadXML($xml);
        
        $objDSig = new XMLSecurityDSig('');
        $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
        $objDSig->addReference(
            $doc,
            XMLSecurityDSig::SHA1,
            ['http://www.w3.org/2000/09/xmldsig#enveloped-signature'],
            ['force_uri' => true]
        );
        $objKey = new XMLSecurityKey(
            XMLSecurityKey::RSA_SHA1,
            ['type'=>'private']
        );
        $objKey->loadKey("{$certificate->privateKey}", false);
        $objDSig->sign($objKey);
        $objDSig->add509Cert("{$certificate->publicKey}", true, false);
        $objDSig->appendSignature($doc->documentElement);
        return $doc->saveXML();
    }
}
