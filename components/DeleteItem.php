<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('RedirectBrowserException.php');
	require_once('Item.php');

	require_once('Session.php');
	setSession(0, '/');
	
	$id = isset($_GET['id']) ? $_GET['id'] : -1;

	if( !isset($_SESSION['active']) || !$_SESSION['active'] || $_SESSION['roleid'] > 2 )
	{
		$urlenc = urlencode("components/DeleteItem.php?id=$id");
		throw new RedirectBrowserException("../login.php?code=10&fwd=$urlenc");
	}
	else
	{
		$item = Item::getByID($id);
		if( $item instanceof Item )
		{
			$res = $item->delete();
			if( $res )
			{
				throw new RedirectBrowserException("../item.php?code=10");
			}
			else
			{
				throw new RedirectBrowserException("../item.php?code=11&id=$id");
			}
		}
	}
?>