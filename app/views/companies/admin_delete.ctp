<h2 class="section">Confirm Delete</h2>
<p>Are you sure you want to delete this company including all technicians and reminders under this company?</p>
<?php
    echo 
    $form->create('Company', array('action' => 'admin_delete', 'class' => 'editor_form')),
    $form->hidden('id');
	$options = array(
    	'label' => 'Delete',
    	'name' => 'Delete',
    	'div' => array(
        	'class' => 'wf-form-button',
    		)
		);
	echo $form->end($options);
?> <span>or</span> <?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('class' => 'cancel')); ?>
