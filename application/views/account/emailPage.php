<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="<?= base_url() ?>/css/bootstrap.min.css">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="<?= base_url() ?>/js/bootstrap.min.js"></script>
</head>
<body>
	<h1>Password Recovery</h1>

	<p>Please check your email for your new password.
	</p>



<?php
	if (isset($errorMsg)) {
		echo "<p>" . $errorMsg . "</p>";
	}

	echo "<p>" . anchor('account/index','Login') . "</p>";
?>
</body>

</html>

