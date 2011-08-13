	<div id="register"> 
        <h1>Create a new account</h1>
        <?php 
		    if ($session->check('Message.flash')) {
		        $session->flash();
		    }
		?> 
 		<?php
 		echo $form->create('Company', array('action' => 'register'));
		echo $form->input('name', array('label' => 'Company Name'));
		echo $form->input('phone', array('label' => 'Company Phone'));
		echo $form->input('website_url', array('label' => 'Company Website'));
		echo $form->input('industry_id', array('label' => 'Choose your industry'));
		echo $form->input('User.0.name', array('label' => 'Your Name'));
		echo $form->input('User.0.email', array('label' => 'Your Email'));
		echo $form->input('User.0.login', array('label' => 'Choose a Username'));
		echo $form->input('User.0.password', array('label' => 'Password'));
		echo $form->input('User.0.confirm_password', array('label' => 'Confirm Password', 'type' => 'password'));
		$options = array(
    	'label' => 'Create Account',
    	'name' => 'create_account'
		);
		echo $form->end($options);
		?>
    </div>