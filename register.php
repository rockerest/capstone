<?php
	set_include_path('backbone:components:content:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');
	require_once('Database.php');
	require_once('capstone.db');
	
	$page = new Page(0, "OrderUp - Register");
	$tmpl = new Template();
	
	$submit = isset( $_GET['submit'] ) ? $_GET['submit'] : 0;
	$db = new Database($user, $pass, $dbname, $host, 'mysql');
	
	$page->run();
	
	if($submit != 1)
	{
		$html = $tmpl->build('register.html');
		$css = $tmpl->build('register.css');
		//$js = $tmpl->build('register.js');
	}
	else
	{
		if($_POST['fname']!=''&&$_POST['lname']!=''&&$_POST['email']!=''&&$_POST['email_confirm']!=''&&$_POST['pass']!=''&&$_POST['pass_confirm']!='')
		{
			$fname = $_POST['fname'];
			$lname = $_POST['lname'];
			$email = $_POST['email']==$_POST['email_confirm'] ? $_POST['email'] : -1; 
			$password = $_POST['pass']==$_POST['pass_confirm'] ? $_POST['pass'] : -1; 
			
			$role = isset($_POST['register_role']) ? $_POST['register_role'] : 3;
			
			if($email==-1||$password==-1)
			{
				echo "incorrect";
			}
			else
			{
				$salt = substr(hash('whirlpool',rand(100000000000, 999999999999)), 0, 64);
				$real_pass = hash('whirlpool', $salt.$password);
				
				//insert into users
				$sql = "INSERT INTO users (fname, lname) VALUES (?,?)";
				$values = array($fname, $lname);
				$db->qwv($sql, $values);
				if( $db->stat() )
				{
					$userid = $db->last();
				}
				
				//insert into auth
				$sql = "INSERT INTO authentication (identity, salt, password, userid, roleid) VALUES (?,?,?,?,?)";
				$values = array($email, $salt, $real_pass, $userid, $role);
				$db->qwv($sql, $values);
			}
			
		}
		else
		{
			echo "not all vals entered";
		}
	}
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	$css,
						'js' => $js
						);

	print $page->build($appContent);
	
?>