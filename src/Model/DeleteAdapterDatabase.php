<?php

declare(strict_types=1);

namespace SimpleMVC\Model;

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

use SimpleMVC\Model\Interfaces\DeleteAdapterInterface;

class DeleteAdapterDatabase implements DeleteAdapterInterface
{

  private $dao;

  function __construct(DAOInteraction $dao)
  {
    $this->dao = $dao;
  }

  function setDependencyService(DAOInteraction $service)
  {
    $this->dao = $service;
  }

  function deleteFrom($name)
  {
    return $this->dao->deleteBlob($name);
  }

  function rollbackDelete($name)
  {
    // TODO
    return;
  }
}
