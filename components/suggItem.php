<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('Item.php');
	
	$q = isset($_GET['q']) ? $_GET['q'] : null;
	
	$items = Item::getBySearch($q);
	
	header('Content-type: application/json');
	
	if( $items instanceof Item )
	{
		$json = "[ " . jsonItem($items) . " ]";
		print $json;
	}
	elseif( is_array($items) )
	{
		$json = "[ ";
		$arr = array();
		foreach( $items as $item )
		{
			array_push($arr, jsonItem($item));
		}
		$json .= implode(',', $arr);
		$json .= " ]";
		
		print $json;
	}
	
	function jsonItem($item)
	{
		$dat["value"] = $item->itemid;
		$dat["name"] = $item->name;
		return json_encode($dat);
	}
?>