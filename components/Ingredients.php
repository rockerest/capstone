<?php
	error_reporting(E_ALL);
	set_include_path('backbone:components:content:styles:scripts');
	
	require_once('Database.php');
	require_once('capstone.db');

	class Ingredients
	{
		private $db;
		private $ing_labels;
		private $itemid;
		
		function __construct($itemid)
		{
			$this->itemid = $itemid;
		}
		
		public function run()
		{
			//in case you need to do something that's not constructing and not generating
			$this->ing_labels = array();
			
			$this->db = new Database($GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['dbname'], $GLOBALS['host'], 'mysql');
			
			$ingredients = $this->db->q("SELECT * FROM ingredients WHERE ingredientid IN (SELECT ingredientid FROM items_have_ingredients WHERE itemid=".$itemid.");" );
			foreach( $ingredients as $ingredient )
			{
				array_push($this->ing_labels, $ingredient['name']);
			}
		}
		
		public function generate()
		{
			$tmpl = new Template();
			$tmpl->ing_labels = $this->ing_labels;

			$css = $tmpl->build('ingredients.css');		
			$html = $tmpl->build('ingredients.html');
			//$js = $tmpl->build('menubar.js'); // For any JS related to the menubar
			
			$content = array('html' => $html, 'css' => $css, 'js' => $js);
			return $content;
		}
}

?>