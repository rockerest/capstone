<?php
	require_once('connect.php');
	require_once('Ingredient.php');
	require_once('Characteristic.php');
	require_once('Rating.php');
	require_once('View.php');
	require_once('Category.php');
	require_once('Search.php');
	
	class Item
	{
		public static function getByID($id)
		{
			global $db;
			//get raw item stuff (items table only)
			$itemSQL = "SELECT * FROM items WHERE itemid=?";
			$values = array($id);
			$item = $db->qwv($itemSQL, $values);
			
			$items = Item::wrap($item);
			
			if( count($items) > 1 )
			{
				return $items;
			}
			elseif( count($items) == 1 )
			{
				return $items[0];
			}
			else
			{
				return false;
			}
		}
		
		public static function getByName($name)
		{
			global $db;
			$sql = "SELECT FROM items WHERE LOWER(name)=?";
			$values = array(strtolower($name));
			$items = $db->qwv($sql, $values);
			
			if( count($items) > 0 )
			{
				return $items[0];
			}
			else
			{
				return false;
			}
		}
		
		public static function add($name, $description, $image, $price, $preptime, $cooklvl, $chars, $ings, $recs)
		{
			$item = Item::getByName($name);
			if( !$item )
			{
				$item['name'] = $name;
				$item['description'] = $description;
				$item['image'] = $image;
				$item['price'] = $price;
				$item['prepTime'] = $preptime;
				$item['hasCookLevels'] = $cooklvl;
				
				foreach( $chars as $char )
				{
					array_push($charList, Characteristic::add($char));
				}
				
				foreach( $ings as $ing )
				{
					array_push($ingList, Ingredient::add($ing['name'], $ing['isVegetarian'], $ing['isAllergenic'], $ing['canBeSide']));
				}
				
				foreach( $recs as $rec )
				{
					array_push($recList, Ingredient::add($ing['name'], $ing['isVegetarian'], $ing['isAllergenic'], $ing['canBeSide']));
				}
				
				$item = new Item($item, $ingList, $recList, $charList);
				return $item->save();
			}
			else
			{
				return $item;
			}
		}
		
		public static function wrap($items)
		{
			$itemList = array();
			foreach( $items as $item )
			{
				$ingredients = Ingredient::getByItem($item['itemid']);
				$recommendations = Ingredient::getRecommendedByItem($item['itemid']);
				$characteristics = Characteristic::getByItem($item['itemid']);
				array_push($itemList, new Item($item, $ingredients, $recommendations, $characteristics));
			}
			return $itemList;
		}

		private $ingredients;
		private $recommendations;
		private $characteristics;
		
		private $itemid;
		private $name;
		private $description;
		private $image;
		private $price;
		private $prepTime;
		private $hasCookLevels;
		
		public function __construct($item, $ing, $rec, $char)
		{
			$this->ingredients = $ing;
			$this->recommendations = $rec;
			$this->characteristics = $char;
			
			$this->itemid = isset($item['itemid']) ? $item['itemid'] : null;
			$this->name = $item['name'];
			$this->description = $item['description'];
			$this->image = $item['image'];
			$this->price = $item['price'];
			$this->prepTime = $item['prepTime'];
			$this->hasCookLevels = $item['hasCookLevels'];
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
		
		public function save()
		{
			global $db;
			if( $this->itemid == null )
			{
				//if the item object is new and needs to be inserted
				$sql = "INSERT INTO items (name, description, image, price, prepTime, hasCookLevels) VALUES (?, ?, ?, ?, ?, ?)";
				$values = array( $this->name, $this->description, $this->image, $this->price, $this->prepTime, $this->hasCookLevels );
				$db->qwv($sql, $values);
				
				if( $db->stat() )
				{
					//set the itemid
					$this->itemid = $db->last();
					
					//link the item to ingredients, characteristics
					$sql = "INSERT INTO items_have_ingredients (ingredientid, itemid) VALUES (?, ?)";
					$db->prep($sql);
					foreach( $this->ingredients as $ing )
					{
						$db->qwv(null, array($ing['ingredientid'], $this->itemid));
					}
					
					$sql = "INSERT INTO items_have_recommended_ingredients (ingredientid, itemid) VALUES (?, ?)";
					$db->prep($sql);
					foreach( $this->recommendations as $rec )
					{
						$db->qwv(null, array($rec['ingredientid'], $this->itemid));
					}
					
					$sql = "INSERT INTO items_have_characteristics (characteristicid, itemid) VALUES (?, ?)";
					$db->prep($sql);
					foreach( $this->characteristics as $char )
					{
						$db->qwv(null, array($char['characteristicid'], $this->itemid));
					}
					
					//This function should do a check to make sure everything inserted properly.  Return a warning to check/modify the item if something failed?
					
					return $this;
				}
				else
				{
					return false;
				}
			}
			else
			{
				//if the object exists and is updated
			}
		}
	}
?>
