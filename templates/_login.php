<?php $this->layout('login', ['title' => 'Start']) ?>

<div class="container">
  <h2>Form</h2>
  <form class="form-horizontal" action="/index.php" method="POST">
    <div class="form-group" action="/index.php" method="POST">
      <label class="control-label col-sm-2" for="email">Email:</label>
      <div class="col-sm-10">
        <input type="email" class="form-control" id="mail" placeholder="Enter email" name="mail">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="pwd">Password:</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd">
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
          <label><input type="checkbox" name="remember"> Remember me</label>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-danger btn-lg">Submit</button>
        <button type="button" class="btn btn-success btn-lg mp">Sign Up</button>
      </div>
    </div>
  </form>
</div>

<?php $this->start('js') ?>
<!-- <script type="module" src="../script/???"></script> -->
<?php $this->stop() ?>
