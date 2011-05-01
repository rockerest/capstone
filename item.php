<?php
	set_include_path('backbone:components:content:render:model:scripts:styles:images');
	
	require_once('Item.php');
	require_once('View.php');
	require_once('Page.php');
	require_once('Template.php');
	require_once('Image.php');
	require_once('RedirectBrowserException.php');
	require_once('Breadcrumb.php');
	
	$tmpl = new Template();

	$tmpl->itemid = isset($_GET['id']) ? $_GET['id'] : -1;
	$tmpl->action = isset($_GET['action']) ? $_GET['action'] : null;
	$tmpl->code = isset($_GET['code']) ? $_GET['code'] : -1;
	$tmpl->item = Item::getByID($tmpl->itemid);
	
	switch( $tmpl->action )
	{
		case 'add':
					$page = new Page(1, "OrderUp - Create New Item");
					break;
		case 'edit':
					checkLogin(array(1,2));
					$page = new Page(1, "OrderUp - Edit Existing Item");
					$img = new Image("../sub_cap/images/".$tmpl->item->image, "../sub_cap/images/" . preg_replace('#(\.[\w]+)#', '_50x50$1', $tmpl->item->image));
					if( !$img->check() )
					{
						$img->resize(50, 50, false);
						$img->output();
						$img->clean();
					}
					break;
		case 'delete':
					checkLogin(array(1,2));
					$page = new Page(1, "OrderUp - Delete Existing Item");
					break;
		case null:
		default:
					$page = new Page(1, "OrderUp - View Item");
					break;
		
	}
	
	if( !$tmpl->item )
	{
		if( $tmpl->code == -1 && $tmpl->action == null )
		{
			$tmpl->code = 0;
		}
		unset($tmpl->item);
	}
	else
	{
		$tmpl->breadcrumb = new Breadcrumb('item', $tmpl->item->itemid);
		View::add($_SESSION['userid'], $tmpl->item->itemid);
	}
	
	switch( $tmpl->code )
	{
		case 0:
			$tmpl->message = "Could not find item.";
			$tmpl->css = "error";
			break;
		case 10:
			$tmpl->message = "Deleting item succeeded.";
			$tmpl->css = "okay";
			break;
		case 11:
			$tmpl->message = "Deleting item failed.";
			$tmpl->css = "error";
			break;
		case 13:
			$tmpl->message = "Adding item succeeded.";
			$tmpl->css = "okay";
			break;
		default:
			break;
	}
	
	$page->run();
	
	$html = $tmpl->build('item.html');
	$css = $tmpl->build('item.css');	
	$js = $tmpl->build('item.js');
	
	$appContent = array(
						'html'	=>	$html,
						'css'	=>	array(	'code' => $css,
											'link' => 'item'
											),
						'js' => $js
						);

	print $page->build($appContent);
	
	function checkLogin($roles)
	{
		if( !in_array($_SESSION['roleid'], $roles) )
		{
			throw new RedirectBrowserException("login.php?code=10");
		}
	}
?>