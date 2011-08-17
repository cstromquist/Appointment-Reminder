<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<body style="text-align: center; background-color: #aeaeae;">
		<div style="text-align: center;">
			<?php if($this->base): ?>
			<a href="http://<?php echo $companyurl; ?>" title="Click here to visit our company website"><?php echo $html->image(FULL_BASE_URL . $this->base . '/img/uploads/reminders/'.$reminder['Reminder']['image_path']); ?></a>
			<?php else: ?>
			<a href="http://<?php echo $companyurl; ?>" title="Click here to visit our company website"><?php echo $html->image(FULL_BASE_URL . '/img/uploads/reminders/'.$reminder['Reminder']['image_path']); ?></a>
			<?php endif; ?>
		</div>
	</body>
</html>