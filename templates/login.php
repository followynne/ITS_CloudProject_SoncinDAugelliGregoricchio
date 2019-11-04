<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $this->e($title) ?></title>
    <script src="/node_modules/jquery/dist/jquery.min.js"></script>
    <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.css"></link>
    <link rel="stylesheet" href="/css/main.css"></link>
</head>
<body>

  <?= $this->section('content')?>
  <?= $this->section('js')?>

</body>
</html>
