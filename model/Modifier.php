<?php
	require_once('connect.php');
	
	class Modifier
	{
		public static function getByID($id)
		{
			global $db;
			//get modifier from database
			$modifierSQL = "SELECT * FROM modifiers WHERE modifierid=?";
			$values = array($id);
			$modifier = $db->qwv($modifierSQL, $values);
			
			return Modifier::wrap($modifier);
		}
		
		public static function getByCustomization($id)
		{
			global $db;
			//get customization from database
			$customizationSQL = "SELECT * FROM customizations WHERE customizationid=?";
			$values = array($id);
			$customization = $db->qwv($customizationSQL, $values);
			
			//get modifier from database
			$modifierSQL = "SELECT * FROM modifiers WHERE modifierid=?";
			$values = array($customization[0]['modifierid']);
			$modifier = $db->qwv($modifierSQL, $values);
			
			return Modifier::wrap($modifier);
		}
		
		public static function wrap($mods)
		{
			$modList = array();
			foreach( $mods as $mod )
			{
				array_push($modList, new Modifier($mod['modifierid'], $mod['text'], $mod['isCookLevel']));
			}
			
			if( count( $modList ) > 1 )
			{
				return $modList;
			}
			elseif( count( $modList ) == 1 )
			{
				return $modList[0];
			}
			else
			{
				return false;
			}
		}

		private $modifierid;
		private $text;
		private $isCookLevel;
		
		public function __construct($modifierid, $text, $isCookLevel)
		{
			$this->modifierid = $modifierid;
			$this->text = $text;
			$this->isCookLevel = $isCookLevel;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>