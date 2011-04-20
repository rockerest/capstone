<?php
	require_once('connect.php');
	require_once('Status.php');
	require_once('User.php');
	require_once('Table.php');
	require_once('Order_Item.php');
	
	class Order extends Base
	{
		public static function getByID($id)
		{
			global $db;
			$sql = "SELECT * FROM orders WHERE orderid=?";
			$values = array($id);
			$order = $db->qwv($sql, $values);
			return Order::wrap($order);
		}
		
		public static function getByUser($userid)
		{
			global $db;
			$sql = "SELECT * FROM orders WHERE userid=?";
			$values = array( $userid );
			$orders = $db->qwv($sql, $values);
			return Order::wrap($orders);
		}
<<<<<<< HEAD
	
		//update an order by id, to a new status
		public static function updateByID($id, $newstatus)
		{
			global $db;
			$orderSQL = "UPDATE orders SET statusid=? WHERE orderid=?";
			$values = array($newstatus, $id);
			return $db->qwv($orderSQL, $values);
		}
			
=======
		
		//where $statuses is an array of ints: array(4, 9);
		public static getForStatusesByUser($userid, $statuses)
		{
			$prelimOrders = Order::getByUser($userid);
			if( $prelimOrders instanceof Order && in_array($prelimOrders->statusid, $statuses, true) )
			{
				return $prelimOrders;
			}
			else
			{
				if( is_array($prelimOrders) )
				{
					$tmp = array();
					foreach( $prelimOrders as $order )
					{
						if( in_array($order->statusid, $statuses, true) )
						{
							array_push($tmp, $order);
						}
					}
					
					return sendback($tmp);
				}
				else
				{
					return false;
				}
			}
		}

>>>>>>> 0c39751e8be7046851964bf671e5cbadd0b0d63f
		public static function getActiveByUser($userid)
		{
			global $db;
			$sql = "SELECT orderid FROM orders WHERE userid=? AND statusid!=4 AND statusid!=9";
			$values = array($userid);
			$orders = $db->qwv($sql, $values);
			return Order::wrap($orders);
		}
		
		public static function getAllActive()
		{
			global $db;
			$sql = "SELECT * FROM orders WHERE statusid!=4 AND statusid!=9";
			$values = array($userid);
			$orders = $db->qwv($sql, $values);
			return Order::wrap($orders);
		}
		
		public static function getAllInactive()
		{
			global $db;
			$sql = "SELECT * FROM orders WHERE statusid=4 OR statusid=9";
			$values = array($userid);
			$orders = $db->qwv($sql, $values);
			return Order::wrap($orders);
		}
		
		public static function getByUserFavorites($userid)
		{
			global $db;
			$sql = "SELECT * FROM orders WHERE orderid IN (SELECT orderid FROM users_have_favorite_orders WHERE userid=?)";
			$values = array($userid);
			$orders = $db->qwv($sql, $values);
			return Order::wrap($orders);
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
				array_push($orderObs, new Order($order['orderid'], $order['time'], $order['specialComment'], $order['tableid'], $order['userid'], $order['statusid']));
			}
			
			return sendback($orderObs);
		}
		
		private $orderid;
		private $time;
		private $specialComment;
		
		private $tableid;
		private $userid;
		private $statusid;
		
		public function __construct($orderid, $time, $specialComment, $tableid, $userid, $statusid)
		{
			$this->orderid = $orderid;
			$this->time = $time;
			$this->specialComment = $specialComment;
		
			$this->tableid = $tableid;
			$this->userid = $userid;
			$this->statusid = $statusid;
		}
		
		public function __get($var)
		{
			if( $var == 'table' )
			{
				return Table::getByID($this->tableid);
			}
			elseif( $var == 'user' )
			{
				return User::getByID($this->userid);
			}
			elseif( $var == 'status' )
			{
				return Status::getByID($this->statusid);
			}
			elseif( $var == 'items' )
			{
				return Order_Item::getByOrder($this->orderid);
			}
			else
			{
				return $this->$var;
			}
		}
		
		public function __set($name, $val)
		{
			if( $name == 'status' || $name == 'specialComment' )
			{
				$this->$name = $val;
				return $this->save();
			}
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
				$values = array($this->userid, $this->orderid);
				$fav = $db->qwv($sql, $values);
				if( count($fav) > 0 )
				{
					return true;
				}
				else
				{
					$sql = "INSERT INTO users_have_favorite_orders (userid, orderid) VALUES (?, ?)";
					$values = array($this->userid, $this->orderid);
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
				$values = array($this->userid, $this->orderid);
				$fav = $db->qwv($sql, $values);
				if( count($fav) == 0 )
				{
					return true;
				}
				else
				{
					$sql = "DELETE FROM users_have_favorite_orders WHERE userid=? AND orderid=? LIMIT 1";
					$values = array($this->userid, $this->orderid);
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

		public function save()
		{
			global $db;
			if( $this->orderid == null )
			{
				//if order is new and needs to be inserted
				$sql = "INSERT INTO orders (tableid, userid, statusid, time, specialComment) VALUES (?, ?, ?, ?, ?)";
				$values = array(	$this->tableid,
									$this->userid,
									$this->statusid,
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
				$values = array(	$this->statusid,
									$this->specialComment,
									$this->orderid
								);
				$db->qwv($sql, $values);
				
				return $db->stat() ? $this : false;
			}
		}
	}
?>
