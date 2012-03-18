<h2 class="section"><?php __('Your Profile'); ?></h2>

<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>

<?php echo 
    $form->create('User', array('action' => 'profile', 'class' => 'editor_form'));
	echo
    $form->input('name'),
    $form->input('email'),
    $form->input('login'),
    $form->input('password'),
    $form->input('confirm_password', array('type' => 'password')),
    $form->hidden('id'); 
		$options = array(
    	'label' => 'Save',
    	'name' => 'Save',
    	'div' => array(
        	'class' => 'wf-form-button',
    		)
		);
		echo $form->end($options);
	?> <span>or</span> <?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('class' => 'cancel')); ?>