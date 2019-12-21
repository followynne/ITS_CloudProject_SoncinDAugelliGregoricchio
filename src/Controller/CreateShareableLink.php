<?php
session_start();
chdir(dirname(__DIR__));
require "vendor/autoload.php";

use AzureClasses\AzureInteractionBlob;

//TODO
$container = $_SESSION['container'];
$container = 'prova1';
//TODO

$builder = new DI\ContainerBuilder();
$builder->addDefinitions('config/config.php');
$cont = $builder->build();
$bloblink = $cont->get(AzureInteractionBlob::class);
$bloblink->setContainer($container);

$value = [];
$jsonimgs = json_decode($_POST['imgname']);
$timestamp = $_POST['expirydate']/1000;
$UTCdate = new DateTime("@$timestamp");
$UTCdateformatted = $UTCdate->format("Y-m-d").'T'. $UTCdate->format("H:i:s") .'Z';

foreach($jsonimgs as $url){
  $value[] = $bloblink->getShareableBlob($url, $UTCdateformatted);
}

do {
  $filename = substr($UTCdateformatted, 0, 10) . '_' . rand() . '.txt';
} while (file_exists('sharefile/' . $filename));
file_put_contents('sharefile/' . $filename, serialize($value));

// inserire e controllare il serverhost
$referer = parse_url($_SERVER['HTTP_REFERER']);
echo $referer['scheme'] . '://' .$referer['host'] . ':' . $referer['port'] .  '/public/sharedfolder.php?url=' . $filename;
