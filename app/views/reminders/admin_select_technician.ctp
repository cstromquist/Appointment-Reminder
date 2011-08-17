<h2 class="section">Select your technician</h2>
<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>
<script type="text/javascript">
	<!--
	var technician_id = "<?php echo $this->data['Reminder']['technician_id']; ?>";
	 $(document).ready(function(){
		$('#choose_tech li').click( function(){
			$('li.selected').removeClass('selected');
			$(this).addClass('selected');
			$('#technician_id').val($(this).attr('id'));
		});
		if(technician_id) {
			$('#' + technician_id).addClass('selected');
		} else {
			$('#0').addClass('selected');
		}
	});
	-->
</script>
<?php echo $form->create('Reminder', array('action' => 'email_details', 'class' => 'editor_form')); ?>
<?php echo $form->hidden('technician_id', array('id' => 'technician_id')); ?>
<?php echo $form->hidden('theme', array('id' => 'theme', 'value' => $theme)); ?>
<?php echo $form->hidden('id'); ?>
<?php echo $form->hidden('form_email_details', array('value' => false)); ?>
<ul id="choose_tech">
	<?php foreach($technicians as $tech): ?>
	<li class="technician_column" id="<?= $tech['Technician']['id'] ?>">
		<div class="photo">
		<?php if($tech['Technician']['image_path'] != "") { echo $html->image('uploads/technicians/'.$tech['Technician']['image_path']); } ?>
		</div>
		<div class="info">
			<h4><?php echo $tech['Technician']['name'] ?></h4>
			<p><?php echo $tech['Technician']['bio'] ?></p> 
		</div>
	</li>
	<?php endforeach; ?>
	<li class="technician_column" id="0">
		<div class="photo">
		<?php echo $html->image('no-tech.png'); ?>
		</div>
		<div class="info">
			<h4>NO TECH</h4>
			<p>Choose this option if you would like to show a group shot of your company.</p> 
		</div>
	</li>
</ul>
	<div class="clear"></div>
	<?php
		$options = array(
	    	'label' => 'Continue',
	    	'name' => 'Continue',
	    	'div' => array(
	        	'class' => 'wf-form-button',
	    		)
			);
		//echo $form->button('Go Back', array('onclick' => "location.href='/admin/reminders/select_theme'")); 
		echo $form->end($options);
	?>or
	<?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('class' => 'cancel'));	?>

	<?php echo $this->renderElement('sidebar_preview'); ?>