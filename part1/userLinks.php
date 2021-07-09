<?php

	/*
		File: userLinks.php
		Purpose: Allows user to view and manage their links
		Assignment: COMP466 TMA2
		Date: June 8, 2021
		Copyright: Brad Edwards, 2021
	*/

	// Session needed to get username, suppress errors for session already started in index
	@session_start();

	// Index page will only allow userLinks to display if the user is logged in. So we do not need to check
	// for login. This may break if index no longer guarantees a user is logged in.

	// The login and registration pages add the user's username to the session on successful login/registration.
	// This will break if the login and registration pages do not guarantee the username is set.

	$username = trim($_SESSION['username']);

	// load database manager
	require_once './scripts/database_manager.php';

	// Accesses the database to get a user's bookmarks
	// param: $user - The username of the user to get bookmarks for
	// returns: null if user does not exist or username is not provided, otherwise 
	// an array with the stored result of the query and any error messages. Results will 
	// be at index 0, error messages at index 1. Index 1 will be an empty string if there
	// were no errors. Index 0 will be null and index 1 will be a message if there were
	// errors.
	function getUserBookmarks($user) {
		// username is required
		if ($user == null || $user == '') {
			return array (null, 'A username must be provided');
		}

		$db = @dbConnect();
		
		if ($db->connect_error) {
			return array (null, "Could not connect to the database. Try again later.");
		} else {
			// Check user exists
			// Set up query
			$query = "SELECT user_id FROM users WHERE username = ?";

			if ($stmt = mysqli_prepare($db, $query)) {
				// bind query
				mysqli_stmt_bind_param($stmt, "s", $param_username);
				$param_username = $user;

				// Execute and check for results. Should be exactly 1 if user exists, usernames are unique.
				if (mysqli_stmt_execute($stmt)) {

					mysqli_stmt_store_result($stmt);
					if (mysqli_stmt_num_rows($stmt) != 1) {
						return array (null, "There is a problem with your username. Please logout and login again.");
					} else {
						// We can grab the user's bookmarks, if any
						// Set up query
						$query = "SELECT address FROM ((bookmarks INNER JOIN urls ON urls.url_id=bookmarks.url_id) INNER JOIN users ON users.user_id=bookmarks.user_id) WHERE username = ?";

						if ($stmt = mysqli_prepare($db, $query)) {
							// bind query parameters
							mysqli_stmt_bind_param($stmt, "s", $param_username);
							$param_username = $user;
							// Execute and return results
							if (mysqli_stmt_execute($stmt)) {
								mysqli_stmt_store_result($stmt);
								$result = array();
								mysqli_stmt_bind_result($stmt, $address);
								while (mysqli_stmt_fetch($stmt)) {									
									array_push($result, $address); 
								}
								@dbClose($db);
								return array ($result, '');
							} else {
								@dbClose($db);
								return array (null, "Could not get bookmarks from the database. Try again later.");
							}
						} else {
							@dbClose($db);
							return array (null, "There was an error in the SQL query for the user's bookmarks: " . $query);
						}
					}
				}
			}
		}
		@dbClose($db);	
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="./shared/styles.css">
		<script type="text/javascript" src="./scripts/userLinks.js"></script>
	</head>
	<body>
		<!--
			// add new links box
			// list of links
		-->
		<div id="newBookmarkDiv">
			<form id="addBookmarkForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<div id="addBookmarkDiv">
					<label for="newBookmarkAddress">Add Bookmark</label>
					<input type="text" name="newBookmarkAddress" id="newBookmarkAddress" class="formElement" text="www.google.com" >
					<input type="submit" name="addNewBookmarkButton" id="addNewBookmarkButton" class="formButton defaultButton" value="Add">
					<p id="addBookmarkErrorMessage"></p>
				</div>
			</form>
		</div>
		<div id="userLinksDiv">
			<table id="userLinksTable">
				<?php 
					$result = getUserBookmarks($username);
					if ($result[0] == null) {
						echo $result[1];
					} else {
						$urls = $result[0];
						sort($urls);
						foreach ($urls as $address) {
							echo '<tr><td>' . '<a href="http://' . $address . '" target="_blank">' . $address . '</a></td><td><input type="button" class="edit_bookmark_button" value="Edit"></td><td><input type="button" class="delete_bookmark_button" value="Delete"></td></tr>';
						}		
					}
				?>
			</table>
		</div>
	</body>
</html>