<?php
declare(strict_types=1);

namespace AzureClasses;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Internal\StorageServiceSettings;
use MicrosoftAzure\Storage\Blob\BlobSharedAccessSignatureHelper;
use MicrosoftAzure\Storage\Common\Internal\Resources;
use Dotenv\Dotenv;

/*  refer to:
    https://docs.microsoft.com/it-it/rest/api/storageservices/create-service-sas
    example at :
    https://github.com/Azure/azure-storage-php/blob/master/azure-storage-blob/src/Blob/BlobSharedAccessSignatureHelper.php

//  MakeSASConnection object gets credentials files and load them for usage.

//  Main Method createSAS() accept 2 parameters, one required ($resource you're requesting a SAS for)
    and one optional ($date, if the SAS access has an expirytime set)
*/

class MakeSAConnection {

   function __construct(){
    $dotenv = Dotenv::create(__DIR__.'/../');
    $dotenv->load();
  }

  function createBaseAccountStorageUrl(){
    $connectionString = $_ENV['CONNECTION_STRING'];
    $settings = StorageServiceSettings::createFromConnectionString($connectionString);
    $accountName = $settings->getName();
    return 'https://'. $accountName . '.blob.core.windows.net/';
  }

  function createSAS(string $resource, string $date = null ){
    return $this->CreateSharedAccessRetrieve($resource, $date);
  }

  private function CreateSharedAccessRetrieve(string $resource, string $date = null){

    $connectionString = $_ENV['CONNECTION_STRING'];
    $settings = StorageServiceSettings::createFromConnectionString($connectionString);
    $accountName = $settings->getName();
    $accountKey = $settings->getKey();

    $sharedAccessUrl = new BlobSharedAccessSignatureHelper(
        $accountName,
        $accountKey
    );

    // $date set = SAS for Blob with ExpiryDate Specified
    // otherwise: if resource contains / is a SAS Blob else is a SAS Container
    if (!($date == null)){
      // $expirytime = date($date);
      $sas = $sharedAccessUrl->generateBlobServiceSharedAccessSignatureToken(
        Resources::RESOURCE_TYPE_BLOB,
        $resource,                          // TODO: ContainerName must be got from Session Var
        'r',                            // ReadCreatWriteDeleteList
        $date
      );
      } else {
        if (strstr($resource, '/')){
          $sas = $sharedAccessUrl->generateBlobServiceSharedAccessSignatureToken(
            Resources::RESOURCE_TYPE_BLOB,
            $resource,                          // TODO: ContainerName must be got from Session Var
            'r',                            // ReadCreatWriteDeleteList
            $this->getExpiryTime()       // A valid ISO 8601 format expiry time
          );
        } else {
          $sas = $sharedAccessUrl->generateBlobServiceSharedAccessSignatureToken(
            Resources::RESOURCE_TYPE_CONTAINER,
            $resource,                          // TODO: ContainerName must be got from Session Var
            'rcwdl',                            // ReadCreatWriteDeleteList
            $this->getExpiryTime()       // A valid ISO 8601 format expiry time
          );
        }
      }
    return $connectionStringWithSAS = Resources::BLOB_ENDPOINT_NAME .
      '='.
      'https://' .
      $accountName .
      '.' .
      Resources::BLOB_BASE_DNS_NAME .
      ';' .
      Resources::SAS_TOKEN_NAME .
      '=' .
      $sas;
  }

  private function getExpiryTime(){
    $date_utc = new \DateTime("now + 3 minutes", new \DateTimeZone("UTC"));
    return $date_utc->format("Y-m-d").'T'. $date_utc->format("H:i:s") .'Z';
  }

  function getSASTokenValue($SAScompleteskey){
    $SASQueryKey = 'SharedAccessSignature=';
    $offsetWhereSASQueryKeyStart = strpos($SAScompleteskey, $SASQueryKey);
    return substr($SAScompleteskey, $offsetWhereSASQueryKeyStart+strlen($SASQueryKey));
  }

}
