$( '#gotoItem' ).click(function(){
	go_here('item.php?action=add');
	return false;
});

$( '#gotoIng' ).click(function(){
	go_here('add.php?type=ingredient');
	return false;
});

$( '#gotoChar' ).click(function(){
	go_here('add.php?type=characteristic');
	return false;
});

function split( val ) {
			return val.split( /,\s*/ );
		}
		
function extractLast( term ) {
	return split( term ).pop();
}
	
var makeSuggest = function( obj, which, limit, show, insert, complex)
{
	var url = "";
	switch( which )
	{
		case 0:
				url = "components/suggItem.php";
				break;
		case 1:
				url = "components/suggCat.php";
				break;
		case 2:
				url = "components/suggIng.php";
				break;
		case 3:
				url = "components/suggChar.php";
				break;
		default:
				url = "";
				break;
	}
	
	obj
		// don't navigate away from the field on tab when selecting an item
		.bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
					$( this ).data( "autocomplete" ).menu.active ) {
				event.preventDefault();
			}
			
			if ((event.keyCode || event.which) == 13)
			{
				event.preventDefault();
			}
		})
		.autocomplete({
			source: function( request, response ) {
				$.getJSON( url, {
					q: extractLast( request.term )
				}, response );
			},
			focus: function( event, ui ) {
				return false;
			},
			select: function( event, ui ) {
				var terms = split( this.value );
				if( terms.length <= limit )
				{
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item[insert] );
				}
				else
				{
					terms.pop();
				}
				
				if( terms.length < limit )
				{
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
				}
				
				this.value = terms.join( ", " );
				return false;
			},
			delay: 100
		}).data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( function(){
					if( complex == 1 )
					{
						return "<a>" + item[show] + " : " + item[insert] + "</a>";
					}
					else if( complex == 0 )
					{
						return "<a>" + item[show] + "</a>";
					}
				})
				.appendTo( ul );
		};
}

if( $('#char').length > 0 )
{
	makeSuggest($('#char #name'), 3, 0, "name", "name", 0);
}

$( '#char button[type="submit"]' ).click(function(){
	var name = $( '#char #name' ).val(),
		
	json = {
				"name" : name
			};
	$.ajax({
		type : "POST",
		url : "components/AddCharacteristic.php",
		data : json,
		success : function(data){
			var data = $.parseJSON(data),
				css = data.status ? "okay" : "error",
				msgbox = $( '#ajaxError' );
				
			if( msgbox.length == 1 )
			{
				msgbox.html(data.message).removeClass('error okay').addClass(css);
			}
			else
			{
				$( '#switch' ).after('<div id="ajaxError" class="message-box center w50 bump ' + css + '">' + data.message + '</div>');
			}
		}
	});
	
	return false;
});

$( '#refreshPage' ).click(function(){
	window.location.reload();
});