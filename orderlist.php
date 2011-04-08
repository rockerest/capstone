<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	require_once('Page.php');
	require_once('Template.php');
	require_once('Order.php');
	require_once('Order_Item.php');
	
	$page = new Page(0, "OrderUp - Orders");
	$tmpl = new Template();
	
	$userid = $_SESSION['userid'];
	$active_order_objects = array();
	$inactive_order_objects = array();
	
	//get all active orders
	if(Order::getAllActive())
	{
			$active_order_objects = Order::getAllActive();
	}
	
	//get all inactive orders
	if(Order::getAllInactive())
	{
			$inactive_order_objects = Order::getAllInactive();
	}
	
	$tmpl->active_order_objects = $active_order_objects;
	$tmpl->inactive_order_objects = $inactive_order_objects;
	$page->run();
	
	$html = $tmpl->build('orderlist.html');
	//$css = $tmpl->build('orderlist.css');
	//$js = $tmpl->build('orderlist.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'orderlist'
											),
						'js' => $js
						);

	print $page->build($appContent);
?>