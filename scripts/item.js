$( 'a.y' ).click(function(){
	$( 'input[type="radio"]#lvl_yes' ).click();
	$( 'a.n' ).removeClass('active');
	$( this ).addClass('active');
	return false;
});

$( 'a.n' ).click(function(){
	$( 'input[type="radio"]#lvl_no' ).click();
	$( 'a.y' ).removeClass('active');
	$( this ).addClass('active');
	return false;
});

$( '#add_ing,#add_char' ).click(function(){
	var inp = $( this ).prev();
	inp.after(inp.clone().val(''));
	return false;
});