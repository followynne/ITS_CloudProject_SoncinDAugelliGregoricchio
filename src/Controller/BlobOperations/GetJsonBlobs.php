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
    if (!isset($_SESSION['mail'])) {
      echo "Unauthorized. You'll be soon redirected to login.";
      header('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url= /login');
      die();
    }
    $containername = $_SESSION['container'];
    $this->blobClient->setContainer($containername);

    $referer = parse_url($request->getServerParams()['HTTP_REFERER']);
    if ($referer['path'] == '/completegallery') {
      $htmlBlobsList = $this->blobClient->getBlobJson(-1);
    } else {
      $htmlBlobsList = $this->blobClient->getBlobJson((int)$request->getQueryParams()['indexpage'] ?? 0);
    }
    echo $htmlBlobsList;
  }
}
