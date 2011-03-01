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
			
			return new Customization($comment[0]);
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
				
				return wrap($customizations);
			}
			return false;
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
			
			return $custObs;
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