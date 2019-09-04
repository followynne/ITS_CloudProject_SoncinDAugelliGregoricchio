<?php
declare(strict_types=1);

namespace AzureClasses;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;

class AzureInteraction
{
  private $blobClient;

  function __construct()
  {
    $connectionString = "DefaultEndpointsProtocol=https;AccountName=imagestorageforcloudprj;AccountKey=dRD9BsGv+fdmjXWk0uyW+ua+X05zp9kOiu2aoZfRwjVmKT5FsodeS2y/Rf8SMz6fZpox3tvL7eQCOVx6qzjW6w==;EndpointSuffix=core.windows.net";
    $this->blobClient = BlobRestProxy::createBlobService($connectionString);
  }

  function getBlobJson(string $containerName, int $indexPageRequested)
  {
    $blobJson = $this->createBlobJson($containerName, $indexPageRequested);
    return $blobJson;
  }

  function createBlobJson(string $container, int $indexPageRequested){
    $listBlobsOptions = new ListBlobsOptions();
    $blobs = $this->getBlobsListfromContainer($container, $listBlobsOptions);
    $maxBlobsPerSubPage = 12;
    $startingBlobIndex = 0 + $maxBlobsPerSubPage*$indexPageRequested;
    $blob = $blobs->getBlobs();

    $blobList = '{
      "pageData":{
      "totalBlobsCount":"' . count($blobs->getBlobs()).'",
      "maxBlobsPerSubPage":'. $maxBlobsPerSubPage.',
      "blobs":[';

    for ($i = $startingBlobIndex; $i < $startingBlobIndex+$maxBlobsPerSubPage; $i++)
    {
      if (empty($blob[$i])){
        continue;
      }
      $blobList .= '{"name":"'.$blob[$i]->getName().'","url":"'.$blob[$i]->getUrl().'"},';
    }
    return substr($blobList,0, strlen($blobList)-1).']}}';
  }

  function getBlobsListfromContainer(string $container, object $listBlobsOptions){
    return $listBlobsAndProperties = $this->blobClient->listBlobs($container, $listBlobsOptions);
  }
}
