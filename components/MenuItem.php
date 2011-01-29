<?php
	set_include_path('backbone:components:content:styles:scripts');
	
	require_once('Database.php');
	require_once('capstone.db');

	class MenuItem
	{
		private $db;
		public $newitems;
		public $ingredients;
		
		private $item_info;
		private $ing_labels;
		
		function __construct($itemid)
		{
			$this->item_info = array();
			$this->ing_labels = array();
			
			$this->db = new Database($GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname'], $GLOBALS['host'], 'mysql');
			
			$sql = "SELECT * FROM items WHERE itemid='".$itemid."';";
			$newitems = $this->db->q($sql);
			foreach( $newitems as $item )
			{
				$this->item_info = $item;
			}
			
			$sql = "SELECT * FROM ingredients WHERE ingredientid IN (SELECT ingredientid FROM items_have_ingredients WHERE itemid='".$itemid."');";
			$ingredients = $this->db->q($sql);
			foreach( $ingredients as $ingredient )
			{	
				array_push($this->ing_labels, $ingredient['name']);
				//$this->ing_labels = $ingredients;
			}

		}
		
		public function run()
		{
			//in case you need to do something that's not constructing and not generating
		}
		
		public function generate($view)
		{
			$tmpl = new Template();
			$tmpl->item_info = $this->item_info;
			$tmpl->view = $view;
			$tmpl->ing_labels = $this->ing_labels;
			$css = $tmpl->build('menuitem.css');		
			$html = $tmpl->build('menuitem.html');
			//$js = $tmpl->build('menubar.js'); // For any JS related to the menubar
			
			$content = array('html' => $html, 'css' => $css, 'js' => $js);
			return $content;
		}
}

?>