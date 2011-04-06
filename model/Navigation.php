<?php
	require_once('connect.php');
	
	require_once('Role.php');
	
	class Navigation
	{
		public static function getByID($id)
		{
			global $db;
			$sql = "SELECT * FROM navigation WHERE navigationid=? ORDER BY sequence DESC";
			$values = array($id);
			$nav = $db->qwv($sql, $values);
			
			return Navigation::wrap($nav);
		}
		
		public static function getByRole($roleid)
		{
			global $db;
			$sql = "SELECT * FROM navigation WHERE roleid >= ? ORDER BY sequence DESC";
			$values = array($roleid);
			$nav = $db->qwv($sql, $values);
			
			return Navigation::wrap($nav);
		}
		
		public static function wrap($navs)
		{
			$navList = array();
			foreach( $navs as $nav )
			{
				array_push($navList, new Navigation($nav['navigationid'], $nav['display'], $nav['link'], $nav['icon'], $nav['roleid'], $nav['sequence']));
			}
			
			if( count( $navList ) > 1 )
			{
				return $navList;
			}
			elseif( count( $navList ) == 1 )
			{
				return $navList[0];
			}
			else
			{
				return false;
			}
		}

		private $navigationid;
		private $display;
		private $link;
		private $icon;
		private $roleid;
		private $sequence;
		
		public function __construct($id, $disp, $link, $ico, $role, $sequence)
		{
			$this->navigationid = $id;
			$this->display = $disp;
			$this->link = $link;
			$this->icon = $ico;
			$this->roleid = $role;
			$this->sequence = $sequence;
		}
		
		public function __get($var)
		{
			if( $var == 'role' )
			{
				return Role::getByID($this->roleid);
			}
			else
			{
				return $this->$var;
			}
		}
	}
?>