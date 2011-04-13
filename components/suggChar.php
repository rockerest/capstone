<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('Characteristic.php');
	
	$q = isset($_GET['q']) ? $_GET['q'] : null;
	
	$chars = Characteristic::getBySearch($q);
	
	header('Content-type: application/json');
	
	if( $chars instanceof Characteristic )
	{
		$json = "[ " . jsonChar($chars) . " ]";
		print $json;
	}
	elseif( is_array($chars) )
	{
		$json = "[ ";
		$arr = array();
		foreach( $chars as $char )
		{
			array_push($arr, jsonChar($char));
		}
		$json .= implode(',', $arr);
		$json .= " ]";
		
		print $json;
	}
	
	function jsonChar($char)
	{
		$dat["value"] = $char->characteristicid;
		$dat["name"] = $char->characteristic;
		return json_encode($dat);
	}
?>