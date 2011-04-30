<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	
	require_once('Page.php');
	require_once('Template.php');

	$page = new Page(0, "OrderUp - add things");
	$tmpl = new Template();
	
	if( !$_SESSION['active'] || $_SESSION['roleid'] > 2 )
	{
		$loc = urlencode("add.php");
		header("Location: /login.php?code=10&fwd=$loc");
	}
	
	$tmpl->type = isset($_GET['type']) ? $_GET['type'] : null;
	
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