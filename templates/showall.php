<?php $this->layout('gallery', ['title' => 'Your Gallery']) ?>

<div class="container-fluid p-4" style="width:80%;">
  <div class="row divForImagesShowing justify-content-center">
  </div>
</div>

<div>
    <ul class="pagination pagination-lg justify-content-center ">
    </ul>
</div>

<?php $this->start('js') ?>
<script src="../script/gallery.js"></script>
<?php $this->stop() ?>
