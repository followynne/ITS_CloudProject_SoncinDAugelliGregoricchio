<?php

require_once __DIR__. "/../vendor/autoload.php";

use AzureClasses\DAOInteraction;
/*
checked my phpinfo() and found that upload_max_filesize was only set to 2M. I added php.ini to the directory of the offending file and included:

upload_max_filesize = 250M
post_max_size = 250M
max_execution_time = 300
*/

$image_properties = exif_read_data($_FILES['photo']['tmp_name']);
$exif = createExifArrayData($image_properties);

$dao = new DAOInteraction();
print_r($dao->insertExifData(1, $exif));
