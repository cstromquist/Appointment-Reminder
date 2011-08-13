<h2 class="section">Change password for user <?php echo hsc($this->data['User']['name']) ?></h2>
<?php echo 
    $form->create('User', array('url' => $html->url(array('action' => 'admin_update_password', 'base' => false)), 'class' => 'editor_form')),
    $form->input('password', array('label' => 'New password', 'tabindex' => '1')),
    $form->input('confirm_password', array('label' => 'New password again', 'type' => 'password', 'tabindex' => '2')),
    $form->hidden('name'),
    $form->hidden('id');
?>
    <?php
		$options = array(
    	'label' => 'Save',
    	'name' => 'Save',
    	'div' => array(
        	'class' => 'wf-form-button',
    		)
		);
		echo $form->end($options);
	?> <span>or</span> <?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('class' => 'cancel')); ?>
