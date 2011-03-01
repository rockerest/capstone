<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	
	require_once('RedirectBrowserException.php');
	require_once('Session.php');
	setSession(0, '/');
	
	//No DB Connection.  THere will be a model class for adding orders soon

	//order table vars
	$tableid = 1;
	$userid = $_SESSION['userid'];
	$statusid = 1;
	$time = time();
	$specialcomment = $_POST['message'];

	//order_items table vars
	$orderid;
	$item_id = $_POST['itemid'];
	$isCustomized = 0;

	//check to see if order exists -- implement when we have more users/ability to complete orders
	$sql = "SELECT * FROM orders WHERE userid='".$userid."' AND status<>4";

	$sql = "INSERT INTO orders (tableid, userid, statusid, time, specialComment) VALUES (?,?,?,?,?)";
	$values = array($tableid, $userid, $statusid, $time, $specialcomment);
	$db->qwv($sql, $values);
	if( $db->stat() )
	{
		$orderid = $db->last();
	}

	//insert into order items
	$sql = "INSERT INTO order_items (orderid, itemid, isCustomized) VALUES (?,?,?)";
	$values = array($orderid, $item_id, $isCustomized);
	$db->qwv($sql, $values);
	if( $db->stat() )
	{
		$order_item_id = $db->last();
	}
?>