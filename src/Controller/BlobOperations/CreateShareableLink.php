<?php

declare(strict_types=1);

namespace SimpleMVC\Controller\BlobOperations;

use DateTime;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;
use SimpleMVC\Model\AzureInteractionBlob;

class CreateShareableLink implements ControllerInterface
{
  protected $bloblink;

  public function __construct(AzureInteractionBlob $bloblink)
  {
    $this->bloblink = $bloblink;
  }

  public function execute(ServerRequestInterface $request)
  {
    if (!isset($_SESSION['mail'])) {
      echo "Unauthorized. You'll be soon redirected to login.";
      header('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url= /login');
      die();
    }
    $container = $_SESSION['container'];
    $this->bloblink->setContainer($container);

    $value = [];
    $jsonimgs = json_decode($request->getParsedBody()['imgname']);
    $timestamp = $request->getParsedBody()['expirydate'] / 1000;
    $UTCdate = new DateTime("@$timestamp");
    $UTCdateformatted = $UTCdate->format("Y-m-d") . 'T' . $UTCdate->format("H:i:s") . 'Z';

    foreach ($jsonimgs as $url) {
      $value[] = $this->bloblink->getShareableBlob($url, $UTCdateformatted);
    }

    do {
      $filename = substr($UTCdateformatted, 0, 10) . '_' . rand() . '.txt';
    } while (file_exists('sharefile/' . $filename));
    file_put_contents('sharefile/' . $filename, serialize($value));

    $referer = parse_url($request->getServerParams()['HTTP_REFERER']);
    echo $referer['scheme'] . '://' . $referer['host'] . ':' . $referer['port'] .  '/share?url=' . $filename;
  }
}
