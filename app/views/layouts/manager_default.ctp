<?php echo $html->doctype('xhtml-strict') ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
	
	<title><?php echo $title_for_layout; ?></title>
	
	<meta name="description" content="" />
	
    <link rel="shortcut icon" href="<?php echo $this->webroot; ?>favicon.ico" type="image/x-icon" />	
	<?php echo $html->css('datepicker')."\n"; ?>
	<?php echo $html->css('imgareaselect-animated.css')."\n"; ?>
	<?php echo $javascript->link('datepicker.js')."\n"; ?> 
	<?php 
		$prefix = Configure::read('Routing.admin');
        echo
        // Load your CSS files here
        $html->css(array(
            '/wildflower/css/wf.main',
        )),
        // TinyMCE 
        // @TODO load only on pages with editor?
        $javascript->link('/wildflower/js/tiny_mce/tiny_mce');
    ?>
     
    <!--[if lte IE 7]>
    <?php
        // CSS file for Microsoft Internet Explorer 7 and lower
        echo $html->css('/wildflower/css/wf.ie7');
    ?>
    <![endif]-->
    
    <!-- JQuery Light MVC -->
    <script type="text/javascript" src="<?php echo $html->url('/' . Configure::read('Routing.admin') . '/assets/jlm'); ?>"></script>
    <script type="text/javascript">
    //<![CDATA[
        $.jlm.config({
            base: '<?php echo $this->base ?>',
            controller: '<?php echo $this->params['controller'] ?>',
            action: '<?php echo $this->params['action'] ?>', 
            prefix: '<?php echo $prefix; ?>',
            custom: {
                wildflowerUploads: '<?php echo Configure::read('Wildflower.uploadsDirectoryName'); ?>',
                wildflowerMPrefix: '<?php echo Configure::read('Wildflower.mediaRoute'); ?>'
            }
        });
        
        tinyMCE.init($.jlm.components.tinyMce.getConfig());

        $(function() {
           $.jlm.dispatch(); 
        });
    //]]>
    </script>
    <?php echo $html->css('thickbox')."\n"; ?> 
	<?php echo $javascript->link('thickbox-compressed.js')."\n"; ?>
	<?php echo $javascript->link('validation.js')."\n"; ?>
</head>
<body>
 
<div id="header">
    <h1 id="site_title"><?php echo hsc($siteName); ?></h1>
    
    <div id="login_info">
        <?php echo $htmla->link(__('Logout', true), array('plugin' => null, $prefix => true, 'controller' => 'users', 'action' => 'logout'), array('id' => 'logout')); ?>
    </div>
	<ul id="nav">
        <li><?php echo $htmla->link(__('Dashboard', true), '/' . $prefix, array('strict' => true)); ?></li>
        <li><?php echo $htmla->link(__('Technicians', true), array('plugin' => null, $prefix => true, 'controller' => 'technicians', 'action' => 'index')); ?></li>
        <li><?php echo $htmla->link(__('Appointment Reminders', true), array('plugin' => null, $prefix => true, 'controller' => 'reminders', 'action' => 'index')); ?></li>
        <li class="nav_item_on_right"><?php echo $htmla->link(__('Users', true), array('plugin' => null, $prefix => true, 'controller' => 'users', 'action' => 'index')); ?></li>
        <li class="nav_item_on_right"><?php echo $htmla->link(__('Company Settings', true), array('plugin' => null, $prefix => true, 'controller' => 'companies', 'action' => 'edit')); ?></li>
        <li class="nav_item_on_right"><?php echo $htmla->link(__('Service Settings', true), array('plugin' => null, $prefix => true, 'controller' => 'company_services', 'action' => 'index')); ?></li>
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
    
    <?php if (isset($sidebar_for_layout)): ?>
    <div id="sidebar">
        <ul>
            <?php echo $sidebar_for_layout; ?>
        </ul>
    </div>
    <?php endif; ?>

	<?php if (isset($form_for_layout)) echo '</form>'; ?>
        
    <div class="cleaner"></div>
</div>

</body>
</html>

