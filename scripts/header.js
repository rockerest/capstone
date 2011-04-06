$( '#hide-1' ).click(function(){
	$( '.rid-1' ).toggle();
	$(this).toggleClass('okay');
	$(this).toggleClass('error');
});

$( '#hide-2' ).click(function(){
	$( '.rid-2' ).toggle();
	$(this).toggleClass('okay');
	$(this).toggleClass('error');
});

$( '.rid-2, .rid-1' ).hide();