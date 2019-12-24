<?php

declare(strict_types=1);

namespace SimpleMVC\Controller;

use League\Plates\Engine;
use PDOException;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Model\DAOInteraction;

class Login implements ControllerInterface
{
  protected $plates;
  protected $dao;

  public function __construct(Engine $plates, DAOInteraction $dao)
  {
    $this->plates = $plates;
    $this->dao = $dao;
  }

  public function execute(ServerRequestInterface $request)
  {
    if ($request->getUri()->getPath() == '/logout'){
      $this->logout();
      exit;
    }
    if (isset($_SESSION['mail'])) {
      header('Location: /');
      return;
    } else if ($request->getMethod() != 'POST') {
      echo $this->plates->render('_login', []);
    } else {
      $mail = $request->getParsedBody()['mail'];
      $password = $request->getParsedBody()['pwd'];
      try {
        $user = $this->dao->checkUser($mail, $password);
      } catch (PDOException $ex){
        $user = false;
      } 
      if ($user) {
        $_SESSION['mail'] == $mail;
        header('Location: /');
        exit;
      } else {
        echo $this->plates->render('_login', []); exit;
      }
    }
  }

  public function logout()
  {
    unset($_SESSION);
    session_unset();
    session_destroy();

    session_start();
    session_regenerate_id(true);
    header('Location: /login');
  }
}
