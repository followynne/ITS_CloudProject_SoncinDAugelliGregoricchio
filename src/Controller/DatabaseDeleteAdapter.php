<?php
declare(strict_types=1);
namespace AzureClasses;
chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

use Interfaces\DeleteAdapterInterface;
use AzureClasses\DAOInteraction;

class DatabaseDeleteAdapter implements DeleteAdapterInterface
{

  private $dao;

  function __construct(DAOInteraction $dao){
    $this->dao = $dao;
  }

  function deleteFrom($name){
      return $this->dao->deleteBlob($name);
  }

  function rollbackDelete($name){
    // TODO
    return;
  }

}
