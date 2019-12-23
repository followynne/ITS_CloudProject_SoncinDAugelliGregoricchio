<?php

declare(strict_types=1);

namespace SimpleMVC\Controller\BlobOperations;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Model\AzureInteractionContainer;

class GetJsonBlobs implements ControllerInterface
{
  protected $plates;
  protected $blobClient;

  public function __construct(AzureInteractionContainer $blobClient)
  {
    $this->blobClient = $blobClient;
  }

  public function execute(ServerRequestInterface $request)
  {
    try {
      $this->blobClient->setContainer('prova1');
    } catch (Exception $e) {
      echo "Error establishing Azure Connection.";
      die();
    }

    // $referer = str_replace($_SERVER['HTTP_ORIGIN'], '',  $_SERVER['HTTP_REFERER']);
    // HTTP_ORIGIN dà problemi con Chrome e non è affidabile
    //$referer = str_replace('http://localhost:9999/public', '',  $_SERVER['HTTP_REFERER']);

    $referer = parse_url($_SERVER['HTTP_REFERER']);
    if ($referer['path'] == '/public/getallblobs.php') {
      $htmlBlobsList = $this->blobClient->getBlobJson(-1);
    } else {
      $htmlBlobsList = $this->blobClient->getBlobJson($_GET['indexpage'] ?? 0);
    }
    echo $htmlBlobsList;
  }
}

//TODO: containername needs to be taken from $_SESSION['usercontainer']
//TODO
