<script language="javascript">
	$(function(){
	 	$('#service_message').keyup(function(){
	 		limitChars('service_message', 81, 'smlimitinfo');
	 	})
	});
	
	$(document).ready(function(){
	 	limitChars('service_message', 81, 'smlimitinfo');
	});
	
	/* features/benefits */
	$(function(){
	 	$('#features_benefits').keyup(function(){
	 		limitLines('features_benefits', 8, 'fblimitinfo');
	 	})
	});
	
	$(document).ready(function(){
	 	limitLines('features_benefits', 8, 'fblimitinfo');
	 	$('#features_benefits').blur(function(){
	 		document.onkeypress = go;
	 	});
	});
	
	/* services */
	$(function(){
	 	$('#services').keyup(function(){
	 		limitLines('services', 9, 'slimitinfo');
	 	})
	});
	
	$(document).ready(function(){
	 	limitLines('services', 9, 'slimitinfo');
	 	$('#services').blur(function(){
	 		document.onkeypress = go;
	 	});
	});
	
	/* other services */
	$(function(){
	 	$('#other_services').keyup(function(){
	 		limitLines('other_services', 8, 'oslimitinfo');
	 	})
	});
	
	$(document).ready(function(){
	 	limitLines('other_services', 8, 'oslimitinfo');
	 	$('#other_services').blur(function(){
	 		document.onkeypress = go;
	 	});
	});
</script>