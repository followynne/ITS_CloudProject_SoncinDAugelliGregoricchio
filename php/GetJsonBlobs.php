<?php

require_once __DIR__. "/../vendor/autoload.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use AzureClasses\AzureInteraction;

$blobClient = new AzureInteraction;
$htmlBlobsList = $blobClient->getBlobJson('prova1', $_GET['indexpage'] ?? 0);
echo $htmlBlobsList;
