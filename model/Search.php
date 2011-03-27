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
				array_push($srchList, new Search($srch['searchid'], $srch['input'], $srch['searched'], $srch['closestMatch']));
			}
			
			if( count( $srchList ) > 1 )
			{
				return $srchList;
			}
			elseif( count( $srchList ) == 1 )
			{
				return $srchList[0];
			}
			else
			{
				return false;
			}
		}
		
		private $searchid;
		private $input;
		private $searched;
		private $closestMatch;
		
		public function __construct($searchid, $input, $searched, $closestMatch)
		{
			$this->searchid = $searchid;
			$this->input = $input;
			$this->searched = $searched;
			$this->closestMatch = $closestMatch;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
