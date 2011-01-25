<?php

//		
//		2010/11/16
//		
//		
//		

	require_once('backbone/Database.php');
	require_once('backbone/capstone.db');
	require_once('backbone/Session.php');
	//There's a new Session.php available.
	/*
	setSession(0,'/');
	*/
	require_once('components/Header.php');
	$db = new Database($user, $pass, $dbname, $host, 'mysql');
	
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=us-ascii">
<title>CS4970 | Checkout</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/blueprint/screen.css" type="text/css" media="screen, projection">
<link rel="stylesheet" href="css/blueprint/print.css" type="text/css" media="print">
<!--[if lt IE 8]>
  <link rel="stylesheet" href="css/blueprint/ie.css" type="text/css" media="screen, projection">
<![endif]-->
<link rel="stylesheet" type="text/css" href="sdmenu/sdmenu.css" />
	
</head>
<body>

	<?php
		//build header
		$cur_page = "foo";
		$h = new Header($cur_page);
		$h->build();
	?>
	

<div id="main">
<h1>Pay</h1>
	<?php
		
	?>
</div>



<div id="foot">
</div>
</body>
</html>