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