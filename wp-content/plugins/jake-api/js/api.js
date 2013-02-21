function api(url, data, onDone, skipAlert) 
{
	// final update
	jQuery.ajax({
		type: 'POST',
		url: url,
		data: data,
		success: function(dat, textStatus, XMLHttpRequest) 
		{
			// the API should always return JSON data
			try {
				var res = eval('(' + dat + ')');

				if( !res.success && !(skipAlert || res.skipAlert) )
					alert(res.message)

				onDone(res);
			}
			catch(e) {

				var message = 'Error parsing: ' + dat;

				if( !skipAlert )
					alert(message)

				onDone({success:false, message:message});
			}
		},
		error: function(MLHttpRequest, textStatus, errorThrown) 
		{
			var message = 'Error: ' + errorThrown;

			if( !skipAlert )
				alert(message)

			onDone({success:false, message:message});
		}
	});
}