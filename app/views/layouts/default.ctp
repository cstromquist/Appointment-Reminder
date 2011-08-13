<?php echo $html->doctype('xhtml-strict'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php echo $html->charset(); ?>
    
    <title><?php echo $title_for_layout; ?></title>
    
	<meta name="description" content="<?php echo isset($pageMeta['descriptionMetaTag']) ? $pageMeta['descriptionMetaTag'] : Configure::read('Wildflower.settings.description'); ?>" />
	<meta name="keywords" content="<?php echo isset($pageMeta['keywordsMetaTag']) ? $pageMeta['keywordsMetaTag'] : Configure::read('Wildflower.settings.keywords'); ?>" />
    
    <link rel="shortcut icon" href="<?php echo $this->webroot;?>favicon.ico" type="image/x-icon" />
    <link rel="alternate" type="application/rss+xml" title="<?php echo $siteName; ?> RSS Feed" href="<?php echo $html->url('/rss'); ?>" />
    
    <?php echo $html->css('wfsite'); ?>
    <?php echo $scripts_for_layout; ?>
</head>
<body>
<div id="wrap">
    
    <div id="content">
        <?php echo $content_for_layout; ?>
        <span class="cleaner">&nbsp;</span>
    </div>
    
    <hr />
    
</div>
<?php echo $this->element('google_analytics'); ?>
</body>
</html>

