<?php $this->layout('login', ['title' => 'Start']) ?>

<body>

	<div class="limiter">
		<div class="container-login100" style="background-image: url('/images/photo-1495422964407-28c01bf82b0d.jpg');">
			<div class="wrap-login100 p-t-30 p-b-50">
				<span class="login100-form-title p-b-41">
					Login
				</span>
				<span class="login100-form-title p-b-41"><?= $this->e($msg) ?></span>
				<form class="login100-form validate-form p-b-33 p-t-5" action="/login" method="POST">

					<div class="wrap-input100 validate-input" data-validate="Enter mail">
						<input class="input100" type="text" id="mail" name="mail" placeholder="Mail">
						<span class="focus-input100" data-placeholder="&#xe82a;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" id="pwd" name="pwd" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xe80f;"></span>
					</div>

					<div class="container-login100-form-btn m-t-32">
						<button class="login100-form-btn" type="submit">
							Login
						</button>
						<button class="login100-form-btn " type="button" onclick="window.location.href='/register'">
							Sign up
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="dropDownSelect1">
	</div>