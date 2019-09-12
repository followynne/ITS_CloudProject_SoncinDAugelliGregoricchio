<?php
session_start();
require "vendor/autoload.php";

use League\Plates\Engine;

// check for Login

$_SESSION['test'] = 'cartomante';
echo $_SESSION['test'];

$templates = new Engine('templates/');
echo $templates->render('_homepage', []);


?>
