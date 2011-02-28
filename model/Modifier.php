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
			
			return new Modifier($modifier[0]);
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
			
			return new Modifier($modifier[0]);
		}

		private $modifierid;
		private $text;
		private $isCookLevel;
		
		public function __construct($modifier)
		{
			$this->modifierid = $modifier['modifierid'];
			$this->text = $modifier['text'];
			$this->isCookLevel = $modifier['isCookLevel'];
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>