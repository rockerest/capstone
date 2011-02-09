<?php
	set_include_path('backbone:components:content:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');
	
	$page = new Page(4, "OrderUp - Login");
	$tmpl = new Template();
	
	$page->run();
	
	$html = $tmpl->build('l.html');
	$css = $tmpl->build('l.css');
	$js = $tmpl->build('l.js');

	$appContent = array(
						'html'	=>	$html,
						'css'	=>	$css,
						'js' => $js
						);

	print $page->build($appContent);
	
?>