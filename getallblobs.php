<?php
session_start();
require "vendor/autoload.php";

use League\Plates\Engine;

// check for Login

$_SESSION['requestSingleImage'] = 'active';
$templates = new Engine('templates/');

echo $templates->render('_completegallery', ['display' => 'display: none']);
