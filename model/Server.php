<?php
	require_once('connect.php');
	require_once('User.php');
	require_once('Table.php');
	
	class Server
	{
		public static function getByID($id)
		{
			global $db;
			$sql = "SELECT * FROM servers WHERE serverid=?";
			$values = array($id);
			$server = $db->qwv($sql, $values);
			
			return Server::wrap($server);
		}
		
		public static function getWorking()
		{
			global $db;
			$sql = "SELECT * FROM servers WHERE isWorking=1";
			$server = $db->q($sql);
			
			return Server::wrap($server);
		}
		
		public static function wrap($servers)
		{
			$serverObs = array();
			foreach($servers as $server)
			{
				array_push($serverObs, new Server($server['serverid'], $server['isWorking'], $server['userid']));
			}
			
			if( count( $serverObs ) > 1 )
			{
				return $serverObs;
			}
			elseif( count( $serverObs ) == 1 )
			{
				return $serverObs[0];
			}
			else
			{
				return false;
			}
		}
		
		private $serverid;
		private $isWorking;
		
		private $userid;

		public function __construct($serverid, $isWorking, $userid)
		{
			$this->serverid = $serverid;
			$this->isWorking = $isWorking;
			
			$this->userid = $userid;
		}
		
		public function __get($var)
		{
			if( $var == 'fname' || $var == 'lname' )
			{
				$usr = User::getByID($this->userid);
				return $usr->$var;
			}
			elseif( $var == 'tables' )
			{
				return Table::getByServer($this->serverid);
			}
			else
			{
				return $this->$var;
			}
		}
	}
?>