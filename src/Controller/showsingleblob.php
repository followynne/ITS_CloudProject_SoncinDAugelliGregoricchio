<?php
session_start();
chdir(dirname(__DIR__));
require "vendor/autoload.php";

use League\Plates\Engine;
use AzureClasses\AzureInteractionBlob;
use AzureClasses\DAOInteraction;

//TODO:
$_SESSION['username'] = 'prova';
$_SESSION['idContainer'] = 1;
$_SESSION['containerName'] = 'prova1';
$user = $_SESSION['username'];
$idcont = $_SESSION['idContainer'];
$contname = $_SESSION['containerName'];
//TODO:

if (!isset($user)){
  echo "Unauthorized. You'll be soon redirected to login.";
  header ('HTTP/1.1 401 Unauthorized');
  header('Refresh:3; url= start.php');
  die();
}

if ($_SESSION['requestSingleImage'] == 'active' ){
  try {
    $builder = new DI\ContainerBuilder();
    $builder->addDefinitions('config/config.php');
    $cont = $builder->build();
    $dao = $cont->get(DAOInteraction::class);
    $dao->setIdContainer($idcont);
    $blob = $cont->get(AzureInteractionBlob::class);
    $blob->setContainer($contname);
  } catch (Exception $ex){
    echo $ex;
  }
  $blobUrlWithSA = $blob->getShareableBlob($_GET['name']);
  $exifBlobArray = $dao->getBlobExif($_GET['name']);
  $tagsBlob = $dao->getBlobTags($_GET['name']);

} else {
  echo "<div>
      You shouldn't be there. Well - you'll be soon redirected to login.
      </div>";
  header ('HTTP/1.1 401 Unauthorized');
  header('Refresh:3; url= start.php');
  die();
}

$templates = new Engine('templates/');
echo $templates->render('_imagedetail', ['url' => $blobUrlWithSA, 'name' => $_GET['name'], 'exif' => $exifBlobArray, 'tags' => $tagsBlob]);
