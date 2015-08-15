<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="<?= base_url() ?>/css/bootstrap.min.css">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="<?= base_url() ?>/js/bootstrap.min.js"></script>
</head>
<body>
	<h1>Login</h1>
<?php
	if (isset($errorMsg)) {
		echo "<p>" . $errorMsg . "</p>";
	}


	echo form_open('account/login');
	echo form_label('Username');
	echo form_error('username');
	echo form_input('username',set_value('username'),"required");
	echo form_label('Password');
	echo form_error('password');
	echo form_password('password','',"required");

	echo form_submit('submit', 'Login');

	echo "<p>" . anchor('account/newForm','Create Account') . "</p>";

	echo "<p>" . anchor('account/recoverPasswordForm','Recover Password') . "</p>";


	echo form_close();
?>
</body>

</html>

