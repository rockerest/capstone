<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	
	require_once('Page.php');
	require_once('Template.php');

	$page = new Page(0, "OrderUp - Submit Order");
	$tmpl = new Template();
	
	$page->run();
	
	$html = $tmpl->build('order.html');
	//$css = $tmpl->build('order.css');
	//$js = $tmpl->build('order.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => '/styles/order.css'
											),
						'js' => $js
						);

	print $page->build($appContent);
?>