<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	
	require_once('Page.php');
	require_once('Template.php');

	$page = new Page(0, "OrderUp - Menu");
	$tmpl = new Template();
	
	$page->run();
	
	$html = $tmpl->build('menu.html');
	$css = $tmpl->build('menu.css');
	//$js = $tmpl->build('menu.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => '/styles/menu.css'
											),
						'js' => $js
						);

	print $page->build($appContent);
?>