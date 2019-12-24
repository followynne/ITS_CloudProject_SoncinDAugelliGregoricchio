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

    $this->blobClient->setContainer('prova1');

    // $referer = str_replace($_SERVER['HTTP_ORIGIN'], '',  $_SERVER['HTTP_REFERER']);
    // HTTP_ORIGIN dà problemi con Chrome e non è affidabile
    //$referer = str_replace('http://localhost:9999/public', '',  $_SERVER['HTTP_REFERER']);

    $referer = ($request->getUri()->getPath());
    if ($referer == '/completegallery') {
      $htmlBlobsList = $this->blobClient->getBlobJson(-1);
    } else {
      $htmlBlobsList = $this->blobClient->getBlobJson((int)$request->getQueryParams()['indexpage'] ?? 0);
    }
    echo $htmlBlobsList;
  }
}
