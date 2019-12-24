<?php

declare(strict_types=1);

namespace SimpleMVC\Controller\BlobOperations;

use SimpleMVC\Controller\ControllerInterface;
use Exception;
use PDOException;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Model\AzureInteractionContainer;
use SimpleMVC\Model\DeleteAdapterAzureStorage;
use SimpleMVC\Model\DAOInteraction;
use SimpleMVC\Model\DeleteAdapterDatabase;

class DeleteBlobs implements ControllerInterface
{
  protected $dao;
  protected $blobClient;
  protected $azureadapter;
  protected $dbadapter;

  public function __construct(
    DAOInteraction $dao,
    AzureInteractionContainer $blobClient,
    DeleteAdapterAzureStorage $azureadapter,
    DeleteAdapterDatabase $dbadapter
  ) {
    $this->dao = $dao;
    $this->blobClient = $blobClient;
    $this->azureadapter = $azureadapter;
    $this->dbadapter = $dbadapter;
  }

  public function execute(ServerRequestInterface $request)
  {
    //TODO: containername needs to be taken from $_SESSION['usercontainer']
    $_SESSION['idContainer'] = 1;
    $container = $_SESSION['idContainer'];
    //TODO



      $this->dao->setIdContainer($container);
      $this->blobClient->setContainer('prova1');

    $this->azureadapter->setDependencyService($this->blobClient);
    $this->dbadapter->setDependencyService($this->dao);

    $data = !is_array($request->getQueryParams()['name']) ? array($request->getQueryParams()['name']) : $request->getQueryParams()['name'];

    foreach ($data as $name) {
      $azuredel = $$this->azureadapter->deleteFrom($name);
      if ($azuredel == 'successful') {
        try {
          $dbdel = $$this->dbadapter->deleteFrom($name);
        } catch (PDOException $ex) {
          $$this->azureadapter->rollbackDelete($name);
          print("Some deletes unsuccessful. Please check after refresh.");
          return;
        }
      } else {
        echo 'Some deletes unsuccessful. Please check after refresh.';
        return;
      }
    }
    echo $dbdel;
  }
}
