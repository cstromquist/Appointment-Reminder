<h2 class="section">Create Technician</h2>
<?php 

    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>
<div id="technician_details">
		<?php
	    echo 
	    $form->create('Technician', array('action' => 'admin_create', 'class' => 'editor_form')),
	    $form->hidden('company_id'),
	    $form->input('name', array('between' => '', 'label' => 'Technician name', 'div' => array('class' => 'title_input'))),
	    $form->hidden('id');
		$options = array(
    	'label' => 'Create this technician',
    	'name' => 'Create this technician',
    	'div' => array(
        	'class' => 'wf-form-button',
    		)
		);
		if(isset($this->params['url']['redirect'])) {
			echo $form->hidden('redirect', array('value' => $this->params['url']['redirect']));
		}
		echo $form->end($options);
	?> <span>or</span> <?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('class' => 'cancel')); ?>
</div>

<span class="cleaner"></span>

<?php $partialLayout->blockStart('sidebar'); ?>
    
<?php $partialLayout->blockEnd(); ?>
