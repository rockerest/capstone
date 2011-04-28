<?php
	require_once('connect.php');
	
	require_once('Item.php');
	require_once('Rating.php');

	class Predict extends Base
	{
		private $user;
		public function __construct($user)
		{
			$this->user = $user;
		}
		
		public function compare($one, $two)
		{
			if( !($one instanceof Item) )
			{
				if( is_integer($one) )
				{
					$one = Item::getByID($one);
				}
				else
				{
					return false;
				}
			}
			
			if( !($two instanceof Item) )
			{
				if( is_integer($two) )
				{
					$two = Item::getByID($two);
				}
				else
				{
					return false;
				}
			}
			
			$ratings[0] = Rating::getByUserForItem($this->user->userid, $one->itemid);
			$ratings[1] = Rating::getByUserForItem($this->user->userid, $two->itemid);
			$views[0] = View::getByUserForItem($this->user->userid, $one->itemid);
			$views[1] = View::getByUserForItem($this->user->userid, $two->itemid);
			
			//first, try to compare ratings
			if( $ratings[0] || $ratings[1] )
			{
				if( $ratings[0] && $ratings[1] )
				{
				}
				else
				{
					//one item doesn't have ratings
				}
			}
			else
			{
				//if the user has no ratings, try to compare number of views
				//people are more likely to view something if they want it (even subconsciously)
				//so the comparison of views could offer a rough estimate
				if( $views[0] || $views[1] )
				{
					if( $views[0] && $views[1] )
					{
					}
					else
					{
						//one of the items hasn't been viewed
					}
				}
				else
				{
					//The user has no ratings for either item, and no views for either item.
					//The only option is to return an unknown for the direct comparison
				}
			}
			
			
		}
	}
?>