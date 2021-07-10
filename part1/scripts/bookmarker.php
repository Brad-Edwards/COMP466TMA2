
<?php
	/*
	File: bookmarker.php
	Purpose: Adds and removes user bookmarks
	Assignment: COMP466 TMA2
	Date: July 5, 2021
	Copyright: Brad Edwards, 2021
	*/

	// Session needed to get username, suppress errors for session already started in index
	@session_start();

	// The login and registration pages add the user's username to the session on successful login/registration.
	// This will break if the login and registration pages do not guarantee the username is set.
	$username = trim($_SESSION['username']);

	// load database manager
	require_once 'database_manager.php';

	$add_bookmark_error = 'Could not add your bookmark. Try again later.';
	$remove_bookmark_error = 'Could not remove your bookmark. Try again later.';
	$username_error = 'Could not validate username.';

	// check if adding or removing a bookmark and get url to add or remove
	$action = '';
	$url = '';
	if (isset($_GET['add']) && trim($_GET['add']) != '') {
		$action = "add";
		$url = trim($_GET['add']);
	} else if (isset($_GET['remove']) && trim($_GET['remove']) != '') {
		$action = "remove";
		$url = trim($_GET['remove']);
	} else if (isset($_GET['old']) && trim($_GET['old']) != '' && isset($_GET['new']) && trim($_GET['new']) != '') {
		$action = "change";
		$oldBookmark = trim($_GET['old']);
		$newBookmark = trim($_GET['new']);
	}

	$db1 = @dbConnect();

	if ($db1->connect_error) {
		return array (null, 'Could not connect to the database. Try again later.');
	} else {
		// Check user exists
		// Set up query
		$query = "SELECT user_id FROM users WHERE username = ?";

		if ($stmt = mysqli_prepare($db1, $query)) {
			// bind query
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			$param_username = $username;

			// Execute and check for results. Should be exactly 1 if user exists, usernames are unique.
			if (mysqli_stmt_execute($stmt)) {

				mysqli_stmt_store_result($stmt);
				if (mysqli_stmt_num_rows($stmt) != 1) {
					return array (null, "There is a problem with your username. Please logout and login again.");
				} else {
					// Add bookmark
					if ($action == 'add') {
						// Create query
						// Make this atomic
						$query2 = 'START TRANSACTION;';
						// Add url to urls table if it doesn't exist
						$query2 .= 'INSERT INTO urls (address) SELECT "' . $url . '" FROM dual WHERE NOT EXISTS (SELECT * FROM urls WHERE address = "' . $url . '");';
						$query2 .= 'SELECT ROW_COUNT() INTO @count1;';
						// Set values needed for bookmark insert
						$query2 .= 'SET @userid = (SELECT user_id FROM users WHERE username = "' . $username . '");';
						$query2 .= 'SET @urlid = (SELECT url_id FROM urls WHERE address = "' . $url . '");';
						// Insert into bookmarks as long as it's unique
						$query2 .= 'INSERT INTO bookmarks (user_id, url_id) SELECT @userid, @urlid FROM dual WHERE NOT EXISTS (SELECT * FROM bookmarks WHERE user_id=@userid AND url_id=@urlid);';
						$query2 .= 'SELECT ROW_COUNT() INTO @count2;';
						$query2 .= 'COMMIT;';
						$query2 .= 'SELECT (@count1 + @count2);';
						$db2 = dbConnect();
						$row = null;
						if ($db2->multi_query($query2)) {
							do {
								if ($resultSet = $db2->store_result()) {
									while ($row = $resultSet->fetch_row()) {
										echo $row[0];
									}
								}
							} while ($db2->next_result());
							@dbClose($db2);				
						} else {
							echo mysqli_stmt_error($db2);
							@dbClose($db2);

							echo "There was a database error when adding your bookmark.";
							return;
						}	
					} else if ($action == 'remove') {
						// Remove bookmark
						// Create query
						$query2 = 'SET @url_id = (SELECT url_id FROM urls WHERE address="' . $url . '");';
						$query2 .= 'SET @user_id = (SELECT user_id FROM users WHERE username="' . $username . '");';
						$query2 .= 'DELETE FROM bookmarks WHERE url_id=@url_id AND user_id=@user_id;';
						$query2 .= 'SELECT ROW_COUNT() INTO @count1;';
						$query2 .= 'SELECT @count1;';

						$db2 = dbConnect();
						$row = null;
						if ($db2->multi_query($query2)) {
							do {
								if ($resultSet = $db2->store_result()) {
									while ($row = $resultSet->fetch_row()) {
										echo $row[0];
									}
								}
							} while ($db2->next_result());
							@dbClose($db2);
						} else {
							echo "Could not ask the database to delete your bookmark.";
							@dbClose($db2);
						}						
					} else if ($action == 'change') {
						// Change bookmark
						// Create query
						// Make query atomic
						// Remove old bookmark
						$query2 = 'START TRANSACTION;';
						$query2 .= 'SET @url_id = (SELECT url_id FROM urls WHERE address="' . $oldBookmark . '");';
						$query2 .= 'SET @user_id = (SELECT user_id FROM users WHERE username="' . $username . '");';
						$query2 .= 'DELETE FROM bookmarks WHERE url_id=@url_id AND user_id=@user_id;';
						$query2 .= 'SELECT ROW_COUNT() INTO @count1;';
						// Add new bookmark
						$query2 .= 'INSERT INTO urls (address) SELECT "' . $newBookmark . '" FROM dual WHERE NOT EXISTS (SELECT * FROM urls WHERE address = "' . $newBookmark . '");';
						$query2 .= 'SELECT ROW_COUNT() INTO @count2;';
						// Set values needed for bookmark insert
						$query2 .= 'SET @userid = (SELECT user_id FROM users WHERE username = "' . $username . '");';
						$query2 .= 'SET @urlid = (SELECT url_id FROM urls WHERE address = "' . $newBookmark . '");';
						// Insert into bookmarks as long as it's unique
						$query2 .= 'INSERT INTO bookmarks (user_id, url_id) SELECT @userid, @urlid FROM dual WHERE NOT EXISTS (SELECT * FROM bookmarks WHERE user_id=@userid AND url_id=@urlid);';
						$query2 .= 'SELECT ROW_COUNT() INTO @count3;';
						$query2 .= 'COMMIT;';
						$query2 .= 'SELECT (@count1 + @count2 + @count3);';

						$db2 = dbConnect();
						$row = null;
						if ($db2->multi_query($query2)) {
							do {
								if ($resultSet = $db2->store_result()) {
									while ($row = $resultSet->fetch_row()) {
										echo $row[0];
									}
								}
							} while ($db2->next_result());
							echo "results found?";
							@dbClose($db2);
						} else {
							echo "Could not edit your bookmark.";
							@dbClose($db2);
						}
					}
				} 
			} else {
				@dbClose($db1);
				echo $username_error;
				return;
			}
		} else {
			@dbClose($db1);
			echo $username_error;
			return;
		}
	}

	// Make sure the database connection is closed, we don't need errors if it's already closed
	@dbClose($db1);
?>