<?php $this->layout('home', ['title' => 'HomePage']) ?>

<div class="text-right">
  <button type="button" class="btn btn-dark btn-lg" onclick="window.location.href='/public/logout.php'">Log-out</button>
</div>

<div class="jumbotron">
  <h1 class="display-4">Hello, <?= $this->e($mail)?></h1>
  <p class="lead"> Dashboard</p>

  <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
    <?php foreach ($url as $bb)
          {
            for($i = 0; $i <1; $i++)
            {
              if(!empty($bb))
              {
                  ?><div class="carousel-item active">
                  <img class="d-block w-50" src="<?= $this->e($bb->getUrl())?>" alt="<?= $this->e($bb->getName())?>" >
                </div>
                <?php
                }
                else
                {
                  $imageRandom = array();
                  for($i = 0; $i <6; $i++){
                    ?><div class="carousel-item active">
                    <img class="d-block w-50" src="https://www.phonetoday.it/wp-content/uploads/2018/02/Nothing.jpg" alt="" >
                  </div><?php
                  }
                }
            }
          } ?> 
    </div>
  </div>

  <hr class="my-4">
  <p>Cose a caso</p>
  <p class="lead">
  <div class="container-fluid" style="width:40%">
    <button type="button" class="btn btn-success btn-lg gallery" onclick="window.location.href='/public/getblobsperpage.php'">Gallery</button>
    <button type="button" class="btn btn-danger btn-lg upPhotos" data-toggle="modal" data-target="#exampleModal">Upload Photos</button>
    <button type="button" class="btn btn-warning btn-lg mp" onclick="window.location.href='/public/map.php'">Go To Maps Photo Location Page</button>
  </div>

  <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" id="exampleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="/php/UploadPhotos.php" method="POST" enctype="multipart/form-data">
              Select image to upload:
              <input type="file" name="image" id="image">
              <button type="submit" class="btn btn-primary btn-round" name="upload">Upload</button>          
          </form>
        </div>
        <div class="row">
          <div class="col-lg-5 mx-auto">
            <div>
              <img src="https://res.cloudinary.com/mhmd/image/upload/v1557366994/img_epm3iz.png" alt="" width="200" class="d-block mx-auto mb-4 rounded-pill">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->start('js') ?>
  <!-- <script type="module" src="../script/???"></script> -->
<?php $this->stop() ?>