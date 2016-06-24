--TEST--
C14N_COMMENTS signatures.
--DESCRIPTION--
Test signing with C14N with comments.
--FILE--
<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../xmlseclibs.php');

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/sign-c14-comments.xml')) {
    unlink($_SERVER['DOCUMENT_ROOT'] . '/sign-c14-comments.xml');
}

$xml = "<ApplicationRequest xmlns=\"http://example.org/xmldata/\"><CustomerId>12345678</CustomerId><Command>GetUserInfo</Command><Timestamp>1317032524</Timestamp><Status>ALL</Status><Environment>DEVELOPMENT</Environment><SoftwareId>ExampleApp 0.1\b</SoftwareId><FileType>ABCDEFG</FileType></ApplicationRequest>"; 

$doc = new DOMDocument(); 
$doc->formatOutput = false; 
$doc->preserveWhiteSpace = false; 
$doc->loadXML($xml);

$objDSig = new XMLSecurityDSig(); 

$objDSig->setCanonicalMethod(XMLSecurityDSig::C14N_COMMENTS); 

$objDSig->addReference($doc, XMLSecurityDSig::SHA1, array('http://www.w3.org/2000/09/xmldsig#enveloped-signature', XMLSecurityDSig::C14N_COMMENTS)); 

$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));
/* load private key */
$objKey->loadKey($_SERVER['DOCUMENT_ROOT'] . '/privkey.pem', TRUE);

$objDSig->sign($objKey, $doc->documentElement);

/* Add associated public key */
$objDSig->add509Cert(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mycert.pem'));

$objDSig->appendSignature($doc->documentElement);

$doc->save($_SERVER['DOCUMENT_ROOT'] . '/sign-c14-comments.xml');

$sign_output = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/sign-c14-comments.xml');
$sign_output_def = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/sign-c14-comments.res');
if ($sign_output != $sign_output_def) {
    echo "NOT THE SAME\n";
}
echo "DONE\n";
?>
--EXPECTF--
DONE
