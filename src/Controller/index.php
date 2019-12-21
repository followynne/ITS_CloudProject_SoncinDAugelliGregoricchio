<?php
declare(strict_types=1);
namespace AzureClasses;

session_start();
chdir(dirname(__DIR__));
require "vendor/autoload.php";

use League\Plates\Engine;

$templates = new Engine('templates/');

if (isset($_SESSION['mail'])) {
  $mail = $_SESSION['mail'];
  echo $templates->render('_homepage', ['mail'=> $mail]);
} else {
  echo '<script type="text/javascript">
          alert("Credentials wrong");
        </script>';
  header('Location: start.php');
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use DI\ContainerBuilder;
use SimpleMVC\Controller\Error404;
use Zend\Diactoros\ServerRequestFactory;

$builder = new ContainerBuilder();
$builder->addDefinitions('config/container.php');
$container = $builder->build();

$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

// Routing
$path   = $request->getUri()->getPath();
$method = $request->getMethod();
$murl   = sprintf("%s %s", $method, $path);

$routes = require 'config/route.php';
$controllerName = $routes[$murl] ?? Error404::class;

$controller = $container->get($controllerName);
$controller->execute($request);
