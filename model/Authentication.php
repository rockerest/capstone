<?php
	require_once('connect.php');
	
	require_once('Role.php');
	require_once('User.php');
	
	class Authentication
	{
		public static function validate($identity, $password)
		{
			global $db;
			
			$identSQL = "SELECT salt FROM authentication WHERE identity=?";
			$values = array($identity);
			$res = $db->qwv($identSQL, $values);
			
			$saltPass = hash('whirlpool', $res[0]['salt'].$password);
			$authSQL = "SELECT * FROM authentication WHERE identity=? AND password=?";
			$values = array($identity, $saltPass);
			$res = $db->qwv($authSQL, $values);
			
			if( count($res) != 1 )
			{
				return false;
			}
			else
			{
				return User::getByID($res[0]['userid']);
			}
		}
		
		public static function getByUserID($id)
		{
			global $db;
			$authSQL = "SELECT * FROM authentication WHERE userid=?";
			$values = array($id);
			$auth = $db->qwv($authSQL, $values);
			
			return Authentication::wrap($auth);
		}
	
		public static function wrap($auths)
		{
			$authList = array();
			foreach( $auths as $auth )
			{
				$role = Role::getByID($auth['roleid']);
				array_push($authList, new Authentication($auth, $role));
			}
			
			return $authList;
		}
	
		private $authenticationid;
		private $identity;
		private $salt;
		private $password;
		
		private $role;
	
		public function __construct($auth, $role)
		{
			$this->role = $role;
			
			$this->authenticationid = $auth['authenticationid'];
			$this->identity = $auth['identity'];
			$this->salt = $auth['salt'];
			$this->password = $auth['password'];
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
