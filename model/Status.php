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
			
			return Status::wrap($stat);
		}
		
		public static function getByStatus($status)
		{
			global $db;
			$sql = "SELECT * FROM statuses WHERE LOWER(status)=?";
			$values = array(strtolower($status));
			$stat = $db->qwv($sql, $values);
			
			return Status::wrap($stat);
		}
		
		public static function wrap($stats)
		{
			$statObs = array();
			foreach($stats as $stat)
			{
				array_push($statObs, new Status($stat['statusid'], $stat['status']));
			}
			
			if( count( $statObs ) > 1 )
			{
				return $statObs;
			}
			elseif( count( $statObs ) == 1 )
			{
				return $statObs[0];
			}
			else
			{
				return false;
			}
		}
		
		private $statusid;
		private $status;

		public function __construct($statusid, $status)
		{
			$this->statusid = $statusid;
			$this->status = $status;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>