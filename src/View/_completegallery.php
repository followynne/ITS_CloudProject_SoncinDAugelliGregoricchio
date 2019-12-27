<?php $this->layout('home', ['title' => 'Your Gallery']) ?>

<div class="text-right">
  <div class="container-fluid" style="width:90%">
    <div class="row justify-content-end">
      <button type="button" class="btn btn-success btn-lg gallery" onclick="window.location.href='/'">Homepage</button>
      <a href="/completegallery" target="_blank">
        <button type="button" class="btn btn-warning btn-lg" id="fetchall" style="<?= $this->e($display) ?>">Show All Images</button>
      </a>
      <button type="button" class="btn btn-light btn-lg" id="selectall">Select All</button>
      <button type="button" class="btn btn-dark btn-lg" id="deselectall">DeSelect All</button>
      <button class="btn btn-primary btn-lg" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        Share Selected
      </button>
      <button type="button" class="btn btn-danger btn-lg" id="deleteselected">Delete Selected</button>
    </div>
    <div class="row justify-content-end" style="background-color:whitesmoke;">
      <div class="collapse p-2" id="collapseExample">
        <span> Scegli la data e l'ora (UTC) di scadenza dello share: </span>
          <input type="date" id="datetimepicker">
          <input type="time" id="hourpicker" value="00:00">
        
          <button type="button" id="getlink" class="btn-primary btn-md" data-toggle="popover" data-trigger="focus" data-content="Hai selezionato una scadenza non valida o nessuna immagine.">Get Link</button>
          <input id="sharelink" readonly>
          <button type= "button" class="btn btn-md copy" data-clipboard-target="#sharelink">
            <img src="/images/clippy.svg" alt="Copy to clipboard">
          </button>
      </div>
    </div>
  </div>

  <div class="container-fluid" style="width:90%; background-color:seashell;">
    <div class="row justify-content-end" style="margin-top:4px">
      Inserisci le keyword separate da spazi. Per keyword composte da più termini, separare ogni parola con un - (dash), ex) word1-word2-word3.
    <input type="button" name="allsearch" class="btn btn-dark btn-md" id="btnsearchall" value="Search By All Data Specified">
    </div>

    <div class="row justify-content-end" style="margin-top:4px">
      <input type="text" name="tags" class="inputdata" id="taginput" placeholder="Tags" style="width:500px">
      <input type="button" class="btn btn-dark btn-md" name="tagsearch" id="btnsearchfortags" value="Search By Tags">
    </div>
    <div class="row justify-content-end" style="margin-top:4px">
      <input type="text" name="brand" class="inputdata" placeholder="Brands" id="brandinput" style="width:500px">
      <input type="button" class="btn btn-dark btn-md" name="brandsearch" id="btnsearchforbrands" value="Search By Brand">
    </div>
    <div class="row justify-content-end" style="margin-top:4px">
      <input type="text" name="dates" class="inputdata" placeholder="Dates (yyyy-mm-dd)" id="datesinput" style="width:500px">
      <input type="button" class="btn btn-dark btn-md" name="datesearch" id="btnsearchfordates" value="Search By Dates">
    </div>
    <div class="row justify-content-end" style="margin-top:4px">

    </div>
  </div>
</div>
<div class="container-fluid p-4" style=" background-color:beige;">
  <div class="row divForImagesShowing justify-content-center">
  </div>
</div>

<div>
  <ul class="pagination pagination-lg justify-content-center">
  </ul>
</div>

<?php $this->start('js') ?>
<script type="module" src="/script/gallery.js"></script>
<?php $this->stop() ?>