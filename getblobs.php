<?php

require "vendor/autoload.php";

use League\Plates\Engine;

$templates = new Engine('templates/');

echo $templates->render('showall', []);
