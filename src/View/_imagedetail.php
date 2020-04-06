<?php $this->layout('home', ['title' => 'Photo:' . $this->e($name)]) ?>

<div class="container-fluid" >
<div class="card card-cascade wider mx-auto mt-3 mb-3 w-50">

  <div class="view view-cascade overlay">
    <img class="card-img-top w-40" src="<?= $this->e($url) ?>" alt="Card image cap">
    <a href="#!">
      <div class="mask rgba-white-slight"></div>
    </a>
  </div>

  <div class="card-body card-body-cascade text-center pb-0">
    <h4 class="card-title"><strong><?= $this->e($exif[0]['Name']) ?></strong></h4>
      <ul class="list-group">
    <?php foreach ($exif[0] as $key => $value) : ?>
          <li class="list-group-item"><?= $this->e($key) ?>: <?= $this->e($value) ?></li>
        <?php endforeach ?>
        <?php if ($this->e($tags) != "") : ?>
          <li class="list-group-item">Tags: <?= $this->e($tags) ?></li>
        <?php endif ?>
      </ul>

  </div>
  </div>
</div>

