<?php

require_once __DIR__. "/../vendor/autoload.php";


use AzureClasses\DAOInteraction;


// $test = new DAOInteraction();
// echo $test->test();

// $file = var_dump($_FILES);
// $exif = exif_read_data('/home/matteo/Immagini/exiftest.jpeg');
// $image_properties = getimagesize($_FILES['photo']['tmp_name']);
$image_properties = exif_read_data($_FILES['photo']['tmp_name']);
print "<PRE>";
print_r($image_properties);
print "</PRE>";


//TODO:
//create the correct db
//test get info from db
//create a element
//create element with exif
//test exit with diff img
 ?>
