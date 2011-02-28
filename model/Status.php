<?php
	require_once('connect.php');
	
	class Status
	{
		public static function getByID($id)
		{
			global $db;
			$sql = "SELECT * FROM statuses WHERE statusid=?";
			$values = array($id);
			$stat = $db->qwv($sql, $values);
			
			$status = Status::wrap($stat);
			if( count($status) > 0 )
			{
				return $status[0];
			}
			else
			{
				return false;
			}
		}
		
		public static function getByStatus($status)
		{
			global $db;
			$sql = "SELECT * FROM statuses WHERE LOWER(status)=?";
			$values = array(strtolower($status));
			$stat = $db->qwv($sql, $values);
			
			$status = Status::wrap($stat);
			if( count($status) > 0 )
			{
				return $status[0];
			}
			else
			{
				return false;
			}
		}
		
		public static function wrap($stats)
		{
			$statObs = array();
			foreach($stats as $stat)
			{
				array_push($statObs, new Status($stat));
			}
			return $statObs;
		}
		
		private $statusid;
		private $status;

		public function __construct($stat)
		{
			$this->statusid = $stat['statusid'];
			$this->status = $stat['status'];
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>