<?php

namespace AzureClasses;

//require "vendor/autoload.php";

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

  function returnBlobJson(string $containerName)
  {
    $blobJson = $this->createBlobJson($containerName);
    return $blobJson;
  }

  function createBlobJson($container){
    $listBlobsOptions = new ListBlobsOptions();
    $listBlobsOptions->setPrefix("HelloWorld");
    $blobs = $this->createBlobsListfromContainer($container);

    $blobList = '{
        "pageData":{
          "totalBlobsCount":"' . count($blobs->getBlobs()).'",
          "maxBlobsPerSubPage":"5",
          "blobs":[';
    do
    {
      foreach ($blobs->getBlobs() as $blob)
      {
        $blobList .= '{"name":"'.$blob->getName().'","url":"'.$blob->getUrl().'"},';
      }
      $listBlobsOptions->setContinuationToken($blobs->getContinuationToken());
    } while($blobs->getContinuationToken());
    return substr($blobList,0, strlen($blobList)-1).']}}';
  }

  function createBlobsListfromContainer($container){
    return $listBlobsAndProperties = $this->blobClient->listBlobs($container);
  }

}
