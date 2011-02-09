<?php
	set_include_path('backbone:components:content:scripts:styles');
	
	require_once('Template.php');

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

			$tmpl->menu = array(
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
						);
			
			$css = $tmpl->build('header.css');
			$html = $tmpl->build('header.html');
			//$js = $tmpl->build('header.js');
			
			$content = array('html' => $html, 'css' => $css, 'js' => $js);
			return $content;
		}
	}

?>