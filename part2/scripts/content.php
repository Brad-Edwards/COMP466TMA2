<?php
	/*
	File: content.php
	Purpose: Display LMS content to logged in user
	Date: July 13, 2021
	Copyright: Brad Edwards, 2021
	*/

	// Load database methods
	require_once 'database_manager.php';

?>

<!DOCTYPE html>

<html>
	<div id="contentDiv" class="boxShadow flexContainer">
		<?php if(trim($_SESSION["role"]) == 'student') : ?>
			<nav>
				<ul>
					<li>Your Courses</li>
					<?php
						// Get list of courses user is registered for
						$query = "SELECT "
					?>
					<li><hr></li>
					<a href=""><li>Register</li></a>
					<a href=""><li>Grades</li></a>					
				</ul>
			</nav>
		<?php else : ?>
			<h1>Instructor</h1>
		<?php endif; ?>
	</div>
</html>