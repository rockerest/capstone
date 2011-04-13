<?php
	require_once('Template.php');
	require_once('Session.php');
	setSession(0,"/");
	
	require_once('Item.php');
	require_once('Category.php');
	
	class Breadcrumb
	{
		private $type;
		private $id;
		
		private $path;
		
		public function __construct($type, $id)
		{
			$this->type = $type;
			$this->id = $id;

			if( $type == 'item' )
			{
				$item = Item::getByID($id);
				if( $item instanceof Item )
				{
					$cat = $item->category;
					
					$categories = array($cat);
					while( $cat->getParent() instanceof Category )
					{
						$cat = $cat->getParent();
						array_push($categories, $cat);
					}
					$categories = array_reverse($categories);
					
					$this->path = '<a href="menu.php" class="button"><span class="icon book"></span>Menu</a>';
					foreach( $categories as $cat )
					{
						$this->path .= '|<a href="menu.php?cat=' . $cat->categoryid . '" class="button">' . $cat->name . '</a>';
					}
					$this->path .= '|<a href="#" class="button">' . $item->name . '</a>';
				}
			}
			elseif( $type == 'menu' )
			{
				$c = Category::getByID($id);
				if( $c instanceof Category )
				{
					$categories = array($c);
					
					while( $c->getParent() instanceof Category )
					{
						$c = $c->getParent();
						array_unshift($categories, $c);
					}
					
					$this->path = '<a href="menu.php" class="button"><span class="icon book"></span>Menu</a>';
					foreach( $categories as $cat )
					{
						$this->path .= '|<a href="menu.php?cat=' . $cat->categoryid . '" class="button">' . $cat->name . '</a>';
					}
				}
				else
				{
					$this->path = '<a href="menu.php" class="button"><span class="icon book"></span>Menu</a>';
				}
			}
			elseif( $type == 'report' )
			{
				$report_name = $id == 0 ? 'Item Frequency' : 'Another report??';
				$this->path = '<a href="reporting.php" class="button"><span class="icon book"></span>Reports</a>';
				if($id != null)
				{
					$this->path .= '<a href="reporting.php?report='.$report_name.'" class="button"><span class="icon clock"></span>'.$report_name.'</a>';
				}
			}
		}
		
		public function __get($name)
		{
			if( $name == 'path' )
			{
				return $this->path;
			}
		}
	}

?>