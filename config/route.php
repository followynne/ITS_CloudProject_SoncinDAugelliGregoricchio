<?php
use SimpleMVC\Controller;

return [
    'GET /' => Controller\Home::class,
    'GET /login' => Controller\Login::class,
    'POST /login' => Controller\Login::class,
    'GET /logout' => Controller\Login::class,
    'GET /register' => Controller\Register::class,
    'POST /register' => Controller\Register::class,
    'GET /gallery' => Controller\Gallery::class,
    'GET /completegallery' => Controller\Gallery::class,
    'GET /showone' => Controller\ShowOne::class,
    'POST /upload' => Controller\Upload::class,
    'GET /share' => Controller\Shared::class,
    'GET /map' => Controller\Map::class,
    /*================================================*/
    'GET /getblobs' => Controller\BlobOperations\GetJsonBlobs::class,
    'DELETE /deleteblobs' => Controller\BlobOperations\DeleteBlobs::class,
    'POST /search' => Controller\BlobOperations\GetSearchedBlobs::class,
    'POST /shareablelink' => Controller\BlobOperations\CreateShareableLink::class,
    'GET /sharedblobslist' => Controller\BlobOperations\GetSharedBlobs::class,


];
