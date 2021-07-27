<?php
	/*
	File: course_register.php
	Purpose: Display LMS content to logged in user
	Date: July 27, 2021
	Copyright: Brad Edwards, 2021
	*/

	// Load database methods
	require_once 'database_manager.php';

	$username = trim($_SESSION['username']);

	if ($_SERVER['REQUEST_METHOD'] === 'GET') : ?>

		<?php 
			// Get courses list
			$query = "SELECT * FROM courses;"
		?>

		<div id="courseRegisterFormDiv" class="boxShadow">
			<h2>Course Registration</h2>
			<form id="courseRegisterForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<label>Select a course:</label>

			</form>
		</div>

	<? elseif ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>

	<? endif;
?>