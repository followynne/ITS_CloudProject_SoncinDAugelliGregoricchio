<?php
session_start();
chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

use AzureClasses\DAOInteraction;
use AzureClasses\AzureInteractionBlob;

//TODO: containername needs to be taken from $_SESSION['usercontainer']
$idContainer = $_SESSION['idContainer'];
$containername = $_SESSION['containerName'];
$idContainer = 1;
$containername = 'prova1';
//TODO

$builder = new DI\ContainerBuilder();
$builder->addDefinitions('config/config.php');
$cont = $builder->build();

try{
  $dao = $cont->get(DAOInteraction::class);
  $dao->setIdContainer($idContainer);
  $azureblob = $cont->get(AzureInteractionBlob::class);
  $azureblob->setContainer($containername);
} catch (Exception $e){
  echo "Error establishing connection.";
  die();
}

$data = [];
if (isset($_POST['data'])){
  $obj = json_decode($_POST['data']);
  $obj2 = (array)$obj;
  !isset($obj2['tags']) ? : $data['Tag.Name'] = implode('\', \'', $obj2['tags']);
  !isset($obj2['brand']) ? : $data['Brand'] = implode('\', \'', $obj2['brand']);
  !isset($obj2['dates']) ? : $data['Date'] = implode('\', \'', $obj2['dates']);
} else {
  isset($_POST['tags']) ? $data = ['Tag.Name' => implode('\', \'', json_decode($_POST['tags']))] :
    ( isset($_POST['brand']) ? $data = ['Brand' => implode('\', \'', json_decode($_POST['brand']))] :
    ($data = ['Date' => implode('\', \'', json_decode($_POST['dates']))]));
}

$referer = parse_url($_SERVER['HTTP_REFERER']);
$blobnames = $dao->searchBlobsByColumn($data);

if ($blobnames[0] == null){
  return;
}
if ($referer['path']=='/public/getallblobs.php'){
  echo $azureblob->createBlobJsonWithBlobNames($blobnames, -1);
} else {
  echo $azureblob->createBlobJsonWithBlobNames($blobnames, $_POST['indexpage'] ?? 0);
}
