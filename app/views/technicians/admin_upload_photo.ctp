	<h2 class="section">Please choose a vertical photo of the technician to upload.</h2>
	<div id="tech-photo-instr">
		<img src="<?php echo $this->base ?>/img/tech-photo.png" width="300" />
	</div>
	<?php echo $form->create('Technician', array('action' => 'crop_image', "enctype" => "multipart/form-data"));?> 
    <?php 
    	echo $form->hidden('id'); 
        echo $form->input('image',array("type" => "file"));  
        echo $form->end('Upload'); 
    ?>