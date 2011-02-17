<?php
	set_include_path('global:jquery:backbone:components:content:render:model:scripts:styles:images');
	require_once('Template.php');
	require_once('Header.php');	
	require_once('Session.php');
	setSession(0,"/");
	

    class Page {
		protected $header;
		
		private $curr;
		private $page_title;

        public function __construct($curr, $page_title )
		{
			$this->curr = $curr;
			$this->page_title = $page_title;		
        }

		public function run()
		{
			$this->header = new Header($this->curr);
			
			$this->header->run();
        }

        public function build($appContent)
		{
            $tmpl = new Template();
			$tmpl->headerContent = $this->header->generate();
            $tmpl->appContent = $appContent;
			$tmpl->title = $this->page_title;

            return $tmpl->build('page.html');
        }
    }
?>