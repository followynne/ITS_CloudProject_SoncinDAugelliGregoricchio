<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $this->e($title) ?></title>
    <script src="/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="/node_modules/clipboard/dist/clipboard.min.js"></script>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/css/footer.css">
    <link rel="stylesheet" type="text/css" href="/css/gallery.css">
    <link rel="stylesheet" type="text/css" href="/css/util.css">


</head>
<body>
  <?= $this->section('content')?>
  <?= $this->section('js')?>
  <link rel="icon" type="image/png" href="/images/icons/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href= "/node_modules/animate.css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/hamburgers/dist/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/select2/dist/css/select2.min.css">
  <?= $this->insert('footer') ?>
    
</body>
</html>

