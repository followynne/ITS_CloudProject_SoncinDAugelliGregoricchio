<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?= $this->e($title) ?></title>
</head>

<body>

  <?= $this->section('content') ?>

  <script src="/dist/assets/jquery.min.js"></script>
  <script src="/dist/assets/popper.js"></script>
  <script src="/dist/assets/bootstrap.js"></script>
  <script src="/dist/assets/clipboard.min.js"></script>
  <link rel="stylesheet" href="/dist/assets/bootstrap.min.css">
  <?= $this->section('js') ?>
  <link rel="stylesheet" type="text/css" href="/dist/assets/animate.min.css">
  <link rel="stylesheet" type="text/css" href="/dist/assets/hamburgers.min.css">
  <link rel="stylesheet" type="text/css" href="/dist/assets/select2.min.css">
  <link rel="stylesheet" type="text/css" href="/css/util.css">
  <link rel="stylesheet" type="text/css" href="/css/main.css">
  <link rel="stylesheet" type="text/css" href="/css/home.css">
  <link rel="icon" type="image/png" href="/images/icons/favicon.ico" />
  <link rel="stylesheet" type="text/css" href="/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">

</body>

</html>