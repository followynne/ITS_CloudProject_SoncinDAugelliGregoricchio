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
    $_SESSION['username'] = 'prova';
    if (!isset($_SESSION['username'])){
      echo "Unauthorized. You'll be soon redirected to login.";
      header ('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url= start.php');
      die();
    }
    
    
    
    $data = $this->dao->retrieveDataForMapMarkers();
    
    echo $this->templates->render('_map', ['data' => $data]);
  }
}



