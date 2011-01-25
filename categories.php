<?php
	set_include_path('backbone:components:content:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');
	require_once('Database.php');
	require_once('capstone.db');

	$page = new Page(0, "OrderUp - All Categories");
	$db = new Database($user, $pass, $dbname, $host, 'mysql');
	$tmpl = new Template();
	
	$sql = "SELECT * FROM categories ORDER BY number ASC";
	$tmpl->cats = $db->q($sql);
	
	$page->run();
	
	$html = $tmpl->build('categories.html');
	//$css = $tmpl->build('categories.css');
	//$js = $tmpl->build('categories.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	$css,
						'js' => $js
						);

	print $page->build($appContent);
	
?>