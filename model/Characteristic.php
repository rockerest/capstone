<?php
	require_once('connect.php');
	
	class Characteristic
	{
		public static function getByID($id)
		{
			global $db;
			$characteristicsSQL = "SELECT * FROM characteristics WHERE characteristicid=?";
			$values = array($id);
			$char = $db->qwv($characteristicsSQL, $values);
			
			return Characteristic::wrap($char);
		}
		
		public static function getByItem($id)
		{
			global $db;
			//get characteristics linked to item
			$characteristicsSQL = "SELECT * FROM items_have_characteristics WHERE itemid=?";
			$values = array($id);
			$charList = $db->qwv($characteristicsSQL, $values);
			
			//get characteristic info for each characteristic linked to the item
			$characteristicSQL = "SELECT * FROM characteristics WHERE characteristicid=?";
			$characteristics = array();
			$db->prep($characteristicSQL);
			foreach($charList as $charID)
			{
				$values = array($charID['characteristicid']);
				$char = $db->qwv(null, $values);
				array_push($characteristics, $char[0]);
			}
			
			return Characteristic::wrap($characteristics);
		}
		
		public static function wrap($chars)
		{
			$charObs = array();			
			foreach($chars as $char)
			{
				$tmp = new Characteristic($char);
				array_push($charObs, $tmp);
			}
			
			return $charObs;
		}

		private $characteristicid;
		private $characteristic;
		
		public function __construct($char)
		{
			$this->characteristicid = $char['characteristicid'];
			$this->characteristic = $char['characteristic'];
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
