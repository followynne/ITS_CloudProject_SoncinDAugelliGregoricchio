<?php

require_once __DIR__. "/../vendor/autoload.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use AzureClasses\AzureInteraction;
//echo($_GET);

$blobClient = new AzureInteraction;
$htmlBlobsList = $blobClient->returnBlobJson('prova1');
echo $htmlBlobsList;
