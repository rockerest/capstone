<?php
	require_once('connect.php');
	
	class View
	{
		public static function getByID($id)
		{
			global $db;
			$viewSQL = "SELECT * FROM views WHERE viewid=?";
			$values = array($id);
			$view = $db->qwv($viewSQL, $values);
			
			return View::wrap($view);
		}
		
		public static function getByItem($id)
		{
			global $db;
			$viewSQL = "SELECT * FROM views WHERE itemid=?";
			$values = array($id);
			$view = $db->qwv($viewSQL, $values);
			
			return View::wrap($view);
		}
		
		public static function getByUser($id)
		{
			global $db;
			$viewSQL = "SELECT * FROM views WHERE userid=?";
			$values = array($id);
			$view = $db->qwv($viewSQL, $values);
			
			return View::wrap($view);
		}
		
		public static function wrap($views)
		{
			$viewList = array();
			foreach( $views as $vw )
			{
				array_push($viewList, new View($vw));
			}
			
			return $viewList;
		}
		
		private $viewsid;
		private $userid;
		private $itemid;
		private $time;
		
		public function __construct($view)
		{
			$this->viewsid = $view['viewsid'];
			$this->userid = $view['userid'];
			$this->itemid = $view['itemid'];
			$this->time = $view['time'];
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
