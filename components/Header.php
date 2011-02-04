<?php
	set_include_path('backbone:components:content:scripts:styles');
	
	require_once('Template.php');
	require_once('Session.php');
	setSession(0,"/");
	
	class Header
	{
		
		private $cur_page;
		
		public function __construct($cur_page)
		{
			$this->cur_page = $cur_page;
		}
		
		public function run()
		{
		}
		
		public function generate()
		{
			$tmpl = new Template();
			$tmpl->curr = $this->cur_page;
			
			$tmpl->menu = $_SESSION['active'] ? $_SESSION['role']==1 ? 
						array(
						"display" => array(
										"Home",
										"Menu",
										"Pay",
										"OrderList",
										"DBTEST",
										"Item Upload",
										"Create Users",
										"Logout"
										),
						"link" => array(
										"index.php",
										"order.php",
										"checkout.php",
										"orderlist.php",
										"dbtest.php",
										"uploadForm.php",
										"register.php",
										"login.php?logout=true"
										)
						) :  array(
						"display" => array(
										"Home",
										"Menu",
										"Pay",
										"Logout"
										),
						"link" => array(
										"index.php",
										"order.php",
										"checkout.php",
										"login.php?logout=true"
										)
						) :  array(
						"display" => array(
										"Home",
										"Menu",
										"Pay",
										"Login"
										),
						"link" => array(
										"index.php",
										"order.php",
										"checkout.php",
										"login.php"
										)
						);
			
			$css = $tmpl->build('header.css');
			$html = $tmpl->build('header.html');
			//$js = $tmpl->build('header.js');
			
			$content = array('html' => $html, 'css' => $css, 'js' => $js);
			return $content;
		}
	}

?>