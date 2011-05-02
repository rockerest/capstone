<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	
	require_once('Page.php');
	require_once('Template.php');

	$page = new Page(0, "OrderUp - Error");
	$tmpl = new Template();
	
	$page->run();
	
	$html = $tmpl->build('error.html');
	//$css = $tmpl->build('error.css');
	//$js = $tmpl->build('error.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'error'
											),
						'js' => $js
						);

	print $page->build($appContent);
?>