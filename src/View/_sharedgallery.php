<?php $this->layout('shared', ['title' => 'Shared Gallery']) ?>

<div class="container-fluid p-4" style="width:85%;">
  <div class="row divForImagesShowing justify-content-center">
  </div>
</div>

<div>
  <ul class="pagination pg-purple pagination-circle mb-2 justify-content-center">
  </ul>
</div>

<?php $this->start('js') ?>
<script type="module" src="/script/sharedgallery.js"></script>
<?php $this->stop() ?>