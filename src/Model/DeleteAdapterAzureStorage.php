<?php
declare(strict_types=1);
namespace SimpleMVC\Model;

require_once 'vendor/autoload.php';

use Exception;
use SimpleMVC\Model\Interfaces\DeleteAdapterInterface;

class DeleteAdapterAzureStorage implements DeleteAdapterInterface
{

  private $aic;

  function __construct(){
  }

  function setDependencyService($service)
  {
    if (!$service instanceof AzureInteractionContainer) throw new Exception;
    $this->aic = $service;
  }

  function deleteFrom($name){
      return $this->aic->deleteBlob($name);
  }

  function rollbackDelete($name){
    return $this->aic->rollbackDelete($name);
  }

}
