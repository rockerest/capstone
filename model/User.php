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
			$this->userid = $user['userid'];
			$this->fname = $user['fname'];
			$this->lname = $user['lname'];
			
			$this->authentication = $auth;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
