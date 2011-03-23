<?php
	require_once('connect.php');
	
	class Comment
	{
		public static function getByID($id)
		{
			global $db;
			//get comment from database
			$commentSQL = "SELECT * FROM comments WHERE commentid=?";
			$values = array($id);
			$comment = $db->qwv($commentSQL, $values);
			
			return Comment::wrap($comment);
		}
		
		public static function getByRating($id)
		{
			global $db;
			//get rating from database
			$ratingSQL = "SELECT * FROM ratings WHERE ratingid=?";
			$values = array($id);
			$rating = $db->qwv($ratingSQL, $values);
			
			//get comment from database
			$commentSQL = "SELECT * FROM comments WHERE commentid=?";
			$values = array($rating[0]['commentid']);
			$comment = $db->qwv($commentSQL, $values);
			
			return Comment::wrap($comment);
		}
		
		public static function wrap($coms)
		{
			$comList = array();
			foreach( $coms as $com )
			{
				array_push($comList, new Comment($com['commentid'], $com['comment']));
			}
			
			if( count( $comList ) > 1 )
			{
				return $comList;
			}
			elseif( count( $comList ) == 1 )
			{
				return $comList[0];
			}
			else
			{
				return false;
			}
		}

		private $commentid;
		private $comment;
		
		public function __construct($commentid, $comment)
		{
			$this->commentid = $commentid;
			$this->comment = $comment;
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
