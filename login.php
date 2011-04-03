<?php
	set_include_path('backbone:components:content:scripts:styles:images:render');
	
	require_once('Page.php');
	require_once('Template.php');
	
	$page = new Page(4, "OrderUp - Login");
	$tmpl = new Template();
	
	$tmpl->action = isset($_GET['action']) ? $_GET['action'] : null;
	
	if( $tmpl->action == 'logout' )
	{
		session_destroy();
		header('Location: /');
	}
	
	$tmpl->identity = isset($_GET['identity']) ? $_GET['identity'] : '';
	$tmpl->fname = isset($_GET['fname']) ? $_GET['fname'] : '';
	$tmpl->lname = isset($_GET['lname']) ? $_GET['lname'] : '';
	
	$tmpl->code = isset($_GET['code']) ? $_GET['code'] : -1;
	$tmpl->fwd = isset($_GET['fwd']) ? $_GET['fwd'] : null;
	
	switch($tmpl->code)
	{
		case 1:
				$tmpl->css = "error";
				$tmpl->message = "Username or password incorrect.";
				break;
		case 2:
				$tmpl->css = "error";
				$tmpl->message = "You must fill both fields.";
				break;
		case 3:
				$tmpl->css = "error";
				$tmpl->message = "You must enter a first name.";
				break;
		case 4:
				$tmpl->css = "error";
				$tmpl->message = "You must enter a last name.";
				break;
		case 5:
				$tmpl->css = "error";
				$tmpl->message = "You must enter a valid email address.";
				break;
		case 6:
				$tmpl->css = "error";
				$tmpl->message = "You must enter a password.";
				break;
		case 7:
				$tmpl->css = "error";
				$tmpl->message = "The email addresses you entered do not match.  Please check your spelling and try again.";
				break;
		case 8:
				$tmpl->css = "error";
				$tmpl->message = "The passwords you entered do not match, please try again.";
				break;
		case 9:
				$tmpl->css = "error";
				$tmpl->message = "The user could not be created.  Please try again later.";
				break;
		case 10:
				$tmpl->css = "alert";
				$tmpl->message = "You must be authorized to perform that action.  Please log in.";
				break;
		case -1:
		default:
				$tmpl->css = "hide";
				$tmpl->message = "Unknown error.";
				break;
	}
	
	$page->run();
	
	$html = $tmpl->build('login.html');
	$css = $tmpl->build('login.css');
	//$js = $tmpl->build('login.js');

	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'login'
											),
						'js' => $js
						);

	print $page->build($appContent);
	
?>