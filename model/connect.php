<?php
	set_include_path('../backbone');
	
	require_once('Database.php');
	require_once('capstone.db');
	
	define("db", new Database($user, $pass, $dbname, $host, 'mysql'));
?>
