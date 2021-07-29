<?php
	/*
	File: course_register.php
	Purpose: Display LMS content to logged in user
	Date: July 27, 2021
	Copyright: Brad Edwards, 2021
	*/
	error_log("course register top");
	@session_start();
	// Load database methods
	require_once 'database_manager.php';
	$db = dbConnect();
	$username = trim($_SESSION['username']);
	$options = "";
	$query = "SELECT course_id, course_code, course_name FROM courses;";
	if ($stmt = mysqli_prepare($db, $query)) {
		if (mysqli_stmt_execute($stmt)) {
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $id, $code, $course);
			while (mysqli_stmt_fetch($stmt)) {
				$options .= "<option value='$id'>$course $code</option>";
			}
			error_log($options);
		} else {
			error_log("error executing course list query");
			error_log($db->error);
			return false;
		}
	} else {
		error_log("error preparing course list query");
		error_log($db->error);
	}

	// Handle requests
	if ($_SERVER['REQUEST_METHOD'] === 'GET') : ?>
		<div id="courseRegisterFormDiv" class="boxShadow">
			<h2>Course Registration</h2>
			<form id="courseRegisterForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<label>Select a course:</label>
				<select name="selectedCourse" id="courseRegisterSelect">
					<?php echo $options; ?>
				</select>
				<input class="formButtonLeft formButton defaultButton" type="submit" name="submitButton" value="Submit">
				<input class="formButtonRight formButton alternateButton" type="submit" name="cancelButton" value="Cancel">
			</form>
		</div>

	<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
		<?php 
			if(isset($_POST["cancelButton"])) {

			} elseif (isset($_POST['selectedCourse'])) {
				$courseId = trim($_POST['selectedCourse']);
				
			}
		?>
	<?php endif;
	@dbClose($db);
?>