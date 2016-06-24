--TEST--
Test for ds:RetrievalMethod.
--FILE--
<?php
require($_SERVER['DOCUMENT_ROOT'] . '/../xmlseclibs.php');

$doc = new DOMDocument();
$doc->load($_SERVER['DOCUMENT_ROOT'] . "/retrievalmethod-findkey.xml");

$objenc = new XMLSecEnc();
$encData = $objenc->locateEncryptedData($doc);
if (! $encData) {
	throw new Exception("Cannot locate Encrypted Data");
}
$objenc->setNode($encData);
$objenc->type = $encData->getAttribute("Type");
$objKey = $objenc->locateKey();

$objKeyInfo = $objenc->locateKeyInfo($objKey);

if (!$objKeyInfo->isEncrypted) {
	throw new Exception('Expected $objKeyInfo to refer to an encrypted key by now.');
}

echo "OK\n";

?>
--EXPECTF--
OK
