<?php
	require_once('connect.php');
	
	class Category
	{
		public static function getByID($id)
		{
			global $db;
			$categorySQL = "SELECT * FROM categories WHERE categoryid=?";
			$values = array($id);
			$cat = $db->qwv($categorySQL, $values);
			
			return Category::wrap($cat);
		}
		
		public static function getByItem($id)
		{
			global $db;
			$itemSQL = "SELECT * FROM items WHERE itemid=?";
			$values = array($id);
			$item = $db->qwv($itemSQL, $values);
			
			$catSQL = "SELECT * FROM categories WHERE categoryid=?";
			$values = array($item[0]['categoryid']);
			$cat = $db->qwv($catSQL, $values);
			
			return Category::wrap($cat);
		}
		
		public static function wrap($categories)
		{
			$catList = array();
			foreach( $categories as $cat )
			{
				array_push($catList, new Category($cat['categoryid'], $cat['name'], $cat['number']));
			}
			
			if( count( $catList ) > 1 )
			{
				return $catList;
			}
			elseif( count( $catList ) == 1 )
			{
				return $catList[0];
			}
			else
			{
				return false;
			}
		}
		
		private $categoryid;
		private $name;
		private $number;
		
		public function __construct($id, $name, $number)
		{
			$this->categoryid = $id;
			$this->name = $name;
			$this->number = $number;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
