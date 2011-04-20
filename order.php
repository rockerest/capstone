<?php
	set_include_path('backbone:components:content:scripts:styles:images:model:render');
	
	require_once('Page.php');
	require_once('Template.php');
	require_once('Order.php');
	require_once('User.php');
	require_once('Session.php');
	setSession(0, '/');
	
	$page = new Page(0, "OrderUp - Past, Present, and Future Orders");
	$tmpl = new Template();
	
	$page->run();
	
	if( isset($_SESSION['userid']) && $_SESSION['userid'] > 0 )
	{
		$id = $_SESSION['userid'];
	}
	else
	{
		$tblUser = User::getByTable($_SESSION['umbrella']['tableid']);
		$id = $_SESSION['userid'] = $tblUser->userid;
	}
	$pending = Order::getForStatusesByUser($id, array(1));
	
	if( is_array($pending) )
	{
		$tmpl->pending = $pending;
	}
	elseif( $pending instanceof Order )
	{
		$tmpl->pending = array($pending);
	}
	
	$html = $tmpl->build('order.html');
	$css = $tmpl->build('order.css');
	$js = $tmpl->build('order.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'order'
											),
						'js' => $js
						);

	print $page->build($appContent);
?>