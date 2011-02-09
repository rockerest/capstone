<?php
	error_reporting(E_ALL);
	set_include_path('backbone:components:content:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');
	require_once('Database.php');
	require_once('capstone.db');
	
	$page = new Page(2, "OrderUp - a new way to improve your dining experience");
	$db = new Database($user, $pass, $dbname, $host, 'mysql');
	$tmpl = new Template();
	
	$page->run();

	$itemid = isset($_GET['id']) ? $_GET['id'] : -1;
	if($itemid == -1)
	{
		//no id is set in the url, try and insert
		
		//get time submitted
		$date = new DateTime();
		
		//order table vars
		$tableid = 1;
		$userid = $_SESSION['userid'];
		$statusid = 1;
		$time = $date->getTimestamp();
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
		echo $tableid." - ".$userid." - ".$statusid." - ".$time." - ".$specialcomment." - ".$orderid." - ".$item_id." - ".$isCustomized;
		$html = $tmpl->build('additem.html?order=submitted');
		$css = $tmpl->build('additem.css');
		//$js = $tmpl->build('index.js');
	} 
	else
	{
		$html = $tmpl->build('additem.html');
		$css = $tmpl->build('additem.css');
		//$js = $tmpl->build('index.js');
	}
	
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	$css,
						'js' => $js
						);

	print $page->build($appContent);
	
?>