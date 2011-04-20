<?php
	set_include_path('../backbone:../global:../jquery:../components:../content:../images:../model:../render:../scripts:../styles');
	require_once('RedirectBrowserException.php');
	require_once('User.php');
	require_once('Order.php');

	require_once('Session.php');
	setSession(0, '/');
	
	$json = isset($_POST['json']) ? $_POST['json'] : null;
	
	if( !isset($_SESSION['userid']) || $_SESSION['userid'] == null || $_SESSION['userid'] < 1 )
	{
		throw new RedirectBrowserException("../login.php?code=10");
	}
	
	if( $json != null )
	{
		$post = json_decode($json, true);
		$id = $post['id'];
		$comment = $post['comment'];
		$modifiers = $post['modifiers'];
		
		$editable = Order::getForStatusesByUser($_SESSION['userid'], array(1, 2, 3));
		
		if( $editable )
		{
			//add to pending, or the most recent submitted
			if( $editable instanceof Order )
			{
				$word = $editable->statusid == 1 ? "pending" : "current";
				if( $editable->addItem($id, $comment, $modifiers) )
				{
					print jsonify(true, "The item was added to your " . $word . " order.", json_encode(array("items" => count($editable->items))));
				}
				else
				{
					print jsonify(false, "The item could not be added to your " . $word . " order.");
				}
			}
			else
			{
				$pref = null;
				foreach( $editable as $order )
				{
					if( $order->statusid == 1 )
					{
						if( $pref->statusid == 1 )
						{
							if( $pref->time < $order->time )
							{
								$pref = $order;
							}
						}
						else
						{
							$pref = $order;
						}
					}
					else
					{
						if( $pref->statusid != 1 )
						{
							if( $pref->time < $order->time )
							{
								$pref = $order;
							}
						}
					}
				}
				
				$word = $pref->statusid == 1 ? "pending" : "current";
				
				if( $pref->addItem($id, $comment, $modifiers) )
				{
					print jsonify(true, "The item was added to your " . $word . " order.", json_encode(array("items" => count($pref->items))));
				}
				else
				{
					print jsonify(false, "The item could not be added to your " . $word . " order.");
				}
			}
		}
		else
		{
			$no = Order::create($_SESSION['umbrella']['tableid'], $_SESSION['userid']);
			if( $no )
			{
				if( $no->addItem($id, $comment, $modifiers) )
				{
					print jsonify(true, "The item was added to a new order.", json_encode(array("items" => count($no->items))));
				}
				else
				{
					print jsonify(false, "A new order was created, but the item could not be added.");
				}
			}
			else
			{
				print jsonify(false, "The system was unable to create a new order to submit this item.");
			}
		}
	}
	else
	{
		print jsonify(false, "There was an error adding this item to your order.");
	}
	
	function jsonify($stat, $msg, $data)
	{
		$ret = array(	"status" => $stat,
						"message" => $msg,
						"data" => $data
					);
		return json_encode($ret);
	}
?>