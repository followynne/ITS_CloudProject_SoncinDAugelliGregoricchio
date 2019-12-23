<?php

namespace SimpleMVC\Model\Interfaces;

interface DeleteAdapterInterface
{
  /*
   * Adapter Interface for Delete and Rollback Operation.
   */
  public function setDependencyService($service);
  public function deleteFrom($name);
  public function rollbackDelete($name);
}
