<?php $this->layout('home', ['title' => 'Your Gallery']) ?>

<div class="myBackground text-right">
  <div class="container-fluid d-flex justify-content-center" style="width:90%">
    <div class="justify-content-end">
      <a href="/completegallery" target="_blank">
        <button type="button" class="mt-5 btn btn-warning btn-lg" id="fetchall" style="<?= $this->e($display) ?>">Show All Images</button>
      </a>
      <button type="button" class="mt-5 btn btn-light btn-lg" id="selectall">Select All</button>
      <button type="button" class="mt-5 btn btn-dark btn-lg" id="deselectall">DeSelect All</button>
      <button class="mt-5 btn btn-primary btn-lg" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        Share Selected
      </button>
      <button type="button" class="mt-5 btn btn-danger btn-lg" id="deleteselected">Delete Selected</button>
      <div class="" style="background-color:whitesmoke;">
        <div class="collapse p-2" id="collapseExample">
          <span> Scegli la data e l'ora (UTC) di scadenza dello share: </span>
          <input type="date" id="datetimepicker">
          <input type="time" id="hourpicker" value="00:00">
  
          <button type="button" id="getlink" class="btn btn-cyan btn-md" data-toggle="popover" data-trigger="focus" data-content="Hai selezionato una scadenza non valida o nessuna immagine.">Get Link</button>
          <input id="sharelink" readonly>
          <button type="button" class="btn btn-md copy" data-clipboard-target="#sharelink">
            <img src="/images/clippy.svg" alt="Copy to clipboard">
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid" style="width:90%; background-color:F5F5F5;">
    <div class="container">
      <form class="form-inline d-flex justify-content-center md-form form-sm active-purple active-purple-2 mt-2">
        Inserisci le keyword separate da spazi. Per delle keyword composte da più termini, separare ogni parola con un dash eg. word1-word2-word3.
      </form>
    </div>

    <div class="container">
      <form class="form-inline d-flex justify-content-center md-form form-sm active-purple active-purple-2 mt-2">
        <h6 class="row-2 row-form-label">Search by Tags</h6></br>
        <input class="form-control form-control-sm ml-3 w-75 inputdata" type="text" placeholder="Search by tags" name="tags" id="taginput" aria-label="Search">

        <button type="button" class="btn btn-purple btn-sm" name="tagsearch" id="btnsearchfortags">
          <i class="fas fa-search fa-inverse" aria-hidden="true"></i>
        </button>
      </form>
    </div>

    <div class="container">
      <form class="form-inline d-flex justify-content-center md-form form-sm active-purple active-purple-2 mt-2">
        <h6 class="row-2 row-form-label">Search by Date</h6></br>
        <input class="form-control form-control-sm ml-3 w-75 inputdata" name="dates" type="date" data-date-format="yyyy-mm-dd" value="2019-08-08" id="datesinput" style="width:500px">

        <button type="button" class="btn btn-blue btn-sm" name="datesearch" id="btnsearchfordates">
          <i class="fas fa-search fa-inverse" aria-hidden="true"></i>
        </button>
      </form>
    </div>

    <div class="container">
      <form class="form-inline d-flex justify-content-center md-form form-sm active-pink active-pink-2 mt-2">
        <h6 class="row-2 row-form-label">Search by Brand</h6></br>
        <input class="form-control form-control-sm ml-3 w-75 inputdata" type="text" placeholder="Search by Brand" name="brand" id="brandinput" aria-label="Search">

        <button type="button" class="btn btn-pink btn-sm" name="brandsearch" id="btnsearchforbrands">
          <i class="fas fa-search fa-inverse" aria-hidden="true"></i>
        </button>
        <input type="button" name="allsearch" class="btn btn-dark btn-md" id="btnsearchall" value="Search By All Data Specified">
      </form>
    </div>


    <div class="row justify-content-end" style="margin-top:4px">

    </div>
  </div>
</div>
<div class="container-fluid p-4" style=" background-color:F5F5F5;">
  <div class="row divForImagesShowing justify-content-center">
  </div>
</div>


<div>
  <ul class="pagination pg-purple pagination-circle mb-2 justify-content-center">
  </ul>
</div>

<?php $this->start('js') ?>
<script type="module" src="/script/gallery.js"></script>
<?php $this->stop() ?>