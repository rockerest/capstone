<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('RedirectBrowserException.php');
	require_once('Item.php');
	require_once('Category.php');
	require_once('Characteristic.php');
	require_once('Ingredient.php');

	require_once('Session.php');
	setSession(0, '/');
	
	$id = isset($_GET['id']) ? $_GET['id'] : null;
	$name = isset($_POST['name']) ? $_POST['name'] : null;
	$desc = isset($_POST['desc']) ? $_POST['desc'] : null;
	$cat = isset($_POST['cat']) ? $_POST['cat'] : null;
	$img = isset($_POST['image']) ? $_POST['image'] : null;
	$prep = isset($_POST['prep']) ? $_POST['prep'] : null;
	$lvl = isset($_POST['lvl']) ? true : false;
	$price = isset($_POST['price']) ? $_POST['price'] : null;
	$ing = isset($_POST['ing']) ? $_POST['ing'] : null;
	$char = isset($_POST['char']) ? $_POST['char'] : null;
	
	$fwd = isset($_GET['fwd']) ? $_GET['fwd'] : null;
	
	//clean the AS inputs
	$cat = replaceName(explode(',', $cat), 0);
	$ing = replaceName(explode(',', $ing), 1);
	$char = replaceName(explode(',', $char), 2);
	
	$data = array(
				'id' => $id,
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
	
	//check for item
	$item = Item::getByID($data['id']);
	if( !$item )
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
	
	if( $data['img'] != '' && $data['img'] != null )
	{
		$type = $_FILES['image']['type'];
		if( $type != 'image/png' && $type != 'image/gif' && $type != 'image/jpeg' )
		{
			kick(1, $data, 3);
		}	
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
	
	//if it's all okay data:
	
	if( $data['img'] != null && $data['img'] != '' )
	{
		//get the image name
		$fn = pathinfo( $_FILES['image']['name'] );
		$svFn = time() . "." . $fn['extension'];
		
		//get the image destination
		$cat = Category::getByID($data['cat'][0]);

		$cats = array($cat);
		while( $cat )
		{
			$cat = $cat->getParent();
			if( $cat )
			{
				array_push($cats, $cat);
			}
		}
		
		$cats = array_reverse($cats);
		
		$dest = "";
		foreach( $cats as $cat )
		{
			$dest .= $cat->name . "/";
		}
		
		$sysDest = "../images/" . $dest;
		
		//check image destination
		if ( !is_dir($sysDest) )
		{
			if( !mkdir($sysDest, 01755, true) )
			{
				//failed to create folder
				kick(1, $data, 14);
			}
			else
			{
				//set permissions (since a umask could mess up the mkdir)
				chmod($sysDest, 01755);
			}
		}
		
		//create full save location
		$svDBFn = $dest . $svFn;
		
		//move the image
		if( !move_uploaded_file( $_FILES['image']['tmp_name'], "../images/" . $svDBFn ) )
		{
			kick(1, $data, 15);
		}
		
		//save the new image
		$item->image = $svDBFn;
	}
	
	//set the category
	$item->categoryid = $data['cat'][0];
	
	//clear all of the ings/chars linked to the item
	//these need to be on a diff, so the database doesn't get hit so hard.
	//...I'll, uh, do that later.
	$ings = $item->ingredients;
	$chars = $item->characteristics;
	
	if( is_bool($ings) )
	{
		$ings = array();
	}
	elseif( is_object($ings) )
	{
		$ings = array($ings);
	}
	
	if( is_bool($chars) )
	{
		$chars = array();
	}
	elseif( is_object($chars) )
	{
		$chars = array($chars);
	}
	
	foreach( $ings as $ing )
	{
		$ing->deleteLink($data['id']);
	}
	
	foreach( $chars as $char )
	{
		$char->deleteLink($data['id']);
	}
	
	//add all the new ings/chars
	foreach( $data['ing'] as $ing )
	{
		$item->attach('ingredient', intval($ing));
	}
	
	foreach( $data['char'] as $char )
	{
		$item->attach('characteristic', intval($char));
	}
	
	//save all the other stuff.
	$item->price = floatval($data['price']);
	$item->hasCookLevels = $data['lvl'];
	$item->prepTime = $data['prep'];
	$item->description = $data['desc'];
	$item->name = $data['name'];
	
	kick(2, $data, 13);
	
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
				throw new RedirectBrowserException("/item.php?action=edit&code=" . $code . "&" . http_build_query($data));
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