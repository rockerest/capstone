<?php
	set_include_path('backbone:components:content:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');
	
	$page = new Page(4, "OrderUp - Login");
	$tmpl = new Template();
	
	$tmpl->identity = isset($_GET['identity']) ? $_GET['identity'] : '';
	$tmpl->code = isset($_GET['code']) ? $_GET['code'] : -1;
	
	switch($tmpl->code)
	{
		case 1:
				$tmpl->msg-css = "error";
				$tmpl->message = "Username or password incorrect.";
				break;
		case 2:
				$tmpl->msg-css = "error";
				$tmpl->message = "You must fill both fields.";
				break;
		case -1:
		default:
				$tmpl->msg-css = "hide";
				$tmpl->message = "Unknown error.";
				break;
	}
	
	$page->run();
	
	$html = $tmpl->build('login.html');
	$css = $tmpl->build('login.css');
	$js = $tmpl->build('login.js');

	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => '/styles/login.css'
											),
						'js' => $js
						);

	print $page->build($appContent);
	
?>