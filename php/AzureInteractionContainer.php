<?php
declare(strict_types=1);
namespace AzureClasses;
chdir(dirname(__DIR__));

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use AzureClasses\AzureStorageSASOperations;

/**
 * This class access and works on Azure Storage
 * with container-level interactions.
 */
class AzureInteractionContainer
{
  private $saconnection;
  private $blobClient;
  private $SASToken;
  private $resource;
  private $lbo;

  /**
   * Store the AzureStorageSASOperations object instance.
   */
  function __construct(AzureStorageSASOperations $sas)
  {
    $this->saconnection = $sas;
  }

  /**
  * Set the ContainerName. After that, it creates a SAS (Refer to Proper Class for more)
  * to instantiate a Blob Service Access Object + store in a private variable the SASToken
  * value, used for internal class logic.
  */
  function setContainer(string $container){
    $this->resource=$container;
    $this->createBlobClientAndToken();
  }

  private function createBlobClientAndToken(){
    try {
      $sakey = $this->saconnection->createSAS($this->resource);
      $this->SASToken = $this->saconnection->getSASTokenValue($sakey);
      $this->blobClient = BlobRestProxy::createBlobService($sakey);
    } catch (Exception $e){
      throw $e;
    }
  }

  /**
   * Set the ListBlobOptions (via PHP-DI configuration, btw).
   */
  function setListBlobOptions(ListBlobsOptions $lbo){
    $this->lbo = $lbo;
  }

  /**
   * Upload a blob in the Azure Container, setting an unique reference name for the blob.
   */
  function uploadBlob(string $name, $contents)
  {
    try {
      $this->blobClient->createBlockBlob($this->resource, $name, $contents);
      $msg = 'Added successful';
    } catch (ServiceException $e){
      $msg = $e->getMessage();
    }
    return $msg;
  }

  /**
   * TODO: WIP
   */
  function getLastsFiveBlobs($blob)
  {
    for ($i=0; $i<6; $i++){
      $blob = $this->blobClient->getBlob($this->resource, $blob);
      usort($blob, function ($a, $b){
        return strcmp(get_object_vars($b->getProperties()->getLastModified())['date'], get_object_vars($a->getProperties()->getLastModified())['date']);
      });
  
      return fpassthru($blob->getContentStream());
    }
  }

  /**
   * Given a container name and a ListBlobsOptions object, the function returns
   * the blob list (with properties) for that container.
   */
  private function getBlobsListfromContainer()
  {
    try {
      return $listBlobsAndProperties = $this->blobClient->listBlobs($this->resource);
    } catch (ServiceException $e) {
      return;
    }
  }

  /**
   * This function calls one of the JsonBlob creation func, based
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
    $blobs = $this->getBlobsListfromContainer();
    if ($blobs==null){
      return;
    }
    $blob = $blobs->getBlobs();

    //sorting the blobs for last modified date, DESC; usort sort and return original array modified
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
      $this->lbo->setContinuationToken($blobs->getContinuationToken());
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
    $blobs = $this->getBlobsListfromContainer();
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
      $res = 'successful';
    } catch (ServiceException $e){
      $res = $e->getMessage();
    }
    return $res;
  }

  /**
   * Given a blob $name, it makes a PUT request to Azure Undelete API
   * to undelete a blob in the container. cUrl library.
   */
  function rollbackDelete($name){
    $apiurltocall = $this->saconnection->createBaseAccountStorageUrl()
              . $this->resource . '/' . $name . '?comp=undelete&'
              . $this->saconnection->getSASTokenValue($this->saconnection->createSAS($this->resource));
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiurltocall);
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
  }
}
