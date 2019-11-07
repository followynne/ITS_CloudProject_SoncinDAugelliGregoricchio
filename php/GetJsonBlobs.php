<?php
session_start();

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

use AzureClasses\AzureInteractionContainer;

//TODO: containername needs to be taken from $_SESSION['usercontainer']
//TODO

$builder = new DI\ContainerBuilder();
$builder->addDefinitions('config/config.php');
$cont = $builder->build();

try {
  $blobClient = $cont -> get(AzureInteractionContainer::class);
  $blobClient->setContainer('prova1');
} catch (Exception $e){
  echo "Error establishing Azure Connection.";
  die();
}

// $referer = str_replace($_SERVER['HTTP_ORIGIN'], '',  $_SERVER['HTTP_REFERER']);
// HTTP_ORIGIN dà problemi con Chrome e non è affidabile
//$referer = str_replace('http://localhost:9999/public', '',  $_SERVER['HTTP_REFERER']);

$referer = parse_url($_SERVER['HTTP_REFERER']);
if ($referer['path']=='/public/getallblobs.php'){
  $htmlBlobsList = $blobClient->getBlobJson(-1);
} else {
  $htmlBlobsList = $blobClient->getBlobJson($_GET['indexpage'] ?? 0);

}
echo $htmlBlobsList;
