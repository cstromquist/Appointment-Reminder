<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
    <title>Missing Controller</title>
    
	<meta name="description" content="" />
	<meta name="keywords" content="" />
    
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="alternate" type="application/rss+xml" title=" RSS Feed" href="/rss" />
    
    <link rel="stylesheet" type="text/css" href="/css/wfsite.css" />    </head>
<body>
<div id="wrap">

    <div id="header">   
        <h1><a href="/"><span>Missing Controller</span></a></h1>
    </div>
    
    <hr />
    
    <div id="content">
        <h2>Missing Controller</h2>
<p class="error">
	<strong>Error: </strong>
	<em>JsController</em> could not be found.</p>
<p class="error">
	<strong>Error: </strong>
	Create the class <em>JsController</em> below in file: app/controllers/js_controller.php</p>
<pre>
&lt;?php
class JsController extends AppController {

	var $name = 'Js';
}
?&gt;
</pre>
<p class="notice">
	<strong>Notice: </strong>
	If you want to customize this error message, create app/views/errors/missing_controller.ctp</p>        <span class="cleaner">&nbsp;</span>
    </div>
    
    <hr />
    
    <div id="footer">
        <p> <span class="admin_link"><a href="/admin">Site admin</a></span></p>
        
            <div class="wilflower-in-debug" style="color:red;">
        <small>Debug mode 1. In production turn this to <em>0</em> in <em>/app/config/core.php</em>.</small>
    </div>
    </div>
    
</div>
<!-- Google Analytic turned off in debug mode. --></body>
</html>

