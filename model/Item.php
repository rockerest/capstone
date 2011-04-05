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
			
			return Item::wrap($item);
		}
		
		public static function getByName($name)
		{
			global $db;
			$sql = "SELECT FROM items WHERE LOWER(name)=?";
			$values = array(strtolower($name));
			$items = $db->qwv($sql, $values);
			return Item::wrap($items);
		}
		
		public static function getByCategory($category)
		{
			global $db;
			$sql = "SELECT * FROM items WHERE categoryid=?";
			$values = array($category);
			$items = $db->qwv($sql, $values);
			return Item::wrap($items);
		}
		
		public static function add($name, $categoryid, $description, $image, $price, $preptime, $cooklvl)
		{
			$item = Item::getByName($name);
			if( !$item )
			{
				$item = new Item(null, $name, $categoryid, $description, $image, $price, $preptime, $cooklvl);
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
				array_push($itemList, new Item($item['itemid'], $item['name'], $item['categoryid'], $item['description'], $item['image'], $item['price'], $item['prepTime'], $item['hasCookLevels']));
			}
			
			if( count( $itemList ) > 1 )
			{
				return $itemList;
			}
			elseif( count( $itemList ) == 1 )
			{
				return $itemList[0];
			}
			else
			{
				return false;
			}
		}
		
		private $itemid;
		private $name;
		private $categoryid;
		private $description;
		private $image;
		private $price;
		private $prepTime;
		private $hasCookLevels;
		
		public function __construct($itemid, $name, $catgeoryid, $description, $image, $price, $prepTime, $hasCookLevels)
		{
			$this->itemid = $itemid;
			$this->name = $name;
			$this->categoryid = $categoryid;
			$this->description = $description;
			$this->image = $image;
			$this->price = $price;
			$this->prepTime = $prepTime;
			$this->hasCookLevels = $hasCookLevels;
		}
		
		public function __get($var)
		{
			if( $var == 'ingredients' )
			{
				return Ingredient::getByItem($this->itemid);
			}
			elseif( $var == 'recommendations' )
			{
				return Ingredient::getRecommendedByItem($this->itemid);
			}
			elseif( $var == 'characteristics' )
			{
				return Characteristic::getByItem($this->itemid);
			}
			elseif( $var == 'category' )
			{
				return Category::getByID($this->categoryid);
			}
			else
			{
				return $this->$var;
			}
		}
		
		public function __set($name, $value)
		{
			if( $name != 'itemid' )
			{
				$this->$name = $value;
				return $this->save();
			}
		}
		
		public function attach($type, $id = null)
		{
			if( $id == null )
			{
				if( $type instanceof Characteristic )
				{
					return addChar($type->characteristicid);
				}
				elseif( $type instanceof Ingredient )
				{
					return addIng($type->ingredientid);
				}
				else
				{
					return false;
				}
			}
			elseif( strtolower($type) == 'characteristic' )
			{
				if( $id instanceof Characteristic )
				{
					return addChar( $id->characteristicid );
				}
				elseif( is_integer($id) )
				{
					return addChar( $id );
				}
				else
				{
					return false;
				}
			}
			elseif( strtolower($type) == 'ingredient' )
			{
				if( $id instanceof Ingredient )
				{
					return addIng( $id->ingredientid );
				}
				elseif( is_integer($id) )
				{
					return addIng( $id );
				}
				else
				{
					return false;
				}
			}
			elseif( strtolower($type) == 'recommendation' )
			{
				if( $id instanceof Ingredient )
				{
					return addRec( $id->ingredientid );
				}
				elseif( is_integer($id) )
				{
					return addRec( $id );
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
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
				$sql = "UPDATE items SET name=?, description=?, image=?, price=?, prepTime=?, hasCookLevels=? WHERE itemid=?";
				$values = array( $this->name, $this->description, $this->image, $this->price, $this->prepTime, $this->hasCookLevels, $this->itemid);
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
		
		private function addChar($id)
		{
			$sql = "INSERT INTO items_have_characteristics (itemid, characteristicid) VALUES (?,?)";
			$values = array($this->itemid, $id);
			$db->qwv($sql, $values);
			
			return $db->stat();
		}
		
		private function addIng($id)
		{
			$sql = "INSERT INTO items_have_ingredients (itemid, ingredientid) VALUES (?,?)";
			$values = array($this->itemid, $id);
			$db->qwv($sql, $values);
			
			return $db->stat();
		}
		
		private function addRec($id)
		{
			$sql = "INSERT INTO items_have_recommendations (itemid, recommendationid) VALUES (?,?)";
			$values = array($this->itemid, $id);
			$db->qwv($sql, $values);
			
			return $db->stat();
		}
	}
?>
