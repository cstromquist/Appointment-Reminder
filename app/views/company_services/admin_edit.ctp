<h2 class="section">Edit Your Service</h2>

	<?php
	    echo 
	    $form->create('CompanyService', array('action' => 'edit', 'class' => 'editor_form')),
	    $form->hidden('id'),
	    $form->hidden('company_id'),
		$form->hidden('service_id'),
	    $form->input('name', array('class' => 'medium','error' => 'Please specify a service  name.')),
	    $form->input('service_message', array('type' => 'textarea', 'rows' => 10, 'cols' => 60, 'between' => '', 'label' => 'Service Message')),
	    $form->input('features_benefits', array('type' => 'textarea', 'rows' => 10, 'cols' => 60, 'label' => 'You\'ve made a great decision!')),
		$form->input('services', array('type' => 'textarea', 'rows' => 10, 'cols' => 60, 'label' => 'As Long As We\'re Here<br /> Consider taking advantage of our other services:')),
		$form->input('other_services', array('type' => 'textarea', 'rows' => 10, 'cols' => 60, 'label' => 'Are there any other services you would like?'));
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