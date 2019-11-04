<?php
session_start();
chdir(dirname(__DIR__));
require "vendor/autoload.php";

use League\Plates\Engine;

if (!isset($_GET['url'])){
  echo "<div>
      You shouldn't be there. Well - you'll be soon redirected to login.
      </div>";
  header ('HTTP/1.1 401 Unauthorized');
  header('Refresh:3; url=start.php');
  die();
}

if (!file_exists('sharefile/' . $_GET['url'])){
  header ("Location: 404.php", 404);
  die();
}

$templates = new Engine('templates/');
echo $templates->render('_sharedgallery', []);
