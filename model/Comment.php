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
			
			return new CommentObject($comment[0]);
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
			
			return new Comment($comment[0]);
		}

		private $commentid;
		private $comment;
		
		public function __construct($comment)
		{
			$this->commentid = $comment['commentid'];
			$this->comment = $comment['comment'];
		}
		
		public function __get($var)
		{
			return $this->$var;
		}
	}
?>
