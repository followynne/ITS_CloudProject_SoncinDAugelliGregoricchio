<?php

declare(strict_types=1);

namespace SimpleMVC\Controller;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Model\AzureInteractionComputerVision;
use SimpleMVC\Model\AzureInteractionContainer;
use SimpleMVC\Model\DAOInteraction;

class Upload implements ControllerInterface
{
    protected $onDb;
    protected $blob;
    protected $interaction;

    public function __construct(
        DAOInteraction $onDb,
        AzureInteractionContainer $blob,
        AzureInteractionComputerVision $interaction
    ) {
        $this->onDb = $onDb;
        $this->blob = $blob;
        $this->interaction = $interaction;

    }

    public function execute(ServerRequestInterface $request)
    {
        if (!isset($_SESSION['mail'])) {
            echo "Unauthorized. You'll be soon redirected to login.";
            header('HTTP/1.1 401 Unauthorized');
            header('Refresh:3; url= /login');
            die();
        }
        $idContainer = $_SESSION['idcontainer'];
        $containername = $_SESSION['container'];
        $this->onDb->setIdContainer($idContainer);
        $this->blob->setContainer($containername);
        $currentDir = getcwd();
        $uploadDirectory = "uploads/";
        $dir = "\uploads\\";
        $fileName = $_FILES["image"]["name"];
        $target_file = $uploadDirectory . basename($_FILES["image"]["name"]);
        $uploadPath = $currentDir . $dir . basename($fileName);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or a fake image
        if (isset($request->getParsedBody()["upload"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }

        // Check the file size to validate the upload
        if ($_FILES["image"]["size"] > 10000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow only certain file formats upload
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check: if $uploadOk was set to 0 by an error stop the processing.
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            $stream = fopen($_FILES["image"]["tmp_name"], 'r');
            $size = filesize($_FILES["image"]["tmp_name"]);
            $contents = fread($stream, $size);
            fclose($stream);

            $randString = $this->generateRandomString(15);
            $originalfilename = $fileName;
            $fileName = $randString;
            $referenceName = $this->checkRandNameImage($fileName);

            $referenceName .= '.' . $imageFileType;
            $this->blob->uploadBlob($referenceName, $contents);
            $idPhotoonDb = $this->onDb->addDataPhotos($referenceName, $originalfilename);

            $image_properties = exif_read_data($_FILES['image']['tmp_name']);
            $exif = createExifArrayData($image_properties);
            $this->onDb->insertExifData($idPhotoonDb, $exif);

            if ($this->sizeMaxForComputerVision($size)) {
                $result = $this->interaction->getTagsFromComputerVisionAnalysis($contents);
                foreach ($result->tags as $tag) {
                    $idTag = $this->onDb->addDataTag($tag->name);
                    $this->onDb->addDataPhotoTag($idPhotoonDb, $idTag);
                }
                echo '<script type="text/javascript">
                        alert("Image uploaded!");
                        window.location.href = "/";
                      </script>';
            } else {
                echo '<script type="text/javascript">
                        alert("Image uploaded! Image is not valid for return tags")
                        window.location.href = "/"
                      </script>';
            }
        }
    }
    function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    //if randomName photo exists, creates a new randomName
    function checkRandNameImage($namePhoto)
    {
        $container = [];
        for ($i = 0; $i < strlen($namePhoto); $i++) { //sizeof
            $container[] = $namePhoto;
            if (in_array($namePhoto, $container)) {
                return $namePhoto = $this->generateRandomString(15);
            } else {
                return $namePhoto;
            }
            return $namePhoto;
        }
    }

    // check MAX image size for Computer Vision 
    function sizeMaxForComputerVision($size)
    {
        if ($size < 6000000) {
            return true;
        } else {
            return false;
        }
    }
}
