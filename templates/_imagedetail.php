<?php $this->layout('gallery', ['title' => 'Photo:' . $this->e($name)])?>

<div class="container-fluid p-5" style="width:90%">
  <div class="row">
    <div class="col-12">
      <img src="<?= $this->e($url)?>" alt="imagechoosen" style="height:700px;width:auto;">
    </div>
  </div>
</div>
<div class="container-fluid" style="width: 80%">
  <div class="row">
    <div class="col-12">
      <!-- Free space to insert the Image Eventual Datas got from EXIF. -->
    </div>
  </div>
</div>

<!-- <?php $this->start('js') ?>
<script type="module" src=""></script>
<?php $this->stop() ?> -->
