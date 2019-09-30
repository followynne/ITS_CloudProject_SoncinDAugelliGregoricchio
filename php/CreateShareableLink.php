<?php
session_start();
require_once __DIR__. "/../vendor/autoload.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use AzureClasses\AzureInteractionBlob;

$value = [];
$jsonimgs = json_decode($_POST['imgname']);

$ts = $_POST['expirydate']/1000;
$UTCdate = new DateTime("@$ts");
$UTCdateformatted = $UTCdate->format("Y-m-d").'T'. $UTCdate->format("H:i:s") .'Z';

$bloblink = new AzureInteractionBlob('prova1');
foreach($jsonimgs as $url){
  $value[] = $bloblink->getShareableBlob($url, $UTCdateformatted);
}

do {
  $filename = substr($UTCdateformatted, 0, 10) . '_' . rand() . '.txt';
} while (file_exists('../sharefile/' . $filename));

file_put_contents('../sharefile/' . $filename, serialize($value));

// inserire e controllare il serverhost
// echo $_SERVER['HTTP_HOST'] . '/sharedfolder.php?url=' . 'test.txt';
echo 'localhost:9999/sharedfolder.php?url=' . $filename;
