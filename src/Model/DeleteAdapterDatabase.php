<?php

declare(strict_types=1);

namespace SimpleMVC\Model;

require_once 'vendor/autoload.php';

use SimpleMVC\Model\Interfaces\DeleteAdapterInterface;

class DeleteAdapterDatabase implements DeleteAdapterInterface
{

  private $dao;

  function __construct()
  {
  }

  function setDependencyService($service)
  {
    if (!$service instanceof DAOInteraction) return 'ecco';
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
