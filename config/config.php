<?php
require 'vendor/autoload.php';

use Psr\Container\ContainerInterface;
use League\Plates\Engine;
use Dotenv\Dotenv;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use SimpleMVC\Model\AzureInteractionComputerVision;
use SimpleMVC\Model\AzureInteractionContainer;
use SimpleMVC\Model\AzureStorageSASOperations;

return [
  'view_path' => 'src/View',
  Engine::class => function(ContainerInterface $c) {
      return new Engine($c->get('view_path'));
  },
  'Dotenv' => function(ContainerInterface $c){
    try {
      return Dotenv::create(__DIR__);
    } catch (InvalidArgumentException $ex){
      print("Error retrieving personal information.");
      die(print_r($ex));
    }
  },
  'PDO' => function(ContainerInterface $c){
    $c->get('Dotenv')->load();
    try {
      $dns = $_ENV['DB_STRING'];
      $user = $_ENV['DB_USER'];
      $pw = $_ENV['DB_PASSWORD'];
      return new PDO($dns, $user, $pw);
    } catch (Exception $ex){
      print("Error connecting to Database.");
      die(print_r($ex));
    }
  },
  AzureStorageSASOperations::class =>  DI\create()
  ->constructor(DI\get('Dotenv')),
  AzureInteractionComputerVision::class =>  DI\create()
        ->constructor(DI\get('Dotenv')),
  AzureInteractionContainer::class => DI\autowire()
        ->method('setListBlobOptions', DI\get(ListBlobsOptions::class)),
];
