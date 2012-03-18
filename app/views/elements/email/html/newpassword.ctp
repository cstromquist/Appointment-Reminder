<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<body style="text-align: center; background-color: #aeaeae;">
		<div style="text-align: left; padding: 20px;">
			<h2>Your Password</h2>
			<p>Dear <?php echo $name ?>,</p>
			<p>Your password has been updated and is below.  Please click on the link provided below to login.</p>
			<p>
				Password: <?php echo $password ?>
			</p>
			<div><a href="<?php echo $link ?>" title="Click here to login.">Click here to login.</a></div>
		</div>
	</body>
</html>