<?php
session_start();

require_once __DIR__. "/../vendor/autoload.php";

use AzureClasses\DAOInteraction;
use AzureClasses\AzureInteractionBlob;

$tags = json_decode($_POST['tags']);
$dao = new DAOInteraction();
$blobnames = $dao->searchBlobsByTag($tags);
$azureblob = new AzureInteractionBlob();
$azureblob->setContainerName('prova1');
echo $result = $azureblob->createBlobJsonWithBlobNames($blobnames, $_POST['indexpage']);
