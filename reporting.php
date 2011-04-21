<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	require_once('Page.php');
	require_once('Template.php');
	require_once('Order.php');
	require_once('Order_Item.php');
	require_once('Item.php');
	require_once('Breadcrumb.php');
	
	$page = new Page(0, "OrderUp - Reporting");
	$tmpl = new Template();
	
	$userid = $_SESSION['userid'];

	//set breadcrumb
	$report_type = isset($_GET['report']) ? $_GET['report'] : null;
	$bc = new Breadcrumb('report', $report_type);
	$tmpl->breadcrumb = $bc->path;
	$tmpl->report_type = $report_type;
	$page->run();
	

	//get all items
	$items = array();
	$items = Item::getAll();
	$tmpl->items = $items;
	
	$item_counts = array();
	
	//get all item counts
	foreach($items as $item)
	{
		$item_counts[$item->itemid] = Order_Item::getOrderCount($item->itemid);
	}
	
	$tmpl->item_counts = $item_counts;

	$order_times = array();
	
	//push active orders
	$active_orders = Order::getAllActive();
	if(is_array($active_orders))
	{
		foreach($active_orders as $active_order)
		{
			$order_times[date("G", $active_order->time)]++;
		}
	}
	else
	{	
		$order_times[date("G", $active_orders->time)]++;
	}
	
	//push inactive orders
	$inactive_orders = Order::getAllInactive();
	if(is_array($inactive_orders))
	{
		foreach($inactive_orders as $inactive_order)
		{	
			$order_times[date("G", $inactive_order->time)]++;
		}
	}
	else
	{
		$order_times[date("G", $inactive_orders->time)]++;
	}

	$tmpl->order_times = $order_times;

	$html = $tmpl->build('reporting.html');
	$css = $tmpl->build('reporting.css');
	$js = $tmpl->build('reporting.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'reporting'
											),
						'js' => $js
						);

	print $page->build($appContent);
	
	
?>