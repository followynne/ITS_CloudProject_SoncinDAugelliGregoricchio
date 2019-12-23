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


    try {
      $this->dao->setIdContainer($idContainer);
      $this->azureblob->setContainer($containername);
    } catch (Exception $e) {
      echo "Error establishing connection.";
      die();
    }

    $data = [];
    if (isset($_POST['data'])) {
      $obj = json_decode($_POST['data']);
      $obj2 = (array) $obj;
      !isset($obj2['tags']) ?: $data['Tag.Name'] = implode('\', \'', $obj2['tags']);
      !isset($obj2['brand']) ?: $data['Brand'] = implode('\', \'', $obj2['brand']);
      !isset($obj2['dates']) ?: $data['Date'] = implode('\', \'', $obj2['dates']);
    } else {
      isset($_POST['tags']) ? $data = ['Tag.Name' => implode('\', \'', json_decode($_POST['tags']))] : (isset($_POST['brand']) ? $data = ['Brand' => implode('\', \'', json_decode($_POST['brand']))] : ($data = ['Date' => implode('\', \'', json_decode($_POST['dates']))]));
    }

    $referer = parse_url($_SERVER['HTTP_REFERER']);
    $blobnames = $this->dao->searchBlobsByColumn($data);

    if ($blobnames[0] == null) {
      return;
    }
    if ($referer['path'] == '/public/getallblobs.php') {
      echo $this->azureblob->createBlobJsonWithBlobNames($blobnames, -1);
    } else {
      echo $this->azureblob->createBlobJsonWithBlobNames($blobnames, $_POST['indexpage'] ?? 0);
    }
  }
}
