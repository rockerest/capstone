<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	require_once('Page.php');
	require_once('Template.php');
	require_once('Item.php');
	
	$page = new Page(0, "OrderUp - Menu");
	$tmpl = new Template();
	
	$view = isset($_GET['cat']) ? $_GET['cat'] : -1;
	
	if($view == "drinks")
	{
		$tmpl->items = array_merge(
						Item::getByCategory(11),
						Item::getByCategory(2),
						Item::getByCategory(12),
						Item::getByCategory(13),
						Item::getByCategory(14),
						Item::getByCategory(6),
						Item::getByCategory(7),
						Item::getByCategory(8),
						Item::getByCategory(9),
						Item::getByCategory(10)
						);
	}
	else if($view == "apps")
	{
		//pass back apps
		$tmpl->items = Item::getByCategory(4);
	}
	else if($view == "entrees")
	{
		//pass back entrees
		$tmpl->items = array_merge(
						Item::getByCategory(1),
						Item::getByCategory(15),
						Item::getByCategory(16),
						Item::getByCategory(17),
						Item::getByCategory(18),
						Item::getByCategory(19),
						Item::getByCategory(21),
						Item::getByCategory(22)
						);
	}
	else if($view == "desserts")
	{
		//pass back desserts
		$tmpl->items = Item::getByCategory(5);
	}
	else if($view == -1)
	{
		//hit with no category
	}
	else
	{
		//don't know how you got here....
	}
	
	$page->run();
	
	$html = $tmpl->build('menu.html');
	$css = $tmpl->build('menu.css');
	$js = $tmpl->build('menu.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => '/styles/menu.css'
											),
						'js' => $js
						);

	print $page->build($appContent);
?>