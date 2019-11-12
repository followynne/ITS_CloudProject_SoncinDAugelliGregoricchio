<?php $this->layout('home', ['title' => 'Register']) ?>
    <div class="limiter">
            <div class="container-login100" style="background-image: url('/images/bg-01.jpg');">
                <div class="wrap-login100 p-t-30 p-b-50">
                    <span class="login100-form-title p-b-41">
                        Registration
                    </span>
                    <form class="login100-form validate-form p-b-33 p-t-5" action="/public/signup.php" method="POST">

                        <div class="wrap-input100 validate-input" data-validate = "Enter Email">
                            <input class="input100" type="text" id="mail" name="mail" placeholder="Email">
                            <span class="focus-input100" data-placeholder="&#xe82a;"></span>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate = "Enter Name">
                            <input class="input100" type="text" id="mail" name="mail" placeholder="Name">
                            <span class="focus-input100" data-placeholder="&#xe82a;"></span>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Enter password">
                            <input class="input100" type="password" id="pwd" name="pwd" placeholder="Password">
                            <span class="focus-input100" data-placeholder="&#xe80f;"></span>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Repeat password">
                            <input class="input100" type="password" id="pwd" name="pwd" placeholder="Repeat Password">
                            <span class="focus-input100" data-placeholder="&#xe80f;"></span>
                        </div>

                        <div class="container-login100-form-btn m-t-32">
                            <button class="login100-form-btn" type="button" onclick="window.location.href='/public/start.php'">
                                Login
                            </button>
                            <button class="login100-form-btn" type="submit">
                                Sign up
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    </div>
  <div id="dropDownSelect1">
  </div>


<?php $this->start('js') ?>
<!-- <script type="module" src="../script/???"></script> -->
<?php $this->stop() ?>
