<!-- THIS ISN'T WORKING IN IE..WHAT A SURPRISE!!..SO JUST RELOADING PARENT AND CLOSING WINDOW FOR NOW.. -->
<h2 class="section">Success!</h2>
<p>Saving and closing window...</p>
<script type="text/javascript">
	//function closeMe() {
		window.parent.document.forms[0].submit();
		window.close();
	//}
</script>
<!--div style="text-align: center;">
	<?php echo $html->image('uploads/technicians/'.$technician['Technician']['image_path']); ?><br />
	Don't like it? <?php echo $html->link('Click here to upload a new photo', array('controller' => 'technicians', 'action' => 'upload_photo')) ?>
	<br /> - or - <br /> 
	<input type="button" onClick="closeMe()" value="Continue and close window">
</div-->