<h2 class="section">Add Service</h2>

	<?php
	    echo 
	    $form->create('Industry', array('action' => 'create', 'class' => 'editor_form')),
	    $form->input('name', array('class' => 'medium','error' => 'Please specify an industry name.')),
	    $form->input('service_message', array('between' => '', 'label' => 'Service Message')),
	    $form->input('features_benefits', array('type' => 'textarea', 'label' => 'You\'ve made a great decision!')),
		$form->input('services', array('type' => 'textarea', 'label' => 'As Long As We\'re Here<br /> Consider taking advantage of our other services:')),
		$form->input('other_services', array('type' => 'textarea', 'label' => 'Are there any other services you would like?'));
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
		echo $html->link(__(' or Cancel', true), array('action' => 'index'));
	?>
	</div>