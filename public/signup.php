<?php
session_start();
chdir(dirname(__DIR__));
require "vendor/autoload.php";

use League\Plates\Engine;

// check for Login

$templates = new Engine('templates/');
echo $templates->render('_register', []);


?>
