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
    if ($request->getUri()->getPath() == '/logout') {
      $this->logout();
      exit;
    }
    if (isset($_SESSION['mail'])) {
      echo $this->plates->render('_homepage', ['user' => $_SESSION['name']]);
      die;
    } else if ($request->getMethod() != 'POST') {
      echo $this->plates->render('_login', ['msg' => '']);
    } else {
      $mail = $request->getParsedBody()['mail'];
      $password = $request->getParsedBody()['pwd'];
      try {
        $user = $this->dao->validateLogin($mail, $password);
      } catch (PDOException $ex) {
        $user = false;
      }
      if (!$user) {
        echo $this->plates->render('_login', ['msg' => 'Login Error.']);
        exit;
      } else {
        $_SESSION['mail'] = $user['mail'];
        $_SESSION['user'] = $user['name'];
        $_SESSION['idcontainer'] = $user['IdContainer'];
        $_SESSION['container'] = $user['ContainerName'];
        header('Location: /');
        exit;
      }
    }
  }

  private function logout()
  {
    unset($_SESSION);
    session_unset();
    session_destroy();

    header('Location: /');
  }
}
