<h2 class="section">Add a new service</h2>
<?php 

    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>
<p>Below is a list of services you can add to your list. Simply check the box, or uncheck to remove a service and click save.</p>

<?php
echo $form->create('CompanyService', array('action' => 'add'));
echo $form->input('company_services', array('multiple' => 'checkbox', 'type' => 'select', 'label' => 'Services', 'options' => $services, 'selected' => $selectedservices));
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