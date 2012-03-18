<h2 class="section">Update User</h2>
<?php echo $html->link('Change password', array('action' => 'admin_change_password', $this->data['User']['id'])); ?>
<?php 
    echo 
    $form->create('User', array('url' => $html->url(array('action' => 'admin_update', 'base' => false)), 'class' => 'editor_form')),
    $form->input('name', array('tabindex' => '1')),
    $form->input('email', array('tabindex' => '2')),
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

