<?php
declare(strict_types=1);
namespace SimpleMVC\Model;
chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

use SimpleMVC\Model\Interfaces\DeleteAdapterInterface;

class DeleteAdapterAzureStorage implements DeleteAdapterInterface
{

  private $aic;

  function __construct(AzureInteractionContainer $aic){
    $this->aic = $aic;
  }

  function setDependencyService(AzureInteractionContainer $service)
  {
    $this->aic = $service;
  }

  function deleteFrom($name){
      return $this->aic->deleteBlob($name);
  }

  function rollbackDelete($name){
    return $this->aic->rollbackDelete($name);
  }

}
