$( '.div_link' ).click(function(){
	var url = $( this ).attr('class').replace(/^.*url\((.+\..+)\).*$/, '$1');
	window.location = url;
});