<?php $this->layout('gallery', ['title' => 'Your Gallery']) ?>


<div class="container">
  <div class="row divForImagesShowing">
  </div>
</div>

<div>
    <ul class="pagination">
    </ul>
</div>


<?php $this->start('js') ?>
<script src="../script/gallery.js"></script>
<?php $this->stop() ?>
