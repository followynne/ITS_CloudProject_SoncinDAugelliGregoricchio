<?php
declare(strict_types=1);
namespace AzureClasses;

session_start();
chdir(dirname(__DIR__));
require "vendor/autoload.php";

use League\Plates\Engine;
use AzureClasses\AzureInteractionContainer;
use AzureClasses\AzureInteractionComputerVision;
use AzureClasses\DAOInteraction;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;

$_SESSION['username'] = 'prova';
$_SESSION['idContainer'] = 1;
$_SESSION['containerName'] = 'prova1';
$user = $_SESSION['username'];
$idcont = $_SESSION['idContainer'];
$contname = $_SESSION['containerName'];
//TODO

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions('config/config.php');
$cont = $builder->build();

$templates = new Engine('templates/');

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions('config/config.php');
$cont = $builder->build();
try {

    $blob = $cont->get(AzureInteractionContainer::class);
    $blob->setContainer('prova1');
    $interaction = $cont->get(AzureInteractionComputerVision::class);
  } catch (Exception $e){
    echo "Error establishing connection.";
    die();
  }
  $blobUrl = $blob->getLastsFiveBlobs();
  print_r($blobUrl);
//   echo $templates->render('_homepage', ['url' => $blobUrl->getUrl(), 'name' =>  $blobUrl->getName()]);
 
