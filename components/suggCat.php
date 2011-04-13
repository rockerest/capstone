<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('Category.php');
	
	$q = isset($_GET['q']) ? $_GET['q'] : null;
	
	$cats = Category::getBySearch($q);
	
	header('Content-type: application/json');
	
	if( $cats instanceof Category )
	{
		$json = "[ " . jsonCat($cats) . " ]";
		print $json;
	}
	elseif( is_array($cats) )
	{
		$json = "[ ";
		$arr = array();
		foreach( $cats as $cat )
		{
			array_push($arr, jsonCat($cat));
		}
		$json .= implode(',', $arr);
		$json .= " ]";
		
		print $json;
	}
	
	function jsonCat($cat)
	{
		$dat["value"] = $cat->categoryid;
		$dat["name"] = $cat->name;
		return json_encode($dat);
	}
?>