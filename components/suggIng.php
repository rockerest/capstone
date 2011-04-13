<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('Ingredient.php');
	
	$q = isset($_GET['q']) ? $_GET['q'] : null;
	
	$ings = Ingredient::getBySearch($q);
	
	header('Content-type: application/json');
	
	if( $ings instanceof Ingredient )
	{
		$json = "[ " . jsonIng($ings) . " ]";
		print $json;
	}
	elseif( is_array($ings) )
	{
		$json = "[ ";
		$arr = array();
		foreach( $ings as $ing )
		{
			array_push($arr, jsonIng($ing));
		}
		$json .= implode(',', $arr);
		$json .= " ]";
		
		print $json;
	}
	
	function jsonIng($ing)
	{
		$dat["value"] = $ing->ingredientid;
		$dat["name"] = $ing->name;
		return json_encode($dat);
	}
?>