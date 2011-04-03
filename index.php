<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	
	require_once('Page.php');
	require_once('Template.php');

	$page = new Page(0, "OrderUp - improve your dining");
	$tmpl = new Template();
	
	$page->run();
	
	$html = $tmpl->build('index.html');
	$css = $tmpl->build('index.css');
	//$js = $tmpl->build('index.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'index'
											),
						'js' => $js
						);

	print $page->build($appContent);
?>
