<?php
	/*
	File: course_register.php
	Purpose: Display LMS content to logged in user
	Date: July 27, 2021
	Copyright: Brad Edwards, 2021
	*/
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
				<div class="formDiv flexContainer">
					<label>Select a course:</label>
					<select name="selectedCourse" id="courseRegisterSelect" class="formElement">
						<?php echo $options; ?>
					</select>
				</div>
				<div class="formDiv flexContainer">
					<input class="formButtonLeft formButton defaultButton" type="submit" name="submitButton" value="Submit">
					<input class="formButtonRight formButton alternateButton" type="submit" name="cancelButton" value="Cancel">
				</div>
			</form>
		</div>

	<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
		<?php 
			if(isset($_POST["cancelButton"])) {
				header("location: http://34.213.198.190/COMP466TMA2/part2/index.php");
			} elseif (isset($_POST['selectedCourse'])) {
				$courseId = trim($_POST['selectedCourse']);
				// Have to check if user already has course session
				// Start by getting student_id
				$username = trim($_SESSION['username']);
				$query = "SELECT student_id FROM students INNER JOIN users ON students.user_id = users.user_id WHERE username='$username';";
				$studentId;
				if ($stmt = mysqli_prepare($db, $query)) {
					if (mysqli_stmt_execute($stmt)) {
						mysqli_stmt_store_result($stmt);
						if (mysqli_stmt_num_rows($stmt) != 1) {
							error_log("Course registration: user somehow not in database.");
						}
						mysqli_stmt_bind_result($stmt, $studentId);
						mysqli_stmt_fetch($stmt);
					} else {
						error_log("Could not query database for student id in course registrations");
						error_log($db->error);
						return false;
					}
				} else {
					error_log("could not prepare query for student id in course registration.");
					error_log($db->error);
					return false;
				}

				// Now check for sessions
				$query = "SELECT session_id FROM sessions WHERE course_id='$courseId' AND student_id='$studentId';";
				if ($stmt = mysqli_prepare($db, $query)) {
					if (mysqli_stmt_execute($stmt)) {
						mysqli_stmt_store_result($stmt);
						if (mysqli_stmt_num_rows($stmt) > 0) {
							error_log("user already registered in course.");
							// TODO: error message to user
							header("location: http://34.213.198.190/COMP466TMA2/part2/index.php");
						}

						// Now register in session
						$query = "INSERT INTO sessions (course_id, student_id, start_date) VALUES ('$courseId', '$studentId', CURDATE());";
						if ($stmt = mysqli_prepare($db, $query)) {
							if (mysqli_stmt_execute($stmt)) {
								mysqli_stmt_store_result($stmt);
								if (mysqli_affected_rows($stmt) == 1) {
									error_log("not able to insert session for course registration");
								}
								header("location: http://34.213.198.190/COMP466TMA2/part2/index.php");
							} else {
								error_log("could not execute insert session for new course registration.");
								error_log($db->error);
								return false;
							}
						} else {
							error_log("could not prepare query to insert a new session for course registration.");
							error_log($db->close());
							return false;
						}
					} else {
						error_log("Could not execute query to check if user already registered in course.");
						error_log($db->error);
						return false;
					}
				} else {
					error_log("Could not prepare query to check if user already registered in course.");
					error_log($db->error);
					return false;
				}
			}
		?>
	<?php endif;
	@dbClose($db);
?>