<!--
	File: login.php
	Purpose: Login and logout workflow
	Assignment: COMP466 TMA2
	Date: May 18, 2021
	Copyright: Brad Edwards, 2021

	Thanks for the ideas TutorialRepublic - https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php

	NOTE: This really isn't secure. The form does use htmlspecialchars to handle some SQL injection, but this isn't how we'd do this in the real world. This could easily be brute-forced with a Python script, among other attacks.
-->

<?php
	session_start();
	require_once 'database_manager.php';

	// For navbar display
	$credentialPage = true;

	$username = $password = "";
	$usernameError = $passwordError = $loginError = "";
	
	// Handle form post
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		// Process form

		// Clear variables on reset
		if (isset($_POST["resetButton"])) {
			$username = $password = $usernameError = $passwordError = $loginError = "";
		} else {
			// Check credentials were entered
			if (empty(trim($_POST["username"]))) {
				$usernameError = "Please enter your username.";
			} else {
				$username = trim($_POST["username"]);
			}

			if (empty(trim($_POST["password"]))) {
				$passwordError = "Please enter your password.";
			} else {
				$password = trim($_POST["password"]);
			}

			// Validate
			if (empty($usernameError) && empty($passwordError)) {
				// DB connection
				$db = @dbConnect();


				if ($db->connect_error) {
					$loginError = "Could not connect to the database.";
				} else {
					// Prep query
					$query = "SELECT user_id, username, passwd FROM users WHERE username = ?;";

					if ($stmt = mysqli_prepare($db, $query)) {
						// Set query variables
						mysqli_stmt_bind_param($stmt, "s", $param_username);
						$param_username = $username;

						// Execute query
						if (mysqli_stmt_execute($stmt)) {
							// Save results
							mysqli_stmt_store_result($stmt);

							// Verify password if user exists
							if (mysqli_stmt_num_rows($stmt) == 1) {

								// User exists, get params and check password
								mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
					
								if (mysqli_stmt_fetch($stmt)) {
									if (password_verify($password, $hashed_password)) {
										// Password is correct
										
										session_start();

										// Username must be set to show user's links later
										$_SESSION["loggedIn"] = true;
										$_SESSION["username"] = $username;

										// Get user role
										$query = "SELECT category_name FROM users INNER JOIN user_categories ON users.user_category_id = user_categories.user_category_id WHERE username = ?;";

										if ($stmt = mysqli_prepare($db, $query)) {
											mysqli_stmt_bind_param($stmt, "s", $param_username);
											$param_username = $username;

											if (mysqli_stmt_execute($stmt)) {
												// Save results
												mysqli_stmt_store_result($stmt);

												mysqli_stmt_bind_result($stmt, $role);

												mysqli_stmt_fetch($stmt);

												$_SESSION['role'] = $role;
											}
										}

										header("location: http://34.213.198.190/COMP466TMA2/part2/index.php");
									} else {
										// Password fail
										$loginError = "Invalid username or password.";
									}
								}
							} else {
								$loginError = "Invalid username or password.";
							}
						}
					} else {
						$loginError = "Something went wrong. Please try again.";
						error_log($db->error);
					}
				}
				// Suppress errors in case db connection never opened				
				@dbClose($db);
			}
		}
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="UTF-8">
		<Title>Login</Title>
		<link rel="stylesheet" type="text/css" href="../shared/styles.css">
		<nav>
			<?php include 'navbar.php'; ?>
		</nav>
	</head>
	<body>
		<div id="loginFormDiv" class="boxShadow">
			<h2>Login</h2>
			<?php 
				// Display any login errors
				if (!empty($loginError)) {
					echo '<div class="errorDiv">' . $loginError . '</div>';
				}
			?>
			<form id="loginForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<div class="formDiv flexContainer">
					<label>Username</label>
					<input type="text" name="username" class="formElement <?php echo (!empty($usernameError)) ? 'is-invalid' : ''; ?>" autofocus>
					<span class="invalidFeedback"><?php echo $usernameError; ?></span>
				</div>
				<div class="formDiv flexContainer">
					<label>Password</label>
					<input type="password" name="password" class="formElement <?php echo (!empty($passwordError)) ? 'is-invalid' : ''; ?>">
					<span class="invalidFeedback"><?php echo $passwordError; ?></span>
				</div>
				<div class="formDiv flexContainer">
					<input class="formButtonLeft" type="submit" name="submitButton" class="formButton defaultButton" value="Submit">
					<input class="formButtonRight" type="submit" name="resetButton" class="formButton alternateButton" value="Reset">
				</div>
			</form>
			<p>Do not have an account? Click <a href="register.php">here</a> to register.</p>
		</div>
	</body>
</html>


