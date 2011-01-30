<?php

//
//	this is more or less a list of examples of how to get anything out of the database
//	may be eventually merged into receipt...
//
//	Stuart Feldt 2010/11/17
//

require_once('backbone/Database.php');
require_once('backbone/capstone.db');
$d = new Database($user, $pass, $dbname, $host, 'mysql');
$total = 0;

$q = "SELECT * FROM items";
		$menu_items = $d->q($q);
		foreach($menu_items as $menu_item)
		{
			print $menu_item['name']." - ".$menu_item['categoryid']."<br/>";
			$sql = "SELECT * FROM ingredients WHERE ingredientid IN (SELECT ingredientid FROM items_have_ingredients WHERE itemid='".$menu_item['itemid']."');";
			$item_ingredients = $d->q($sql);
			foreach($item_ingredients as $ingredient)
			{
				print "Ingredient - ".$ingredient['name']."</br />";
			}
		}

$q = "SELECT * FROM items_have_ingredients";
$ing = $d->q($q);
foreach($ing as $ping)
{
	print $ping['itemid']."-".$ping['ingredientid']."<br />";
}
$q = "SELECT * FROM ingredients";
$ing = $d->q($q);
foreach($ing as $ping)
{
	print $ping['name']."-".$ping['ingredientid']."<br />";
}

$orders = $d->q("SELECT * FROM orders");
foreach($orders as $order)
{
	print "<h1>ORDER NUMBER:</h1> ".$order['orderid']."<br/>";
	print "Table number: ".$order['tableid']."<br/>";
	print $order['isSubmitted'] ? "Submitted<br/>" : "Not submitted<br/>";
	print "Time: ".$order['time']."<br/>";
	print $order['specialComment']."<br/>";
	
	
	//get user id's at table
	$userq = "SELECT * FROM users WHERE userid = ".$order['userid'];
	$user = $d->q($userq);
	
	//print out each user at table
	foreach($user as $uItem)
	{
		print "First Name: ".$uItem['fname']."<br/>";
		print "Last Name: ".$uItem['lname']."<br/>";
	}
	
	//get all order_items
	$oq = "SELECT * FROM order_items WHERE orderid = ".$order['orderid'];
	$order_items = $d->q($oq);
	
	//print each order_item
	foreach($order_items as $order_item)
	{
		print "<br /><br/>Order Item Id: ".$order_item['order_itemid']."<br />";
		
		//get all items in this order
		$iq = "SELECT * FROM items WHERE itemid = ".$order_item['itemid'];
		$items = $d->q($iq);
		
		//print out each item
		foreach($items as $item)
		{
			print "Item ID: ".$item['itemid']."<br/>";
			//print category
			$categories = $d->q("SELECT * FROM categories WHERE categoryid=".$item['categoryid']);
			foreach($categories as $category)
			{
				print "Category-name: ".$category['name']."<br/>";
				print "Category-id: ".$category['categoryid']."<br/>";
				print "Category-number: ".$category['number']."<br/>";
			}

			print $item['name']." - ".$item['description']."<br/>";
			print "Prep Time: ".$item['preptime']."<br/>";
			print "Price: ".$item['price']."<br/>";
			$total += $item['price'];
			
			//get ingredients in each item
			$iiq = "SELECT * FROM ingredients WHERE ingredientid IN (SELECT ingredientid FROM items_have_ingredients WHERE itemid=".$item['itemid'].")";
			echo $iiq."</br>";
			$ingredients = $d->q($iiq);
			if(!empty($ingredients)){print "Ingredients:<br/>";};
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

}

//print total
print "<br />Total: ".$total;




//status
$dstat = $d->stat();
echo "<br />STATUS: ".$dstat."<br/><br/>";


$tot_categories = $d->q("SELECT * FROM categories");
foreach($tot_categories as $tot_category)
{
	print "Category-id: ".$tot_category['categoryid']."<br/>";
	print "Category-name: ".$tot_category['name']."<br/>";
	print "Category-number: ".$tot_category['number']."<br/>------------------------------------><br/>";
}

echo "__USERS__<br />";
echo "_userid__fname__lname<br />";
$sql = "SELECT * FROM users";
$users = $d->q($sql);
foreach($users as $user)
{
	print $user['userid']."-".$user['fname']."-".$user['lname']."<br />";
}

echo "__TABLES__<br />";
echo "_tableid__serverid__isAvailable<br />";
$sql = "SELECT * FROM tables";
$tables = $d->q($sql);
foreach($tables as $table)
{
	print $table['tableid']."-".$table['serverid']."-".$table['isAvailable']."<br />";
}

echo "__SERVERS__<br />";
echo "_serverid__userid__isWorking<br />";
$sql = "SELECT * FROM servers";
$servers = $d->q($sql);
foreach($servers as $server)
{
	print $server['serverid']."-".$server['userid']."-".$server['isWorking']."<br />";
}


?>
