<?php $this->layout('home', ['title' => 'Your Gallery']) ?>

<div class="myBackground text-right">
  <div class="container-fluid" style="width:90%">
    <div class="row justify-content-end">
      <a href="/completegallery" target="_blank">
        <button type="button" class="mt-5 btn btn-warning btn-lg" id="fetchall" style="<?= $this->e($display)?>" >Show All Images</button>
      </a>
      <button type="button" class="mt-5 btn btn-light btn-lg" id="selectall">Select All</button>
      <button type="button" class="mt-5 btn btn-dark btn-lg" id="deselectall">DeSelect All</button>
      <button class="mt-5 btn btn-primary btn-lg" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
        Share Selected
      </button>
      <button type="button" class="mt-5 btn btn-danger btn-lg" id="deleteselected">Delete Selected</button>
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

  <div class="container-fluid" style="width:90%; background-color:F5F5F5;">
    <div class="row justify-content-end" style="margin-top:4px">
      Inserisci le keyword separate da spazi. Per keyword composte da più termini, separare ogni parola con un - (dash), ex) word1-word2-word3.
    </div>

    <div class="container">
    <form class="form-inline d-flex justify-content-center md-form form-sm active-purple active-purple-2 mt-2">
      <h6 class="row-2 row-form-label">Search by Tags</h6></br>
        <input class="form-control form-control-sm ml-3 w-75 inputdata" type="text" placeholder="Search by tags"
          name="tags" id="taginput" aria-label="Search">
        
          <button type="button" class="btn btn-purple btn-sm" name="tagsearch" id="btnsearchfortags">
            <i class="fas fa-search fa-inverse" aria-hidden="true"></i>
         </button>
      </form>
  </div>

  <div class="container">
  <form class="form-inline d-flex justify-content-center md-form form-sm active-purple active-purple-2 mt-2">
      <h6 class="row-2 row-form-label">Search by Date</h6></br>
      <input class="form-control form-control-sm ml-3 w-75 inputdata" type="date" value="2020-06-19" id="datesinput" style="width:500px">
        
          <button type="button" class="btn btn-blue btn-sm" name="datesearch" id="btnsearchfordates">
            <i class="fas fa-search fa-inverse" aria-hidden="true"></i>
         </button>
      </form>
  </div>

  <div class="container">
  <form class="form-inline d-flex justify-content-center md-form form-sm active-pink active-pink-2 mt-2">
      <h6 class="row-2 row-form-label">Search by Brand</h6></br>
        <input class="form-control form-control-sm ml-3 w-75 inputdata" type="text" placeholder="Search by Brand"
          name="tags" id="taginput" aria-label="Search">
        
          <button type="button" class="btn btn-pink btn-sm" name="tagsearch" id="btnsearchfortags">
            <i class="fas fa-search fa-inverse" aria-hidden="true"></i>
         </button>
      </form>
  </div>

  <input type="button" name="allsearch" class="btn btn-dark btn-md" id="btnsearchall" value="Search By All Data Specified">

<!-- Search form -->
<!-- <form class="form-inline d-flex justify-content-center md-form form-sm active-cyan active-cyan-2 mt-2">
  <i class="fas fa-search" aria-hidden="true"></i>
  <input class="form-control form-control-sm ml-3 w-75" type="text" placeholder="Search"
    aria-label="Search">
</form>


    <div class="row justify-content-end" style="margin-top:4px">
      <input type="text" name="brand" class="inputdata" placeholder="Brands" id="brandinput" style="width:500px">
      <input type="button" class="btn btn-dark btn-md" name="brandsearch" id="btnsearchforbrands" value="Search By Brand">
    </div> -->

    <!-- <div class="row justify-content-end" style="margin-top:4px">
      <input type="text" name="dates" class="inputdata" placeholder="Dates (yyyy-mm-dd)" id="datesinput" style="width:500px">
      <input type="button" class="btn btn-dark btn-md" name="datesearch" id="btnsearchfordates" value="Search By Dates">
    </div> -->

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