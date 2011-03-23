<?php
	require_once('connect.php');
	require_once('Server.php');
	
	class Table
	{
		public static function getByID($id)
		{
			global $db;
			$sql = "SELECT * FROM tables WHERE tableid=?";
			$values = array($id);
			$table = $db->qwv($sql, $values);
			
			return Table::wrap($table);
		}
		
		public static function getByServer($serverid)
		{
			global $db;
			$sql = "SELECT * FROM tables WHERE serverid=?";
			$values = array($id);
			$tables = $db->qwv($sql, $values);
			
			return Table::wrap($tables);
		}
		
		public static function wrap($tables)
		{
			$tableObs = array();
			foreach($tables as $table)
			{
				$server = Server::getByID($server['userid']);
				array_push($tableObs, new Table($table, $server));
			}
			
			if( count( $tableObs ) > 1 )
			{
				return $tableObs;
			}
			elseif( count( $tableObs ) == 1 )
			{
				return $tableObs[0];
			}
			else
			{
				return false;
			}
		}
		
		private $tableid;
		private $isAvailable;
		
		private $server;

		public function __construct($table, $server)
		{
			$this->tableid = $table['tableid'];
			$this->isAvailable = $table['isAvailable'];
			
			$this->server = $server;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>