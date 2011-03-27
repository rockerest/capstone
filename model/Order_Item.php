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
				array_push($oiObs, new Order_Item($oi['order_itemid'], $oi['orderid'], $oi['itemid'], $oi['specialComment'], $oi['isCustomized']));
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
		private $specialComment;
		
		private $itemid;
		
		public function __construct($order_itemid, $orderid, $itemid, $specialComment, $isCustomized)
		{
			$this->order_itemid = $order_itemid;
			$this->orderid = $orderid;
			$this->specialComment = $specialComment;
			$this->isCustomized = $isCustomized;
			
			$this->itemid = $itemid;
		}
		
		public function __get($var)
		{
			if( $var == 'order' )
			{
				return Order::getByID($this->orderid);
			}
			elseif( $var == 'item' )
			{
				return Item::getByID($this->itemid);
			}
			elseif( $var == 'customizations' )
			{
				return Customization::getByOrderItem($this->order_itemis);
			}
			else
			{
				return $this->$var;
			}
		}
	}
?>