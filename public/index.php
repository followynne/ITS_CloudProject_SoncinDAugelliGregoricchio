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