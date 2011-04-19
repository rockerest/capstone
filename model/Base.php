<?php
	class Base
	{
		public function sendback($objects)
		{
			if( count( $objects ) > 1 )
			{
				return $objects;
			}
			elseif( count( $objects ) == 1 )
			{
				return $objects[0];
			}
			else
			{
				return false;
			}
		}
	}
?>