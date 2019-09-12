<?php
session_start();
require_once __DIR__. "/../vendor/autoload.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use AzureClasses\AzureInteractionContainer;

// check for Login

//TODO: containername needs to be taken from $_SESSION

$blobClient = new AzureInteractionContainer('prova1');

if (is_array($_GET['name'])){
  $result = $blobClient->deleteBlobs($_GET['name']);
} else {
  $result = $blobClient->deleteBlob($_GET['name']);
}
echo $result;
