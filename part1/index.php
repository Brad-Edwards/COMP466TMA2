
<?php 
	/*
	File: index.php
	Purpose: Start page for bookmarking app
	Date: May 18, 2021
	Copyright: Brad Edwards, 2021
	*/
	// Start session for application
	session_start();

	// Required for JavaScript cross-site validation
	header('Access-Control-Allow-Origin: *');

	// Load database methods
	require_once './scripts/database_manager.php';

	// Handle logout request if received
	if (isset($_GET['logout']) && trim($_GET['logout'])) {
		$_SESSION["loggedIn"] = false;
	}

	// For navbar display
	$credentialPage = false;

	$mostPopular = false;

	if (isset($_GET['display']) && trim($_GET['display']) == 'popular') {
		$mostPopular = true;
	} else {
		$mostPopular = false;
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="UTF-8">
		<Title>Marked</Title>
		<link rel="stylesheet" type="text/css" href="./shared/styles.css">
		<?php include 'navbar.php'; ?>
	</head>
	<body>
		<div id="mainContentDiv">
			<div id="menuDiv">
				<nav id="mainMenu">
					<ol id="mainMenuList">
						<li><a href="index.php?display=user">Home</a></li>
						<li><a href="index.php?display=popular">Most Popular</a></li>
						<div id="userLinks">
						</div>
					</ol>
				</nav>
			</div>
			<div id="bookmarksDiv">
				<?php
					// Connect to database
					$dbConnection = dbConnect();

					if ($dbConnection->connect_error) {
						echo 'Could not connect to the bookmarks database. Try again later.';
					} else {

						if ($mostPopular || !($_SESSION['loggedIn'])) {
							include 'mostPopular.php';
						} else {
							include 'userLinks.php';
						}	
					}
					@dbClose($dbConnection);
				?>
			</div>
		</div>
	</body>
</html>