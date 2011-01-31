<?php

	/*	This script is terrible.
		Please do not judge me.
		This is for testing purposes only.
		Thank you, good day, and God Bless America.
		
		-Stuart
		2010/01/29
	*/


	set_include_path('backbone:components:content:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');
	require_once('Database.php');
	require_once('capstone.db');
	
	$table_data;
	$page = new Page(0, "OrderUp - All Categories");
	$d = new Database($user, $pass, $dbname, $host, 'mysql');
	$tmpl = new Template();
	
	
	$orders = $d->q("SELECT * FROM orders");
	foreach($orders as $order)
	{
	$total = 0;

		$table_data = $table_data."<td>".$order['orderid']."</td>";
		$table_data = $table_data."<td>".$order['tableid']."</td>";
		$table_data = $order['isSubmitted']==1 ? $table_data."<td>Yes</td>" : $table_data."<td>No</td>";
		$table_data = $table_data."<td>".$order['time']."</td><td>";
		$table_data = $table_data.$order['specialComment']."</td>";
		
		
		//get user id's at table
		$userq = "SELECT * FROM users WHERE userid = ".$order['userid'];
		$user = $d->q($userq);
		
		//print out each user at table
		foreach($user as $uItem)
		{
			$table_data = $table_data."<td>".$uItem['fname']."</td>";
			$table_data = $table_data."<td>".$uItem['lname']."</td>";
		}
		
		//get all order_items
		$oq = "SELECT * FROM order_items WHERE orderid = ".$order['orderid'];
		$order_items = $d->q($oq);
		
		//print each order_item
		foreach($order_items as $order_item)
		{
			$table_data = $table_data."<td>".$order_item['order_itemid']."</td><td>";
			
			//get all items in this order
			$iq = "SELECT * FROM items WHERE itemid = ".$order_item['itemid'];
			$items = $d->q($iq);
			
			//print out each item
			foreach($items as $item)
			{
				//$table_data = $table_data."<td>Item ID: ".$item['itemid']."</td>";
				//print category
				$categories = $d->q("SELECT * FROM categories WHERE categoryid=".$item['categoryid']);
				foreach($categories as $category)
				{
					$table_data = $table_data.$category['name'];
					//$table_data = $table_data."<td>Category-id: ".$category['categoryid']."</td>";
					//$table_data = $table_data."<td>Category-number: ".$category['number']."</td><td>";
				}

				//$table_data = $table_data.$item['name']." - ".$item['description']."</td>";
				$table_data = $table_data."</td><td>".$item['preptime']."</td>";
				//$table_data = $table_data."<td>Price: ".$item['price']."</td>";
				$total += $item['price'];
				
				//get ingredients in each item
				/*
				$iiq = "SELECT * FROM ingredients WHERE ingredientid IN (SELECT ingredientid FROM items_have_ingredients WHERE itemid=".$item['itemid'].")";
				$ingredients = $d->q($iiq);
				if(!empty($ingredients)){print "<td>Ingredients:</td>";};
				foreach($ingredients as $ingredient)
				{
					print $ingredient['name'];
					print $ingredient['isVegetarian'] ? "-Vegetarian<br/>" : "<br/>";
					print $ingredient['isAllergenic'] ? "-Allergy Warning<br/>" : "<br/>";
				}
				
				//get item characteristics
				$cq = "SELECT * FROM characteristics WHERE characteristicid IN(SELECT characteristics_characteristicid FROM characteristics_for_items WHERE items_itemid = ".$item['itemid'].")";
				$characteristics = $d->q($cq);
				if(!empty($characteristics)){print "Characteristics:<br/>";};
				
				foreach($characteristics as $characteristic)
				{
					print $characteristic['characteristic']."<br/>";
				}
				
				/*
				$iiq = "SELECT ingredients_ingredientid FROM items_have_recommended_ingredients WHERE itemid=".$item['itemid'];
				$ingredients = $d->q($iiq);			
				
				//
				//	Not sure what this is for..
				//

				print "RECCOMMENDED? INGREDIENTS:<br/>";
				foreach($ingredients as $ingredient)
				{
					print $ingredient['name']."<br/>";
					print $ingredient['isVegetarian'] ? "Vegetarian" : "";
					print $ingredient['isAllergenic'] ? "Allergy Warning" : "";
				}
				*/
			}
		}
		
	//print total
	$table_data = $table_data."<td>Total: ".$total."</td></tr>";
	}
	$tmpl->table_data = $table_data;
	
	$page->run();
	$html = $tmpl->build('orderlist.html');
	//$css = $tmpl->build('orderlist.css');
	//$js = $tmpl->build('orderlist.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	$css,
						'js' => $js
						);

	print $page->build($appContent);

?>
