<?php

declare(strict_types=1);

namespace SimpleMVC\Controller\BlobOperations;

use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Controller\ControllerInterface;

class GetSharedBlobs implements ControllerInterface
{

  public function __construct()
  {
  }

  public function execute(ServerRequestInterface $request)
  {
    $data = $request->getQueryParams()['url'];
    $indexPageRequested = $request->getQueryParams()['indexpage'];

    $filedata =  file_get_contents('sharefile/' . $data);

    $dataavailable = unserialize($filedata);
    $maxBlobsPerSubPage = 12;
    $startingBlobIndex = 0 + $maxBlobsPerSubPage * $indexPageRequested;

    $blobList = '{
      "pageData":{
        "totalBlobsCount":"' . count($dataavailable) . '",
        "maxBlobsPerSubPage":' . $maxBlobsPerSubPage . ',
        "blobs":[';

    for ($i = $startingBlobIndex; $i < $startingBlobIndex + $maxBlobsPerSubPage; $i++) {
      if (empty($dataavailable[$i])) {
        continue;
      }
      $blobList .= '{"url":"' . $dataavailable[$i] . '"},';
    }
    echo substr($blobList, 0, strlen($blobList) - 1) . ']}}';
  }
}
