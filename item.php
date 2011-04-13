<?php
	set_include_path('backbone:components:content:render:model:scripts:styles:images');
	
	require_once('Item.php');
	require_once('Page.php');
	require_once('Template.php');
	
	require_once('Breadcrumb.php');
	
	$tmpl = new Template();

	$tmpl->itemid = isset($_GET['id']) ? $_GET['id'] : -1;
	$tmpl->action = isset($_GET['action']) ? $_GET['action'] : null;
	$tmpl->code = isset($_GET['code']) ? $_GET['code'] : -1;
	$tmpl->item = $tmp = Item::getByID($tmpl->itemid);
	
	switch( $tmpl->action )
	{
		case 'add':
					$page = new Page(1, "OrderUp - Create New Item");
					break;
		case 'edit':
					$page = new Page(1, "OrderUp - Edit Existing Item");
					break;
		case 'delete':
					$page = new Page(1, "OrderUp - Delete Existing Item");
					break;
		case null:
		default:
					$page = new Page(1, "OrderUp - View Item");
					break;
		
	}
	
	if( !$tmpl->item )
	{
		if( $tmpl->code == -1 && $tmpl->action == null )
		{
			$tmpl->code = 0;
		}
		unset($tmpl->item);
	}
	else
	{
		$tmpl->breadcrumb = new Breadcrumb('item', $tmpl->item->itemid);
	}
	
	switch( $tmpl->code )
	{
		case 0:
			$tmpl->message = "Could not find item.";
			$tmpl->css = "error";
			break;
		case 10:
			$tmpl->message = "Deleting item succeeded.";
			$tmpl->css = "okay";
			break;
		case 11:
			$tmpl->message = "Deleting item failed.";
			$tmpl->css = "error";
			break;
		case 13:
			$tmpl->message = "Adding item succeeded.";
			$tmpl->css = "okay";
			break;
		default:
			break;
	}
	
	$page->run();
	
	$html = $tmpl->build('item.html');
	$css = $tmpl->build('item.css');	
	$js = $tmpl->build('item.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'item'
											),
						'js' => $js
						);

	print $page->build($appContent);

?>