<?php
	set_include_path('backbone:components:content:styles:scripts');
	
	require_once('Database.php');
	require_once('capstone.db');

	class Menubar
	{
		private $db;
		private $labels;
		private $links;
		private $current;

		function __construct()
		{
			$this->labels = array();
			$this->links = array();	
			$rootlink = "order.php?id="; // or something similar
			
			$this->db = new Database($GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname'], $GLOBALS['host'], 'mysql');
			
			$newitems = $this->db->q("SELECT * FROM items");
			foreach( $newitems as $item )
			{
				array_push($this->labels, $item['name']);
				array_push($this->links, $rootlink.$item['itemid']);
			}
		}
		
		public function run()
		{
			//in case you need to do something that's not constructing and not generating
		}
		
		public function generate()
		{
			$tmpl = new Template();
			$tmpl->labels = $this->labels;
			$tmpl->links = $this->links;
			
			$css = $tmpl->build('menubar.css');
			$html = $tmpl->build('menubar.html');
			$js = $tmpl->build('menubar.js'); // For any JS related to the menubar
			
			$content = array('html' => $html, 'css' => $css, 'js' => $js);
			return $content;
		}
}

?>