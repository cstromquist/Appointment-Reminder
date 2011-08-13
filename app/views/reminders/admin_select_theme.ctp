<h2 class="section">Select Your Theme</h2>
<?php 
    if ($session->check('Message.flash')) {
        $session->flash();
    }
?>
<script type="text/javascript">
	<!--
	var theme = "<?php echo $theme; ?>";
	 $(document).ready(function(){
		$('#theme_select li').click( function(){
			$('li.selected').removeClass('selected');
			$(this).addClass('selected');
			$('#theme').val($(this).attr('id'));
		});
		if(theme) {
			$('#' + theme).addClass('selected');
		}
	});
	-->
</script>
<?php echo $form->create('Reminder', array('action' => 'select_technician', 'class' => 'editor_form')); ?>
<?php echo $form->hidden('theme', array('id' => 'theme', 'value' => $theme)); ?>
<?php echo $form->hidden('id'); ?>
<ul id="theme_select">
	<li id="spring"><?php echo $html->image('theme_spring.jpg', array('width' => 150)); ?><br /><strong>Spring/Summer Theme</strong></li>
	<li id="fall"><?php echo $html->image('theme_fall.jpg', array('width' => 150)); ?><br /><strong>Fall Theme</strong></li>
	<li id="winter"><?php echo $html->image('theme_winter.jpg', array('width' => 150)); ?><br /><strong>Winter Theme</strong></li>
</ul>

<div class="clear" style="padding-bottom: 10px;"></div>
	<?php
	    echo $form->create('Reminder', array('action' => 'select_technician', 'class' => 'editor_form'));
		$options = array(
	    	'label' => 'Continue',
	    	'name' => 'Continue',
	    	'div' => array(
	        	'class' => 'wf-form-button',
	    		)
			);
		echo $form->end($options);
	?>or
	<?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('class' => 'cancel'));	?>
	</div>