<script language="javascript">
	function limitChars(textid, limit, infodiv)
	{
		var text = $('#'+textid).val();	
		var textlength = text.length;
		if(textlength > limit)
		{
			$('#' + infodiv).html('You cannot write more then '+limit+' characters!');
			$('#'+textid).val(text.substr(0,limit));
			return false;
		}
		else
		{
			$('#' + infodiv).html('You have '+ (limit - textlength) +' characters left.');
			return true;
		}
	}
	
	function limitLines(textid, limit, infodiv) {
		var text = $('#'+textid).val();	
		var lines = text.split(/\r?\n|\r/).length;
		if(lines > (limit-1))
		{	
			$('#' + infodiv).html('Limit of '+limit+' points reached.');
			document.onkeypress = kk;
			return false;
		}
		else
		{
			$('#' + infodiv).html('You have '+ (limit - lines) +' points left.');
			document.onkeypress = go;
			return true;
		}
	}
	
	function kk(e) {		
		key = e ? e.which : window.event.keyCode;
		if(key==13) {
			return false;
		} else {
			return true;
		}
	}
	
	function go(e) {
		return true;
	}

	
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