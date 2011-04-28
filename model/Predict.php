<?php
	require_once('connect.php');
	
	require_once('Item.php');
	require_once('View.php');
	require_once('Rating.php');

	class Predict extends Base
	{
		public static function similar($item)
		{
			//make sure $item is an object
			if( !($item instanceof Item) )
			{
				if( is_integer($item) )
				{
					$item = Item::getByID($item);
				}
				else
				{
					return false;
				}
			}
			
			$CHARADD = .1;//each matching characteristic = .1
			$CATADD = .05;//each matching category level = .05
			$VOADD = .025;//a user viewed one and ordered other = .025
			
			$UGTMADD = -.5;//more unmatched characteristics than matched = -.5
			$NOMATCHTOPLVLADD = -.25;//top level category doesn't match = -.25
			
			$chars = Base::toArray($item->characteristics);
			$views = Base::toArray(View::getByItem($item->itemid));
			
			//yes that's right, go get ALL of the items that have ANY of the characteristics
			$items = array();
			foreach( $chars as $char )
			{
				$itms = Base::toArray(Item::getByCharacteristic($char->characteristicid));
				$items = array_merge($items, $itms);
			}
			
			//now go get all the items in the category chain
			$numbers = explode('.', $item->category->number);
			$itms = array();
			while( count($numbers) > 0 )
			{
				$newtms = Base::toArray(Item::getByCategorySearch(implode('.',$numbers).'%')); //mmm, delicious Newtms.
				$itms = array_merge($itms, array_diff($newtms, $itms));
				array_pop($numbers);
			}
			
			//itemset is all chars + all categories
			$items = array_merge($items, $itms);
			$similarities = array();
			foreach( $items as $itm )
			{
				$charcount['num'] = 0;
				$charcount['match'] = 0;
				$itch = Base::toArray($itm->characteristics);
				foreach( $itch as $ch )
				{
					if( in_array($ch, $chars) )
					{
						$charcount['match']++;
					}
					$charcount['num']++;
				}
				
				$itmNum = explode('.', $item->category->number);
				$thisNum = explode('.', $itm->category->number);
				$catcount['topmatch'] = false;
				$catcount['num'] = 0;
				$catcount['match'] = 0;
				for($i = 0; $i<count($itmNum); $i++)
				{
					if( $itmNum[$i] == $thisNum[$i] )
					{
						if( $i == 0 )
						{
							$catcount['topmatch'] = true;
						}
						$catcount['match']++;
					}
					$catcount['num']++;
				}
				
				$similarity = 0;
				if( ($charcount['num'] / 2) > $charcount['match'] )
				{
					$similarity += $UGTMADD;
				}
				if( !$catcount['topmatch'] )
				{
					$similarity += $NOMATCHTOPLVLADD;
				}
				$similarity += ($CATADD * $catcount['match']);
				$similarity += ($CHARADD * $charcount['match']);
				
				$similarities = array_merge($similarities, array($itm->itemid => array("characteristic" => $charcount, "category" => $catcount, "similarity" => $similarity)));
			}
			
			return $similarities;
		}
		
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
		
		public function recommend()
		{
		}
	}
?>