<?php

declare(strict_types=1);

namespace SimpleMVC\Controller;

use League\Plates\Engine;
use Psr\Http\Message\ServerRequestInterface;

class Gallery implements ControllerInterface
{
  protected $plates;

  public function __construct(Engine $plates)
  {
    $this->plates = $plates;
  }

  public function execute(ServerRequestInterface $request)
  {

    $_SESSION['username'] = 'prova';
    if (!isset($_SESSION['username'])) {
      echo "Unauthorized. You'll be soon redirected to login.";
      header('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url= start.php');
      die();
    }
    // aggiungere lo smistamento per la galleria pagina ta o completa!

    $_SESSION['requestSingleImage'] = 'active';
    echo $this->plates->render('_completegallery', []);
    $_SESSION['requestSingleImage'] = 'active';
    $templates = new Engine('templates/');

    echo $templates->render('_completegallery', ['display' => 'display: none']);
  }
}
