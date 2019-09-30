<?php
session_start();

require_once __DIR__. "/../vendor/autoload.php";

use AzureClasses\DAOInteraction;
use AzureClasses\AzureInteractionBlob;

//TODO: containername needs to be taken from $_SESSION['usercontainer']

$tags = json_decode($_POST['tags']);
$dao = new DAOInteraction();
$blobnames = $dao->searchBlobsByTag($tags);
$azureblob = new AzureInteractionBlob('prova1');
echo $azureblob->createBlobJsonWithBlobNames($blobnames, $_POST['indexpage']);
