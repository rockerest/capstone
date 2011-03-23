<?php
	require_once('connect.php');
	require_once('Customization.php');
	require_once('Item.php');
	require_once('Order.php');
	
	class Order_Item
	{
		public static function getByID($id)
		{
			global $db;
			//get order item from database
			$order_itemSQL = "SELECT * FROM order_items WHERE order_itemid=?";
			$values = array($id);
			$order_item = $db->qwv($order_itemSQL, $values);
			
			$oi = Order_Item::wrap($order_item);
			if( count($oi) > 0 )
			{
				return $oi[0];
			}
			else
			{
				return false;
			}
		}
		
		public static function getByOrder($id)
		{
			global $db;
			//get order items from database
			$order_itemSQL = "SELECT * FROM order_items WHERE orderid=?";
			$values = array($id);
			$order_items = $db->qwv($order_itemSQL, $values);
			
			return Order_Item::wrap($order_items);
		}
		
		public static function wrap($ois)
		{
			$oiObs = array();
			foreach($ois as $oi)
			{
				$item = Item::getByID($oi['itemid']);
				$cust = Customization::getByOrderItem($oi['order_itemid']);
				array_push($oiObs, new Order_Item($oi, $item, $cust));
			}
			
			return $oiObs;
		}

		private $order_itemid;
		private $orderid;
		
		private $item;
		private $customizations;
		
		public function __construct($oi, $item, $cust)
		{
			$this->order_itemid = $oi['order_itemid'];
			$this->orderid = $oi['orderid'];
			
			$this->item = $item;
			$this->customizations = $cust;
		}
		
		public function __get($var)
		{
			if( $var == 'order' )
			{
				return Order::getByID($this->orderid);
			}
			else
			{
				return $this->$var;
			}
		}
	}
?>