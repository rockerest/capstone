<?php
	require_once('connect.php');
	require_once('Comment.php');
	
	class Rating
	{
		public static function getByID($id)
		{
			global $db;
			$ratingSQL = "SELECT * FROM ratings WHERE ratingid=?";
			$values = array($id);
			$rating = $db->qwv($ratingSQL, $values);
			
			return Rating::wrap($rating);
		}
		
		public static function getByItem($id)
		{
			global $db;
			$ratingSQL = "SELECT * FROM ratings WHERE itemid=?";
			$values = array($id);
			$ratings = $db->qwv($ratingSQL, $values);
			
			return Rating::wrap($ratings);
		}
		
		public static function getByUser($id)
		{
			global $db;
			$ratingSQL = "SELECT * FROM ratings WHERE userid=?";
			$values = array($id);
			$ratings = $db->qwv($ratingSQL, $values);
			
			return Rating::wrap($ratings);
		}
		
		public static function getByUserForItem($userid, $itemid)
		{
			global $db;
			$ratingSQL = "SELECT * FROM ratings WHERE userid=? AND itemid=?";
			$values = array($userid, $itemid);
			$ratings = $db->qwv($ratingSQL, $values);
			
			return Rating::wrap($ratings);
		}
		
		public static function wrap($ratings)
		{
			//package all ratings
			$rateList = array();
			foreach( $ratings as $rating )
			{
				array_push($rateList, new Rating($rating['ratingid'], $rating['rating'], $rating['userid'], $rating['itemid'], $rating['time'], $rating['commentid']));
			}
			
			if( count($rateList) > 1 )
			{
				return $rateList;
			}
			elseif( count($rateList) == 1 )
			{
				return $rateList[0];
			}
			else
			{
				return false;
			}
		}

		private $commentid;
	
		private $ratingid;
		private $rating;
		private $userid;
		private $itemid;
		private $time;
		
		public function __construct($ratingid, $rating, $userid, $itemid, $time, $commentid)
		{
			$this->commentid = $commentid;			
			$this->ratingid = $ratingid;
			$this->rating = $rating;
			$this->userid = $userid;
			$this->itemid = $itemid;
			$this->time = $time;
		}
		
		public function __get($var)
		{
			if( $var == 'comment' )
			{
				return Comment::getByID($this->commentid);
			}
			else
			{
				return $this->$var;
			}
		}
	}
?>
