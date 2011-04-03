<?php
	header('Content-Type: text/css');

	preg_match_all('#\w+#', $_GET['q'], $files);
	$content = '';
	
	foreach ($files[0] as $fn)
	{
		foreach ( array("../global/$fn.css", "../styles/$fn.css") as $fn )
		{
			if ( file_exists($fn) )
			{
				$content .= file_get_contents($fn);
			}
		}
		$content .= "\n\n";
	}
	
	print $content;
?>