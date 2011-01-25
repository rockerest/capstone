<?php

//		TODO: Modifiers, Customizations
//
//
//
//		2010/11/16

require_once('Receipt.php');

class Ingredients
{
	public function __construct()
	{
	
	}
	public function build($ingredients_list)
	{

	if($ingredients_list == 0)
	{
		print "	<div id=\"menuIngredients\">
			<span id=\"menuIngredients_title\">Included Ingredients:</span>
			<ul id=\"menuIngredients_ul\">
				<li class=\"menuIngredients_li\"><input type=\"checkbox\" checked=\"true\" id=\"menuItem1\"/><label for=\"menuItem1\">Chicken Patty</label></li>
				<li class=\"menuIngredients_li\"><input type=\"checkbox\" checked=\"true\" id=\"menuItem2\"/><label for=\"menuItem2\">Tomatoes</label></li>
				<li class=\"menuIngredients_li\"><input type=\"checkbox\" checked=\"true\" id=\"menuItem3\"/><label for=\"menuItem3\">Lettuce</label></li>
				<li class=\"menuIngredients_li\"><input type=\"checkbox\" checked=\"true\" id=\"menuItem4\"/><label for=\"menuItem4\">Provolone Cheese</label></li>
				<li class=\"menuIngredients_li\"><input type=\"checkbox\" checked=\"true\" id=\"menuItem5\"/><label for=\"menuItem5\">Onions</label></li>
				<li class=\"menuIngredients_li\"><input type=\"checkbox\" checked=\"true\" id=\"menuItem6\"/><label for=\"menuItem6\">Pickles</label></li>
				<li class=\"menuIngredients_li\"><input type=\"checkbox\" checked=\"true\" id=\"menuItem7\"/><label for=\"menuItem7\">\"Special Sauce\"</label></li>
				<li class=\"menuIngredients_li\"><input type=\"checkbox\" id=\"menuItem8\"/><label for=\"menuItem8\"><input type=\"textbox\" value=\"Add an Ingredient\" id=\"addIngredient\"/></label></li>
			</ul>";
	}
	else
	{
		print "	<div id=\"menuIngredients\">
			<span id=\"menuIngredients_title\">Included Ingredients:</span>
			<ul id=\"menuIngredients_ul\">";
		foreach($ingredients_list as $single_ingredient)
		{
			print "<li class=\"menuIngredients_li\"><input type=\"checkbox\" checked=\"true\" id=\"".$single_ingredient['ingredientid']."\"/><label for=\"".$single_ingredient['ingredientid']."\">".$single_ingredient['name']."</label></li>";
		}
		print "</ul>";
	}
		
	$r = new Receipt();
	$r->build();
		
	print "</div>";
	}
}

?>