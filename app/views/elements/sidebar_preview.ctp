	<?php $partialLayout->blockStart('sidebar'); ?>
	<h3 class="sidebar">Your selected theme for this reminder</h3>
	<div class="sidebar_theme"><?php echo $html->image('theme_'.$theme.'.jpg'); ?><br />
		<?php
		$url = '/' . Configure::read('Routing.admin') . '/reminders/select_theme/' . $theme;
		if($this->data['Reminder']['id']) {
			$url .= '/' . $this->data['Reminder']['id'];
		}
        echo $html->link('Change theme', $url, array('escape' => false)); 
		?>
	</div>
	<?php if(isset($technician) && $technician):?>
	<h3 class="sidebar">Your selected technician for this reminder</h3>
	<div class="sidebar_tech">
		<div class="sidebar_tech_photo"><?php echo $html->image('uploads/technicians/'.$technician['Technician']['image_path'], array('width' => 150)); ?></div>
		<div class="sidebar_tech_info">
			<p class="tech_name"><?php echo $technician['Technician']['name'] ?></p>
			<p><?php echo $technician['Technician']['bio'] ?></p>
		</div>
		<?php
		$url = '/' . Configure::read('Routing.admin') . '/reminders/select_technician/' . $theme . '/' . $technician['Technician']['id'];
		if($this->data['Reminder']['id']) {
			$url .= '/' . $this->data['Reminder']['id'];
		}
        echo $html->link('Change technician', $url, array('escape' => false)); 
		?>
	</div>
	<?php else: ?>
	<div class="sidebar_tech">
		<div class="sidebar_tech_photo"><?php echo $html->image('no-tech.png'); ?></div>
		<div class="sidebar_tech_info">
			<p class="tech_name">No technician chosen</p>
			<?php
		$url = '/' . Configure::read('Routing.admin') . '/reminders/select_technician/' . $theme;
		if($this->data['Reminder']['id']) {
			$url .= '/' . $this->data['Reminder']['id'];
		}
        echo $html->link('Choose a technician', $url, array('escape' => false)); 
		?>
		</div>
	</div>
	<?php endif; ?>
	<?php $partialLayout->blockEnd(); ?>