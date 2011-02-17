<?php
	set_include_path('backbone:components:content:scripts:styles:images');
	
	require_once('Page.php');
	require_once('Template.php');
	require_once('Database.php');
	require_once('capstone.db');
	
	$submit = isset( $_GET['submit'] ) ? $_GET['submit'] : 0;

	$page = new Page(0, "OrderUp - Upload CSV");
	$db = new Database($user, $pass, $dbname, $host, 'mysql');
	$tmpl = new Template();
	
	$page->run();
	
	if( $submit != 1 )
	{
		$html = $tmpl->build('upload.html');
		//$css = $tmpl->build('upload.css');
		//$js = $tmpl->build('upload.js');
	}
	else
	{
		$info = pathinfo( $_FILES['file']['name'] );
		$file = $info['filename'];
		if( $info['extension'] != 'csv' )
		{
			//error
		}
		else
		{
			move_uploaded_file( $_FILES['file']['tmp_name'], $file . ".csv" );
			$fh = fopen( $file.".csv",r );
			
			$lines = array();
			while( ( $data = fgetcsv( $fh, 1000, "," ) ) !== FALSE )
			{
				$thirt = array_slice( $data, 0, 12 );
				array_push( $lines, $thirt );
			}
			
			array_shift( $lines );
			array_shift( $lines );
			
			if( count( $lines ) > 0 )
			{
				$last = -1;
				foreach( $lines as $row )
				{
					if( $row[0] == '' && $last == -1 )
					{
						//error out, bitch
						//you can't have a non-item atached to a non-item, dude.
					}
					elseif( $row[0] != '' && $last == -1)
					{
						//starting a new item
						//--------------------
						//find the category.  If it doesn't exist, create it.
						$sql = "SELECT * FROM categories WHERE LCASE(name)=? OR LCASE(number)=?";
						$values = array(strtolower($row[6]), strtolower($row[7]));
						$res = $db->qwv($sql, $values);
						$count = count($res);
						
						if( $count != 0 || $count != 1 )
						{
							//you managed to name two different categories.  You are teh suckorz.
						}
						else
						{
							$catid = -1;
							if( $count == 0 )
							{
								$sql = "INSERT INTO categories (name, number) VALUES (?, ?)";
								$values = array($row[6], $row[7]);
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
							$values = array($catid, $row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
							$db->qwv($sql, $values);
							if( $db->stat() )
							{
								$last = $db->last();
							}
							else
							{
								$last = -1;
							}
						}
					}
					elseif( $row[0] == '' && $last != -1)
					{
						//adding stuff to an item
						if( $row[8] != '' )
						{
							$ingid = -1;
							//ingredients
							//check to see if that ingredient already exists
							//Items can be the "same" but have different vegetarian status, etc. etc., so be strict about matching
							$sql = "SELECT * FROM ingredients WHERE LCASE(name)=?, LCASE(isVegetarian)=?, LCASE(isAllergenic)=?, LCASE(canBeSide)=?";
							$values = array( strtolower($row[8]), strtolower($row[9]), strtolower($row[10]), strtolower($row[11]) );
							$res = $db->qwv($sql, $values);
							
							$num = count($res);
							
							if( $num == 1 )
							{
								$ingid = $res[0]['ingredientid'];
							}
							elseif( $num == 0 )
							{
								$sql = "INSERT INTO ingredients (name, isVegetarian, isAllergenic, canBeSide) VALUES (?, ?, ?, ?)";
								$values = array($row[8], $row[9], $row[10], $row[11]);
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
							}
						}
						elseif( $row[12] != '' )
						{
							//characteristics
							//check to see if that characteristic already exists...
							$charid = -1;
							$sql = "SELECT * FROM characteristics WHERE LCASE(characteristic)=?";
							$values = array( strtolower($row[12]) );
							$res = $db->qwv($sql, $values);
							
							$num = count($res);
							
							if( $num == 1 )
							{
								$charid = $res[0]['characteristicid'];
							}
							elseif( $num == 0 )
							{
								$sql = "INSERT INTO characteristics (characteristic) VALUES (?)";
								$values = array($row[12]);
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
							}
						}
						else
						{
							//fail.
						}
					}
				}
			}
		}
		$html = $tmpl->build('csvresult.html');
		//$css = $tmpl->build('csvresult.css');
		//$js = $tmpl->build('csvresult.js');
	}
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	$css,
						'js' => $js
						);

	print $page->build($appContent);
	
?>