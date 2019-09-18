<?php
require_once __DIR__. "/../vendor/autoload.php";

use AzureClasses\AzureInteractionComputerVision;

$prova = new AzureInteractionComputerVision();

$imagepath = ($_FILES['photo']['tmp_name']);

$data = fopen ($imagepath, 'rb');
$size=filesize ($imagepath);
$contents= fread ($data, $size);
fclose ($data);

$result = $prova->getTagsFromComputerVisionAnalysis($contents);
$print;
foreach($result->tags as $tag){
  $print .= $tag->name . ', ';
}

echo substr($print, 0, (strlen($print)-2));
