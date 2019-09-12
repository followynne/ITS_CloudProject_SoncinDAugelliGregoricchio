<?php
session_start();

require "vendor/autoload.php";
use League\Plates\Engine;
use AzureClasses\MakeSAConnection;
use AzureClasses\AzureInteractionBlob;

// check for Login

if ($_SESSION['requestSingleImage'] == 'active' ){

  $blob = new AzureInteractionBlob('prova1' . '/' . $_GET['name']);
  $blobUrlWithSA = $blob->getShareableBlob();

} else {
  return;
}
//TODO: extract from DB photoDatas, get them as render values.
// model: variables(...); if (ex != NULL**) then variable = ...
// NULL** : the value that gets returned from DB Request if this value is set at null on db.

$templates = new Engine('templates/');
echo $templates->render('_imagedetail', ['url' => $blobUrlWithSA]);
