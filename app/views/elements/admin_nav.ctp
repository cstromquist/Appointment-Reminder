	<?php $prefix = Configure::read('Routing.admin'); ?>
	<ul id="nav">
        <li><?php echo $htmla->link(__('Dashboard', true), '/' . $prefix, array('strict' => true)); ?></li>
        <!--li><?php echo $htmla->link(__('Pages', true), array('plugin' => null, $prefix => true, 'controller' => 'pages', 'action' => 'index')); ?></li>
        <li><?php echo $htmla->link(__('Modules', true), array('plugin' => null, $prefix => true, 'controller' => 'sidebars', 'action' => 'index')); ?></li>
        <li><?php echo $htmla->link(__('Posts', true), array('plugin' => null, $prefix => true, 'controller' => 'posts', 'action' => 'index')); ?></li>
        <li><?php echo $htmla->link(__('Categories', true), array('plugin' => null, $prefix => true, 'controller' => 'categories', 'action' => 'index')); ?></li>
        <li><?php echo $htmla->link(__('Comments', true), array('plugin' => null, $prefix => true, 'controller' => 'comments', 'action' => 'index')); ?></li>
        <li><?php echo $htmla->link(__('Messages', true), array('plugin' => null, $prefix => true, 'controller' => 'messages', 'action' => 'index')); ?></li>
        <li><?php echo $htmla->link(__('Files', true), array('plugin' => null, $prefix => true, 'controller' => 'assets', 'action' => 'index')); ?></li-->
        <li><?php echo $htmla->link(__('Companies', true), array('plugin' => null, $prefix => true, 'controller' => 'companies', 'action' => 'index')); ?></li>
        <li><?php echo $htmla->link(__('Technicians', true), array('plugin' => null, $prefix => true, 'controller' => 'technicians', 'action' => 'index')); ?></li>
        <li><?php echo $htmla->link(__('Appointment Reminders', true), array('plugin' => null, $prefix => true, 'controller' => 'reminders', 'action' => 'index')); ?></li>
        <!--li class="nav_item_on_right"><?php echo $htmla->link(__('Users', true), array('plugin' => null, $prefix => true, 'controller' => 'users', 'action' => 'index')); ?></li>
        <li class="nav_item_on_right"><?php echo $htmla->link(__('Site Settings', true), array('plugin' => null, $prefix => true, 'controller' => 'settings', 'action' => 'index')); ?></li-->
        <li class="nav_item_on_right"><?php echo $htmla->link(__('Company Settings', true), array('plugin' => null, $prefix => true, 'controller' => 'companies', 'action' => 'edit')); ?></li>
        <li class="nav_item_on_right"><?php echo $htmla->link(__('Industry Settings', true), array('plugin' => null, $prefix => true, 'controller' => 'industries', 'action' => 'index')); ?></li>
    </ul>