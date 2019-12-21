<?php
declare(strict_types=1);
namespace AzureClasses;
chdir(dirname(__DIR__));

require_once "vendor/autoload.php";

use AzureClasses\AzureInteractionContainer;
use AzureClasses\AzureInteractionComputerVision;
use AzureClasses\DAOInteraction;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;

//TODO
$idContainer = $_SESSION['idContainer'];
$containername = $_SESSION['container'];
$idContainer = 1;
$containername = 'prova1';
//TODO

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions('config/config.php');
$cont = $builder->build();
try {
    $onDb = $cont->get(DAOInteraction::class);
    $onDb ->setIdContainer($idContainer);
    $blob = $cont->get(AzureInteractionContainer::class);
    $blob->setContainer($containername);
    $interaction = $cont->get(AzureInteractionComputerVision::class);
} catch (Exception $e){
  echo "Error establishing connection.";
  die();
}

$currentDir = getcwd();
$uploadDirectory = "uploads/";
$dir = "\uploads\\";
$fileName = $_FILES["image"]["name"];
$target_file = $uploadDirectory . basename($_FILES["image"]["name"]);
$uploadPath = $currentDir . $dir . basename($fileName); 
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is an actual image or a fake image
if (isset($_POST["upload"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check the file size to validate the upload
if ($_FILES["image"]["size"] > 10000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow only certain file formats upload
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check: if $uploadOk was set to 0 by an error stop the processing.
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    $stream = fopen($_FILES["image"]["tmp_name"], 'r');
    $size=filesize ($_FILES["image"]["tmp_name"]);
    $contents= fread($stream, $size);
    fclose ($stream);

    $randString = generateRandomString(15);
    $originalfilename = $fileName;
    $fileName = $randString;
    $referenceName = checkRandNameImage($fileName);
    
    $blob->uploadBlob($referenceName, $contents);
    $idPhotoonDb = $onDb->addDataPhotos($referenceName,$originalfilename);
    
    $image_properties = exif_read_data($_FILES['image']['tmp_name']);
    $exif = createExifArrayData($image_properties);
    $onDb->insertExifData($idPhotoonDb, $exif);
    
    if (sizeMaxForComputerVision($size))
    {
        $result = $interaction->getTagsFromComputerVisionAnalysis($contents);
        foreach($result->tags as $tag){
            $arrayTags = $tag->name;
            $idTag=$onDb->addDataTag($tag->name);
            $onDb->addDataPhotoTag($idPhotoonDb,$idTag);
        }
        echo '<script type="text/javascript">
                alert("Image uploaded!");
                window.location.href = "/public/index.php";
              </script>';
    } else {
        echo '<script type="text/javascript">
                alert("Image uploaded! Image is not valid for return tags")
                window.location.href = "/public/index.php"
                </script>';
    }
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//if randomName photo exists, creates a new randomName
function checkRandNameImage($namePhoto){
    $container = [];
    for ($i = 0; $i < strlen($namePhoto); $i++){//sizeof
        $container[] = $namePhoto;
            if (in_array($namePhoto, $container)) { 
                return $namePhoto = generateRandomString(15); 
            }else { 
                return $namePhoto;
            } 
    return $namePhoto;
    }
}

// check MAX image size for Computer Vision 
function sizeMaxForComputerVision($size) {
    if ($size < 6000000){
        return true;
    } else {
        return false;
    }
}