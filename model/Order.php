<<<<<<< HEAD
<?php
	require_once('connect.php');
	require_once('Status.php');
	require_once('User.php');
	require_once('Table.php');
	require_once('Order_Item.php');
	
	class Order
	{
		public static function getByID($id)
		{
			global $db;
			$sql = "SELECT * FROM orders WHERE orderid=?";
			$values = array($id);
			$order = $db->qwv($sql, $values);
			$ord = Order::wrap($order);
			
			if( count($ord) > 0 )
			{
				return $ord[0];
			}
			else
			{
				return false;
			}
		}
		
		//
		//	Gets active orders by User
		//
		public static function getActiveByUser($userid)
		{
			global $db;
			$sql = "SELECT orderid FROM orders WHERE userid=? AND statusid<9";
			$values = array($userid);
			$orders = $db->qwv($sql, $values);
			return $orders[0];
		}
		
		public static function getByUserFavorites($userid)
		{
			global $db;
			$sql = "SELECT * FROM orders WHERE orderid IN (SELECT orderid FROM users_have_favorite_orders WHERE userid=?)";
			$values = array($userid);
			$orders = $db->qwv($sql, $values);
			$order = Order::wrap($orders);
			
			if( count($order) > 0 )
			{
				return $order;
			}
			else
			{
				return false;
			}
		}
		
		public static function create($tableid, $userid)
		{
			$order['time'] = time();
			$ord = new Order($order, null, Table::getByID($tableid), User::getByID($userid), null);
			return $ord->save();
		}
		
		public static function wrap($orders)
		{
			$orderObs = array();
			foreach($orders as $order)
			{
				$table = Table::getByID($order['tableid']);
				$user = User::getByID($order['userid']);
				$status = Status::getByID($order['statusid']);
				$items = Order_Item::getByOrder($order['orderid']);
				array_push($orderObs, new Order($order, $items, $table, $user, $status));
			}
			return $orderObs;
		}
		
		private $orderid;
		private $time;
		private $specialComment;
		
		private $table;
		private $user;
		private $status;
		private $items;
		
		public function __construct($order, $itm, $tbl, $usr, $stat)
		{
			$this->orderid = isset($order['orderid']) ? $order['orderid'] : null;
			$this->time = $order['time'];
			$this->specialComment = isset($order['specialComment']) ? $order['specialComment'] : null;
		
			$this->table = $tbl;
			$this->user = $usr;
			$this->status = isset($stat) ? $stat : Status::getByStatus('received');
			$this->items = isset($itm) ? $itm : null;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
		
		public function status($statusid)
		{
			$this->status = Status::getByID($statusid);
			return $this->save();
		}
				
		public function comment($string)
		{
			$this->specialComment = $string;
			return $this->save();
		}
		
		public function addItem($itemid, $comment, $modifiers)
		{
			global $db;
			$sql = "INSERT INTO order_items (orderid, itemid, isCustomized, specialComment) VALUES (?, ?, ?, ?)";
			$values = array($this->orderid, $itemid, is_array($modifiers), $comment);
			$db->qwv($sql, $values);
			
			if( $db->stat() )
			{
				$order_itemid = $db->last();
				if( is_array($modifiers) )
				{
					$db2 = $db;
					
					$sql2 = "SELECT * FROM customizations WHERE order_itemid=? AND modifierid=? AND ingredientid=?";
					$sql = "INSERT INTO customizations (order_itemid, modifierid, ingredientid) VALUES (?, ?, ?)";
					
					$db->prep($sql);
					$db2->prep($sql2);
					//Okay, this is probably a little unclear.
					//Because of the complexity of adding an item to an order,
					//	here's how you have to call this function (addItem) if you
					//	want to include ingredient cusotmizations with the item:
					//		First, gather your normal data:
					//										itemid that's being added to the order
					//										comment on the item ("Please cut into 946 peices.").  If none, use NULL.
					//		Then, pull all the customizations into an array like this:
					//							$modifiers = array(
					//												array(	'ingredientid' => TheIDOfTheIngredient,
					//														'modifiers' => array(	ModifierIDForIngredient,
					//																				OtherModifierIDForIngredient,
					//																				...
					//																			)
					//														),
					//												array(	//Another Array for Another Ingredient
					//														)
					//												);
					//		Then, call the function like this:
					//											addItem($itemid, $comment, $modifiers);
					foreach( $modifiers as $ing )
					{
						$ingredientid = $ing['ingredientid'];
						$modifiers = $ing['modifiers'];
						foreach( $modifiers as $modid )
						{
							//check to see if that customization already exists for the ingredient
							$values = array( $order_itemid, $modid, $ingredientid );
							
							$cust = $db2->qwv(null, $values);
							if( count($cust) == 0 )
							{
								$db->qwv( null, $values );
								if( !$db->stat() )
								{
									//weeeell, damn.
								}
							}
						}
					}
				}
				
				$this->refresh();
				return $this;
			}
			else
			{
				return false;
			}
		}
		
		public function favorite( $switch = true )
		{
			global $db;
			if( $switch )
			{
				//add this order as a favorite for the user
				$sql = "SELECT * FROM users_have_favorite_orders WHERE userid=? AND orderid=?";
				$values = array($this->user->userid, $this->orderid);
				$fav = $db->qwv($sql, $values);
				if( count($fav) > 0 )
				{
					return true;
				}
				else
				{
					$sql = "INSERT INTO users_have_favorite_orders (userid, orderid) VALUES (?, ?)";
					$values = array($this->user->userid, $this->orderid);
					$db->qwv($sql, $values);
					
					if( $db->stat() )
					{
						return true;
					}
					else
					{
						return false;
					}
				}
			}
			else
			{
				//remove this order from the user's favorites
				$sql = "SELECT * FROM users_have_favorite_orders WHERE userid=? AND orderid=?";
				$values = array($this->user->userid, $this->orderid);
				$fav = $db->qwv($sql, $values);
				if( count($fav) == 0 )
				{
					return true;
				}
				else
				{
					$sql = "DELETE FROM users_have_favorite_orders WHERE userid=? AND orderid=? LIMIT 1";
					$values = array($this->user->userid, $this->orderid);
					$db->qwv($sql, $values);
					
					if( $db->stat() )
					{
						return true;
					}
					else
					{
						return false;
					}
				}
			}
		}
		
		public function refresh()
		{
			//reload all order_items in case any were added/deleted since object instantiation
			$this->items = Order_Item::getByOrder($this->orderid);
		}
		
		public function save()
		{
			global $db;
			if( $this->orderid == null )
			{
				//if order is new and needs to be inserted
				$sql = "INSERT INTO orders (tableid, userid, statusid, time, specialComment) VALUES (?, ?, ?, ?, ?)";
				$values = array(	$this->table->tableid,
									$this->user->userid,
									$this->status->statusid,
									$this->time,
									$this->specialComment
								);
				$db->qwv($sql, $values);
				
				if( $db->stat() )
				{
					$this->orderid = $db->last();
					return $this;
				}
				else
				{
					return false;
				}
			}
			else
			{
				//if the order exists and just needs to be updated
				$sql = "UPDATE orders SET statusid=?, specialComment=? WHERE orderid=?";
				$values = array(	$this->status->statusid,
									$this->specialComment,
									$this->orderid
								);
				$db->qwv($sql, $values);
				
				return $db->stat() ? $this : false;
			}
		}
	}
?>
=======
<?php
	require_once('connect.php');
	require_once('Status.php');
	require_once('User.php');
	require_once('Table.php');
	require_once('Order_Item.php');
	
	class Order
	{
		public static function getByID($id)
		{
			global $db;
			$sql = "SELECT * FROM orders WHERE orderid=?";
			$values = array($id);
			$order = $db->qwv($sql, $values);
			$ord = Order::wrap($order);
			
			if( count($ord) > 0 )
			{
				return $ord[0];
			}
			else
			{
				return false;
			}
		}
		
		public static function getByUser($userid)
		{
			global $db;
			$sql = "SELECT * FROM orders WHERE userid=?";
			$values = array( $userid );
			$orders = $db->qwv($sql, $values);
			$order = Order::wrap($orders);
			
			if( count($order) > 0 )
			{
				return $order;
			}
			else
			{
				return false;
			}
		}
		
		public static function getByUserFavorites($userid)
		{
			global $db;
			$sql = "SELECT * FROM orders WHERE orderid IN (SELECT orderid FROM users_have_favorite_orders WHERE userid=?)";
			$values = array($userid);
			$orders = $db->qwv($sql, $values);
			$order = Order::wrap($orders);
			
			if( count($order) > 0 )
			{
				return $order;
			}
			else
			{
				return false;
			}
		}
		
		public static function create($tableid, $userid)
		{
			$order['time'] = time();
			$ord = new Order($order, null, Table::getByID($tableid), User::getByID($userid), null);
			return $ord->save();
		}
		
		public static function wrap($orders)
		{
			$orderObs = array();
			foreach($orders as $order)
			{
				$table = Table::getByID($order['tableid']);
				$user = User::getByID($order['userid']);
				$status = Status::getByID($order['statusid']);
				$items = Order_Item::getByOrder($order['orderid']);
				array_push($orderObs, new Order($order, $items, $table, $user, $status));
			}
			return $orderObs;
		}
		
		private $orderid;
		private $time;
		private $specialComment;
		
		private $table;
		private $user;
		private $status;
		private $items;
		
		public function __construct($order, $itm, $tbl, $usr, $stat)
		{
			$this->orderid = isset($order['orderid']) ? $order['orderid'] : null;
			$this->time = $order['time'];
			$this->specialComment = isset($order['specialComment']) ? $order['specialComment'] : null;
		
			$this->table = $tbl;
			$this->user = $usr;
			$this->status = isset($stat) ? $stat : Status::getByStatus('received');
			$this->items = isset($itm) ? $itm : null;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
		
		public function status($statusid)
		{
			$this->status = Status::getByID($statusid);
			return $this->save();
		}
				
		public function comment($string)
		{
			$this->specialComment = $string;
			return $this->save();
		}
		
		public function addItem($itemid, $comment, $modifiers)
		{
			global $db;
			$sql = "INSERT INTO order_items (orderid, itemid, isCustomized, specialComment) VALUES (?, ?, ?, ?)";
			$values = array($this->orderid, $itemid, is_array($modifiers), $comment);
			$db->qwb($sql, $values);
			
			if( $db->stat() )
			{
				$order_itemid = $db->last();
				if( is_array($modifiers) )
				{
					$db2 = $db;
					
					$sql2 = "SELECT * FROM customizations WHERE order_itemid=? AND modifierid=? AND ingredientid=?";
					$sql = "INSERT INTO customizations (order_itemid, modifierid, ingredientid) VALUES (?, ?, ?)";
					
					$db->prep($sql);
					$db2->prep($sql2);
					//Okay, this is probably a little unclear.
					//Because of the complexity of adding an item to an order,
					//	here's how you have to call this function (addItem) if you
					//	want to include ingredient cusotmizations with the item:
					//		First, gather your normal data:
					//										itemid that's being added to the order
					//										comment on the item ("Please cut into 946 peices.").  If none, use NULL.
					//		Then, pull all the customizations into an array like this:
					//							$modifiers = array(
					//												array(	'ingredientid' => TheIDOfTheIngredient,
					//														'modifiers' => array(	ModifierIDForIngredient,
					//																				OtherModifierIDForIngredient,
					//																				...
					//																			)
					//														),
					//												array(	//Another Array for Another Ingredient
					//														)
					//												);
					//		Then, call the function like this:
					//											addItem($itemid, $comment, $modifiers);
					foreach( $modifiers as $ing )
					{
						$ingredientid = $ing['ingredientid'];
						$modifiers = $ing['modifiers'];
						foreach( $modifiers as $modid )
						{
							//check to see if that customization already exists for the ingredient
							$values = array( $order_itemid, $modid, $ingredientid );
							
							$cust = $db2->qwv(null, $values);
							if( count($cust) == 0 )
							{
								$db->qwv( null, $values );
								if( !$db->stat() )
								{
									//weeeell, damn.
								}
							}
						}
					}
				}
				
				$this->refresh();
				return $this;
			}
			else
			{
				return false;
			}
		}
		
		public function favorite( $switch = true )
		{
			global $db;
			if( $switch )
			{
				//add this order as a favorite for the user
				$sql = "SELECT * FROM users_have_favorite_orders WHERE userid=? AND orderid=?";
				$values = array($this->user->userid, $this->orderid);
				$fav = $db->qwv($sql, $values);
				if( count($fav) > 0 )
				{
					return true;
				}
				else
				{
					$sql = "INSERT INTO users_have_favorite_orders (userid, orderid) VALUES (?, ?)";
					$values = array($this->user->userid, $this->orderid);
					$db->qwv($sql, $values);
					
					if( $db->stat() )
					{
						return true;
					}
					else
					{
						return false;
					}
				}
			}
			else
			{
				//remove this order from the user's favorites
				$sql = "SELECT * FROM users_have_favorite_orders WHERE userid=? AND orderid=?";
				$values = array($this->user->userid, $this->orderid);
				$fav = $db->qwv($sql, $values);
				if( count($fav) == 0 )
				{
					return true;
				}
				else
				{
					$sql = "DELETE FROM users_have_favorite_orders WHERE userid=? AND orderid=? LIMIT 1";
					$values = array($this->user->userid, $this->orderid);
					$db->qwv($sql, $values);
					
					if( $db->stat() )
					{
						return true;
					}
					else
					{
						return false;
					}
				}
			}
		}
		
		public function refresh()
		{
			//reload all order_items in case any were added/deleted since object instantiation
			$this->items = Order_Item::getByOrder($this->orderid);
		}
		
		public function save()
		{
			global $db;
			if( $this->orderid == null )
			{
				//if order is new and needs to be inserted
				$sql = "INSERT INTO orders (tableid, userid, statusid, time, specialComment) VALUES (?, ?, ?, ?, ?)";
				$values = array(	$this->table->tableid,
									$this->user->userid,
									$this->status->statusid,
									$this->time,
									$this->specialComment
								);
				$db->qwv($sql, $values);
				
				if( $db->stat() )
				{
					$this->orderid = $db->last();
					return $this;
				}
				else
				{
					return false;
				}
			}
			else
			{
				//if the order exists and just needs to be updated
				$sql = "UPDATE orders SET statusid=?, specialComment=? WHERE orderid=?";
				$values = array(	$this->status->statusid,
									$this->specialComment,
									$this->orderid
								);
				$db->qwv($sql, $values);
				
				return $db->stat() ? $this : false;
			}
		}
	}
?>
>>>>>>> 61bddadce393fed7e17f5339b5a0a24f73bfbd4d
