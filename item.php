<?php
	set_include_path('global:jquery:backbone:components:content:render:model:scripts:styles:images');
	
	require_once('Item.php');
	require_once('Page.php');
	require_once('Template.php');
	
	$page = new Page(1, "OrderUp - Item Review");
	$tmpl = new Template();

	$tmpl->itemid = isset($_GET['id']) ? $_GET['id'] : -1;
	$tmp = Item::getByID($tmpl->itemid);
	
	if( $tmp )
	{
		$tmpl->item = $tmp;
		
		$tmpl->code = -1;
		$tmpl->message = "Should not display";
		$tmpl->css = "info";
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
	//$js = $tmpl->build('item.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => '/styles/item.css'
											),
						'js' => $js
						);

	print $page->build($appContent);

?>