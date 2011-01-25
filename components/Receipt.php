<?php

class Receipt
{
	public function __construct()
	{
	
	}
	
	public function build()
	{
		print
		"<div id=\"receipt\">
			<p id=\"receipt_table_num\">Table #8</p>
			<table id=\"reciept_items_table\">
				<tr><th>Item</th><th>Quantity</th><th>Price</th></tr>
				<tr><td>Buffalo Chicken Sandwich</td><td class=\"quantity\">1</td><td class=\"price\">$6.99</td></tr>
				<tr><td>Budweiser 22 oz.</td><td class=\"quantity\">3</td><td class=\"price\">$6.00</td></tr>
				<tr><td>Nachos</td><td class=\"quantity\">1</td><td class=\"price\">$5.99</td></tr>
				<tr><td>Rum and Diet Coke</td><td class=\"quantity\">2</td><td class=\"price\">$8.00</td></tr>
			</table>
			<div class=\"line\"></div>
			<table id=\"receipt_totals\">
			<tr><td>Subtotal</td><td>$26.89</td></tr>
			<tr><td>Tax</td><td>$1.89</td></tr>
			<tr><td>Total</td><td>$28.78</td></tr>
			</table>
			<input type=\"submit\" value=\"Request Check\" />
		</div>";
		
	}
}

?>