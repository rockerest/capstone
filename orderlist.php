<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	require_once('Page.php');
	require_once('Template.php');
	require_once('Order.php');
	require_once('Order_Item.php');
	require_once('Item.php');
	
	$page = new Page(0, "OrderUp - Orders");
	$tmpl = new Template();
	
	$userid = $_SESSION['userid'];
	$active_order_objects = array();
	$inactive_order_objects = array();
	$active_order_items = array();
	$active_items = array();
	
	//get all active orders
	if(Order::getAllActive())
	{
			//get all active orders
			$active_order_objects = Order::getAllActive();
			
			//multiple orders
			if(is_array($active_order_objects))
			{
				foreach($active_order_objects as $active_order_object)
				{
					$active_order_items = Order_Item::getByOrder($active_order_object->orderid);
				
					//multiple items on order
					if(is_array($active_order_items))
					{
						foreach($active_order_items as $active_order_item)
						{
							array_push($active_items, Item::getByID($active_order_item->itemid));
						}
					}
					
					//one item on order
					else
					{
					
					}
				}

			}
			//one order in system
			else
			{
				$active_order_items = Order_Item::getByOrder($active_order_objects->orderid);
				
				//multiple items on order
				if(is_array($active_order_items))
				{
					foreach($active_order_items as $active_order_item)
					{
						$thisitem = array(	'orderid' => $active_order_item->orderid,
											'name' => Item::getByID($active_order_item->itemid)->name,
											'specialComment' => Order::getByID($active_order_item->orderid)->specialComment,
											'tablenumber' => Order::getByID($active_order_item->orderid)->tableid,
											'time' => date("g:i (A) m/d/y", Order::getByID($active_order_item->orderid)->time),
											'status' => Order::getByID($active_order_item->orderid)->statusid,
											'user' => Order::getByID($active_order_item->orderid)->userid,
											'itemid' => $active_order_item->itemid
						);
						array_push($active_items, $thisitem);
					}
				}
				
				//one item on order
				else
				{
				
				}
				
			}
	}
	
	//get all inactive orders
	if(Order::getAllInactive())
	{
			$inactive_order_objects = Order::getAllInactive();
	}
	
	$tmpl->active_order_objects = $active_order_objects;
	$tmpl->inactive_order_objects = $inactive_order_objects;
	$tmpl->active_order_items = $active_order_items;
	$tmpl->active_items = $active_items;
	$page->run();
	
	$html = $tmpl->build('orderlist.html');
	$css = $tmpl->build('orderlist.css');
	$js = $tmpl->build('orderlist.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'orderlist'
											),
						'js' => $js
						);

	print $page->build($appContent);
?>