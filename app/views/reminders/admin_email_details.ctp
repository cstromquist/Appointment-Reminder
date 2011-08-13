<h2 class="section">Generate New Email</h2>
<script type="text/javascript">
	var service_message = new Array;
	var fb = new Array;
	var services = new Array;
	var other_services = new Array;
	<?php $i = 0; ?>
	<?php foreach($services as $service): ?>
	service_message[<?php echo $i ?>] = "<?php echo $service['Industry']['service_message'] ?>";
	fb[<?php echo $i ?>] = "<?php echo str_replace(array('"',"'", "\r", "\n", "\0"), array('\"','\\\'','\r', '\n', '\0'), $service['Industry']['features_benefits']) ?>";
	services[<?php echo $i ?>] = "<?php echo str_replace(array('"',"'", "\r", "\n", "\0"), array('\"','\\\'','\r', '\n', '\0'), $service['Industry']['services']) ?>";
	other_services[<?php echo $i ?>] = "<?php echo str_replace(array('"',"'", "\r", "\n", "\0"), array('\"','\\\'','\r', '\n', '\0'), $service['Industry']['other_services']) ?>";
	<?php $i++; ?>
	<?php endforeach; ?>
	function changeService(e) {
		if(e.selectedIndex) {
			$('#ReminderServiceMessage').val(service_message[e.selectedIndex - 1]);
			$('#ReminderFeaturesBenefits').val(fb[e.selectedIndex - 1]);
			$('#ReminderServices').val(services[e.selectedIndex - 1]);
			$('#ReminderOtherServices').val(other_services[e.selectedIndex - 1]);
		}
	}
</script>
<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>
<?php if(isset($valid) && $valid): ?>
	Don't like it? <input type="button" onClick="history.back(-1)" value="Go back and make changes" />
<script type="text/javascript">
	<!--
	 $(document).ready(function(){
		$("#form_preview").submit();
		$('#form_button').click( function(){
			$("#form_send").submit();
		});
	});
	-->
</script>
<div>
<iframe name="preview_frame" width="650" height="800">
  <p>Your browser does not support iframes.</p>
</iframe>
	<?php
	    echo 
	    $form->create('Reminder', array('action' => 'image_generate', 'id' => 'form_preview', 'target' => 'preview_frame')),
	    $form->input('fname', array('class' => 'medium', 'label' => 'First Name', 'error' => 'Please specify a first name.')),
	    $form->input('lname', array('class' => 'medium', 'label' => 'Last Name')),
	    $form->input('email', array('class' => 'medium', 'size' => '20')),
		$form->input('service_date', array('size' => '15', 'class' => 'w8em format-y-m-d divider-dash highlight-days-12 no-transparency')),
		$form->input('from_time', array('type' => 'time')),
		$form->input('to_time', array('type' => 'time')),
		$form->input('service_message', array('type' => 'textarea', 'id' => 'service_message')),
		$form->input('features_benefits', array('type' => 'textarea')),
		$form->input('services', array('type' => 'textarea', 'rows' => 10, 'cols' => 40, 'label' => 'As Long As We\'re Here<br /> Consider taking advantage of our other services:')),
		$form->input('other_services', array('type' => 'textarea', 'rows' => 10, 'cols' => 40, 'label' => 'Are there any other services you would like?')),
		$form->hidden('theme'),
		$form->hidden('technician_id'),
		$form->hidden('id'),
		$form->end();
	?>
	<div class="clear" style="padding-bottom: 10px;"></div>
	<?php
	    echo 
	    $form->create('Reminder', array('action' => 'email_send', 'id' => 'form_send')),
	    $form->input('fname', array('class' => 'medium', 'label' => 'First Name', 'error' => 'Please specify a first name.')),
	    $form->input('lname', array('class' => 'medium', 'label' => 'Last Name')),
	    $form->input('email', array('class' => 'medium', 'size' => '20')),
		$form->input('service_date', array('size' => '15', 'class' => 'w8em format-y-m-d divider-dash highlight-days-12 no-transparency')),
		$form->input('from_time', array('type' => 'time')),
		$form->input('to_time', array('type' => 'time')),
		$form->input('service_message', array('type' => 'textarea')),
		$form->input('features_benefits', array('type' => 'textarea', 'rows' => 10, 'cols' => 40, 'label' => 'You\'ve made a great decision!')),
		$form->input('services', array('type' => 'textarea', 'rows' => 10, 'cols' => 40, 'label' => 'As Long As We\'re Here<br /> Consider taking advantage of our other services:')),
		$form->input('other_services', array('type' => 'textarea', 'rows' => 10, 'cols' => 40, 'label' => 'Are there any other services you would like?')),
		$form->hidden('theme'),
		$form->hidden('technician_id'),
		$form->hidden('id'),
		$form->end();
	?>
	<?php echo $form->button('Send Reminder!', array('id' => 'form_button')); ?>
	<?php
	//$thickbox->setProperties(array('id'=>'domId','type'=>'iframe','iframeUrl'=>'/admin/reminders/email_create', 'title' => 'Preview Appointment Email'));
	//$thickbox->setPreviewContent('Preview');
	//echo $thickbox->output();
	?>or
	<?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('class' => 'cancel'));	?>
</div>
<?php else: ?>
	<div class="generate_email">
	<?php
	    echo 
	    $form->create('Reminder', array('action' => 'email_details', 'class' => 'editor_form')),
	    $form->input('fname', array('class' => 'medium', 'label' => 'First Name', 'error' => 'Please specify a first name.')),
	    $form->input('lname', array('class' => 'medium', 'label' => 'Last Name')),
	    $form->input('email', array('class' => 'medium', 'size' => '20')),
		$form->input('service_date', array('size' => '15', 'class' => 'w8em format-y-m-d divider-dash highlight-days-12 no-transparency')),
		$form->input('from_time', array('type' => 'time')),
		$form->input('to_time', array('type' => 'time'));
		?>
		<div class="input text">
			<label for="ReminderService">Service Type</label>
			<?php echo $form->select('service', $servicelist, $selected=null, array("onchange"=>"changeService(this)"), 'Select a service type'); ?>
		</div>
		<?php
		echo
		$form->input('service_message', array('type' => 'textarea', 'rows' => 10, 'cols' => 60, 'label' => 'Service Message')),
		$form->input('features_benefits', array('type' => 'textarea', 'rows' => 10, 'cols' => 60, 'label' => 'You\'ve made a great decision!')),
		$form->input('services', array('type' => 'textarea', 'rows' => 10, 'cols' => 60, 'label' => 'As Long As We\'re Here<br /> Consider taking advantage of our other services:')),
		$form->input('other_services', array('type' => 'textarea', 'rows' => 10, 'cols' => 60, 'label' => 'Are there any other services you would like?')),
		$form->hidden('theme'),
		$form->hidden('technician_id'),
		$form->hidden('id'),
		$form->hidden('form_email_details', array('value' => true));
	?>
	<div class="clear" style="padding-bottom: 10px;"></div>
	<?php
		$options = array(
	    	'label' => 'Continue',
	    	'name' => 'Continue',
	    	'div' => array(
	        	'class' => 'wf-form-button',
	    		)
			);
		echo $form->end($options);
	?>
	<?php
	//$thickbox->setProperties(array('id'=>'domId','type'=>'iframe','iframeUrl'=>'/admin/reminders/email_create', 'title' => 'Preview Appointment Email'));
	//$thickbox->setPreviewContent('Preview');
	//echo $thickbox->output();
	?>or
	<?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('class' => 'cancel'));	?>
	
	</div>
<?php endif; ?>
	<?php echo $this->renderElement('sidebar_preview'); ?>