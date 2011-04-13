<?php
	require_once('connect.php');
	
	class Ingredient
	{
		public static function getByID($id)
		{
			global $db;
			$sql = "SELECT * FROM ingredients WHERE ingredientid=?";
			$values = array($id);
			$ing = $db->qwv($sql, $values);
			
			return Ingredient::wrap($ing);
		}
	
		public static function getByItem($id)
		{
			global $db;
			//get all ingredients associated with the item
			$sql = "SELECT * FROM items_have_ingredients WHERE itemid=?";
			$values = array($id);
			$ingList = $db->qwv($sql, $values);
			
			//get info for each ingredient
			$sql = "SELECT * FROM ingredients WHERE ingredientid=?";
			$ingredients = array();
			$db->prep($sql);
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
			$sql = "SELECT * FROM items_have_recommended_ingredients WHERE itemid=?";
			$values = array($id);
			$recList = $db->qwv($sql, $values);
			
			//get info for each ingredient
			$sql = "SELECT * FROM ingredients WHERE ingredientid=?";
			$recommends = array();
			$db->prep($sql);
			foreach($recList as $recID)
			{
				$values = array($recID['ingredientid']);
				$ing = $db->qwv(null, $values);
				array_push($recommends, $ing[0]);
			}
			
			return Ingredient::wrap($recommends);
		}
		
		public static function getByName($ingredient)
		{
			global $db;
			$sql = "SELECT * FROM ingredients WHERE LOWER(name)=?";
			$values = array(strtolower($ingredient));
			$ings = $db->qwv($sql, $values);
			return Ingredient::wrap($ings);
		}
		
		public static function getBySearch($str)
		{
			global $db;
			$sql = "SELECT * FROM ingredients WHERE name LIKE ?";
			$values = array("%" . $str . "%");
			$res = $db->qwv($sql, $values);
			
			return Ingredient::wrap($res);
		}
		
		public static function add($name, $isVeg, $isAll, $side)
		{
			global $db;
			$ing = Ingredient::getByName($name);
			if( $ing )
			{
				return $ing;
			}
			else
			{
				$ing = new Ingredient(null, $name, $isVeg, $isAll, $side);
				return $ing->save();
			}
		}
		
		public static function wrap($ingList)
		{
			$ingObs = array();			
			foreach($ingList as $ing)
			{
				array_push($ingObs, new Ingredient($ing['ingredientid'], $ing['name'], $ing['isVegetarian'], $ing['isAllergenic'], $ing['canBeSide']));
			}
			
			if( count( $ingObs ) > 1 )
			{
				return $ingObs;
			}
			elseif( count( $ingObs ) == 1 )
			{
				return $ingObs[0];
			}
			else
			{
				return false;
			}
		}

		private $ingredientid;
		private $name;
		private $isVegetarian;
		private $isAllergenic;
		private $canBeSide;
		
		public function __construct($ingredientid, $name, $isVeg, $isAll, $side)
		{
			$this->ingredientid = $ingredientid;
			$this->name = $name;
			$this->isVegetarian = $isVeg;
			$this->isAllergenic = $isAll;
			$this->canBeSide = $side;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
		
		public function __set($name, $value)
		{
			if( $name != 'ingredientid' )
			{
				$this->$name = $value;
				return $this->save();
			}
		}
		
		public function save()
		{
			global $db;
			if( $this->ingredientid == null )
			{
				//if ingredient object is new and not in database
				$sql = "INSERT INTO ingredients (name, isVegetarian, isAllergenic, canBeSide) VALUES (?, ?, ?, ?)";
				$values = array($this->name, $this->isVegetarian, $this->isAllergenic, $this->canBeSide);
				$db->qwv($sql, $values);
				
				if( $db->stat() )
				{
					$this->ingredientid = $db->last();
					return $this;
				}
				else
				{
					return false;
				}
			}
			else
			{
				//if ingredient exists and needs to be updated
				$sql = "UPDATE ingredients SET name=?, isVegetarian=?, isAllergenic=?, canBeSide=? WHERE ingredientid=?";
				$values = array($this->name, $this->isVegetarian, $this->isAllergenic, $this->canBeSide, $this->ingredientid);
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
		
		public function delete()
		{
			global $db;
			$sql = "DELETE FROM ingredients WHERE ingredientid=?";
			$values = array($this->ingredientid);
			$db->qwv($sql, $values);
			
			return $db->stat();
		}
		
		public function deleteLink($itemid)
		{
			global $db;
			$sql = "DELETE FROM items_have_ingredients WHERE ingredientid=? AND itemid=?";
			$values = array($this->ingredientid, $itemid);
			$db->qwv($sql, $values);
			
			return $db->stat();
		}
		
		public function deleteRec($itemid)
		{
			global $db;
			$sql = "DELETE FROM items_have_recommended_ingredients WHERE ingredientid=? AND itemid=?";
			$values = array($this->ingredientid, $itemid);
			$db->qwv($sql, $values);
			
			return $db->stat();
		}
	}
?>
