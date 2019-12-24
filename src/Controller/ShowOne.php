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
    $_SESSION['mail'] = 'prova';
    $_SESSION['idContainer'] = 1;
    $_SESSION['containerName'] = 'prova1';
    $user = $_SESSION['mail'];
    $idcont = $_SESSION['idContainer'];
    $contname = $_SESSION['containerName'];
    //TODO:

    if (!isset($user)) {
      echo "Unauthorized. You'll be soon redirected to login.";
      header('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url= /login');
      die();
    }

    if ($_SESSION['requestSingleImage'] == 'active') {
      $this->dao->setIdContainer($idcont);
      $this->blob->setContainer($contname);

      $blobUrlWithSA = $this->blob->getShareableBlob($request->getUri()->getPath()['name']);
      $exifBlobArray = $this->dao->getBlobExif($request->getUri()->getPath()['name']);
      $tagsBlob = $this->dao->getBlobTags($request->getUri()->getPath()['name']);
    } else {
      echo "<div>
      You shouldn't be there. Well - you'll be soon redirected to login.
      </div>";
      header('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url= /login');
      die();
    }
    echo $this->templates->render('_imagedetail', ['url' => $blobUrlWithSA, 'name' => $request->getUri()->getPath()['name'], 'exif' => $exifBlobArray, 'tags' => $tagsBlob]);
  }
}
