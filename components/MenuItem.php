<?php
	set_include_path('backbone:components:content:styles:scripts');
	
	require_once('Database.php');
	require_once('capstone.db');

	class MenuItem
	{
		private $db;
		public $newitems;
		private $item_info;
		
		function __construct($itemid)
		{
			$this->item_info = array();
			
			$this->db = new Database($GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname'], $GLOBALS['host'], 'mysql');
			$newitems = $this->db->q("SELECT * FROM items WHERE itemid='".$itemid."';");
			
			foreach( $newitems as $item )
			{
				$this->item_info = $item;
			}
			
		}
		
		public function run()
		{
			//in case you need to do something that's not constructing and not generating
		}
		
		public function generate()
		{
			$tmpl = new Template();
			$tmpl->item_info = $this->item_info;

			$css = $tmpl->build('menuitem.css');
			$html = $tmpl->build('menuitem.html');
			//$js = $tmpl->build('menubar.js'); // For any JS related to the menubar
			
			$content = array('html' => $html, 'css' => $css, 'js' => $js);
			return $content;
		}
}

?>