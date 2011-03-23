<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	
	require_once('RedirectBrowserException.php');
	require_once('Session.php');
	require_once('Order.php');
	setSession(0, '/');
	
	if(isset($_POST['add']))
	{
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
		$modifiers;
		
		//check to see if order is active
		if(Order::getActiveByUser($userid))
		{
			//if active, add to it 
			$active_order_ids = Order::getActiveByUser($userid);
			$active_order = Order::getById($active_order_ids['orderid']);
			$active_order->addItem($item_id, $specialcomment, $modifiers);
			throw new RedirectBrowserException("/orderlist.php");
		}
		else
		{
			//else, we must create an order and add items to it
			//Order::addItem($item_id, $specialcomment, $modifiers);
			throw new RedirectBrowserException("index.php");
		}
	}

?>