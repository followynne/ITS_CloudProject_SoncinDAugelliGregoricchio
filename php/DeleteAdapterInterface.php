<?php
namespace Interfaces;

interface DeleteAdapterInterface
{
  /*
   * Adapter Interface for Delete and Rollback Operation.
   */
  public function deleteFrom($name);
  public function rollbackDelete($name);

}
