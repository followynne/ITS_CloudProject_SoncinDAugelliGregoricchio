<?php
session_start();

require "vendor/autoload.php";
use League\Plates\Engine;
use AzureClasses\AzureInteractionBlob;
use AzureClasses\DAOInteraction;

$_SESSION['username'] = 'prova';

if (!isset($_SESSION['username'])){
  echo "Unauthorized. You'll be soon redirected to login.";
  header ('HTTP/1.1 401 Unauthorized');
  header('Refresh:3; url= ./start');
  die();
}

if ($_SESSION['requestSingleImage'] == 'active' ){
  $blob = new AzureInteractionBlob('prova1');
  $dao = new DAOInteraction();
  $blobUrlWithSA = $blob->getShareableBlob($_GET['name']);
  $exifBlobArray = $dao->getExifForBlob($_GET['name'], $_GET['idcontainer']);
  $tagsBlob = $dao->getTagsofBlob($_GET['name'], $_GET['idcontainer']);
} else {
  return;
}

$templates = new Engine('templates/');
echo $templates->render('_imagedetail', ['url' => $blobUrlWithSA, 'name' => $_GET['name'], 'exif' => $exifBlobArray, 'tags' => $tagsBlob]);
