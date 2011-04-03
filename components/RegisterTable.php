<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	
	require_once('RedirectBrowserException.php');
	require_once('Table.php');
	require_once('Session.php');
	setSession(0, '/');
	
	$id = isset( $_GET['id'] ) ? $_GET['id'] : null;
	
	if( $id == null )
	{
		throw new RedirectBrowserException("table.php?code=1");
	}
	elseif( $id == -1 )
	{
		session_destroy();
		throw new RedirectBrowserException("../login.php?action=logout");
	}
	
	$table = Table::getByID($id);
	if( $table instanceof Table )
	{
		$_SESSION['umbrella']['tableid'] = $table->tableid;
		throw new RedirectBrowserException("../login.php?action=logout");
	}
	else
	{
		throw new RedirectBrowserException("../table.php?code=1");
	}
?>