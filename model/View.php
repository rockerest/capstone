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
				array_push($viewList, new View($vw['viewsid'], $vw['userid'], $vw['itemid'], $vw['time']));
			}
			
			if( count( $viewList ) > 1 )
			{
				return $viewList;
			}
			elseif( count( $viewList ) == 1 )
			{
				return $viewList[0];
			}
			else
			{
				return false;
			}
		}
		
		private $viewsid;
		private $userid;
		private $itemid;
		private $time;
		
		public function __construct($viewsid, $userid, $itemid, $time)
		{
			$this->viewsid = $viewsid;
			$this->userid = $userid;
			$this->itemid = $itemid;
			$this->time = $time;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
