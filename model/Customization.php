<?php
	require_once('connect.php');
	require_once('Modifier.php');
	require_once('Ingredient.php');
	require_once('Order_Item.php');
	
	class Customization
	{
		public static function getByID($id)
		{
			global $db;
			//get customization from database
			$customizationSQL = "SELECT * FROM customizations WHERE customizationid=?";
			$values = array($id);
			$customization = $db->qwv($customizationSQL, $values);
			
			return Customization::wrap($customization);
		}
		
		public static function getByOrderItem($id)
		{
			global $db;
			//get order item from database
			$orderItemSQL = "SELECT * FROM order_items WHERE order_itemid=?";
			$values = array($id);
			$orderItem = $db->qwv($orderItemSQL, $values);
			
			if( $orderItem[0]['isCustomized'] )
			{
				//get customization from database
				$customizationSQL = "SELECT * FROM customizations WHERE order_itemid=?";
				$values = array($orderItem[0]['order_itemid']);
				$customizations = $db->qwv($customizationSQL, $values);
			}
			return Customization::wrap($customizations);
		}
		
		public static function wrap($custs)
		{
			$custObs = array();
			foreach($custs as $cust)
			{
				array_push($custObs, new Customization($cust['customizationid'], $cust['order_itemid'], $cust['modifierid'], $ingredient['ingredientid']));
			}
			
			if( count( $custObs ) > 1 )
			{
				return $custObs;
			}
			elseif( count( $custObs ) == 1 )
			{
				return $custObs[0];
			}
			else
			{
				return false;
			}
		}

		private $customizationid;
		
		private $order_itemid;
		private $modifierid;
		private $ingredientid;
		
		public function __construct($id, $oiid, $modid, $ingid)
		{
			$this->customizationid = $id;
			
			$this->order_itemid = $oiid;
			$this->modifierid = $modid;
			$this->ingredientid = $ingid;
		}
		
		public function __get($var)
		{
			if( $var == 'order_item' )
			{
				return Order_Item::getByID($this->order_itemid);
			}
			elseif( $var == 'modifier' )
			{
				return Modifier::getByID($this->modifierid);
			}
			elseif( $var == 'ingredient' )
			{
				return Ingredient::getByID($this->ingredientid);
			}
			else
			{
				return $this->$var;
			}
		}
	}
?>