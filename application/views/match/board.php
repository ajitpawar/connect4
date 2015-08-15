<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="<?= base_url() ?>/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/css/style.css">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="<?= base_url() ?>/js/jquery.timers.js"></script>
<script>
	var otherUser = "<?= $otherUser->login ?>";
	var user = "<?= $user->login ?>";
	var status = "<?= $status ?>";
	var baseURL = "<?= base_url() ?>";
</script>
<script src="<?= base_url() ?>/js/arcade/board.js"></script>
<script src="<?= base_url() ?>/js/bootstrap.min.js"></script>
</head>
<body>
<div id="global-container-game">
<h1>Game Area</h1>

	<div class="row">
		<div class="col-sm-8" id="game-container">
			<div id="game">
				<aside>
					<div></div><div></div><div></div><div></div><div></div><div></div>
				</aside>
				<aside>
					<div></div><div></div><div></div><div></div><div></div><div></div>
				</aside>
				<aside>
					<div></div><div></div><div></div><div></div><div></div><div></div>
				</aside>
				<aside>
					<div></div><div></div><div></div><div></div><div></div><div></div>
				</aside>
				<aside>
					<div></div><div></div><div></div><div></div><div></div><div></div>
				</aside>
				<aside>
					<div></div><div></div><div></div><div></div><div></div><div></div>
				</aside>
				<aside>
					<div></div><div></div><div></div><div></div><div></div><div></div>
				</aside>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="game-info col-sm-4">
			<div>
			Hello <?= $user->fullName() ?>  <?= anchor('account/logout','(Logout)') ?>
			</div>

			<div id='status'> Status:
			<?php
				if ($status == "playing")
					echo "Playing " . $otherUser->login;
				else
					echo "Wating on " . $otherUser->login;
			?>
			</div>

			<?php /*
				echo form_textarea('conversation');

				echo form_open();
				echo form_input('msg');
				echo form_submit('Send','Send');
				echo form_close();
				*/
			?>

			<!-- <div id="next-player"></div> -->
			<!-- <div id="get-match-state">Get from PHP</div> -->
			<!-- <div id="php-receive"></div> -->
		</div>
	</div>

</div>




</body>

</html>

