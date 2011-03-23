<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	require_once('Page.php');
	require_once('Template.php');
	require_once('Order.php');
	require_once('Order_Item.php');
	
	$page = new Page(0, "OrderUp - Orders");
	$tmpl = new Template();
	
	$userid = $_SESSION['userid'];
	
	if(Order::getActiveByUser($userid))
	{
		$active_order_ids = Order::getActiveByUser($userid);
		$tmpl->active_order = Order::getById($active_order_ids['orderid']);
		$tmpl->active_order_items = Order_Item::getByOrder($active_order_ids['orderid']);
	}
	
	if(Order::getByUser($userid))
	{
		$past_order_ids = Order::getByUser($userid);
		//var_dump($past_order_ids);
		$past_order_objs = array();
		foreach($past_order_ids as $past_order_obj)
		{
			array_push($past_order_objs, $past_order_obj);
		}
		$tmpl->past_orders = $past_order_objs;
		//$tmpl->past_order_items = Order_Item::getByOrder($past_order_ids['orderid']);
	}
	
	$page->run();
	
	$html = $tmpl->build('orderlist.html');
	//$css = $tmpl->build('orderlist.css');
	//$js = $tmpl->build('orderlist.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => '/styles/orderlist.css'
											),
						'js' => $js
						);

	print $page->build($appContent);
?>