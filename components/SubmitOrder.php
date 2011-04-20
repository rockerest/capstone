<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('RedirectBrowserException.php');
	require_once('Order.php');

	require_once('Session.php');
	setSession(0, '/');
	
	$id = isset($_POST['id']) ? $_POST['id'] : null;
	
	if( !isset($_SESSION['userid']) || $_SESSION['userid'] == null || $_SESSION['userid'] < 1 )
	{
		throw new RedirectBrowserException("../login.php?code=10");
	}
	
	$order = Order::getByID($id);
	
	if( $order->userid == $_SESSION['userid'] && $order->statusid == 1)
	{
		$order->statusid = 2;
		
		print true;
	}
	else
	{
		print false;
	}
?>