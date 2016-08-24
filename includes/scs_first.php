<?php

session_start();
	ob_start();	


if ($_SERVER["SERVER_NAME"] == "localhost") {
	$user = 'root';
	$password = 'root';
	$database = 'nick_db';
	$host = 'localhost';
	$port = 8889;

	
}else{
	$database	= "bet_tracking";
	$host 		= "localhost";
	$user		= "webpacy";
	$password	= "Nichola$9";
	$port		= 3306;
}
	

	$conn = mysqli_connect ($host, $user, $password, $database) or die ("Error connecting to the database");
	//$connection = mysqli_select_db($database) or die( "Error selecting the database");
		
?>