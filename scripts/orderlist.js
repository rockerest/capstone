$(document).ready(function() {

	  
	// Return a helper with preserved width of cells
	var fixHelper = function(e, ui) {
	  ui.children().each(function() {
		$(this).width($(this).width());
	  });
	  return ui;
	};
	 
	$("#sort tbody").sortable({
	  helper: fixHelper
	}).disableSelection();
	
	$("#sort2 tbody").sortable({
	  helper: fixHelper
	}).disableSelection();
});

function update_status(orderid, status){
	var ajaxRequest;
	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}

	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			$(".status_id_order_" + orderid).text(status);
		}
	}
	ajaxRequest.open("GET", "components/updatestatus.php?orderid=" + orderid + "&newstatus=" + status, true);
	ajaxRequest.send(null); 
}

$( '.increase_status' ).click(function(){
	if($(this).parent().parent().attr('class')=='status2')
	{
		$(this).parent().parent().removeClass('status2');
		$(this).parent().parent().addClass('status3');
		update_status($(this).attr('id'), 3);
	}
	else if($(this).parent().parent().attr('class')=='status3')
	{
		$(this).parent().parent().removeClass('status3');
		$(this).parent().parent().addClass('status4');
		update_status($(this).attr('id'), 4);
		$('#sort2 tr:last').after($(this).parent().parent());
	}
	else if($(this).parent().parent().attr('class')=='status0')
	{
		$(this).parent().parent().removeClass('status0');
		$(this).parent().parent().addClass('status2');
		update_status($(this).attr('id'), 2);
	}
});

$( '.decrease_status' ).click(function(){
	if($(this).parent().parent().attr('class')=='status0')
	{
		$(this).parent().parent().removeClass('status0');
		$(this).parent().parent().addClass('status2');
		update_status($(this).attr('id'), 2);
	}
	else if($(this).parent().parent().attr('class')=='status3')
	{
		$(this).parent().parent().removeClass('status3');
		$(this).parent().parent().addClass('status2');
		update_status($(this).attr('id'), 2);
	}
	else if($(this).parent().parent().attr('class')=='status4')
	{
		$(this).parent().parent().removeClass('status4');
		$(this).parent().parent().addClass('status3');
		update_status($(this).attr('id'), 3);
		$('#sort thead').after($(this).parent().parent());
	}
});
