<?php
	require_once('Template.php');
	require_once('Session.php');
	setSession(0,"/");
	
	class Footer
	{
		public function __construct()
		{
		}
		
		public function run()
		{
		}
		
		public function generate()
		{
			$tmpl = new Template();
			
			//$css = $tmpl->build('footer.css');
			$html = $tmpl->build('footer.html');
			//$js = $tmpl->build('footer.js');
			
			$content = array('html' => $html, 'css' => array('code' => $css, 'link' => 'footer'), 'js' => $js);
			return $content;
		}
	}

?>