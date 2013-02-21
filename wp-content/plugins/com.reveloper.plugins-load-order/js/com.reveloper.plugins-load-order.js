$(document).ready(function(){
	
	// make list sortable
	$('#pluginsList').sortable();
	
	// submit the custom order
	$('#saveOrder').click(function(){
		$('#hidList').val( array2json( $('#pluginsList').sortable('toArray') ) );
		$('#frmSortPlugins').submit();
	});
	
	// cancel the custom ordering
	$('#cancelOrder').click(function(){
		$('#pluginsList').sortable('cancel');
	});
})