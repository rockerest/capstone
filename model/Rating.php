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
		
		public static function wrap($ratings)
		{
			//package all ratings
			$rateList = array();
			foreach( $ratings as $rating )
			{
				//get comment from rating
				$comment = Comment::getByID($rating[0]['commentid']);
				//add to the package
				array_push($rateList, new Rating($rating[0], $comment));
			}
			
			return $rateList;
		}

		private $comment;
	
		private $ratingid;
		private $rating;
		private $userid;
		private $itemid;
		private $time;
		
		public function __construct($rating, $comment)
		{
			$this->comment = $comment;
			
			$this->ratingid = $rating['ratingid'];
			$this->rating = $rating['rating'];
			$this->userid = $rating['userid'];
			$this->itemid = $rating['itemid'];
			$this->time = $rating['time'];
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
