<?php
	require_once('connect.php');
	
	require_once('Authentication.php');

	class User
	{
		public static function getByID($id)
		{
			global $db;
			$userSQL = "SELECT * FROM users WHERE userid=?";
			$values = array($id);
			$user = $db->qwv($userSQL, $values);
			
			return User::wrap($user);
		}
		
		public static function add($fname, $lname, $ident, $pass, $roleid)
		{
			global $db;
			$userSQL = "INSERT INTO users (fname, lname) VALUES(?,?)";
			$values = array($fname, $lname);
			$db->qwv($userSQL, $values);
			
			if( $db->stat() )
			{
				$auth = Authentication::addForUser($db->last(), $ident, $pass, $roleid);
				
				if( $auth )
				{
					$object = array(	'userid'=>$db->last(),
										'fname'=>$fname,
										'lname'=>$lname
									);
					
					$user = User::wrap(array($object));
					$save = $user[0]->save();
					
					if( $save )
					{
						return $user[0];
					}
					else
					{
						return false;
					}
				}
				else
				{
					$status = User::delete($db->last());
					
					if( $status )
					{
						return false;
					}
					else
					{
						//you are just totally screwed
					}
				}
			}
			else
			{
				return false;
			}
		}
		
		public static function delete($userid)
		{
			global $db;
			$delUser = "DELETE FROM users WHERE userid=?";
			$values = array($userid);
			$db->qwv($delUser, $values);
			
			if( $db->stat() )
			{
				return Authentication::deleteByUser($userid);
			}
			
			return $db->stat();
		}
		
		public static function wrap($users)
		{
			$userList = array();
			foreach( $users as $user )
			{
				$auth = Authentication::getByUserID($user['userid']);
				array_push($userList, new User($user, $auth));
			}
			
			return $userList;
		}
		
		private $userid;
		private $fname;
		private $lname;
		
		private $authentication;
		
		public function __construct($user, $auth)
		{
			$this->userid = isset($user['userid']) ? $user['userid'] : null;
			$this->fname = $user['fname'];
			$this->lname = $user['lname'];
			
			$this->authentication = $auth;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
		
		public function save()
		{
			global $db;
			if( !isset($userid) )
			{
				return false;
			}
			else
			{
				$userSQL = "UPDATE users SET fname=?, lname=? WHERE userid=?";
				$values = array ($this->fname, $this->lname, $this->userid);
				$db->qwv($userSQL, $values);
				
				return $db->stat();
			}
		}
	}
?>
