<?php $this->layout('home', ['title' => 'HomePage']) ?>

<div class="text-right">
<button type="button" class="btn btn-dark btn-lg" onclick="window.location.href='./start.php'">Log-out</button>
</div>

<div class="jumbotron">
  <h1 class="display-4">Hello, <?= $this->e($mail)?></h1>
  <p class="lead"> Dashboard</p>
<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-50" src="https://www.zingarate.com/pictures/2018/05/28/aurora-boreale_1.jpeg" alt="First slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-50" src="https://cdn-01.independent.ie/irish-news/article37870710.ece/5f8ab/AUTOCROP/w620/2019-03-02_iri_48402126_I1.JPG" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-50" src="https://files.salsacdn.com/service/1251-STABG/image/Dollarphotoclub-30128064-X3_z_0_27_514.20190207122938.jpg" alt="Third slide">
    </div>
  </div>
</div>
  <hr class="my-4">
  <p>Cose a caso</p>
  <p class="lead">
  <div class="container-fluid" style="width:40%">
    <button type="button" class="btn btn-success btn-lg gallery" onclick="window.location.href='./getblobsperpage.php'">Gallery</button>
    <button type="button" class="btn btn-danger btn-lg upPhotos">Upload Photos</button>
    <button type="button" class="btn btn-warning btn-lg mp">Go To Maps Photo Location Page</button>
    </div>
  </p>
</div>

<?php $this->start('js') ?>
<!-- <script type="module" src="../script/???"></script> -->
<?php $this->stop() ?>
