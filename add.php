<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	
	require_once('Page.php');
	require_once('Template.php');
	require_once('Characteristic.php');
	require_once('Ingredient.php');

	$page = new Page(0, "OrderUp - add things");
	$tmpl = new Template();
	
	if( !$_SESSION['active'] || $_SESSION['roleid'] > 2 )
	{
		$loc = urlencode("add.php");
		header("Location: /login.php?code=10&fwd=$loc");
	}
	
	$tmpl->type = isset($_GET['type']) ? $_GET['type'] : null;
	
	if( $tmpl->type == 'characteristic' )
	{
		$chars = Characteristic::getAll();
		if( $chars )
		{
			if( is_object($chars) )
			{
				$tmpl->items = array($chars);
			}
			elseif( is_array($chars) )
			{
				$tmpl->items = $chars;
			}
		}
		else
		{
			$tmpl->items = array();
		}
		
		while( (count($tmpl->items) % 6) != 0 )
		{
			array_push($tmpl->items, new Characteristic("",""));
		}
	}
	
	$page->run();
	
	$html = $tmpl->build('add.html');
	$css = $tmpl->build('add.css');
	$js = $tmpl->build('add.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'add'
											),
						'js' => $js
						);

	print $page->build($appContent);
?>