	<div id="register"> 
        <h1 class="success">Reset Your Password</h1>
        <?php 
		    if ($session->check('Message.flash')) {
		        $session->flash();
		    }
		?>
        <p>Simply enter your email address below and a new password will be sent to you.</p>
        <?php
 		echo $form->create('User', array('action' => 'forgot_password'));
		echo $form->input('email', array('label' => 'Your email address', 'size' => 35));
		$options = array(
    	'label' => 'Submit',
    	'name' => 'forgot_password'
		);
		echo $form->end($options);
		?>
    </div>