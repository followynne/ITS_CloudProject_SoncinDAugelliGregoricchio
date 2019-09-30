<?php
declare(strict_types=1);

namespace AzureClasses;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use AzureClasses\AzureStorageSASOperations;

/**
 * This class access and works on Azure Storage
 * with container level interactions.
 */
class AzureInteractionContainer
{
  private $blobClient;
  private $SASToken;
  private $resource;

  /**
   * Create a SAS (Refer to Proper Class for more) to instantiate a
   * Blob Service Access Object + save in a private variable the SASToken
   * value, used for internal class logic.
   * Saves in a private property $resource the container name on which the operations
   * will be executed.
   */
  function __construct($container)
  {
    $this->resource = $container;

    $saconnection = new AzureStorageSASOperations();
    $sakey = $saconnection->createSAS($this->resource);

    $this->SASToken = $saconnection->getSASTokenValue($sakey);
    $this->blobClient = BlobRestProxy::createBlobService($sakey);
  }

  /**
   * Given a container name and a ListBlobsOptions object, the function returns
   * the blob list (with properties) for that container.
   */
  private function getBlobsListfromContainer(object $listBlobsOptions)
  {
    try {
      return $listBlobsAndProperties = $this->blobClient->listBlobs($this->resource, $listBlobsOptions);
    } catch (ServiceException $e) {
      return;
    }
  }

  /**
   * This function calls one of the JsonBlob creation func based
   * on the index page received.
   */
  function getBlobJson(int $indexPageRequested)
  {
    if ($indexPageRequested==-1){
      $blobJson = $this->createAllBlobJson();
    } else {
      $blobJson = $this->createBlobJson($indexPageRequested);
    }
    return $blobJson;
  }

  /**
   * This func calls an Azure API func to get the blobs list in the container,
   * it sorts them by Last Modified Date DESC, then it returns a Json
   * with:
   * - SASToken
   * - the blobs list (array with blob name and url).
   */
  private function createAllBlobJson()
  {
    $listBlobsOptions = new ListBlobsOptions();
    $blobs = $this->getBlobsListfromContainer($listBlobsOptions);
    if ($blobs==null){
      return;
    }
    $blob = $blobs->getBlobs();
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

  /**
   * This func receive the index page and
   * calls an Azure API func to get the blobs list in the container,
   * it sorts them by Last Modified Date DESC, then it returns a Json
   * with:
   * - totalBlobsCount, a.k.a. how many blobs are in the container
   * - max blobs per SubPage
   * - SASToken
   * - the blobs list (array with blob name and url), limited to
   *   maxBlobsPerSubPage value.
   */
  private function createBlobJson(int $indexPageRequested)
  {
    $listBlobsOptions = new ListBlobsOptions();
    $blobs = $this->getBlobsListfromContainer($listBlobsOptions);
    if ($blobs==null){
      return;
    }
    $maxBlobsPerSubPage = 12;
    $startingBlobIndex = 0 + $maxBlobsPerSubPage*$indexPageRequested;
    $blob = $blobs->getBlobs();

    //sorting the blobs for last modified date, DESC; usort sort and return original array modified
    usort($blob, function ($a, $b){
      return strcmp(get_object_vars($b->getProperties()->getLastModified())['date'], get_object_vars($a->getProperties()->getLastModified())['date']);
    });

    $blobList = '{
      "pageData":{
        "totalBlobsCount":"' . count($blobs->getBlobs()).'",
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

  /**
   * Given a blob $name, it calls an Azure API func to delete the blob in the
   * container (the latter given at obj creation).
   */
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

  /**
   * Given a blob names array $name, for each array element
   * calls an Azure API func to delete the blob in the
   * container (the latter given at obj creation).
   */
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
