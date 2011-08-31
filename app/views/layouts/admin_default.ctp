<?php echo $this->renderElement('admin_header', array('title_for_layout' => $title_for_layout)); ?>
<?php $prefix = Configure::read('Routing.admin'); ?>
	<ul id="nav">
        <li><?php echo $htmla->link(__('Dashboard', true), array('plugin' => null, $prefix => true, 'controller' => 'dashboards', 'action' => 'index')); ?></li>
        <li><?php echo $htmla->link(__('Companies', true), array('plugin' => null, $prefix => true, 'controller' => 'companies', 'action' => 'index')); ?></li>
        <li class="nav_item_on_right"><?php echo $htmla->link(__('Users', true), array('plugin' => null, $prefix => true, 'controller' => 'users', 'action' => 'index')); ?></li>
        <li class="nav_item_on_right"><?php echo $htmla->link(__('Global Settings', true), array('plugin' => null, $prefix => true, 'controller' => 'settings', 'action' => 'index')); ?></li>
        <li class="nav_item_on_right"><?php echo $htmla->link(__('Service Settings', true), array('plugin' => null, $prefix => true, 'controller' => 'services', 'action' => 'index')); ?></li>
    </ul>
</div>

<div id="wrap">
	
	<?php if (isset($form_for_layout)) echo $form_for_layout; ?>
	
    <div id="content">
        <div id="co_bottom_shadow">
        <div id="co_right_shadow">
        <div id="co_right_bottom_corner">
        <div id="content_pad">
            <?php echo $content_for_layout; ?>
        </div>
        </div>
        </div>
        </div>
    </div>
<?php echo $this->renderElement('admin_footer'); ?>