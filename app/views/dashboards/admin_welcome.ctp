<h2 class="section">Welcome!</h2>

<p>You're just a few steps from sending your first email. Please configure your account.</p>

<ol>
	<li><?php echo $html->link('Edit your company settings', array('controller' => 'companies', 'action' => 'edit')); ?></li>
	<li><?php echo $html->link('Service messages', array('controller' => 'company_services', 'action' => 'index')); ?></li>
	<li><?php echo $html->link('Create technician profiles', array('controller' => 'technicians', 'action' => 'index')); ?></li>
	<li><?php echo $html->link('Send your first email!', array('controller' => 'reminders', 'action' => 'index')); ?></li>
</ol>