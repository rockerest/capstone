<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('Order.php');
	
	if($_GET['orderid']!='' && $_GET['newstatus'] != '')
	{
			print Order::updateByID($_GET['orderid'], $_GET['newstatus']);
	}
	

	
?>