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
		
		public static function wrap($items)
		{
			$itemList = array();
			foreach( $items as $item )
			{
				$ingredients = Ingredient::getByItem($item['id']);
				$recommendations = Ingredient::getRecommendedByItem($item['id']);
				$characteristics = Characteristic::getByItem($item['id']);
				array_push($itemList, new Item($item, $ingredients, $recommendations, $characteristics);
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
			
			$this->itemid = $item['itemid'];
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
	}
?>
