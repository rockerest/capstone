<?php
	require_once('connect.php');
	
	class View extends Base
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
		
		public static function getByUserForItem($userid, $itemid)
		{
			global $db;
			$sql = "SELECT * FROM views WHERE userid=? AND itemid=?";
			$values = array($userid, $itemid);
			$views = $db->qwv($sql, $values);
			
			return View::wrap($view);
		}
		
		public static function add($userid, $itemid)
		{
			$vw = new View(null, $userid, $itemid, time());
			return $vw->save();
		}
		
		public static function wrap($views)
		{
			$viewList = array();
			foreach( $views as $vw )
			{
				array_push($viewList, new View($vw['viewsid'], $vw['userid'], $vw['itemid'], $vw['time']));
			}
			
			return View::sendback($viewList);
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
		
		public function save()
		{
			global $db;
			if( $this->viewsid == null )
			{
				//insert
				$sql = "INSERT INTO views (userid, itemid, time) VALUES (?, ?, ?)";
				$values = array($this->userid, $this->itemid, $this->time);
				$db->qwv($sql, $values);
				
				if( $db->stat() )
				{
					$this->viewsid = $db->last();
					return $this;
				}
				else
				{
					return false;
				}
			}
			else
			{
				//update
				$sql = "UPDATE views SET userid=?, itemid=?, time=? WHERE viewsid=?";
				$values = array($this->userid, $this->itemid, $this->time, $this->viewsid);
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
	}
?>
