<?php
declare(strict_types=1);

namespace AzureClasses;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use AzureClasses\MakeSAConnection;

class AzureInteractionContainer
{
  private $blobClient;
  private $SASToken;
  private $resource;

  function __construct($container)
  {
    $this->resource = $container;

    $saconnection = new MakeSAConnection();
    $sakey = $saconnection->createSAS($this->resource);

    $this->SASToken = $saconnection->getSASTokenValue($sakey);
    $this->blobClient = BlobRestProxy::createBlobService($sakey);
  }

  private function getBlobsListfromContainer(string $container, object $listBlobsOptions)
  {
    try {
      return $listBlobsAndProperties = $this->blobClient->listBlobs($container, $listBlobsOptions);
    } catch (ServiceException $e) {
      return;
    }
  }

  function getBlobJson(int $indexPageRequested)
  {
    if ($indexPageRequested==-1){
      $blobJson = $this->createAllBlobJson($this->resource);
    } else {
      $blobJson = $this->createBlobJson($this->resource, $indexPageRequested);
    }
    return $blobJson;
  }

  private function createAllBlobJson(string $container)
  {
    $listBlobsOptions = new ListBlobsOptions();
    $blobs = $this->getBlobsListfromContainer($container, $listBlobsOptions);
    if ($blobs==null){
      return;
    }
    $blob = $blobs->getblobsperpage();
    usort($blob, function ($a, $b){
      return strcmp(get_object_vars($b->getProperties()->getLastModified())['date'], get_object_vars($a->getProperties()->getLastModified())['date']);
    });
    $blobList = '{
      "pageData":{
        "tempToken": "' . $this->SASToken . '",
        "blobs":[';
    do {
      foreach ($blob as $bb){
        $blobList .= '{"name":"'.$bb->getName().'","url":"'.$bb->getUrl() . '"},';
      }
      $listBlobsOptions->setContinuationToken($blobs->getContinuationToken());
    } while ($blobs->getContinuationToken());

    return substr($blobList,0, strlen($blobList)-1).']}}';
  }

  private function createBlobJson(string $container, int $indexPageRequested)
  {
    $listBlobsOptions = new ListBlobsOptions();
    $blobs = $this->getBlobsListfromContainer($container, $listBlobsOptions);
    if ($blobs==null){
      return;
    }
    $maxBlobsPerSubPage = 12;
    $startingBlobIndex = 0 + $maxBlobsPerSubPage*$indexPageRequested;
    $blob = $blobs->getblobsperpage();

    //sorting the blobs for last modified date, DESC; usort sort and return original array modified
    usort($blob, function ($a, $b){
      return strcmp(get_object_vars($b->getProperties()->getLastModified())['date'], get_object_vars($a->getProperties()->getLastModified())['date']);
    });

    $blobList = '{
      "pageData":{
        "totalBlobsCount":"' . count($blobs->getblobsperpage()).'",
        "maxBlobsPerSubPage":'. $maxBlobsPerSubPage.',
        "tempToken": "' . $this->SASToken . '",
        "blobs":[';

    for ($i = $startingBlobIndex; $i < $startingBlobIndex+$maxBlobsPerSubPage; $i++)
    {
      if (empty($blob[$i])){
        continue;
      }
      $blobList .= '{"name":"'.$blob[$i]->getName().'","url":"'.$blob[$i]->getUrl() .'"},';
    }
    return substr($blobList,0, strlen($blobList)-1).']}}';
  }

  function deleteBlob(string $name)
  {
    try {
      $this->blobClient->deleteBlob($this->resource, $name);
      $test = 'Delete successful';
    } catch (ServiceException $e){
      $test = $e->getMessage();
    }
    return $test;
  }

  function deleteBlobs(array $name)
  {
    //NY = block both || YN=GO Y, STOP N || YNY = GO Y BLOCK THE REST
    try {
      foreach($name as $blobInArray){
        $this->blobClient->deleteBlob($this->resource, $blobInArray);
      }
      $test = 'Delete successful';
    } catch (ServiceException $e){
      $test = $e->getMessage();
    }
    return $test;
  }
}
