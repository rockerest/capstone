<?php

set_include_path('backbone:components:content:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');
	require_once('Database.php');
	require_once('capstone.db');
	
	$submit = isset( $_GET['submit'] ) ? $_GET['submit'] : 0;	$last;

	$page = new Page(0, "OrderUp - Upload via Form");
	$db = new Database($user, $pass, $dbname, $host, 'mysql');
	$tmpl = new Template();
	
	$page->run();
	
	
	if( $submit != 1 )
	{
		$html = $tmpl->build('uploadForm.html');
		$css = $tmpl->build('uploadForm.css');
		//$js = $tmpl->build('upload.js');
	}
	else
	{
		
		if(($_POST['item_name']!='')&&($_POST['item_desc']!='')&&($_POST['item_price']!='')&&($_POST['item_preptime']!='')&&($_POST['item_hasCookLevels']!='')&&($_POST['item_ingredients']!='')&&($_POST['item_characteristics']!='')&&($_POST['item_category_name']!='')&&($_POST['item_category_number']!=''))
		{			//get the image file			$file_info = pathinfo( $_FILES['item_image']['name'] );			if(($file_info['extension']=='jpg')||($file_info['extension']=='png')||($file_info['extension']=='gif'))			{				$file_name = $file_info['filename'].".".$file_info['extension'];				move_uploaded_file( $_FILES['item_image']['tmp_name'], "images/".$file_name );			}			else			{				echo "this is not an image file we support.";			}
			$catid;
			$item_name = $_POST['item_name'];
			$item_desc = $_POST['item_desc'];
			$item_image = $file_name;
			$item_price = $_POST['item_price'];
			$item_preptime = $_POST['item_price'];
			$item_hasCookLevels = $_POST['item_hasCookLevels'];
			$item_ingredients = $_POST['item_ingredients'];
			$item_characteristics = $_POST['item_characteristics'];
			$item_category_name = $_POST['item_category_name'];
			$item_category_number = $_POST['item_category_number'];
			
			//split ingredients into ind ingredients
			$item_ingredient = explode(",", $item_ingredients);
			
			//same for chars
			$item_characteristic = explode(",", $item_characteristics);
			
			//if category does not exist, add it
				//find the category.  If it doesn't exist, create it.
						$sql = "SELECT * FROM categories WHERE LCASE(name)=? OR LCASE(number)=?";
						$values = array(strtolower($item_category_name), strtolower($item_category_number));
						$res = $db->qwv($sql, $values);
						$count = count($res);
						
						if( !($count != 0 || $count != 1 ))
						{
							echo "you managed to name two different categories.  You are teh suckorz. ".$count."<br />";
						}
						else
						{
							$catid = -1;
							if( $count == 0 )
							{
								$sql = "INSERT INTO categories (name, number) VALUES (?, ?)";
								$values = array($item_category_name, $item_category_number);
								$db->qwv($sql, $values);
								if( $db->stat() )
								{
									$catid = $db->last();
								}
								else
								{
									$catid = -1;
								}
							}
							elseif( $count == 1 )
							{
								$catid = $res[0]['categoryid'];
							}
							$sql = "INSERT INTO items (categoryid, name, description, image, price, prepTime, hasCookLevels) VALUES (?,?,?,?,?,?,?)";
							$values = array($catid, $item_name, $item_desc, $item_image, $item_price, $item_preptime, $item_hasCookLevels);
							$db->qwv($sql, $values);							if( $db->stat() )								{									$last = $db->last();								}
							echo "inserted into items<br />";
							print_r($values);
						}
						foreach($item_ingredient as $ingredient)
						{
							$ingid = -1;
							//explode ingredient
							$ingredient_info = explode("-", $ingredient);
							//ingredients
							//check to see if that ingredient already exists
							//Items can be the "same" but have different vegetarian status, etc. etc., so be strict about matching
							$sql = "SELECT * FROM ingredients WHERE LCASE(name)=?, LCASE(isVegetarian)=?, LCASE(isAllergenic)=?, LCASE(canBeSide)=?";
							$values = array( strtolower($ingredient_info[0]), strtolower($ingredient_info[1]), strtolower($ingredient_info[2]), strtolower($ingredient_info[3]) );
							$res = $db->qwv($sql, $values);
							
							$num = count($res);
							
							if( $num == 1 )
							{
								$ingid = $res[0]['ingredientid'];
							}
							elseif( $num == 0 )
							{
								$sql = "INSERT INTO ingredients (name, isVegetarian, isAllergenic, canBeSide) VALUES (?, ?, ?, ?)";
								$values = array($ingredient_info[0], $ingredient_info[1], $ingredient_info[2], $ingredient_info[3]);
								$db->qwv($sql, $values);
								if( $db->stat() )
								{
									$ingid = $db->last();
								}
								else
								{
									$ingid = -1;
								}
							}
							else
							{
								//WTF?
								//BBQ?
								$ingid = -1;
							}
						
							if( $ingid != -1 )
							{
								$sql = "INSERT INTO items_have_ingredients (itemid, ingredientid) VALUES (?, ?)";
								$values = array($last, $ingid);
								$db->qwv($sql, $values);
								echo "inserted into ingredients<br />";
								print_r($values);
							}
						}
						
						foreach($item_characteristic as $characteristic)
						{
						//characteristics
							//check to see if that characteristic already exists...
							$charid = -1;
							
							$sql = "SELECT * FROM characteristics WHERE LCASE(characteristic)=?";
							$values = array( strtolower($characteristic) );
							$res = $db->qwv($sql, $values);
							
							$num = count($res);
							
							if( $num == 1 )
							{
								$charid = $res[0]['characteristicid'];
							}
							elseif( $num == 0 )
							{
								$sql = "INSERT INTO characteristics (characteristic) VALUES (?)";
								$values = array($characteristic);
								$db->qwv($sql, $values);
								if( $db->stat() )
								{
									$charid = $db->last();
								}
								else
								{
									$charid = -1;
								}
							}
							else
							{
								//WTF?
								$charid = -1;
							}
							
							if( $charid != -1 )
							{
								$sql = "INSERT INTO items_have_characteristics (characteristicid, itemid) VALUES (?, ?)";
								$values = array($charid, $last);
								$db->qwv($sql, $values);
								echo "inserted into characteristics<br />";
								print_r($values);
							}
						}
			
		}
		else
		{
			echo "not everything filled in...";
		}
	}
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	$css,
						'js' => $js
						);

	print $page->build($appContent);
?>