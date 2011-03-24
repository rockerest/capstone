<?php
	require_once('connect.php');
	
	require_once('Item.php');

	class Predict
	{
		private $user;
		public function __construct($user)
		{
			$this->user = $user;
		}
		
		public function users()
		{
		}
		
		public function items()
		{
		}
		
		public function rating( $item )
		{
			if( $item instanceof Item )
			{
				$it = $item;
			}
			elseif( is_integer( $item ) )
			{
				$it = Item::getByID( $item );
			}
			else
			{
				return false;
			}
		}
		
		private function normalize( $chars )
		{
		}
	}
?>