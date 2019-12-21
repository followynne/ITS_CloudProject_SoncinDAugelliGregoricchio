<?php
declare(strict_types=1);
namespace AzureClasses;
chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

use Interfaces\DeleteAdapterInterface;
use AzureClasses\AzureInteractionContainer;

class AzureStorageDeleteAdapter implements DeleteAdapterInterface
{

  private $aic;

  function __construct(AzureInteractionContainer $aic){
    $this->aic = $aic;
  }

  function deleteFrom($name){
      return $this->aic->deleteBlob($name);
  }

  function rollbackDelete($name){
    return $this->aic->rollbackDelete($name);
  }

}
