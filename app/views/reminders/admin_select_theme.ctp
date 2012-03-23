<h2 class="section centered">Select Your Theme</h2>
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
	<li id="spring"><strong>Spring/Summer Theme</strong><br /><?php echo $html->image('theme_spring.jpg', array('width' => 150)); ?></li>
	<li id="fall"><strong>Fall Theme</strong><br /><?php echo $html->image('theme_fall.jpg', array('width' => 150)); ?></li>
	<li id="winter"><strong>Winter Theme</strong><br /><?php echo $html->image('theme_winter.jpg', array('width' => 150)); ?></li>
	<li id="pet_adoption"><strong>Pet Adoption Theme</strong><br /><?php echo $html->image('theme_pet_adoption.jpg', array('width' => 150)); ?></li>
	<li id="bca"><strong>Breast Cancer Awareness Theme</strong><br /><?php echo $html->image('theme_breast_cancer_awareness.jpg', array('width' => 150)); ?></li>
	<li id="thanksgiving"><strong>Thanksgiving Theme</strong><br /><?php echo $html->image('theme_thanksgiving.jpg', array('width' => 150)); ?></li>
	<li id="halloween"><strong>Halloween Theme</strong><br /><?php echo $html->image('theme_halloween.jpg', array('width' => 150)); ?></li>
	<li id="july4th"><strong>4th of July Theme</strong><br /><?php echo $html->image('theme_july4th.jpg', array('width' => 150)); ?></li>
	<li id="blank"><strong>Blank Theme</strong><br /><?php echo $html->image('theme_blank.jpg', array('width' => 150)); ?></li>
</ul>

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
	?>or
	<?php echo $html->link(__('Cancel', true), array('action' => 'index'), array('class' => 'cancel'));	?>
	</div>