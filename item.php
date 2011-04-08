<?php
	set_include_path('backbone:components:content:render:model:scripts:styles:images');
	
	require_once('Item.php');
	require_once('Page.php');
	require_once('Template.php');
	
	require_once('Breadcrumb.php');
	
	$tmpl = new Template();

	$tmpl->itemid = isset($_GET['id']) ? $_GET['id'] : -1;
	$tmpl->action = isset($_GET['action']) ? $_GET['action'] : null;
	$tmp = Item::getByID($tmpl->itemid);
	
	switch( $tmpl->action )
	{
		case 'add':
					$page = new Page(1, "OrderUp - Create New Item");
					break;
		case 'edit':
					$page = new Page(1, "OrderUp - Edit Existing Item");
					break;
		case null:
		default:
					$page = new Page(1, "OrderUp - View Item");
					break;
		
	}
	
	if( $tmp )
	{
		$tmpl->item = $tmp;
		
		$tmpl->code = -1;
		$tmpl->message = "Should not display";
		$tmpl->css = "info";
		
		//set breadcrumb
		$bc = new Breadcrumb('item', $tmp->itemid);
		$tmpl->breadcrumb = $bc->path;
	}
	else
	{
		$tmpl->code = 0;
		$tmpl->message = "Could not find item.";
		$tmpl->css = "error";
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