<?php
	/*
	File: index.php
	Purpose: start page for LMS app
	Date: July 10, 2021
	Copyright: Brad Edwards, 2021
	*/

	// Start session for application
	session_start();

	// Load database methods
	require_once 'scripts/database_manager.php';

	// Handle logout request if received
	if (isset($_GET['logout']) && trim($_GET['logout'])) {
		$_SESSION["loggedIn"] = false;
	}

	// For navbar display
	$credentialPage = false;
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="UTF-8">
		<Title>Studious</Title>
		<link rel="stylesheet" type="text/css" href="shared/styles.css">
		<?php include 'navbar.php'; ?>
	</head>
	<body>
		
	</body>
</html>