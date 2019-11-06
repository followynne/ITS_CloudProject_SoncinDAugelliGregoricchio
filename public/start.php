<?php
session_start();
chdir(dirname(__DIR__));
require "vendor/autoload.php";

use League\Plates\Engine;
use AzureClasses\DAOInteraction;

$templates = new Engine('templates/');
$builder = new DI\ContainerBuilder();
$builder->addDefinitions('config/config.php');
$cont = $builder->build();

try{
  $dao = $cont->get(DAOInteraction::class);
} catch (Exception $e){
  echo "Error establishing connection.";
  die();
}

if (isset($_SESSION['mail'])) {
  header ('Location: index.php');
} else if (!isset($_POST)) {
  echo $templates->render('_login', []);
} else {
  $mail = $_POST['mail'];
  $password = $_POST['pwd'];
  $user = $dao->checkUser($mail, $password);
  if ($user) {
    $_SESSION['mail'];
    header('Location: index.php');
  } else {
    echo $templates->render('_login', []);
  }
}