<?php
	require_once('connect.php');
	
	require_once('Authentication.php');
	require_once('Order.php');
	require_once('Predict.php');

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
			$okay = Authentication::checkIdentity($ident);
			
			if( $okay === 0 )
			{
				$user = new User(null, $fname, $lname);
				$res = $user->save();
				
				if( $res )
				{
					$auth = Authentication::addForUser($res->userid, $ident, $pass, $roleid);
					if( $auth )
					{
						return $res;
					}
					else
					{
						$status = User::delete($res->userid);
						
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
				array_push($userList, new User($user['userid'], $user['fname'], $user['lname']));
			}
			
			if( count( $userList ) > 1 )
			{
				return $userList;
			}
			elseif( count( $userList ) == 1 )
			{
				return $userList[0];
			}
			else
			{
				return false;
			}
		}
		
		private $userid;
		private $fname;
		private $lname;
		
		public function __construct($userid, $fname, $lname)
		{
			$this->userid = $userid;
			$this->fname = $fname;
			$this->lname = $lname;
		}
		
		public function __get($var)
		{
			if( $var == 'favorites' )
			{
				return Order::getByUserFavorites($this->userid);
			}
			elseif( $var == 'authentication' )
			{
				return Authentication::getByUserID($this->userid);
			}
			elseif( $var == 'Predict' )
			{
				return new Predict($this);
			}
			else
			{
				return $this->$var;
			}
		}
		
		public function save()
		{
			global $db;
			if( !isset($userid) )
			{
				$userSQL = "INSERT INTO users (fname, lname) VALUES(?,?)";
				$values = array($this->fname, $this->lname);
				$db->qwv($userSQL, $values);
				
				if( $db->stat() )
				{
					$this->userid = $db->last();
					return $this;
				}
				else
				{
					return false;
				}
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
