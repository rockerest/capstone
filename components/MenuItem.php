<?php
	set_include_path('backbone:components:content:styles:scripts');
	
	require_once('Database.php');
	require_once('capstone.db');

	class MenuItem
	{
		private $db;
		public $newitems;

		function __construct($itemid)
		{
			
			$this->db = new Database($GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname'], $GLOBALS['host'], 'mysql');
			
			$newitems = $this->db->q("SELECT * FROM items WHERE itemid='".$itemid."';");
			var_dump($newitems);
		}
		
		public function run()
		{
			//in case you need to do something that's not constructing and not generating
		}
		
		public function generate()
		{
			$tmpl = new Template();

			$css = $tmpl->build('menuitem.css');
			$html = $tmpl->build('menuitem.html');
			//$js = $tmpl->build('menubar.js'); // For any JS related to the menubar
			
			$content = array('html' => $html, 'css' => $css, 'js' => $js);
			return $content;
		}
}

?>