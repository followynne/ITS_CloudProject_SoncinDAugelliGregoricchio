<?php

require_once __DIR__. "/../vendor/autoload.php";


use AzureClasses\DAOInteraction;


/*
checked my phpinfo() and found that upload_max_filesize was only set to 2M. I added php.ini to the directory of the offending file and included:

upload_max_filesize = 250M
post_max_size = 250M
max_execution_time = 300
*/


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
