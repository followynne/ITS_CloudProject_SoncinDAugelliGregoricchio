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
    if (!isset($_SESSION['mail'])) {
      echo "Unauthorized. You'll be soon redirected to login.";
      header('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url= /login');
      die();
    }
    $idcont = $_SESSION['idcontainer'];
    $contname = $_SESSION['container'];
    $this->dao->setIdContainer($idcont);
    $this->blob->setContainer($contname);
    $blobUrlWithSA = $this->blob->getShareableBlob($request->getQueryParams()['name']);
    $exifBlobArray = $this->dao->getBlobExif($request->getQueryParams()['name']);
    $tagsBlob = $this->dao->getBlobTags($request->getQueryParams()['name']);

    echo $this->plates->render('_imagedetail', ['url' => $blobUrlWithSA, 'name' => $request->getQueryParams()['name'], 'exif' => $exifBlobArray, 'tags' => $tagsBlob]);
  }
}
