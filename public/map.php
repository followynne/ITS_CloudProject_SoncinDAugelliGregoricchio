<?php
session_start();

chdir(dirname(__DIR__));
require "vendor/autoload.php";

use League\Plates\Engine;
use AzureClasses\DAOInteraction;

$_SESSION['username'] = 'prova';
if (!isset($_SESSION['username'])){
  echo "Unauthorized. You'll be soon redirected to login.";
  header ('HTTP/1.1 401 Unauthorized');
  header('Refresh:3; url= start.php');
  die();
}

$builder = new DI\ContainerBuilder();
$builder->addDefinitions('config/config.php');
$cont = $builder->build();
$dao = $cont->get(DAOInteraction::class);
$data = $dao->retrieveDataForMapMarkers();

$templates = new Engine('templates/');
echo $templates->render('_map', ['data' => $data]);
