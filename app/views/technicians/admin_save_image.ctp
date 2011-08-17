<h2 class="section">Success! Your final image is below.</h2>
<script type="text/javascript">
	function closeMe() {
		parent.location.reload();
		window.close();
	}
</script>
<div style="text-align: center;">
	<?php echo $html->image('uploads/technicians/'.$technician['Technician']['image_path']); ?><br />
	Don't like it? <?php echo $html->link('Click here to upload a new photo', array('controller' => 'technicians', 'action' => 'upload_photo')) ?>
	<br /> - or - <br /> 
	<input type="button" onClick="closeMe()" value="Continue and close window">
</div>