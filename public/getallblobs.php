<?php
session_start();
chdir(dirname(__DIR__));
require "vendor/autoload.php";

use League\Plates\Engine;

$_SESSION['username'] = 'prova';
if (!isset($_SESSION['username'])){
  echo "Unauthorized. You'll be soon redirected to login.";
  header ('HTTP/1.1 401 Unauthorized');
  header('Refresh:3; url= start.php');
  die();
}

$_SESSION['requestSingleImage'] = 'active';
$templates = new Engine('templates/');

echo $templates->render('_completegallery', ['display' => 'display: none']);
