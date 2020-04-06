<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?= $this->e($title) ?></title>
</head>

<body>

  <?= $this->insert('navbar') ?>  
  <?= $this->section('content') ?>
  <?= $this->insert('footer') ?>

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.15.0/css/mdb.min.css" rel="stylesheet">

  
  <script src="/dist/assets/jquery.min.js"></script>
  <script src="/dist/assets/popper.js"></script>
  <script src="/dist/assets/bootstrap.js"></script>
  <script src="/dist/assets/clipboard.min.js"></script>
  <link rel="stylesheet" href="/dist/assets/bootstrap.min.css">
  <?= $this->section('js') ?>
  <link rel="stylesheet" type="text/css" href="/dist/assets/select2.min.css">
  <link rel="stylesheet" type="text/css" href="/css/util.css">
  <link rel="stylesheet" type="text/css" href="/css/main.css">
  <link rel="stylesheet" type="text/css" href="/css/home.css">
  <link rel="stylesheet" type="text/css" href="/css/gallery.css">
  <link rel="stylesheet" type="text/css" href="/css/footer.css">
  <link rel="icon" type="image/png" href="/images/icons/favicon.ico" />
  <link rel="stylesheet" type="text/css" href="/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">

</body>

</html>