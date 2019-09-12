<?php
session_start();
require "vendor/autoload.php";

use League\Plates\Engine;

// check for Login

//here goes the login/signup login, if exist render _login->home else render signup[...] and come back here
$templates = new Engine('templates/');
if (true){
  //person logged in session
} else {
  //redirect to index.php with login data in session
  if (true){
    //login exist, must be tested if logged
    echo $templates->render('_login', []);
  } else if (false){
    //login not exist, click on signup
    //redirect to signup.php with login data in session

  }
}


?>
