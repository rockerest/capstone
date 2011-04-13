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

if( $('#add').length > 0 )
{
	makeSuggest($('#add #name'), 0, 1, "name", "name", 0);
	makeSuggest($('#add #cat'), 1, 1, "name", "name", 0);
	makeSuggest($('#add #chars'), 3, 10000, "name", "name", 0);
	makeSuggest($('#add #ings'), 2, 10000, "name", "name", 0);
}

$( '#delete #no' ).click(function(){
	history.go(-1);
	return false;
});