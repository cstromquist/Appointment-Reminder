<?php
echo $form->create('User', array('url' => $here));
echo $form->input('login', array('between' => '<br />'));
echo $form->input('password',  array('between' => '<br />'));
?>
<div id="remember"><?php echo $form->input('remember', array(
	'type' => 'checkbox',
	'label' => __('Keep me logged in on this computer for 2 weeks.', true))); ?></div>
<?php

// Auth error message
if ($session->check('Message.auth')) {
	$session->flash('auth');
}

echo $form->submit('Log in');
echo $form->end();
?>
<p id="gohome"><?php echo $html->link("Back to $siteName", '/'); ?></p>
