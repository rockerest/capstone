<?php
	set_include_path('backbone:components:content:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');

	$page = new Page(1, "OrderUp - a new way to improve your dining experience");
	$tmpl = new Template();
	
	$page->run();
	
	$html = $tmpl->build('order.html');
	$css = $tmpl->build('order.css');
	$js = $tmpl->build('order.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	$css,
						'js' => $js
						);

	print $page->build($appContent);
	
?>