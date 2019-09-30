<?php
session_start();

require_once __DIR__. "/../vendor/autoload.php";

use AzureClasses\AzureInteractionContainer;

//TODO: containername needs to be taken from $_SESSION['usercontainer']

$blobClient = new AzureInteractionContainer('prova1');

// $referer = str_replace($_SERVER['HTTP_ORIGIN'], '',  $_SERVER['HTTP_REFERER']);
// HTTP_ORIGIN dà problemi con Chrome e non è affidabile
$referer = str_replace('http://localhost:9999', '',  $_SERVER['HTTP_REFERER']);
if ($referer=='/getallblobs.php'){
  $htmlBlobsList = $blobClient->getBlobJson(-1);
} else {
  $htmlBlobsList = $blobClient->getBlobJson($_GET['indexpage'] ?? 0);
}

echo $htmlBlobsList;
