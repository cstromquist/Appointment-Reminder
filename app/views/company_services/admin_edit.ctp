<h2 class="section">Edit Your Service</h2>
<?php echo $this->renderElement('service_validation'); ?>
<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>
	<?php
	    echo 
	    $form->create('CompanyService', array('action' => 'edit', 'class' => 'editor_form')),
	    $form->hidden('id'),
	    $form->hidden('company_id'),
		$form->hidden('service_id'),
	    $form->input('name', array('class' => 'medium','error' => 'Please specify a service  name.'));
		$options = array(
							'type' => 'textarea', 
							'rows' => 5, 
							'cols' => 60, 
							'label' => 'Service Message', 
							'id' => 'service_message',
							'after' => "<div id=\"smlimitinfo\" class=\"hint\">You have 81 characters left</div>");
		echo $form->input('service_message', $options);
		$options = array(
							'type' => 'textarea', 
							'rows' => 10, 
							'cols' => 60, 
							'label' => 'You\'ve made a great decision!', 
							'id' => 'features_benefits',
							'after' => "<div id=\"fblimitinfo\" class=\"hint\">You have 8 points left. </div>");
		echo $form->input('features_benefits', $options);
		$options = array(
							'type' => 'textarea', 
							'rows' => 10, 
							'cols' => 60, 
							'label' => 'As Long As We\'re Here<br /> Consider taking advantage of our other services.', 
							'id' => 'services',
							'after' => "<div id=\"slimitinfo\" class=\"hint\">You have 9 points left. </div>");
		echo $form->input('services', $options);
		$options = array(
							'type' => 'textarea', 
							'rows' => 10, 
							'cols' => 60, 
							'label' => 'Are there any other services you would like?', 
							'id' => 'other_services',
							'after' => "<div id=\"oslimitinfo\" class=\"hint\">You have 8 points left. </div>");
		echo $form->input('other_services', $options);
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
	</div>