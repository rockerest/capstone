<?php
	require_once('connect.php');
	
	class Ingredient
	{
		public static function getByID($id)
		{
			global $db;
			$ingredientSQL = "SELECT * FROM ingredients WHERE ingredientid=?";
			$values = array($id);
			$ing = $db->qwv($ingredientsSQL, $values);
			
			return Ingredient::wrap($ing);
		}
	
		public static function getByItem($id)
		{
			global $db;
			//get all ingredients associated with the item
			$ingredientsSQL = "SELECT * FROM items_have_ingredients WHERE itemid=?";
			$values = array($id);
			$ingList = $db->qwv($ingredientsSQL, $values);
			
			//get info for each ingredient
			$ingredientSQL = "SELECT * FROM ingredients WHERE ingredientid=?";
			$ingredients = array();
			$db->prep($ingredientSQL);
			foreach($ingList as $ingID)
			{
				$values = array($ingID['ingredientid']);
				$ing = $db->qwv(null, $values);
				array_push($ingredients, $ing[0]);
			}
			
			return Ingredient::wrap($ingredients);
		}
		
		public static function getRecommendedByItem($id)
		{
			global $db;
			//get all ingredients recommended for the item
			$recommendedSQL = "SELECT * FROM items_have_recommended_ingredients WHERE itemid=?";
			$values = array($id);
			$recList = $db->qwv($recommendedSQL, $values);
			
			//get info for each ingredient
			$recommendedSQL = "SELECT * FROM ingredients WHERE ingredientid=?";
			$recommends = array();
			$db->prep($recommendedSQL);
			foreach($recList as $recID)
			{
				$values = array($recID['ingredientid']);
				$ing = $db->qwv(null, $values);
				array_push($recommends, $ing[0]);
			}
			
			return Ingredient::wrap($recommends);
		}
		
		public static function wrap($ingList)
		{
			$ingObs = array();			
			foreach($ingList as $ing)
			{
				$tmp = new Ingredient($ing);
				array_push($ingObs, $tmp);
			}
			
			return $ingObs;
		}

		private $ingredientid;
		private $name;
		private $isVegetarian;
		private $isAllergenic;
		private $canBeSide;
		
		public function __construct($ing)
		{
			$this->ingredientid = $ing['ingredientid'];
			$this->name = $ing['name'];
			$this->isVegetarian = $ing['isVegetarian'];
			$this->isAllergenic = $ing['isAllergenic'];
			$this->canBeSide = $ing['canBeSide'];
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
