<h2 class="section">Preview</h2>
Don't like it? <a href="javascript: history.back(-1)">Go back and make changes.</a>
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
<iframe name="preview_frame" width="600" height="100%">
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
		$form->input('service_message', array('type' => 'textarea')),
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