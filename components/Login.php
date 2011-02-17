<?php
	set_include_path('../model:../backbone');
	
	require_once('RedirectBrowserException.php');
	require_once('Authentication.php');
	require_once('Session.php');
	setSession(0, '/');
	
	$password = isset($_POST['pass']) ? $_POST['pass'] : null;
	$identity = isset($_POST['email']) ? $_POST['email'] : null;
	
	if( $password != null && $identity != null )
	{
		$tmp = Authentication::validate($identity, $password);
		
		if( $tmp )
		{
			$user = $tmp[0];
			setSessionVar('active', true);
			setSessionVar('fname', $user->fname);
			setSessionVar('lname', $user->lname);
			setSessionVar('roleid', $user->authentication[0]->role[0]->roleid);
			setSessionVar('userid', $user->userid);
			
			kick( null, 0 );
		}
		else
		{
			kick($identity, 1);
		}
	}
	else
	{
		kick( $identity, 2);
	}
	
	function kick($identity = null, $code)
	{
		if( $identity != null )
		{
			throw new RedirectBrowserException("/login.php?code=" . $code . "&identity=" . $identity);
		}
		else
		{
			throw new RedirectBrowserException("/index.php?code=" . $code);
		}
	}
?>