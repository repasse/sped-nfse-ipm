<?php


namespace NFePHP\NFSeIPM\Common;

use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use NFePHP\Common\Certificate;

class Signer2
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
            array('http://www.w3.org/2000/09/xmldsig#enveloped-signature')
        );
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));
        $objKey->loadKey("{$cert->privateKey}", false);
        $objDSig->sign($objKey);
        $objDSig->add509Cert("{$cert->publicKey}", true, false);
        $objDSig->appendSignature($doc->documentElement);
        return $doc->saveXML();
    }
}
