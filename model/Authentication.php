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
		
		public static function addForUser($id, $ident, $pass, $roleid)
		{
			$salt = substr(hash('whirlpool',rand(100000000000, 999999999999)), 0, 64);
			$real_pass = hash('whirlpool', $salt.$pass);
			
			$object = array(	'identity'=>$ident,
								'password'=>$pass,
								'userid'=>$id,
								'roleid'=>$roleid,
								'salt'=>$salt
							);
			
			$auth = Authentication::wrap($object);
			$save = $auth[0]->save();
			
			if( $save )
			{
				return $auth[0];
			}
			else
			{
				return false;
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
		
		private $userid;
		
		private $role;
	
		public function __construct($auth, $role)
		{
			$this->role = $role;
			
			$this->authenticationid = isset($auth['authenticationid']) ? $auth['authenticationid'] : null;
			$this->userid = $auth['userid'];
			$this->identity = $auth['identity'];
			$this->salt = $auth['salt'];
			$this->password = $auth['password'];
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
		
		public function save()
		{
			global $db;
			if( isset($this->authenticationid) )
			{
				if( $this->allSet() )
				{
					$authSQL = "UPDATE authentication SET identity=?, salt=?, password=?, roleid=? WHERE authenticationid=? AND userid=?";
					$values = array($this->identity, $this->salt, $this->password, $this->role[0]->roleid, $this->authenticationid, $this->userid);
					$db-qwv($authSQL, $values);
					
					return $db->stat();
				}
				else
				{
					return false;
				}
			}
			else
			{
				if( $this->allSet() )
				{
					$authSQL = "INSERT INTO authentication (identity, salt, password, userid, roleid) VALUES (?,?,?,?,?)";
					$values = array($this->identity, $this->salt, $this->password, $this->userid, $this->role[0]->roleid);
					$db->qwv($authSQL, $values);
					
					if( $db->stat() )
					{
						$this->authenticationid = $db->last();
					}
					
					return $db->stat();
				}
				else
				{
					return false;
				}
			}
		}
		
		private function allSet()
		{
			return isset($this->identity, $this->salt, $this->password, $this->role);
		}
	}
?>
