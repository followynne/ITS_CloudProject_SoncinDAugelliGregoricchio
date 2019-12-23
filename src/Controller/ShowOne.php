<?php

declare(strict_types=1);

namespace SimpleMVC\Controller;

use Exception;
use League\Plates\Engine;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Model\AzureInteractionBlob;
use SimpleMVC\Model\DAOInteraction;

class ShowOne implements ControllerInterface
{
  protected $plates;
  protected $dao;
  protected $blob;

  public function __construct(Engine $plates, DAOInteraction $dao, AzureInteractionBlob $blob)
  {
    $this->plates = $plates;
    $this->dao = $dao; 
    $this->blob = $blob; 
  }

  public function execute(ServerRequestInterface $request)
  {
    //TODO:
    $_SESSION['username'] = 'prova';
    $_SESSION['idContainer'] = 1;
    $_SESSION['containerName'] = 'prova1';
    $user = $_SESSION['username'];
    $idcont = $_SESSION['idContainer'];
    $contname = $_SESSION['containerName'];
    //TODO:

    if (!isset($user)) {
      echo "Unauthorized. You'll be soon redirected to login.";
      header('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url= start.php');
      die();
    }

    if ($_SESSION['requestSingleImage'] == 'active') {
      try {
        
        $this->dao->setIdContainer($idcont);
        $this->blob->setContainer($contname);
      } catch (Exception $ex) {
        echo $ex;
      }
      $blobUrlWithSA = $this->blob->getShareableBlob($_GET['name']);
      $exifBlobArray = $this->dao->getBlobExif($_GET['name']);
      $tagsBlob = $this->dao->getBlobTags($_GET['name']);
    } else {
      echo "<div>
      You shouldn't be there. Well - you'll be soon redirected to login.
      </div>";
      header('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url= start.php');
      die();
    }
    echo $this->templates->render('_imagedetail', ['url' => $blobUrlWithSA, 'name' => $_GET['name'], 'exif' => $exifBlobArray, 'tags' => $tagsBlob]);
  }
}
