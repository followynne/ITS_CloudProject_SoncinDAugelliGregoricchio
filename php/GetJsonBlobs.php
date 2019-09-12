<?php
session_start();

require_once __DIR__. "/../vendor/autoload.php";

use AzureClasses\AzureInteractionContainer;

// check for Login

$blobClient = new AzureInteractionContainer('prova1');

//TODO: containername needs to be taken from $_SESSION
$referer = str_replace($_SERVER['HTTP_ORIGIN'], '',  $_SERVER['HTTP_REFERER']);
if ($referer=='/getallblobs.php'){
  $htmlBlobsList = $blobClient->getBlobJson(-1);
} else {
  $htmlBlobsList = $blobClient->getBlobJson($_GET['indexpage'] ?? 0);
}

echo $htmlBlobsList;
