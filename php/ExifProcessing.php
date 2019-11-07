<?php

/**
 * Takes an exif data array and return an array with
 * only the properties storable in the project DB.
 * If a properties isn't set, it set the value to NULL (string).
 */
function createExifArrayData(array $image_properties) {
  $exif = [];
  if (isset($image_properties['FileSize'])){
    $_mb = (float)$image_properties['FileSize']/1000000;
    $exif['filesize'] = sprintf('%.3f', $_mb);
  } else {
    $exif['filesize'] = 'NULL';
  }
  isset($image_properties['MimeType']) ? $exif['filetype'] = $image_properties['MimeType'] : $exif['filetype'] = 'NULL';
  isset($image_properties['COMPUTED']['Height']) ? $exif['height'] = $image_properties['COMPUTED']['Height'] : $exif['height'] = 'NULL';
  isset($image_properties['COMPUTED']['Width']) ? $exif['width'] = $image_properties['COMPUTED']['Width'] : $exif['width'] = 'NULL';
  isset($image_properties['Make']) ? $exif['brand'] = $image_properties['Make'] : $exif['brand'] = 'NULL';
  isset($image_properties['Model']) ? $exif['model'] = $image_properties['Model'] : $exif['model'] = 'NULL';
  if (isset($image_properties['Orientation'])) {
    switch ($image_properties['Orientation']){
      case 1 : $exif['orientation'] = 'Horizontal'; break;
      case 2 : $exif['orientation'] = 'Mirror Horizontal'; break;
      case 3 : $exif['orientation'] = 'Rotate 180'; break;
      case 4 : $exif['orientation'] = 'Mirror Vertical'; break;
      case 5 : $exif['orientation'] = 'Mirror Horizontal and Rotate 270 CW'; break;
      case 6 : $exif['orientation'] = 'Rotate 90 CW'; break;
      case 7 : $exif['orientation'] = 'Mirror Horizontal and Rotate 90 CW'; break;
      case 8 : $exif['orientation'] = 'Rotate 270 CW'; break;
    }
  } else { $exif['orientation'] = 'NULL'; }
  if (isset($image_properties['DateTimeOriginal'])){
    $date = new DateTime($image_properties['DateTimeOriginal']);
    $dateForSqlServer = $date->format('Ymd');
    $exif['date'] = (int)$dateForSqlServer;
  } else {  $exif['date'] = 'NULL'; }
  //https://www.latlong.net/degrees-minutes-seconds-to-decimal-degrees
  //https://www.codexworld.com/get-geolocation-latitude-longitude-from-image-php/
  if (isset($image_properties['GPSLatitude'])){
    $degLat = gps2Num($image_properties['GPSLatitude'][0]);
    $minLat = gps2Num($image_properties['GPSLatitude'][1]);
    $secLat = gps2Num($image_properties['GPSLatitude'][2]);
    $refLat = $image_properties['GPSLatitudeRef'] == 'S' || $image_properties['GPSLatitudeRef'] == 'W'  ? -1 : 1;
    $exif['latitude'] = str_replace(',', '.', ($refLat * ($degLat + ($minLat/60) + ($secLat/3600))));
  } else {  $exif['latitude'] = 'NULL'; }
  if (isset($image_properties['GPSLongitude'])){
    $degLong = gps2Num($image_properties['GPSLongitude'][0]);
    $minLong = gps2Num($image_properties['GPSLongitude'][1]);
    $secLong =  gps2Num($image_properties['GPSLongitude'][2]);
    $refLong = $image_properties['GPSLongitudeRef'] == 'W' || $image_properties['GPSLongitudeRef'] == 'S' ? -1 : 1;
    $exif['longitude'] = str_replace(',', '.', ($refLong * ($degLong + ($minLong/60) + ($secLong/3600))));
  } else {  $exif['longitude'] = 'NULL'; }
  return $exif;
}

/**
 * Takes a GPS coordinates part and returns its float value
 */
function gps2Num($coordPart){
  $parts = explode('/', $coordPart);
  if(count($parts) <= 0)
  return 0;
  if(count($parts) == 1)
  return $parts[0];
  return floatval($parts[0]) / floatval($parts[1]);
}
