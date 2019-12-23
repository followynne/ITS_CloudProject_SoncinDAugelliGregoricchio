<?php

declare(strict_types=1);

namespace SimpleMVC\Controller;

use League\Plates\Engine;
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
    // try{
    //   $dao = $cont->get(DAOInteraction::class);
    // } catch (Exception $e){
    //   echo "Error establishing connection.";
    //   die();
    // }

    // aggiungere la funzione di logout sulla chiamata giusta!
    if (isset($_SESSION['mail'])) {
      header('Location: index.php');
      return;
    } else if (!isset($_POST)) {
      echo $this->plates->render('_login', []);
    } else {
      $mail = $_POST['mail'];
      $password = $_POST['pwd'];
      $user = $this->dao->checkUser($mail, $password);
      if ($user) {
        $_SESSION['mail'];
        header('Location: index.php');
      } else {
        echo $this->plates->render('_login', []);
      }
    }
  }

  public function logout()
  {
    unset($_SESSION['mail']);
    session_unset();
    session_destroy();

    session_start();
    session_regenerate_id(true);
    header('Location:start.php');
  }
}
