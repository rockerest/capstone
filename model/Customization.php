<?php
	require_once('connect.php');
	require_once('Modifier.php');
	require_once('Ingredient.php');
	
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
				$mod = Modifier::getByID($cust['modifierid']);
				$ing = Ingredient::getByID($cust['ingredientid']);
				array_push($custObs, new Customization($cust, $mod, $ing));
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
		
		private $modifier;
		private $ingredient;
		
		public function __construct($customization, $mod, $ing)
		{
			$this->customizationid = $customization['customizationid'];
			$this->order_itemid = $customization['order_itemid'];
			
			$this->modifier = $mod;
			$this->ingredient = $ing;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>