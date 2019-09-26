<?php
session_start();
require_once __DIR__. "/../vendor/autoload.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use AzureClasses\AzureInteractionBlob;

// check for Login

$value = [];
$jsonimgs = json_decode($_POST['imgname']);

$UTCdate = new DateTime();
$UTCdate->setTimestamp($_POST['expirydate']);
$UTCdateformatted = $UTCdate->format("Y-m-d").'T'. $UTCdate->format("H:i:s") .'Z';

foreach($jsonimgs as $url){
  $bloblink = new AzureInteractionBlob();
  $value[] = $bloblink->getShareableBlob('prova1' . '/' . $url, $UTCdateformatted);
}

do {
  $filename = substr($_POST['expirydate'], 0, 10) . '_' . rand() . '.txt';
} while (file_exists('../sharefile/' . $filename));

file_put_contents('../sharefile/' . $filename, serialize($value));

// inserire e controllare il serverhost
// echo $_SERVER['HTTP_HOST'] . '/sharedfolder.php?url=' . 'test.txt';
echo 'localhost:9999/sharedfolder.php?url=' . $filename;
