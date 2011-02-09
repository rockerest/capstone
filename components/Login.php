<?php
	set_include_path('backbone:components:content:styles:scripts:model');
	
	require_once('Authentication.php');
	require_once('Role.php');
	
	class Login
	{
		private $submit;
		private $message;
		
		public function __construct()
		{
			if(isset($_GET['logout'])&&$_GET['logout']==true)
			{
				session_destroy();
				header('Location: index.php');
			}

				
			$this->submit = isset( $_GET['loginsubmit'] ) ? $_GET['loginsubmit'] : 0;
			//$db = new Database($user, $pass, $dbname, $host, 'mysql');
			
			if($this->submit == 1)
			{
				if($_POST['email']!=''&&$_POST['pass']!='')
				{
					$email = $_POST['email'];
					$userpass = $_POST['pass'];
					//$sql = 'SELECT salt FROM authentication WHERE identity=?';
					//$results = $this->db->qwv( $sql, array($email) );
					//$salt = $results[0]['salt'];
					//$salted = hash('whirlpool', $salt.$userpass);
					//$sql = 'SELECT * FROM authentication WHERE identity=? AND password=?';
					//$values = array( $email, $salted );
					//$results = $this->db->qwv( $sql, $values );
					//$num = count($results);
					//$this->message = "Email -".$email." - userpass - ".$userpass." - salt - ".$salt." - salted - ".$salted;
					//if($num == 1)
					//{
					//	$userid = $results[0]['userid'];
						$user_info = Authentication::validate($email, $userpass);
						var_dump($user_info);
					//	
					//	setSessionVar('active', true);
					//	setSessionVar('role', $results[0]['roleid']);
					//	setSessionVar('userid', $userid);
				}
				else
				{
						$this->message =  "Please fill out all fields. Thanks.";
				}
			}
		}
		
		public function run()
		{
		
		}
		
		public function generate()
		{	
			if($this->submit!=1)
			{
				$tmpl = new Template();
				$tmpl->message = $this->message;
				$html = $tmpl->build('login.html');
				$css = $tmpl->build('login.css');
				$content = array('html' => $html, 'css' => $css);
				return $content;
			}
			else
			{
				$tmpl = new Template();
				$tmpl->message = $this->message;
				$html = $tmpl->build('login.html');
				$css = $tmpl->build('login.css');
				$content = array('html' => $html, 'css' => $css);
				return $content;
			}
			
		}
		
	}
	
	?>