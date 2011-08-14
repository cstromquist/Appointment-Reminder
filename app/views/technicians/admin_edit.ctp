<h2 class="section">Edit Technician</h2>
<script type="text/javascript">
	<!--
	function confirmDelete() {
		var answer = confirm("Are you sure you want to delete this technician?")
		if (answer){
			window.location = "<?php echo $this->base ?>/admin/technicians/delete/<?php echo $this->data['Technician']['id'] ?>"; 
		}
	}
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
		if($this->data['Technician']['image_path']) {
			echo $html->image('uploads/technicians/small/'.$this->data['Technician']['image_path']);
		} else {
			echo $html->image('avatar.png');
		}
	?>
	<br />
	<?php	
		echo $html->link(__('Change Photo', true), array('action' => 'change_photo/' . $this->data['Technician']['id'])); 
	?>
	</div>
	<div class="tech_info">
		<?php
	    echo 
	    $form->create('Technician', array('action' => 'admin_update', 'class' => 'editor_form', 'type' => 'file')),
	    $form->hidden('company_id'),
	    $form->input('name', array('between' => '', 'label' => 'Technician name')),
	    $form->input('bio', array('between' => '', 'label' => 'Technician Bio', 'type' => 'text', 'rows' => 10, 'cols' => 60)),
	    $form->hidden('id'); 
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
    <div id="tech-photo-instr" style="display: none">
		<img src="/img/tech-photo.png" />
		<p><strong>Please upload a vertical photo of the technician.</strong></p>
	</div>
<?php $partialLayout->blockEnd(); ?>
