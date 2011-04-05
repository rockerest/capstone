<?php
	require_once('Template.php');
	require_once('Header.php');
	require_once('Footer.php');
	require_once('Session.php');
	setSession(0,"/");

    class Page {
		protected $header;
		protected $footer;
		
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
			$this->footer = new Footer();
			
			$this->header->run();
			$this->footer->run();
        }

        public function build($appContent)
		{
            $tmpl = new Template();
			$tmpl->headerContent = $this->header->generate();
            $tmpl->appContent = $appContent;
			$tmpl->footerContent = $this->footer->generate();
			$tmpl->title = $this->page_title;

            return $tmpl->build('page.html');
        }
    }
?>