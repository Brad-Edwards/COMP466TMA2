<?php
	/*
	File: database_manager.php
	Purpose: Database connection management
	Assignment: COMP466 TMA2
	Date: July 10, 2021
	Copyright: Brad Edwards, 2021
	*/

	$db = "lms";
	$dbHost = "localhost";
	$dbPassword = "COMP466!";
	$dbUser = "lms_server";

	function dbClose($dbConnection) {
		@$dbConnection->close();
	}

	function dbConnect() {
		global $db, $dbHost, $dbPassword, $dbUser;
		$dbConnection = mysqli_connect($dbHost, $dbUser, $dbPassword, $db); 

		return $dbConnection;
	}
?>