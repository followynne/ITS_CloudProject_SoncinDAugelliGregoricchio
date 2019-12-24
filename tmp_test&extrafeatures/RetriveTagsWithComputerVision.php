<?php
//chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';
use AzureClasses\AzureInteractionComputerVision;

$builder = new DI\ContainerBuilder();
$builder->addDefinitions('config/config.php');
$cont = $builder->build();
$cpobj = $cont->get(AzureInteractionComputerVision::class);

$imagepath = ($_FILES['photo']['tmp_name']);

$data = fopen ($imagepath, 'rb');
$size=filesize ($imagepath);
$contents= fread ($data, $size);
fclose ($data);

$result = $cpobj->getTagsFromComputerVisionAnalysis($contents);
$print;
foreach($result->tags as $tag){
  $print .= $tag->name . ', ';
}

echo substr($print, 0, (strlen($print)-2));
