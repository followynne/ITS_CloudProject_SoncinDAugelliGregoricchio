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
    //TODO
    $container = $_SESSION['container'];
    $container = 'prova1';
    //TODO

    $this->bloblink->setContainer($container);

    $value = [];
    $jsonimgs = json_decode($_POST['imgname']);
    $timestamp = $_POST['expirydate'] / 1000;
    $UTCdate = new DateTime("@$timestamp");
    $UTCdateformatted = $UTCdate->format("Y-m-d") . 'T' . $UTCdate->format("H:i:s") . 'Z';

    foreach ($jsonimgs as $url) {
      $value[] = $this->bloblink->getShareableBlob($url, $UTCdateformatted);
    }

    do {
      $filename = substr($UTCdateformatted, 0, 10) . '_' . rand() . '.txt';
    } while (file_exists('sharefile/' . $filename));
    file_put_contents('sharefile/' . $filename, serialize($value));

    // inserire e controllare il serverhost
    $referer = parse_url($_SERVER['HTTP_REFERER']);
    echo $referer['scheme'] . '://' . $referer['host'] . ':' . $referer['port'] .  '/public/sharedfolder.php?url=' . $filename;
  }
}
