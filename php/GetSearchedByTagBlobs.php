<?php
session_start();

require_once __DIR__. "/../vendor/autoload.php";

use AzureClasses\DAOInteraction;
use AzureClasses\AzureInteractionBlob;

$idContainer = $_SESSION['idContainer'];
$containername = $_SESSION['containerName'];
$idContainer = 1;
$containername = 'prova1';

//TODO: containername needs to be taken from $_SESSION['usercontainer']

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
$dao = new DAOInteraction();
$blobnames = $dao->searchBlobsByColumn($data, $idContainer);
print_r($blobnames);
$azureblob = new AzureInteractionBlob($containername);
echo $azureblob->createBlobJsonWithBlobNames($blobnames, $_POST['indexpage']);
