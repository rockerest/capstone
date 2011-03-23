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
			
			return Order_Item::wrap($order_item);
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
				$cust = Customizations::getByOrderItem($oi['order_itemid']);
				array_push($oiObs, new Order_Item($oi, $item, $cust));
			}
			
			if( count( $oiObs ) > 1 )
			{
				return $oiObs;
			}
			elseif( count( $oiObs ) == 1 )
			{
				return $oiObs[0];
			}
			else
			{
				return false;
			}
		}

		private $order_itemid;
		private $orderid;
		private $special_comment;
		
		private $item;
		private $customizations;
		
		public function __construct($oi, $item, $cust)
		{
			$this->order_itemid = $oi['order_itemid'];
			$this->orderid = $oi['orderid'];
			$this->special_comment = $oi['special_comment'];
			
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