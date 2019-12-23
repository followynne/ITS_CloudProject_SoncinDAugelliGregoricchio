<?php
declare(strict_types=1);
namespace SimpleMVC\Model;
chdir(dirname(__DIR__));

/**
 * This class access and works on Azure Storage
 * with blob-level interactions.
 */
class AzureInteractionBlob
{
  private $saconnection;
  private $container;

  /**
   * Constructor to assign to property a AzureStorageSASOperations Object parameter.
   */
  function __construct(AzureStorageSASOperations $sas){
    $this->saconnection = $sas;
  }

  /**
   * Set the property container name. Client mandatory operation.
   */
  function setContainer($container){
    $this->container = $container;
  }

  /**
   * From the AzureStorageSASOperations obj, gets SASToken and blob base url.
   */
  function getSASTokenAndBlobUrl($blobandcontainer, $expirydate = null){
    $sakey = $this->saconnection->createSAS($blobandcontainer, $expirydate);
    $SASToken = $this->saconnection->getSASTokenValue($sakey);
    $url = $this->saconnection->createBaseAccountStorageUrl();
    return [$SASToken, $url];
  }

  /**
   * USED ONLY FOR BLOBS RETRIEVAL BY SQL_DB DATA SEARCH (Tags||Exif)
   * --------------------------------------------------------------------
   * Given an array of blobs' names and the index page requested,
   * the function creates and return a Json with:
   * - totalBlobsCount, a.k.a. how many blobs are in the container
   * - max blobs per SubPage
   * - SASToken
   * - the blobs list (array with blob name and url), limited to
   *   maxBlobsPerSubPage value.
   * The main difference from the funcs based on container is that
   * this func doesn't create a BlobRestProxy Object to get the blobs
   * but uses the blobs names array to build each blob url.
   * Used for tag-exif search (blobs names are retrieved in the SQLdatabase =>
   * it doesn't require azure storage api)
   */
  function createBlobJsonWithBlobNames(array $names, int $indexPageRequested){
    $arr = $this->getSASTokenAndBlobUrl($this->container);
    $maxBlobsPerSubPage = 12;
    $startingBlobIndex =  $indexPageRequested != -1 ? 0 + $maxBlobsPerSubPage*$indexPageRequested : 0;
    $finalIndexBlobForCycle = $indexPageRequested != -1 ? $startingBlobIndex+$maxBlobsPerSubPage : count($names);
    $blobList = '{
      "pageData":{
        "totalBlobsCount":' . count($names);
    if ($indexPageRequested != -1){
      $blobList .= ', "maxBlobsPerSubPage":"'. $maxBlobsPerSubPage . '"';
    }
    $blobList .= ', "tempToken": "' . $arr[0] . '",
                 "blobs":[';

    for ($i = $startingBlobIndex; $i < $finalIndexBlobForCycle; $i++)
    {
      if (empty($names[$i]['ReferenceName'])){
        continue;
      }
      $blobList .= '{"name":"'.$names[$i]['ReferenceName'] .'","url":"'. $arr[1] . $this->container . '/' . $names[$i]['ReferenceName'] .'"},';
    }
    return substr($blobList,0, strlen($blobList)-1).']}}';
  }

  /**
   * Given a @blobname and a optional @expirydate, the func first get an SASToken
   * for the blob and the base blob url of the account, then it returns
   * the blob url = {BaseUrl}\{Container}\{Blob}&{SASToken}.
   */
  function getShareableBlob($blobname, $expirydate = null){
    $blobandcontainer = $this->container . '/' . $blobname;
    $arr = $this->getSASTokenAndBlobUrl($blobandcontainer, $expirydate);
    return $this->getBlobByNameAndSAS($arr[1], $blobandcontainer, $arr[0]);
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
