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