var alternateRows = function()
{
	$( 'tbody tr' ).each(function(index, value){
		if( index % 2 != 0 )
		{
			$(this).css('background-color', "#EFEFEF");
		}
	});
}

alternateRows();

$( '.elevate_order' ).click( function(){
	var id = $(this).attr('data-id');
	$.ajax({
		type : "POST",
		data : {"id" : id},
		url : "components/SubmitOrder.php",
		success : function(data){
			location.reload();
		}
	});
});
