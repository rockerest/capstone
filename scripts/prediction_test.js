
function split( val ) {
			return val.split( /,\s*/ );
		}
		
function extractLast( term ) {
	return split( term ).pop();
}
	
var makeSuggest = function( obj, which, limit, show, insert, complex)
{
	var url = "components/suggItem.php";
	
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
					$("#item_suggest").attr('action', "?item="+ui.item[insert]);
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

if( $('#item_suggest').length > 0 )
{
	makeSuggest($('#item_suggest #name'), 0, 1, "name", "name", 0);
}

