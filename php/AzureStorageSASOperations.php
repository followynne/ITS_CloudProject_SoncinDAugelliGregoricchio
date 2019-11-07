<?php
declare(strict_types=1);
namespace AzureClasses;
chdir(dirname(__DIR__));

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Internal\StorageServiceSettings;
use MicrosoftAzure\Storage\Blob\BlobSharedAccessSignatureHelper;
use MicrosoftAzure\Storage\Common\Internal\Resources;
use Dotenv\Dotenv;

/**  refer to:
 *    https://docs.microsoft.com/it-it/rest/api/storageservices/create-service-sas
 *    api docs at :
 *    https://github.com/Azure/azure-storage-php/blob/master/azure-storage-blob/src/Blob/BlobSharedAccessSignatureHelper.php

 *    MakeSASConnection object gets credentials files and load them for usage.
 *    The class interact with Azure Storage to create SHARED ACCESS RETRIEVE
 *    keys (and tokens), used to enable user access to own storage resources.
 */

class AzureStorageSASOperations {

  /**
   * The constructor loads .env file to get Azure Storage
   * owner data (used to create SAS) via PHP-DI configuration.
   */
  function __construct(Dotenv $dotenv){
    $dotenv->load();
  }

  /**
   * It creates the Base Storage Url for each of the storage resources.
   */
  function createBaseAccountStorageUrl(){
    $connectionString = $_ENV['CONNECTION_STRING'];
    $settings = StorageServiceSettings::createFromConnectionString($connectionString);
    $accountName = $settings->getName();
    return 'https://'. $accountName . '.blob.core.windows.net/';
  }

  /**
   * Given a @resource and an optional @date, the function calls Azure API
   * to make a SAS connection string. SAS can be made for 3 resource-type:
   * - container,
   * - blob,
   * - blob with expiry date specified for the SAS.
   */
  function createSAS(string $resource, string $date = null ){
    $connectionString = $_ENV['CONNECTION_STRING'];
    $settings = StorageServiceSettings::createFromConnectionString($connectionString);
    $accountName = $settings->getName();
    $accountKey = $settings->getKey();

    $sharedAccessUrl = new BlobSharedAccessSignatureHelper(
      $accountName,
      $accountKey
    );

    // isset($date) = SAS for Blob with ExpiryDate Specified
    // otherwise: if resource contains / is a SAS Blob else is a SAS Container
    if (!($date == null)){
      $sas = $sharedAccessUrl->generateBlobServiceSharedAccessSignatureToken(
        Resources::RESOURCE_TYPE_BLOB,
        $resource,
        'r',                            // ReadCreatWriteDeleteList
        $date
      );
    } else {
      if (strstr($resource, '/')){
        $sas = $sharedAccessUrl->generateBlobServiceSharedAccessSignatureToken(
          Resources::RESOURCE_TYPE_BLOB,
          $resource,
          'r',                            // ReadCreatWriteDeleteList
          $this->getExpiryTime()       // A valid ISO 8601 format expiry time
        );
      } else {
        $sas = $sharedAccessUrl->generateBlobServiceSharedAccessSignatureToken(
          Resources::RESOURCE_TYPE_CONTAINER,
          $resource,
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

  /**
   * This function returns a DateTime obj 3 minutes in the future from the
   * current date.
   */
  private function getExpiryTime(){
    $date_utc = new \DateTime("now + 3 minutes", new \DateTimeZone("UTC"));
    return $date_utc->format("Y-m-d").'T'. $date_utc->format("H:i:s") .'Z';
  }

  /**
   * Given an SAS connection string, it returns only the SAS Token Value
   * (without account url and resource data).
   */
  function getSASTokenValue($SAScompleteskey){
    $SASQueryKey = 'SharedAccessSignature=';
    $offsetWhereSASQueryKeyStart = strpos($SAScompleteskey, $SASQueryKey);
    return substr($SAScompleteskey, $offsetWhereSASQueryKeyStart+strlen($SASQueryKey));
  }

}
