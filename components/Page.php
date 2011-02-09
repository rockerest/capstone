<?php
	set_include_path('global:jquery:backbone:components:content:scripts:styles:images');
	require_once('Template.php');
	require_once('Menubar.php');
	require_once('Header.php');	
	require_once('MenuItem.php');
	require_once('Session.php');
	require_once('Login.php');
	setSession(0,"/");
	

    class Page {
		protected $menu;
		protected $header;
		protected $menuItem;
		protected $ingredients_list;
		protected $login;
		private $curr;
		private $page_title;

		
        public function __construct($curr, $page_title )
		{
			$this->curr = $curr;
			$this->page_title = $page_title;		
        }

		public function run()
		{
            $this->menu = new Menubar();
			$this->header = new Header($this->curr);
			$this->menuItem = new MenuItem(isset($_GET['id']) ? $_GET['id'] : 1);
			$this->login = new Login();
            $this->menu->run();
			$this->header->run();			
			$this->menuItem->run();
        }

        public function build($appContent)
		{
            $tmpl = new Template();
			$tmpl->headerContent = $this->header->generate();
            $tmpl->menuContent = $this->curr == 1 ? $this->menu->generate() : "";			
			$tmpl->menuItem = ($this->curr == 1) ? $this->menuItem->generate($this->curr) : "";
            $tmpl->appContent =  $this->curr == 4 ? $this->login->generate() : $appContent;
			$tmpl->title = $this->page_title;

            return $tmpl->build('page.html');
        }
    }
?>