<?php
	/*
	File: index.php
	Purpose: start page for LMS app
	Date: July 10, 2021
	Copyright: Brad Edwards, 2021

	Hours: 24
	*/

	// Start session for application
	session_start();

	// Load database methods
	require_once 'scripts/database_manager.php';

	$isLoggedIn = false;

	// Handle logout request if received
	if (isset($_GET['logout']) && trim($_GET['logout'])) {
		$_SESSION["loggedIn"] = false;
	} 

	$isLoggedIn = $_SESSION["loggedIn"];

	if ($isLoggedIn) {
		$username = trim($_SESSION["username"]);
		$userRole = trim($_SESSION["role"]);
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
		<?php include 'scripts/navbar.php'; ?>
		<script type="text/javascript">
			var username = '<?php echo $_SESSION["username"];?>';
			var userRole = '<?php echo $_SESSION["role"];?>';
		</script>
	</head>
	<body>
		<text>HI EVERYONE!</text>
		<div id="mainContentDiv" class="flexContainer">
			<?php
				if (!$isLoggedIn) {
					include 'scripts/welcome.php';
				} else {
					include 'scripts/content.php';
				}
			?>
		</div>
	</body>
</html>