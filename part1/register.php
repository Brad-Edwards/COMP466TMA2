<!--
	File: register.php
	Purpose: User registration workflow
	Assignment: COMP466 TMA2
	Date: May 18, 2021
	Copyright: Brad Edwards, 2021

	Thanks for the ideas TutorialRepublic - https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php

	NOTE: This really isn't secure. The form does use htmlspecialchars to handle some SQL injection, but this isn't how we'd do this in the real world. This could easily be brute-forced with a Python script, among other attacks.
-->

<?php
	session_start();
	require_once './scripts/database_manager.php';

	// For navbar display
	$credentialPage = true;

	$username = $password = $confirmPassword = "";
	$usernameError = $passwordError = $confirmPasswordError = $registrationError = "";

	// Handle form post
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		// Clear variables on reset
		if (isset($_POST["resetButton"])) {
			$username = $password = $confirmPassword = "";
			$usernameError = $passwordError = $confirmPasswordError = $registrationError = "";
		} else {
			// Process form

			// Check input is valid

			// username
			if (empty(trim($_POST["username"]))) {
				// Not empty
				$usernameError = "Please enter a username";
			} elseif (!preg_match('/^[a-zA-Z0-9_]+/', trim($_POST["username"]))) {
				// No invalid chars
				$usernameError = "Username must only contain letters, numbers and underscores.";
			} else {
				// Make sure username doesn't already exist
				$query = "SELECT user_id FROM users WHERE username = ?";
				$dbConnection = dbConnect();
				if ($stmt = mysqli_prepare($dbConnection, $query)) {
					// Set up query
					mysqli_stmt_bind_param($stmt, "s", $param_username);
					$param_username = trim($_POST["username"]);

					// Make query
					if (mysqli_stmt_execute($stmt)) {
						mysqli_stmt_store_result($stmt);

						if (mysqli_stmt_num_rows($stmt) != 0) {
							$usernameError = "That username is already taken.";
						} else {
							$username = trim($_POST["username"]);
						}
					} else {
						$registrationError = "Something went wrong. Please try again.";
					}
				}
				// Suppress erorrs in case db connection never opened
				@dbClose($dbConnection);
			}

			// password
			if (empty(trim($_POST["password"]))) {
				$passwordError = "Please enter a password.";
			} elseif (strlen(trim($_POST["password"])) < 7) {
				$passwordError = "Password must be at least 7 characters long.";
			} else {
				$password = trim($_POST["password"]);
			}

			// confirm password
			if (empty(trim($_POST["confirmPassword"]))) {
				$confirmPasswordError = "Please confirm your password";
			} else {
				$confirmPassword = trim($_POST["confirmPassword"]);
				if (empty($passwordError) && $password != $confirmPassword) {
					$confirmPasswordError = "Passwords do not match.";
				}
			}

			if (empty($usernameError) && empty($passwordError) & empty($confirmPasswordError)) {

				$query = "INSERT INTO users (username, password) VALUES (?, ?)";
				$dbConnection = dbConnect();

				if ($stmt = mysqli_prepare($dbConnection, $query)) {
					mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
					$param_username = $username;
					// Only store hashed passwords in db
					$param_password = password_hash($password, PASSWORD_DEFAULT);

					if (mysqli_stmt_execute($stmt)) {
						// Registration successful

						session_start();

						// Username must be set to show user's links later.
						$_SESSION["loggedIn"] = true;
						$_SESSION["username"] = $username;

						header("location: http://34.213.198.190/COMP466TMA2/part1/index.php");
					} else {
						// Registration fail
						$registrationError = "Could not complete registration. Please try again later.";
					}
				} else {
					$registrationError = "Could not connect to the database. Please try again.";
				}
				// Suppress errors in case db connection never opened
				@dbClose($dbConnection);
			} 
		}
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="UTF-8">
		<Title>Registration</Title>
		<link rel="stylesheet" type="text/css" href="./shared/styles.css">
		<?php include 'navbar.php'; ?>
	</head>
	<body>
		<div id="registrationFormDiv">
			<h2>Register</h2>
			<?php
				// Display any registration errors
				if (!empty($registrationError)) {
					echo '<div class="errorDiv">' . $registrationError . '</div>';
				}
			?>
			<form id="registrationForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<div class="formDiv">
					<label>Username</label>
					<input type="text" name="username" class="formElement <?php echo (!empty($usernameError)) ? 'is-invalid' : ''; ?>" autofocus>
					<span class="invalidFeedback"><?php echo $usernameError; ?></span>
				</div>
				<div class="formDiv">
					<label>Password</label>
					<input type="password" name="password" class="formElement <?php echo (!empty($passwordError)) ? 'is-invalid' : '' ?>">
					<span class="invalidFeedback"><?php echo $passwordError; ?></span>
				</div>
				<div class="formDiv">
					<label>Confirm Password</label>
					<input id="registrationConfirmPasswordInput" type="password" name="confirmPassword" class="formElement <?php echo (!empty($confirmPasswordError)) ? 'is-invalid' : '' ?>">
					<span class="invalidFeedback"><?php echo $confirmPasswordError; ?></span>
				</div>
				<div class="formDiv">
					<input class="formButtonLeft" type="submit" name="submitButton" class="formButton defaultButton" value="Submit">
					<input class="formButtonRight" type="submit" name="resetButton" class="formButton alternateButton" value="Reset">
				</div>
			</form>
			<p>Already have an account? Click <a href="login.php">here</a> to login.</p>
		</div>
	</body>
</html>