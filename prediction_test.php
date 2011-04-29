<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	require_once('Page.php');
	require_once('Template.php');
	require_once('Predict.php');
	require_once('Item.php');
	
	$page = new Page(0, "OrderUp - Prediction_test");
	$tmpl = new Template();
	$page->run();
	
	$suggested_item_objects = array();
	
	$suggested_items = isset($_GET['item']) ? Predict::similar(Item::getByName($_GET['item'])) : -1;
	var_dump($suggested_items);
	foreach($suggested_items as $suggested_item)
	{
		//var_dump($suggested_item);
		array_push($suggested_item_objects, Item::getByID($suggested_item['itemid'])); 
	}
	$tmpl->suggested_item_objects = $suggested_item_objects;
	
	
	$html = $tmpl->build('prediction_test.html');
	$css = $tmpl->build('reporting.css');
	$js = $tmpl->build('prediction_test.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'reporting'
											),
						'js' => $js
						);

	print $page->build($appContent);
	
?>