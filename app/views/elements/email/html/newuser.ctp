<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<body style="text-align: center; background-color: #aeaeae;">
		<div style="text-align: left; padding: 20px;">
			<h2>Account Created!</h2>
			<p>Dear <?php echo $name ?>,</p>
			<p>Your account has been created with your account details below.  Please click on the link provided below to login.</p>
			<p>
				Username: <?php echo $username ?><br />
				Password: <?php echo $password ?>
			</p>
			<div><a href="<?php echo $link ?>" title="Click here to login.">Click here to login.</a></div>
		</div>
	</body>
</html>