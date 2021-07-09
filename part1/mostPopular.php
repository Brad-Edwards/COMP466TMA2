<!--
	File: mostPopular.php
	Purpose: User registration workflow
	Assignment: COMP466 TMA2
	Date: June 1, 2021
	Copyright: Brad Edwards, 2021
-->

<?php
	// load database manager
	require_once './scripts/database_manager.php';
?>

<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="./shared/styles.css">
	</head>
	<body>
		<?php
			$dbConnection = dbConnect();

			if ($dbConnection->connect_error) {
				echo 'Could not connect to the bookmarks database. Try again later.';
			} else {
				// Welcome message per assignment requirements
				echo '<h1>Most Popular Bookmarks</h1>';
				echo '<p>Welcome to Marked, where you can keep track of your favourite websites from across the Internet.</p>';
				// Show 10 most popular per assignment requirements
				$query = "SELECT urls.address, COUNT(*) FROM bookmarks INNER JOIN urls ON urls.url_id=bookmarks.url_id GROUP BY urls.url_id HAVING COUNT(*)>0 ORDER BY COUNT(*) DESC LIMIT 10";
				if ($stmt = mysqli_prepare($dbConnection, $query)) {
					// Execute query
					if (mysqli_stmt_execute($stmt)) {
						mysqli_stmt_store_result($stmt);
						mysqli_stmt_bind_result($stmt, $address, $number);
						while (mysqli_stmt_fetch($stmt)) {
							echo '<p>' . '<a href="http://' . $address . '" target="_blank">' . $address . '</a></p>';
						}
					}		
				} else {
					// Query prep error, probably a syntax error in the SQL query
					
				}
			}
			@dbClose($dbConnection);
		?>
	</body>
</html>