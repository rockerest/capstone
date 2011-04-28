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
		
		public function toArray($objects)
		{
			if( is_array($objects) )
			{
				return $objects;
			}
			elseif( is_object($objects) )
			{
				return array($objects);
			}
			else
			{
				return null;
			}
		}
	}
?>