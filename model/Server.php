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
			
			$serv = Server::wrap($server);
			if( count($serv) > 0 )
			{
				return $serv[0];
			}
			else
			{
				return false;
			}
		}
		
		public static function getWorking()
		{
			global $db;
			$sql = "SELECT * FROM servers WHERE isWorking=1";
			$server = $db->q($sql);
			
			$serv = Server::wrap($server);
			if( count($serv) > 0 )
			{
				return $serv;
			}
			else
			{
				return false;
			}
		}
		
		public static function wrap($servers)
		{
			$serverObs = array();
			foreach($servers as $server)
			{
				$user = User::getByID($server['userid']);
				$tables = Table::getByServer($server['serverid']);
				array_push($serverObs, new Server($server, $user, $tables));
			}
			return $serverObs;
		}
		
		private $serverid;
		private $isWorking;
		
		private $user;
		private $tables;

		public function __construct($server, $user, $tables)
		{
			$this->serverid = $server['serverid'];
			$this->isWorking = $server['isWorking'];
			
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