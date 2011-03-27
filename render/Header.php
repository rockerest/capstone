<?php
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
			
			if( $_SESSION['active'] )
			{
				if( $_SESSION['roleid'] == 1 )
				{
					$tmpl->menu = array(
										"display" => array(
														"Home",
														"Menu",
														"Order",
														"OrderList",
														"DBTEST",
														"Item Upload",
														"Create Users",
														"Logout"
														),
										"link" => array(
														"/index.php",
														"/menu.php",
														"/order.php",
														"/_demo/orderlist.php",
														"/_demo/dbtest.php",
														"/_demo/uploadForm.php",
														"/login.php?action=register",
														"/login.php?action=logout"
														),
										'icon' => array(
														null,
														null,
														null,
														null,
														null,
														null,
														null,
														'user'
														)
										);
				}
				else
				{
					$tmpl->menu = array(
										"display" => array(
														"Order",
														"Menu",														
														"Logout"
														),
										"link" => array(
														"/order.php",
														"/menu.php",
														"/login.php?action=logout"
														),
										"icon" => array(
														null,
														null,
														"user"
														)
										);
				}
			}
			else
			{
				$tmpl->menu = array(
										"display" => array(
														"Order",
														"Menu",														
														"Logout"
														),
										"link" => array(
														"/order.php",
														"/menu.php",
														"/login.php?action=logout"
														),
										"icon" => array(
														null,
														null,
														"user"
														)
										);
			}
			
			$css = $tmpl->build('header.css');
			$html = $tmpl->build('header.html');
			//$js = $tmpl->build('header.js');
			
			$content = array('html' => $html, 'css' => array('code' => $css, 'link' => '/styles/header.css'), 'js' => $js);
			return $content;
		}
	}

?>