<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	require_once('Page.php');
	require_once('Template.php');
	require_once('Item.php');
	require_once('Category.php');
	
	$page = new Page(0, "OrderUp - Menu");
	$tmpl = new Template();
	
	$cat = isset($_GET['cat']) ? $_GET['cat'] : -1;
	
	if( $cat == -1 )
	{
		$tmpl->cats = Category::getTopLevel();
		if( $tmpl->cats instanceof Category )
		{
			$tmpl->cats = array( $tmpl->cats );
		}
	}
	else
	{
		$tmpl->cats = Category::getByParent($cat);
		if( !$tmpl->cats )
		{
			$tmpl->items = Item::getByCategory($cat);
		}
		elseif( $tmpl->cats instanceof Category )
		{
			$tmpl->items = Item::getByCategory($tmpl->cats->categoryid);
		}
		
		if( $tmpl->items instanceof Item )
		{
			$tmpl->items = array( $tmpl->items );
		}
	}
	
	$page->run();
	
	$html = $tmpl->build('menu.html');
	$css = $tmpl->build('menu.css');
	//$js = $tmpl->build('menu.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'menu'
											),
						'js' => $js
						);

	print $page->build($appContent);
?>