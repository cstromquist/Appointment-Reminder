	<?php $prefix = Configure::read('Routing.admin'); ?>
	<ul id="nav">
        <li><?php echo $htmla->link(__('Dashboard', true), '/' . $prefix, array('strict' => true)); ?></li>
        <li><?php echo $htmla->link(__('Technicians', true), array('plugin' => null, $prefix => true, 'controller' => 'technicians', 'action' => 'index')); ?></li>
        <li><?php echo $htmla->link(__('Appointment Reminders', true), array('plugin' => null, $prefix => true, 'controller' => 'reminders', 'action' => 'index')); ?></li>
        <li class="nav_item_on_right"><?php echo $htmla->link(__('Company Settings', true), array('plugin' => null, $prefix => true, 'controller' => 'companies', 'action' => 'edit')); ?></li>
    </ul>