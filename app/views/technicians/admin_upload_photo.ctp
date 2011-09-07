	<h2 class="section">Please choose a photo to upload.</h2>
	<?php 
	if ($session->check('Message.flash')) {
	    $session->flash();
	}
	?>
	<?php echo $form->create('Technician', array('action' => 'crop_image', "enctype" => "multipart/form-data"));?> 
    <?php 
    	echo $form->hidden('id'); 
        echo $form->input('image',array("type" => "file", 'after' => '<div id="slimitinfo" class="hint">Image must be in JPEG/JPG format only.</div>'));  
        echo $form->end('Upload'); 
    ?>