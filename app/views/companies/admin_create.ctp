<h2 class="section">Create Company</h2>
<?php 

    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>
<div id="technician_details">
		<?php
	    echo 
	    $form->create('Company', array('action' => 'admin_create', 'class' => 'editor_form')),
	    $form->input('name', array('between' => '', 'label' => 'Company name', 'div' => array('class' => 'title_input'))),
	    $form->hidden('id');
		$options = array(
    	'label' => 'Create this company',
    	'name' => 'Create this company',
    	'div' => array(
        	'class' => 'wf-form-button',
    		)
		);
		echo $form->end($options);
	?> <span>or</span> <?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('class' => 'cancel')); ?>
</div>

<span class="cleaner"></span>

<?php $partialLayout->blockStart('sidebar'); ?>
    
<?php $partialLayout->blockEnd(); ?>
