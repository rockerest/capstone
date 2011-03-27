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
		
		public static function checkIdentity($ident)
		{
			global $db;
			$sql = "SELECT authenticationid FROM authentication WHERE identity=?";
			$values = array($ident);
			$res = $db->qwv($sql, $values);
			
			return count($res);
		}
		
		public static function addForUser($id, $ident, $pass, $roleid)
		{
			$salt = substr(hash('whirlpool',rand(100000000000, 999999999999)), 0, 64);
			$real_pass = hash('whirlpool', $salt.$pass);
			
			$auth = Authentication::wrap(null, $ident, $salt, $real_pass, $id, $roleid);
			$save = $auth->save();
			if( $save )
			{
				return $auth;
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
		
		public static function deleteByUser($id)
		{
			global $db;
			$delSQL = "DELETE FROM authentication WHERE userid=?";
			$values = array($id);
			$del = $db->qwv($delSQL, $values);
			
			return $db->stat();
		}
	
		public static function wrap($auths)
		{
			$authList = array();
			foreach( $auths as $auth )
			{
				array_push($authList, new Authentication($auth['authenticationid'], $auth['identity'], $auth['salt'], $auth['password'], $auth['userid'], $auth['roleid']));
			}
			
			if( count( $authList ) > 1 )
			{
				return $authList;
			}
			elseif( count( $authList ) == 1 )
			{
				return $authList[0];
			}
			else
			{
				return false;
			}
		}
	
		private $authenticationid;
		private $identity;
		private $salt;
		private $password;
		
		private $userid;
		private $roleid;
	
		public function __construct($id, $ident, $salt, $pass, $userid, $roleid)
		{
			$this->roleid = $roleid;
			$this->userid = $userid;
			
			$this->authenticationid = $id;
			$this->identity = $ident;
			$this->salt = $salt;
			$this->password = $pass;
		}
		
		public function __get($var)
		{
			if( $var == 'role' )
			{
				return Role::getByID($this->roleid);
			}
			elseif( $var == 'user' )
			{
				return User::getByID($this->userid);
			}
			else
			{
				return $this->$var;
			}
		}
		
		public function __set($name, $value)
		{
			if( $name == 'salt' )
			{
				return false;
			}
			elseif( $name == 'password' )
			{
				$salt = substr(hash('whirlpool',rand(100000000000, 999999999999)), 0, 64);
				$real_pass = hash('whirlpool', $salt.$value);
				$this->salt = $salt;
				$this->password = $real_pass;
			}
			else
			{
				$this->$name = $value;
			}
			return $this->save();
		}
		
		public function save()
		{
			global $db;
			if( isset($this->authenticationid) )
			{
				if( $this->allSet() )
				{
					$authSQL = "UPDATE authentication SET identity=?, salt=?, password=?, roleid=? WHERE authenticationid=? AND userid=?";
					$values = array($this->identity, $this->salt, $this->password, $this->roleid, $this->authenticationid, $this->userid);
					$db->qwv($authSQL, $values);
					
					if( $db->stat() )
					{
						return $this;
					}
					else
					{
						return false;
					}
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
					$values = array($this->identity, $this->salt, $this->password, $this->userid, $this->roleid);
					$db->qwv($authSQL, $values);
					
					if( $db->stat() )
					{
						$this->authenticationid = $db->last();
						return $this;
					}
					else
					{
						return false;
					}					
				}
				else
				{
					return false;
				}
			}
		}
		
		private function allSet()
		{
			return isset($this->identity, $this->salt, $this->password, $this->roleid);
		}
	}
?>
