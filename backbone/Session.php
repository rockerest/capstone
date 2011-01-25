<?php
	if( !isset( $_COOKIE['identifier'] ) )
	{
		$myTempVar = uniqid(TRUE);
		$whirl = substr( hash('whirlpool', $myTempVar), 0, 68 );
	
		setcookie('identifier', $whirl);
		session_name($whirl);
		session_start();
	}
	else
	{
		session_name($_COOKIE['identifier']);
		session_start();
	}
?>
