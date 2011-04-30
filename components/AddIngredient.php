<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('RedirectBrowserException.php');
	require_once('Ingredient.php');

	require_once('Session.php');
	setSession(0, '/');
	
	$name = isset($_POST['name']) ? $_POST['name'] : null;
	$veg = isset($_POST['veg']) ? true : false;
	$all = isset($_POST['all']) ? true : false;
	$side = isset($_POST['side']) ? true : false;
	
	if( !isset($_SESSION['userid']) || $_SESSION['userid'] == null || $_SESSION['userid'] < 1 )
	{
		throw new RedirectBrowserException("../login.php?code=10");
	}
	
	if( $name != null )
	{
		if( Ingredient::getByName($name) )
		{
			print jsonify(false, "This ingredient already exists", $name);
		}
		else
		{
			if( Ingredient::add($name, $veg, $all, $side) )
			{
				print jsonify(true, "The ingredient was added successfully", $name);
			}
			else
			{
				print jsonify(false, "The ingredient could not be added", $name);
			}
		}
	}
	else
	{
		print jsonify(false, "There was an error adding this ingredient", $name);
	}
	
	function jsonify($stat, $msg, $data = null)
	{
		$ret = array(	"status" => $stat,
						"message" => $msg,
						"data" => $data
					);
		return json_encode($ret);
	}
?>