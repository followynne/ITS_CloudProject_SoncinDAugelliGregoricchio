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

    $_SESSION['mail'] = 'prova';
    if (!isset($_SESSION['mail'])) {
      echo "Unauthorized. You'll be soon redirected to login.";
      header('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url=  /login');
      die();
    }
    // aggiungere lo smistamento per la galleria pagina ta o completa!
    $_SESSION['requestSingleImage'] = 'active';
    if ($request->getUri()->getPath() == '/completegallery') {
      echo $this->plates->render('_completegallery', ['display' => 'display: none']);
    } else {
      echo $this->plates->render('_completegallery', []);
    }
  }
}
