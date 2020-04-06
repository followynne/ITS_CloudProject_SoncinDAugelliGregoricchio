<nav class="navbar navbar-light bg-light justify-content-between">
  <span class="iconHome"></span>
  <form class="form-inline">
    <button class="btn btn-outline-info mr-2 my-2 my-sm-0" type="button" onclick="window.location.href='/'"><i class="fa fa-home" ></i> Home </button>
    <button class="btn btn-outline-success mr-2 my-2 my-sm-0" type="button" onclick="window.location.href='/gallery'"><i class="fa fa-camera"></i> Gallery</button>
    <button class="btn btn-outline-danger mr-2 my-2 my-sm-0" type="button" onclick="window.location.href='/map'"><i class="fa fa-map-o" ></i> Maps</button>
    <button class="btn btn-outline-dark mr-2 my-2 my-sm-0" type="button" onclick="window.location.href='/logout'"><i class="fa fa-sign-out"></i> Log Out</button>
  </form>
</nav>
<div style="width:200px; margin:0 auto;" class="mt-2">
  <button type="button" class="mt-2 btn btn-primary btn-lg upPhotos" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-cloud-upload"></i> Upload Photos
  </button>
</div>


<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" id="exampleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="/upload" method="POST" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="image" id="image">
            <button type="submit" class="btn btn-primary btn-round" name="upload">Upload</button>
          </form>
        </div>
        <div class="row">
          <div class="col-lg-5 mx-auto">
            <div>
              <img src="https://res.cloudinary.com/mhmd/image/upload/v1557366994/img_epm3iz.png" alt="" width="200" class="d-block mx-auto mb-4 rounded-pill">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>