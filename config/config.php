<?php
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use Psr\Container\ContainerInterface;
use Dotenv\Dotenv;
use AzureClasses\AzureInteractionComputerVision;
use AzureClasses\AzureInteractionContainer;
use AzureClasses\AzureStorageSASOperations;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;

return [
  'Dotenv' => function(ContainerInterface $c){
    try {
      return $dotenv = DotEnv::create('../');
    } catch (InvalidArgumentException $ex){
      print("Error retrieving personal information.");
      die(print_r($e));
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
      die(print_r($e));
    }
  },
  AzureStorageSASOperations::class =>  DI\create()
  ->constructor(DI\get('Dotenv')),
  AzureInteractionComputerVision::class =>  DI\create()
        ->constructor(DI\get('Dotenv')),
  AzureInteractionContainer::class => DI\autowire()
        ->method('setListBlobOptions', DI\get(ListBlobsOptions::class)),
];
