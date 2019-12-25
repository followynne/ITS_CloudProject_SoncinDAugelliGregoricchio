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
    if (!isset($_SESSION['mail'])) {
      echo "Unauthorized. You'll be soon redirected to login.";
      header('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url= /login');
      die();
    }

    $idcontainer = $_SESSION['idcontainer'];
    $containername = $_SESSION['container'];
    $this->dao->setIdContainer($idcontainer);
    $this->blobClient->setContainer($containername);

    $this->azureadapter->setDependencyService($this->blobClient);
    $this->dbadapter->setDependencyService($this->dao);

    $data = !is_array($request->getQueryParams()['name']) ? array($request->getQueryParams()['name']) : $request->getQueryParams()['name'];

    foreach ($data as $name) {
      $azuredel = $this->azureadapter->deleteFrom($name);
      if ($azuredel == 'successful') {
        try {
          $dbdel = $this->dbadapter->deleteFrom($name);
        } catch (PDOException $ex) {
          $this->azureadapter->rollbackDelete($name);
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
