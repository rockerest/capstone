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
				$user = User::getByID($server['userid']);
				$tables = Table::getByServer($server['serverid']);
				array_push($serverObs, new Server($server['serverid'], $server['isWorking'], $user, $tables));
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
		
		private $user;
		private $tables;

		public function __construct($serverid, $isWorking, $user, $tables)
		{
			$this->serverid = $serverid;
			$this->isWorking = $isWorking;
			
			$this->user = $user;
			$this->tables = $tables;
		}
		
		public function __get($var)
		{
			if( $var == 'fname' || $var == 'lname' )
			{
				return $this->user->$var;
			}
			return $this->$var;
		}
	}
?>