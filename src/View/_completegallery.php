<?php $this->layout('gallery', ['title' => 'Your Gallery']) ?>

<div class="container-fluid" style="width:85%">
  <div class="row justify-content-end">
    <a href="/public/getallblobs.php" target="_blank">
      <button type="button" class="btn btn-dark" id="fetchall" style="<?=$this->e($display)?>">Show All Images</button>
    </a>
    <button type="button" class="btn btn-light" id="selectall">Select All</button>
    <button type="button" class="btn btn-dark" id="deselectall">DeSelect All</button>
    <button type="button" class="btn btn-danger" id="deleteselected">Delete Selected</button>
  </div>
  <div class="row justify-content-end"  style="margin-top:4px">
    <span>Inserisci le keyword separate da spazi. Per keyword composte da più termini, separare ogni parola con un - ex: word1-word2-word3.</span>
    <input type="button" name="allsearch" id="btnsearchall" value="Search By All Data Specified">
  </div>
  <div class="row justify-content-end"  style="margin-top:4px">
    <span>Tags</span>
    <input type="text" name="tags" class="inputdata" id="taginput" style="width:500px">
    <input type="button" name="tagsearch" id="btnsearchfortags" value="Search By Tags">
  </div>
  <div class="row justify-content-end" style="margin-top:4px">
    <span>Brands</span>
    <input type="text" name="brand" class="inputdata" id="brandinput" style="width:500px">
    <input type="button" name="brandsearch" id="btnsearchforbrands" value="Search By Brand">
  </div>
  <div class="row justify-content-end"  style="margin-top:4px">
    <span>Date (inserire secondo sintassi: yyyy-mm-dd)</span>
    <input type="text" name="dates" class="inputdata" id="datesinput" style="width:500px">
    <input type="button" name="datesearch" id="btnsearchfordates" value="Search By Dates">
  </div>
  <div class="row justify-content-end"  style="margin-top:4px">
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
      Share Selected
    </button>
      <div class="collapse p-2" id="collapseExample">
        <span style="display:inline"> Scegli la data e l'ora (UTC) di scadenza dello share:
          <input type="date" id="datetimepicker">
          <input type="time" id="hourpicker" value="00:00">
        </span>
        <span class="card card-body"  style="display:inline; padding:0">
          <button id="getlink" data-toggle="popover" data-trigger="focus"
          data-content="Hai selezionato una scadenza non valida o nessuna immagine.">Get Link</button>
        </span>
        <span>
          <input id="sharelink" readonly>
          <button class="btn copy" data-clipboard-target="#sharelink">
            <img src="/images/clippy.svg" alt="Copy to clipboard">
          </button>
        </span>
    </div>
  </div>
</div>

<div class="container-fluid p-4" style="width:85%;">
  <div class="row divForImagesShowing justify-content-center">
  </div>
</div>

<div>
    <ul class="pagination pagination-lg justify-content-center ">
    </ul>
</div>

<?php $this->start('js') ?>
<script type="module" src="/script/gallery.js"></script>
<?php $this->stop() ?>
