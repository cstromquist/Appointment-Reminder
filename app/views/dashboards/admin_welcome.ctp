<h2 class="section">Welcome!</h2>

<p>You're just a few steps from sending your first email. Please configure your account.</p>

<ol>
	<li><?php echo $html->link('Edit your company settings', array('controller' => 'companies', 'action' => 'edit', '?' => 'redirect=' . '/admin/dashboards/welcome/')); ?><?php if($companycheck): ?> <?php echo $html->image('icon-green-checkmark.png'); ?><?php endif; ?>
		<ul>
			<li>Add your company logo.<?php if($haslogo): ?> <?php echo $html->image('icon-green-checkmark.png', array('height' => 15)); ?><?php endif; ?></li>
			<li>Add your company group photo.<?php if($hasphoto): ?> <?php echo $html->image('icon-green-checkmark.png', array('height' => 15)); ?><?php endif; ?></li>
			<li>Add your customer payment methods.<?php if($haspaymentmethods): ?> <?php echo $html->image('icon-green-checkmark.png', array('height' => 15)); ?><?php endif; ?></li>
			<li>Add your company email.<?php if($hasemail): ?> <?php echo $html->image('icon-green-checkmark.png', array('height' => 15)); ?><?php endif; ?></li>
			<li>Add your company phone.<?php if($haswebsite): ?> <?php echo $html->image('icon-green-checkmark.png', array('height' => 15)); ?><?php endif; ?></li>
		</ul>
	</li>
	<li><?php echo $html->link('Service messages', array('controller' => 'company_services', 'action' => 'index')); ?><?php if($companyservicescheck): ?> <?php echo $html->image('icon-green-checkmark.png'); ?><?php endif; ?></li>
	<li><?php echo $html->link('Create technician profiles', array('controller' => 'technicians', 'action' => 'edit', '?' => 'redirect=' . '/admin/dashboards/welcome/')); ?><?php if($techniciancheck): ?> <?php echo $html->image('icon-green-checkmark.png'); ?><?php endif; ?></li>
	<li><?php echo $html->link('Send your first email!', array('controller' => 'reminders', 'action' => 'init')); ?><?php if($reminderscheck): ?> <?php echo $html->image('icon-green-checkmark.png'); ?><?php endif; ?></li>
</ol>