<?php
	set_include_path('backbone:components:content:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');
	require_once('Database.php');
	require_once('capstone.db');
	
	if(isset($_GET['logout']&&$_GET['logout']==true)
	{
		session_destroy();
		header('Location: index.php');
	}
	
	$page = new Page(0, "OrderUp - a new way to improve your dining experience");
	$submit = isset( $_GET['submit'] ) ? $_GET['submit'] : 0;
	$db = new Database($user, $pass, $dbname, $host, 'mysql');
	
	$tmpl = new Template();
	
	$page->run();
	

	
	if($submit != 1)
	{
		$html = $tmpl->build('login.html');
		$css = $tmpl->build('login.css');
		//$js = $tmpl->build('index.js');
	}
	else
	{
		if($_POST['email']!=''&&$_POST['pass']!='')
		{
			$email = $_POST['email'];
			$userpass = $_POST['pass'];

			$sql = 'SELECT salt FROM authentication WHERE identity=?';
			$results = $db->qwv( $sql, array($email) );
			$salt = $results[0]['salt'];
			$salted = hash('whirlpool', $salt.$userpass);
			$sql = 'SELECT * FROM authentication WHERE identity=? AND password=?';
			$values = array( $email, $salted );
			$results = $db->qwv( $sql, $values );
			$num = count($results);
			if($num == 1)
			{
				$userid = $results[0]['userid'];
				$user_info = $db->q("SELECT * FROM users WHERE userid = '$userid'");
				
				setSessionVar('active', true);
				setSessionVar('role', $results[0]['roleid']);
				setSessionVar('userid', $userid);
			}
		}
	}

	$appContent = array(
						'html'	=>	$html,
						'css'	=>	$css,
						'js' => $js
						);

	print $page->build($appContent);
	
?>