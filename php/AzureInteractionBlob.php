<?php
declare(strict_types=1);

namespace AzureClasses;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use AzureClasses\MakeSAConnection;


class AzureInteractionBlob
{
  private $blobClient;
  private $SASToken;
  private $resource;

  function __construct($blob)
  {
    $this->resource = $blob;
  }

  private function getSASTokenValue($sakey){
    $SASQueryKey = 'SharedAccessSignature=';
    $offsetWhereSASQueryKeyStart = strpos($sakey, $SASQueryKey);
    return substr($sakey, $offsetWhereSASQueryKeyStart+strlen($SASQueryKey));
  }

  function getShareableBlob($expirydate = null){
    $saconnection = new MakeSAConnection();
    $sakey = $saconnection->createSAS($this->resource, $expirydate);
    $this->SASToken = $this->getSASTokenValue($sakey);
    $url = $saconnection->createBaseBlobUrl();
    return $this->getBlobByNameAndSAS($url);

  }

  function getBlobByNameAndSAS(string $url){
    return $url . $this->resource . '?' . $this->SASToken;
  }


}
