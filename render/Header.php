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
														"Order",
														"Menu",
														"OrderList",
														"DBTEST",
														"Item Upload",
														"Create Users",
														"Logout"
														),
										"link" => array(
														"/order.php",
														"/menu.php",
														"/_demo/orderlist.php",
														"/_demo/dbtest.php",
														"/_demo/uploadForm.php",
														"/login.php?action=register",
														"/login.php?action=logout"
														),
										'icon' => array(
														null,
														'book',
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
														'book',
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
														"Login"
														),
										"link" => array(
														"/order.php",
														"/menu.php",
														"/login.php"
														),
										"icon" => array(
														null,
														'book',
														"user"
														)
										);
			}
			
			if( $_SERVER['SCRIPT_NAME'] != '/login.php' && !isset($_SESSION['umbrella']['tableid']) )
			{
				if( $_SESSION['roleid'] > 2 )
				{
					$loc = urlencode("login.php?code=11");
					header('Location: login.php?action=logout&fwd='.$loc);
				}
				else
				{
					if( $_SERVER['SCRIPT_NAME'] != '/table.php' )
					{
						header('Location: table.php?code=0');
					}
				}
			}
			
			$css = $tmpl->build('header.css');
			$html = $tmpl->build('header.html');
			//$js = $tmpl->build('header.js');
			
			$content = array('html' => $html, 'css' => array('code' => $css, 'link' => 'header'), 'js' => $js);
			return $content;
		}
	}

?>