<?php
	require_once('connect.php');
	
	class Search
	{
		public static function getByID($id)
		{
			global $db;
			$searchSQL = "SELECT * FROM searches WHERE searchid=?";
			$values = array($id);
			$srch = $db->qwv($searchSQL, $values);
			
			return Search::wrap($srch);
		}
		
		public static function getByItem($id)
		{
			global $db;
			$srchSQL = "SELECT * FROM searches WHERE closestMatch=?";
			$values = array($id);
			$srch = $db->qwv($srchSQL, $values);
			
			return Search::wrap($srch);
		}
		
		public static function wrap($search)
		{
			$srchList = array();
			foreach( $search as $srch )
			{
				array_push($srchList, new Search($srch));
			}
			
			return $srchList;
		}
		
		private $searchid;
		private $string;
		private $searched;
		private $closestMatch;
		
		public function __construct($srch)
		{
			$this->searchid = $srch['searchid'];
			$this->string = $srch['string'];
			$this->searched = $srch['searched'];
			$this->closestMatch = $srch['closestMatch'];
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
