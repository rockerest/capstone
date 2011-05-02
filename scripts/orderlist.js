$(document).ready(function() {
	
	setTimeout("location.reload()", 10000);
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
	var classint = parseInt($(this).parent().parent().attr('class'));
	classint++;
	$(this).parent().parent().attr('class', classint);
	update_status($(this).attr('id'), classint);
	if(classint==6 || classint==11)
	{
		$('#sort2 tr:last').after($(this).parent().parent());
	}
	if(classint==7)
	{
		$('#sort thead').after($(this).parent().parent());
	}
});

$( '.decrease_status' ).click(function(){
	var classint = parseInt($(this).parent().parent().attr('class'));
	classint--;
	$(this).parent().parent().attr('class', classint);
	update_status($(this).attr('id'), classint);	
	if(classint==5 || classint==10)
	{
		$('#sort thead').after($(this).parent().parent());
	}
});
