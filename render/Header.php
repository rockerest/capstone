<?php
	require_once('Template.php');
	require_once('Session.php');
	setSession(0,"/");
	
	require_once('Navigation.php');
	
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
				$tmpl->menu = Navigation::getByRole($_SESSION['roleid']);
				array_push($tmpl->menu, new Navigation(
														null,
														"Logout",
														"/login.php?action=logout",
														'user',
														4,
														0
														));
			}
			else
			{
				$tmpl->menu = Navigation::getByRole(4);
				array_push($tmpl->menu, new Navigation(
														null,
														"Login",
														"/login.php",
														'user',
														4,
														0
														));
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
			$js = $tmpl->build('header.js');
			
			$content = array('html' => $html, 'css' => array('code' => $css, 'link' => 'header'), 'js' => $js);
			return $content;
		}
	}

?>