<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	
	require_once('Page.php');
	require_once('Template.php');
	require_once('Table.php');

	$page = new Page(0, "OrderUp - Table Designation");
	$tmpl = new Template();
	
	$tmpl->code = isset($_GET['code']) ? $_GET['code'] : -1;
	
	if( !$_SESSION['active'] || $_SESSION['roleid'] > 2 )
	{
		$loc = urlencode("table.php");
		header("Location: /login.php?code=10&fwd=$loc");
	}
	
	$page->run();
	
	$tables = Table::get();
	
	if( $tables && is_array($tables) )
	{
		$tmpl->tables = $tables;
	}
	else
	{
		$tmpl->tables = array($tables);
	}
	
	switch($tmpl->code)
	{
		case 0:
				$tmpl->css = "error";
				$tmpl->message = "To be activated, this device needs to be assigned to a table.";
				break;
		case 1:
				$tmpl->css = "error";
				$tmpl->message = "Unknown or invalid table id.  Choose another.";
				break;
		case -1:
		default:
				$tmpl->css = "hide";
				$tmpl->message = "Unknown error.";
				break;
	}
	
	$html = $tmpl->build('table.html');
	$css = $tmpl->build('table.css');
	//$js = $tmpl->build('table.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'table'
											),
						'js' => $js
						);

	print $page->build($appContent);
?>