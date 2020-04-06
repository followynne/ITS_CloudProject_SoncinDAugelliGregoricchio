<?php $this->layout('home', ['title' => 'HomePage']) ?>

<div class="container align-items-center myCarousel">
  <div class="jumbotron myCarousel">
    <div class="text-center">
      <h1 class="display-4">Hello, <?= $this->e($user) ?></h1>
    </div>
    <div id="carouselExampleSlidesOnly" class="mt-5 carousel slide" data-ride="carousel">
      <div class="carousel-inner">
        <?php foreach ($data as $img) : ?>
          <div class="carousel-item">
            <img class="imagePosition d-block w-50 h-50" src="<?= $this->e($img) ?>" alt="slide" style="width:100%;">
          </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>
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
          <form action="/upload" method="POST" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="image" id="image">
            <button type="submit" class="btn btn-primary btn-round" name="upload">Upload</button>
          </form>
          <span class=""><?= $this->e($error) ?></span>
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


<?php $this->start('js') ?>
<script type="module" src="/script/index.js"></script>
<?php $this->stop() ?>