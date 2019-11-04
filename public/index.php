<?php
declare(strict_types=1);
namespace AzureClasses;

session_start();
chdir(dirname(__DIR__));
require "vendor/autoload.php";

use League\Plates\Engine;
use AzureClasses\DAOInteraction;
use \PDO;
// check for Login
$templates = new Engine('templates/');

//$name = $_POST['name'];
$mail = $_POST['mail'];
$password = $_POST['pwd'];
$x = new DAOInteraction();
$r = $x->checkUser($mail,$password);

  /*function prepareAndExecuteQuery($sqlQuery){
    $query = $this->conn->prepare($sqlQuery);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }*/



echo $templates->render('_homepage', ['mail' => $r]);

?>
