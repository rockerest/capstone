<?php

	require_once('connect.php');
	
	class Role
	{
		public static function getByID($id)
		{
			global $db;
			$roleSQL = "SELECT * FROM roles WHERE roleid=?";
			$values = array($id);
			$role = $db->qwv($roleSQL, $values);
			
			return Role::wrap($role);
		}
		
		public static function getByRole($role)
		{
			global $db;
			$roleSQL = "SELECT * FROM roles WHERE role LIKE '%?%'";
			$values = array($role);
			$role = $db->qwv($roleSQL, $values);
			
			return Role::wrap($role);
		}
		
		public static function wrap($roles)
		{
			$roleList = array();
			foreach( $roles as $role )
			{
				array_push($roleList, new Role($role['roleid'], $role['role']));
			}
			
			if( count( $roleList ) > 1 )
			{
				return $roleList;
			}
			elseif( count( $roleList ) == 1 )
			{
				return $roleList[0];
			}
			else
			{
				return false;
			}
		}
		
		private $roleid;
		private $role;
		
		public function __construct($roleid, $role)
		{
			$this->roleid = $roleid;
			$this->role = $role;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
