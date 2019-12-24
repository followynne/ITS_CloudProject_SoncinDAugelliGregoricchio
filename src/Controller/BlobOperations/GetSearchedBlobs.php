<?php

declare(strict_types=1);

namespace SimpleMVC\Controller\BlobOperations;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Model\AzureInteractionBlob;
use SimpleMVC\Model\DAOInteraction;

class GetSearchedBlobs implements ControllerInterface
{
  protected $blobClient;

  public function __construct(AzureInteractionBlob $azureblob, DAOInteraction $dao)
  {
    $this->azureblob = $azureblob;
    $this->dao = $dao;
  }

  public function execute(ServerRequestInterface $request)
  {
    
    //TODO: containername needs to be taken from $_SESSION['usercontainer']
    $idContainer = $_SESSION['idContainer'];
    $containername = $_SESSION['containerName'];
    $idContainer = 1;
    $containername = 'prova1';
    //TODO


      $this->dao->setIdContainer($idContainer);
      $this->azureblob->setContainer($containername);


    $data = [];
    if (isset($request->getParsedBody()['data'])) {
      $obj = json_decode($request->getParsedBody()['data']);
      $obj2 = (array) $obj;
      !isset($obj2['tags']) ?: $data['Tag.Name'] = implode('\', \'', $obj2['tags']);
      !isset($obj2['brand']) ?: $data['Brand'] = implode('\', \'', $obj2['brand']);
      !isset($obj2['dates']) ?: $data['Date'] = implode('\', \'', $obj2['dates']);
    } else {
      isset($request->getParsedBody()['tags']) ? $data = ['Tag.Name' => implode('\', \'', json_decode($request->getParsedBody()['tags']))] : (isset($request->getParsedBody()['brand']) ? $data = ['Brand' => implode('\', \'', json_decode($request->getParsedBody()['brand']))] : ($data = ['Date' => implode('\', \'', json_decode($request->getParsedBody()['dates']))]));
    }

    $referer = $request->getUri()->getPath();
    $blobnames = $this->dao->searchBlobsByColumn($data);

    if ($blobnames[0] == null) {
      return;
    }
    if ($referer['path'] == '/completegallery') {
      echo $this->azureblob->createBlobJsonWithBlobNames($blobnames, -1);
    } else {
      echo $this->azureblob->createBlobJsonWithBlobNames($blobnames, $request->getParsedBody()['indexpage'] ?? 0);
    }
  }
}
