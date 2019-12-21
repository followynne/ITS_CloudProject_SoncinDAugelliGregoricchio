<?php
session_start();
chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

$data = $_GET['url'];
$indexPageRequested = $_GET['indexpage'];

$filedata =  file_get_contents('sharefile/' . $data);

$dataavailable = unserialize($filedata);
$maxBlobsPerSubPage = 12;
$startingBlobIndex = 0 + $maxBlobsPerSubPage*$indexPageRequested;

$blobList = '{
  "pageData":{
    "totalBlobsCount":"' . count($dataavailable).'",
    "maxBlobsPerSubPage":'. $maxBlobsPerSubPage.',
    "blobs":[';

for ($i = $startingBlobIndex; $i < $startingBlobIndex+$maxBlobsPerSubPage; $i++)
{
  if (empty($dataavailable[$i])){
    continue;
  }
  $blobList .= '{"url":"'.$dataavailable[$i] .'"},';
}
echo substr($blobList,0, strlen($blobList)-1).']}}';
