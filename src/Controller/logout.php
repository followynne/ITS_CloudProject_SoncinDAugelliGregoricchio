<?php
namespace AzureClasses;
session_start();
chdir(dirname(__DIR__));

require "vendor/autoload.php";

use League\Plates\Engine;

$templates = new Engine('templates/');

unset($_SESSION['mail']); 
session_unset();
session_destroy();

session_start();
session_regenerate_id(true);
header('Location:start.php');

