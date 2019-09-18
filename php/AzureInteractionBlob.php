<?php
declare(strict_types=1);

namespace AzureClasses;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use AzureClasses\MakeSAConnection;


class AzureInteractionBlob
{
  private $container;

  function __construct(){}

  function setContainerName($container){
    $this->container = $container;
  }

  function createBlobJsonWithBlobNames(array $names, int $indexPageRequested){
    $saconnection = new MakeSAConnection();
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
      $blobList .= '{"name":"'.$names[$i]['Name'] .'","url":"'. $url . $this->resource . '/' . $names[$i]['Name'] .'"},';
    }
    return substr($blobList,0, strlen($blobList)-1).']}}';
  }

  function getShareableBlob($blob, $expirydate = null){
    $saconnection = new MakeSAConnection();
    $sakey = $saconnection->createSAS($blob, $expirydate);
    $SASToken = $saconnection->getSASTokenValue($sakey);
    $url = $saconnection->createBaseAccountStorageUrl();
    return $this->getBlobByNameAndSAS($url, $blob, $SASToken);

  }

  function getBlobByNameAndSAS(string $url, $blob, $SASToken){
    return $url . $blob . '?' . $SASToken;
  }


}
