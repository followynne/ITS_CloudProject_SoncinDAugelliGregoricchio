<?php
session_start();
chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

use AzureClasses\DAOInteraction;
use AzureClasses\AzureInteractionContainer;
use AzureClasses\AzureStorageDeleteAdapter;
use AzureClasses\DatabaseDeleteAdapter;

//TODO: containername needs to be taken from $_SESSION['usercontainer']
$_SESSION['idContainer'] = 1;
$container = $_SESSION['idContainer'];
//TODO

$builder = new DI\ContainerBuilder();
$builder->addDefinitions('config/config.php');
$cont = $builder->build();

try {
  $dao = $cont->get(DAOInteraction::class);
  $dao->setIdContainer($container);
  $blobClient = $cont->get(AzureInteractionContainer::class);
  $blobClient -> setContainer('prova1');
} catch (Exception $e){
  echo $e;
  echo "Error establishing connection to remote services.";
  die();
}
$azureadapter = new AzureStorageDeleteAdapter($blobClient);
$dbadapter = new DatabaseDeleteAdapter($dao);

$data = !is_array($_GET['name']) ? array($_GET['name']) : $_GET['name'];

foreach($data as $name){
  $azuredel = $azureadapter->deleteFrom($name);
  if ($azuredel == 'successful'){
    try {
      $dbdel = $dbadapter->deleteFrom($name);
    } catch (PDOException $ex) {
      $azureadapter->rollbackDelete($name);
      print("Some deletes unsuccessful. Please check after refresh.");
      return;
    }
  } else {
    echo 'Some deletes unsuccessful. Please check after refresh.';
    return;
  }
}
echo $dbdel;
