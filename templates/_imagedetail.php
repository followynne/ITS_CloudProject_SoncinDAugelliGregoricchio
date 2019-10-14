<?php $this->layout('gallery', ['title' => 'Photo:' . $this->e($name)])?>

<div class="container-fluid p-5" style="width:90%">
  <div class="row">
    <div class="col-12">
      <img src="<?= $this->e($url)?>" alt="ImageRequested - if empty, doesn't exist." style="height:700px;width:auto;">
    </div>
  </div>
</div>
<div class="container-fluid" style="width: 80%">
  <div class="row">
    <div class="col-12">
      <ul class="list-group">
      <?php foreach($exif[0] as $key=>$value): ?>
          <li class="list-group-item"><?= $this->e($key)?>: <?= $this->e($value)?></li>
      <?php endforeach ?>
      <li class="list-group-item">Tags: <?= $this->e($tags)?></li>
    </ul>
    </div>
  </div>
</div>
