<?php

declare(strict_types=1);

namespace SimpleMVC\Controller;

use League\Plates\Engine;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Model\DAOInteraction;

class Map implements ControllerInterface
{
  protected $plates;
  protected $dao;

  public function __construct(Engine $plates, DAOInteraction $dao )
  {
    $this->plates = $plates;
    $this->dao = $dao;
  }

  public function execute(ServerRequestInterface $request)
  {
    //$_SESSION['mail'] = 'prova';
    unset($_SESSION['mail']);
    if (!isset($_SESSION['mail'])){
      echo "Unauthorized. You'll be soon redirected to login.";
      header ('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url= /login');
      die();
    }
    $data = $this->dao->retrieveDataForMapMarkers();
    echo $this->plates->render('_map', ['data' => $data]);
  }
}



