--TEST--
Basic Encryption
--FILE--
<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../xmlseclibs.php');

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/oaep_sha1.xml')) {
    unlink($_SERVER['DOCUMENT_ROOT'] . '/oaep_sha1.xml');
}

$dom = new DOMDocument();
$dom->load($_SERVER['DOCUMENT_ROOT'] . '/basic-doc.xml');

$objKey = new XMLSecurityKey(XMLSecurityKey::AES256_CBC);
$objKey->generateSessionKey();

$siteKey = new XMLSecurityKey(XMLSecurityKey::RSA_OAEP_MGF1P, array('type'=>'public'));
$siteKey->loadKey($_SERVER['DOCUMENT_ROOT'] . '/mycert.pem', TRUE, TRUE);

$enc = new XMLSecEnc();
$enc->setNode($dom->documentElement);
$enc->encryptKey($siteKey, $objKey);

$enc->type = XMLSecEnc::Element;
$encNode = $enc->encryptNode($objKey);

$dom->save($_SERVER['DOCUMENT_ROOT'] . '/oaep_sha1.xml');

$root = $dom->documentElement;
echo $root->localName."\n";

unlink($_SERVER['DOCUMENT_ROOT'] . '/oaep_sha1.xml');

?>
--EXPECTF--
EncryptedData
