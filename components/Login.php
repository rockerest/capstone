<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('RedirectBrowserException.php');
	require_once('Authentication.php');
	require_once('User.php');
	require_once('Session.php');
	setSession(0, '/');
	
	$action = isset($_GET['action']) ? $_GET['action'] : null;
	$fwd = isset($_GET['fwd']) ? $_GET['fwd'] : null;
	
	$password = isset($_POST['pass']) ? $_POST['pass'] : null;
	$identity = isset($_POST['email']) ? $_POST['email'] : null;
	
	$fname = isset($_POST['fname']) ? $_POST['fname'] : null;
	$lname = isset($_POST['lname']) ? $_POST['lname'] : null;
	$identity = isset($_POST['email']) ? $_POST['email'] : null;
	$ident_conf = isset($_POST['email_confirm']) ? $_POST['email_confirm'] : null;
	$password = isset($_POST['pass']) ? $_POST['pass'] : null;
	$pass_conf = isset($_POST['pass_confirm']) ? $_POST['pass_confirm'] : null;
	$role = isset($_POST['register_role']) ? $_POST['register_role'] : 3;
	
	if( $action == 'login' )
	{
		if( $password != null && $identity != null )
		{
			$tmp = Authentication::validate($identity, $password);
			
			if( $tmp )
			{
				setSessionVar('active', true);
				setSessionVar('fname', $tmp->fname);
				setSessionVar('lname', $tmp->lname);
				setSessionVar('roleid', $tmp->authentication->role->roleid);
				setSessionVar('userid', $tmp->userid);
				
				kick(2, null, 0 );
			}
			else
			{
				kick(0, array('identity' => $identity), 1);
			}
		}
		else
		{
			kick(0, array('identity' => $identity), 2);
		}
	}
	elseif( $action == 'register' )
	{
		$vals = array(	'identity' => $identity,
						'fname' => $fname,
						'lname' => $lname
						);
		if( $fname == null || $fname == "" )
		{
			kick(1, $vals, 3);
		}
		
		if( $lname == null || $lname == "" )
		{
			kick(1, $vals, 4);
		}
		
		if( $identity == null || $identity == "" )
		{
			kick(1, $vals, 5);
		}
		
		if( $identity !== $ident_conf )
		{
			kick(1, $vals, 7);
		}
		
		if( $password == null || $password == "" )
		{
			kick(1, $vals, 6);
		}

		if( $password !== $pass_conf )
		{
			kick(1, $vals, 8);
		}
		
		$stat = User::add($fname, $lname, $identity, $password, $role);
		
		if( $stat )
		{
			kick(2, null, 1);
		}
		else
		{
			kick(1, $vals, 9);
		}
	}
	else
	{
		kick(0, array('identity' => $identity), 3);
	}
	
	function kick($page, $input, $code)
	{
		if( $fwd != null )
		{
			throw new RedirectBrowserException($fwd);
		}
		else
		{
			if( $page == 0 )
			{
				throw new RedirectBrowserException("/login.php?code=" . $code . "&identity=" . $input['identity']);
			}
			elseif( $page == 1 )
			{
				throw new RedirectBrowserException("/login.php?action=register&code=" . $code . "&identity=" . $input['identity'] . "&fname=" . $input['fname'] . "&lname=" . $input['lname']);
			}
			elseif( $page == 2 )
			{
				throw new RedirectBrowserException("/index.php?code=" . $code);
			}
		}
	}
?>