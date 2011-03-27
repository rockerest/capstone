<?php
	require_once('connect.php');
	
	class Characteristic
	{
		public static function getByID($id)
		{
			global $db;
			$sql = "SELECT * FROM characteristics WHERE characteristicid=?";
			$values = array($id);
			$char = $db->qwv($sql, $values);
			
			return Characteristic::wrap($char);
		}
		
		public static function getByItem($id)
		{
			global $db;
			//get characteristics linked to item
			$sql = "SELECT * FROM items_have_characteristics WHERE itemid=?";
			$values = array($id);
			$charList = $db->qwv($sql, $values);
			
			//get characteristic info for each characteristic linked to the item
			$sql = "SELECT * FROM characteristics WHERE characteristicid=?";
			$characteristics = array();
			$db->prep($sql);
			foreach($charList as $charID)
			{
				$values = array($charID['characteristicid']);
				$char = $db->qwv(null, $values);
				array_push($characteristics, $char[0]);
			}
			
			return Characteristic::wrap($characteristics);
		}
		
		public static function getByCharacteristic($string)
		{
			global $db;
			$sql = "SELECT * FROM characteristics WHERE LOWER(characteristic)=?";
			$values = array(strtolower($string));
			$chars = $db->qwv($sql, $values);
			
			return Characteristic::wrap($chars);
		}
		
		public static function add($characteristic)
		{
			$char = Characteristic::getByCharacteristic($characteristic);
			if( !char )
			{
				$char = new Characteristic(null, $characteristic));
				return $char->save();
			}
			else
			{
				return $char;
			}
		}
		
		public static function wrap($chars)
		{
			$charObs = array();			
			foreach($chars as $char)
			{
				array_push($charObs, new Characteristic($char['characteristicid'], $char['characteristic']));
			}
			
			if( count( $charObs ) > 1 )
			{
				return $charObs;
			}
			elseif( count( $charObs ) == 1 )
			{
				return $charObs[0];
			}
			else
			{
				return false;
			}
		}

		private $characteristicid;
		private $characteristic;
		
		public function __construct($id, $char)
		{
			$this->characteristicid = $id;
			$this->characteristic = $char;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
		
		public function save()
		{
			global $db;
			
			//if char is new object
			if( $this->characteristicid == null )
			{
				$sql = "INSERT INTO characteristics (characteristic) VALUES (?)";
				$value = array($this->characteristic);
				$db->qwv($sql, $values);
				
				if( $db->stat() )
				{
					$this->characteristicid = $db->last();
					return $this;
				}
				else
				{
					return false;
				}
			}
			else
			{
				$sql = "UPDATE characteristics SET characteristic=? WHERE characteristicid=?";
				$value = array($this->characteristic, $this->characteristicid);
				$db->qwv($sql, $values);
				
				if( $db->stat() )
				{
					return $this;
				}
				else
				{
					return false;
				}
			}
		}
	}
?>
