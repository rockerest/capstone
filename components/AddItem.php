<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('RedirectBrowserException.php');
	require_once('Item.php');
	require_once('Category.php');
	require_once('Characteristic.php');
	require_once('Ingredient.php');

	require_once('Session.php');
	setSession(0, '/');
	
	$name = isset($_POST['name']) ? $_POST['name'] : null;
	$desc = isset($_POST['desc']) ? $_POST['desc'] : null;
	$cat = isset($_POST['cat']) ? $_POST['cat'] : null;
	$img = isset($_POST['image']) ? $_POST['image'] : null;
	$prep = isset($_POST['prep']) ? $_POST['prep'] : null;
	$lvl = isset($_POST['lvl']) ? $_POST['lvl'] : false;
	$price = isset($_POST['price']) ? $_POST['price'] : null;
	$ing = isset($_POST['ing']) ? $_POST['ing'] : null;
	$char = isset($_POST['char']) ? $_POST['char'] : null;
	
	$fwd = isset($_GET['fwd']) ? $_GET['fwd'] : null;
	
	//clean the AS inputs
	$cat = replaceName(explode(',', $cat), 0);
	$ing = replaceName(explode(',', $ing), 1);
	$char = replaceName(explode(',', $char), 2);
	
	$data = array(
				'name' => $name,
				'desc' => $desc,
				'cat' => $cat,
				'img' => $img,
				'prep' => $prep,
				'lvl' => $lvl,
				'price' => $price,
				'ing' => $ing,
				'char' => $char				
				);
				
	if( !isset($_SESSION['active']) || !$_SESSION['active'] || $_SESSION['roleid'] > 2 )
	{
		kick(0, $data, 0);
	}
	
	//check for existing name
	$item = Item::getByName($date['name']);
	if( $item )
	{
		kick(1, $data, 9);
	}
	
	if( $data['name'] == '' || $data['name'] == null )
	{
		kick(1, $data, 0);
	}
	
	if( $data['desc'] == '' || $data['desc'] == null )
	{
		kick(1, $data, 1);
	}
	
	if( $data['cat'] == "" || $data['cat'] == null || !is_array($data['cat']) || count($data['cat']) != 1 )
	{
		kick(1, $data, 2);
	}
	
	if( $data['img'] == '' || $data['img'] == null )
	{
		//only kick for image if it's not supported.
		//kick(1, $data, 3);
	}
	
	if( $data['prep'] == '' || $data['prep'] == null || !is_numeric($data['prep']) )
	{
		kick(1, $data, 4);
	}
	
	if( $data['lvl'] == '' || $data['lvl'] == null )
	{
		kick(1, $data, 5);
	}
	
	if( $data['price'] == '' || $data['price'] == null ||  !is_numeric($data['price']) )
	{
		kick(1, $data, 6);
	}
	
	if( $data['ing'] == '' || $data['ing'] == null || !is_array($data['ing']) || count($data['ing']) <= 0 )
	{
		kick(1, $data, 7);
	}
	
	if( $data['char'] == '' || $data['char'] == null || !is_array($data['char']) || count($data['char']) <= 0 )
	{
		kick(1, $data, 8);
	}
	
	//if it's all okay data
	$newItem = Item::add($data['name'], intval($data['cat'][0]), $data['desc'], $data['img'], floatval($data['price']), intval($data['prep']), intval($data['lvl']));
	if( $newItem instanceof Item )
	{
		foreach( $data['ing'] as $ing )
		{
			$newItem->attach('ingredient', intval($ing));
		}
		
		foreach( $data['char'] as $char )
		{
			$newItem->attach('characteristic', intval($char));
		}
		
		$data['id'] = $newItem->itemid;
		
		kick(2, $data, 3);
	}
	else
	{
		kick(1, $data, 9);
	}
	
	function kick( $to, $data, $code )
	{
		if( $fwd != null )
		{
			throw new RedirectBrowserException($fwd);
		}
		else
		{
			if( $to == 0 )
			{
				$urlenc = urlencode("item.php?" . http_build_query($data));
				throw new RedirectBrowserException("/login.php?code=" . $code . "&fwd=" . $urlenc);
			}
			elseif( $to == 1 )
			{
				throw new RedirectBrowserException("/item.php?action=add&code=" . $code . "&" . http_build_query($data));
			}
			elseif( $to == 2 )
			{
				throw new RedirectBrowserException("/item.php?code=" . $code . "&id=" . $data['id']);
			}
		}
	}
	
	function replaceName($str, $type)
	{
		$itms = array();
		foreach( $str as $splt )
		{
			if( $splt != null && $splt != '' )
			{
				array_push($itms, trim($splt));
			}
		}
		
		$ints = array();
		foreach( $itms as $name )
		{
			switch( $type )
			{
				case 0:
					$cat = Category::getByName($name);
					if( $cat instanceof Category )
					{
						array_push($ints, $cat->categoryid);
					}
					break;
				case 1:
					$ing = Ingredient::getByName($name);
					if( $ing instanceof Ingredient )
					{
						array_push($ints, $ing->ingredientid);
					}
					break;
				case 2:
					$char = Characteristic::getByCharacteristic($name);
					if( $char instanceof Characteristic )
					{
						array_push($ints, $char->characteristicid);
					}
					break;
				default:
					break;
			}
		}
		
		return $ints;
	}
?>