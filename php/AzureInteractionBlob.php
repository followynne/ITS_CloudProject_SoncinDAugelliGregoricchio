<?php
declare(strict_types=1);

namespace AzureClasses;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use AzureClasses\AzureStorageSASOperations;

/**
 * This class access and works on Azure Storage
 * with blob level interactions.
 */
class AzureInteractionBlob
{
  private $container;

  function __construct($container){
    $this->container = $container;
  }

  /** | USED ONLY FOR BLOBS RETRIEVAL BY SQLDATABASE DATA SEARCH (Tags. Exif)
   * Given an array of blobs' names and the index page requested,
   * the function creates and return a Json with:
   * - totalBlobsCount, a.k.a. how many blobs are in the container
   * - max blobs per SubPage
   * - SASToken
   * - the blobs list (array with blob name and url), limited to
   *   maxBlobsPerSubPage value.
   * The main difference from the ones based on container is that
   * this func doesn't create a BlobRestProxy Object to get the blobs
   * but uses the blobs names array to build blob urls.
   * Used for tag-exif search (blobs names are retrieved in the SQLdatabase =>
   * it doesn't require azure storage api)
   */
  function createBlobJsonWithBlobNames(array $names, int $indexPageRequested){
    $saconnection = new AzureStorageSASOperations();
    $sakey = $saconnection->createSAS($this->container);
    $SASToken = $saconnection->getSASTokenValue($sakey);
    $url = $saconnection->createBaseAccountStorageUrl();

    $maxBlobsPerSubPage = 12;
    $startingBlobIndex = 0 + $maxBlobsPerSubPage*$indexPageRequested;

    $blobList = '{
      "pageData":{
        "totalBlobsCount":"' . count($names).'",
        "maxBlobsPerSubPage":'. $maxBlobsPerSubPage.',
        "tempToken": "' . $SASToken . '",
        "blobs":[';

    for ($i = $startingBlobIndex; $i < $startingBlobIndex+$maxBlobsPerSubPage; $i++)
    {
      if (empty($names[$i]['Name'])){
        continue;
      }
      $blobList .= '{"name":"'.$names[$i]['Name'] .'","url":"'. $url . $this->container . '/' . $names[$i]['Name'] .'"},';
    }
    return substr($blobList,0, strlen($blobList)-1).']}}';
  }

  /**
   * Given a @blobname and a optional @expirydate, the func create
   * a SASToken for the specific blob. It saves the SASToken value in a
   * var, then create the BaseAzureStorage url and returns
   * the blob url, complete of BaseUrl, Container+Blob names, SASToken.
   */
  function getShareableBlob($blobname, $expirydate = null){
    $blobandcontainer = $this->container . '/' . $blobname;
    $saconnection = new AzureStorageSASOperations();
    $sakey = $saconnection->createSAS($blobandcontainer, $expirydate);
    $SASToken = $saconnection->getSASTokenValue($sakey);
    $url = $saconnection->createBaseAccountStorageUrl();
    return $this->getBlobByNameAndSAS($url, $blobandcontainer, $SASToken);
  }

  /**
   * This func takes an AzureStorage Base Url, a blob name written as
   * <containername>/<blobname>, a SAS token value,
   * to return an accessible url for the specific blob.
   */
  function getBlobByNameAndSAS(string $storagebaseurl, $blob, $SASToken){
    return $storagebaseurl . $blob . '?' . $SASToken;
  }

}
