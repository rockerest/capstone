<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('RedirectBrowserException.php');
	require_once('Characteristic.php');

	require_once('Session.php');
	setSession(0, '/');
	
	$name = isset($_POST['name']) ? $_POST['name'] : null;
	
	if( !isset($_SESSION['userid']) || $_SESSION['userid'] == null || $_SESSION['userid'] < 1 )
	{
		throw new RedirectBrowserException("../login.php?code=10");
	}
	
	if( $name != null )
	{
		if( Characteristic::getByCharacteristic($name) )
		{
			print jsonify(false, "This characteristic already exists", $name);
		}
		else
		{
			if( Characteristic::add($name) )
			{
				print jsonify(true, "The characteristic was added successfully", $name);
			}
			else
			{
				print jsonify(false, "The characteristic could not be added", $name);
			}
		}
	}
	else
	{
		print jsonify(false, "There was an error adding this characteristic", $name);
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