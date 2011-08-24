<h2 class="section">Edit Technician</h2>
<script type="text/javascript">
	<!--
	function confirmDelete() {
		var answer = confirm("Are you sure you want to delete this technician?")
		if (answer){
			window.location = "<?php echo $this->base ?>/admin/technicians/delete/<?php echo $this->data['Technician']['id'] ?>"; 
		}
	}
	$(function(){
	 	$('#bio').keyup(function(){
	 		limitChars('bio', 270, 'biolimitinfo');
	 	})
	});
	
	$(document).ready(function(){
	 	limitChars('bio', 270, 'biolimitinfo');
	});
	//-->
</script>
<?php 

    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>
<div id="technician_details">
	<div class="tech_photo">
	<?php	
		if(isset($this->data['Technician']['image_path']) && $this->data['Technician']['image_path']) {
			echo $html->image('uploads/technicians/'.$this->data['Technician']['image_path'] .'?' . time(), array('width' => 75));
		} else {
			echo $html->image('avatar.png');
		}
	?>
	<br />
	<?php	
		//echo $html->link(__('Change Photo', true), array('action' => 'change_photo/' . $this->data['Technician']['id'])); 
	?>
	<?php
	$thickbox->setProperties(array('id'=>'domId','type'=>'iframe','iframeUrl'=>'/admin/technicians/upload_photo/'.$this->data['Technician']['id'], 'title' => 'Upload Technician Photo'));
	$thickbox->setPreviewContent('Upload Photo');
	echo $thickbox->output();
	?>
	</div>
	<div class="tech_info">
		<?php
	    echo 
	    $form->create('Technician', array('action' => 'admin_edit', 'class' => 'editor_form', 'type' => 'file', 'name' => 'tech_form')),
	    $form->hidden('id'),
	    $form->hidden('image_path'),
	    $form->hidden('company_id'),
	    $form->input('name', array('between' => '', 'label' => 'Technician name'));
	    $options = array(
							'type' => 'textarea', 
							'rows' => 10, 
							'cols' => 60, 
							'label' => 'Technician Bio', 
							'id' => 'bio',
							'after' => "<div id=\"biolimitinfo\" class=\"hint\">You have 270 characters remaining. </div>");
		echo $form->input('bio', $options);
		$options = array(
    	'label' => 'Save',
    	'name' => 'Save',
    	'div' => array(
        	'class' => 'wf-form-button',
    		)
		);
		echo $form->end($options);
	?> <span>or</span> <?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('class' => 'cancel')); ?>
	<input type="button" onClick="javascript: return confirmDelete();" value="Delete this technician" id="delete-button" />
	</div>
</div>

<span class="cleaner"></span>

<?php $partialLayout->blockStart('sidebar'); ?>
    
<?php $partialLayout->blockEnd(); ?>
