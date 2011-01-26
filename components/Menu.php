<?php
	require('backbone/Database.php');
	require('backbone/capstone.db');

		//	TODO: Everything - Pull table #, recent orders, log in/out, register, 
		//
		//	Query Database for All menu items,
		//	including predicted menu Items and
		//	popular menu Items
		//
		//	Display all items
		//	
		//	CURRENT: Fake data not pulled from DB 11/15/10


class Menu
{
	
		function __construct()
		{
			//
			//
		}
		
		
		
		public function build($menu_items)
		{
		
			//This does not work...
			/* I dont know why this is here...
			
			
			$d = new Database($user, $pass, $dbname, $host, 'mysql');
			$newitems = $d->q("SELECT * FROM items");
			var_dump($newitems);
			
				print "<div id=\"menuNav\">
				<div style=\"float: left\" id=\"my_menu\" class=\"sdmenu\">
				  <div>
					<span>Appetizers</span>
					<a href=\"#\">Nachos</a>
					<a href=\"#\">Chicken Tenders</a>
					<a href=\"#\">Buffalo Wings</a>
					<a href=\"#\">Cheese Sticks</a>
					<a href=\"#\">Potato Skins</a>
				  </div>
				  <div>
					<span>Entrees</span>
					<a href=\"#\">Chicken Sandwich</a>
					<a href=\"#\">Cheeseburger</a>
					<a href=\"#\">Buffalo Ranch Chicken Sandwich</a>
					<a href=\"#\">French Dip</a>
					<a href=\"#\">Chicken Caesar Salad</a>
					<a href=\"#\">Meat Loaf</a>
				  </div>
				  <div class=\"collapsed\">
					<span>Desserts</span>
					<a href=\"#\">Ice Cream Cone</a>
					<a href=\"#\">Chocolate Cake</a>
					<a href=\"#\">Birthday Cake</a>
					<a href=\"#\">Cheescake</a>
				  </div>
				  <div>
					<span>Drinks</span>
						<span class=\"subTitle\">Alcoholic</span>
						<a href=\"#\">Beer</a>
						<a href=\"#\">Rum</a>
						<a href=\"#\">Vodka</a>
						<a href=\"#\">Tequila</a>
						<span class=\"subTitle\">Non-Alcoholic</span>
						<a href=\"#\">Soda</a>
						<a href=\"#\">Iced Tea</a>
						<a href=\"#\">Water</a>
				  </div>
				  <div>
					<span>Items You'll Love</span>
						<a href=\"#\">Beer</a>
						<a href=\"#\">Beer</a>
						<a href=\"#\">Beer</a>
						<a href=\"#\">Beer</a>
						<a href=\"#\">Cheesecake</a>
				  </div>
				  <div>
					<span>Popular Items</span>
						<span class=\"subTitle\">Today</span>
							<a href=\"#\">Buffalo Wings</a>
							<a href=\"#\">Nachos</a>
							<a href=\"#\">Cheeseburger</a>
							<a href=\"#\">Chicken Caesar Salad</a>
							<a href=\"#\">Water</a>
						<span class=\"subTitle\">This Week</span>
							<a href=\"#\">Cheeseburger</a>
							<a href=\"#\">French Dip</a>
							<a href=\"#\">Meat Loag</a>
							<a href=\"#\">Beer</a>
						<span class=\"subTitle\">All Time</span>
							<a href=\"#\">Meat Loaf</a>
							<a href=\"#\">Cheesecake</a>
							<a href=\"#\">Beer</a>
							<a href=\"#\">Nachos</a>
				  </div>
				</div>
				</div>";
				*/
		}
}

?>