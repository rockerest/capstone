<?php
	require_once('connect.php');
	
	require_once('Authentication.php');
	require_once('Order.php');
	
	class Predict
	{
		private $user;
		public function __construct($user)
		{
			$this->user = $user;
		}
		
		public function users()
		{
		}
		
		public function items()
		{
		}
		
		public function rating( $item )
		{
			if( $item instanceof Item )
			{
				$it = $item;
			}
			elseif( is_integer( $item ) )
			{
				$it = Item::getByID( $item );
			}
			else
			{
				return false;
			}
		}
		
		private function normalize( $chars )
		{
		}
	}

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
				$favs = Order::getByUserFavorites($user['userid']);
				array_push($userList, new User($user['userid'], $user['fname'], $user['lname'], $auth, $favs));
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
		
		private $authentication;
		private $favorites;
		
		private $Predict;
		
		public function __construct($userid, $fname, $lname, $auth = null, $favs = null)
		{
			if( $favs == null && $auth == null )
			{
				$auth = $lname;
				$lname = $fname;
				$fname = $userid;
				$userid = null;
			}
			elseif( $favs == null )
			{
				if( !($auth instanceof Authentication) )
				{
					$favs = $auth;
					$auth = $lname;
					$lname = $fname;
					$fname = $userid;
					$userid = null;
				}
			}

			$this->userid = $userid;
			$this->fname = $fname;
			$this->lname = $lname;
			
			$this->authentication = $auth;
			$this->favorites = $favs;
			
			$this->Predict = new Predict($user);
		}
		
		public function __get($var)
		{
			if( $var == 'favorites' )
			{
				//make sure the list is up-to-date
				$this->refresh();
			}
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
		
		public function refresh()
		{
			//pull in favorites again, in case one was added/removed since this object was created
			$this->favorites = Order::getByUserFavorites($user['userid']);
		}
	}
?>
