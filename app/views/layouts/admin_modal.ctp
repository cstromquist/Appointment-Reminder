<?php echo $html->doctype('xhtml-strict') ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
	
	<title><?php echo $title_for_layout; ?></title>
	
	<meta name="description" content="" />
	
    <link rel="shortcut icon" href="<?php echo $this->webroot; ?>favicon.ico" type="image/x-icon" />	
	<?php echo $html->css('datepicker')."\n"; ?>
	<?php echo $html->css('imgareaselect.css')."\n"; ?>
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
</head>
<body>

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
            
    <div class="cleaner"></div>
</div>

</body>
</html>

