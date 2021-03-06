<?php
	/*
	File: content.php
	Purpose: Display LMS content to logged in user
	Date: July 13, 2021
	Copyright: Brad Edwards, 2021
	*/

	// Load database methods
	require_once 'database_manager.php'; 

	function addCourse($course) {
		if ($course->getName() != 'course_type') {
			error_log("Can only add XML course_type to the database.");
			return false;
		}

		$db = @dbConnect();

		if ($db->connect_error) {
			error_log("Could not open database to add course.");
			return false;
		} 

		// Check if instructors are users. Can only add course with 
		// existing users

		$instructors = array();
		
		foreach ($course->instructors[0]->instructor AS $instructor) {
			array_push($instructors, array($instructor->firstname, $instructor->lastname));			
		}

		if (count($instructors) < 1) {
			error_log("New courses must have instructors.");
			return false;
		}

		// Create query
		$query = "SELECT instructor_id FROM instructors INNER JOIN users ON instructors.user_id = users.user_id WHERE ";
		$i = 0;

		foreach ($instructors AS $instructor) {
			$query .= "(first_name='" . $instructor[0] . "' AND last_name='" . $instructor[1] ."')";
			if ($i < count($instructors) - 1) {
				$query .= " OR ";
			}
			$i++;
		}
		$query .= ";";

		// Execute query
		if ($stmt = mysqli_prepare($db, $query)) {
			if (mysqli_stmt_execute($stmt)) {
				mysqli_stmt_store_result($stmt);
				if (mysqli_stmt_num_rows($stmt) != count($instructors)) {
					error_log("Not all instructors listed in the new course are in the users or instructors table.");
					return false;
				}
				// Store user_id's for later use
				mysqli_stmt_bind_result($stmt, $id);
				$i = 0;
				while (mysqli_stmt_fetch($stmt)) {
					array_push($instructors[$i], $id);
					$i++;
				}
			} else {
				error_log($db->error);
				return false;
			}
		} else {
			error_log($db->error);
			return false;
		}

		// Add course and its pieces to the database

		// Add course
		// escape any apostrophes with ''
		$name = $course->name;
		$name = str_replace("'", "''", $name);
		$code = $course->code;
		$code = str_replace("'", "''", $code);
		$summary = $course->summary->asXML();
		$summary = str_replace("'", "''", $summary);
		$introduction = $course->introduction->asXML();
		$introduction = str_replace("'", "''", $introduction);
		$courseContent = $course->sections->asXML();
		$courseContent = str_replace("'", "''", $courseContent);

		$query = "START TRANSACTION;";
		$query = "INSERT INTO courses (course_name, course_code, summary, introduction, content) VALUES ('$name', '$code', '$summary', '$introduction', '$courseContent');";
		$query .= "SET @course_id = LAST_INSERT_ID();";

		// Add course_instructors
		foreach ($instructors AS $instructor) {
			$query .= "INSERT INTO course_instructors (course_id, instructor_id) VALUES (@course_id, '$instructor[2]');";
		}

		// Add assignments
		// TODO: Didn't add assignments to any test data, not required by assignment

		// Add quizzes
		foreach ($course->quizzes[0] AS $quiz) {
			$order = $quiz->order;
			$title = str_replace("'", "''", $quiz->title);
			$weight = $quiz->weight;
			$questions = str_replace("'", "''", $quiz->questions->asXML());
			$query .= "INSERT INTO quizzes (course_id, quiz_weight, quiz_order, quiz_name, quiz_content) VALUES (@course_id, '$weight', '$order', '$title', '$questions');";
		}

		// Add 
		$query .= "COMMIT;";

		// Execute query
		if ($db->multi_query($query)) {
			do {
				if ($resultSet = $db->store_result()) {
					while ($row = $resultSet->fetch_row()) {
						if ($row[0] == 1) {
							return true;
						}
					}
				}
			} while ($db->next_result());		
		} else {
			error_log("Database error while adding course.");
			error_log(mysqli_error($db));
			return false;
		}
		@dbClose($db);
	}

	// Generates a random string
	// Returns: string of random characters
	// Thanks A. Cheshirov - https://stackoverflow.com/questions/4356289/php-random-string-generator
	function generateRandomString($length = 7) {
    	return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
	?>

	<!-- Every version of content needs the script -->
	<script src="/COMP466TMA2/part2/scripts/content.js"></script>

	<!-- User clicked to add a course -->
	<?php if (isset($_GET['action']) && trim($_GET['action']) == 'addCourse') : ?>
		<div id="addCourseFormDiv" class="boxShadow">
			<h2>Add Course</h2>
			<form enctype="multipart/form-data" id="addCourseForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
				<label>Select a text file containing a new course.</label>
				<input type="file" id="courseFileButton" name="courseFile" class="formElement">
				<div class="formDiv flexContainer">
					<input class="formButtonLeft" type="submit" name="submitButton" class="FormButton defaultButton" value="Submit">
					<input class="formButtonRight" type="submit" name="cancelButton" class="formButton alternateButton" value="Cancel">
				</div>
			</form>
		</div>
	<!-- User clicked to cancel adding a course -->
	<?php elseif (isset($_POST['cancelButton'])) : ?>
		<?php 
			header("location: http://34.213.198.190/COMP466TMA2/part2/index.php"); 
		?>
	<!-- User sent a file containing a course to add -->
	<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
		<?php 
			if ($_FILES['courseFile']['error'] == UPLOAD_ERR_OK
				&& is_uploaded_file($_FILES['courseFile']['tmp_name'])) {
				
				$xml = simplexml_load_file($_FILES['courseFile']['tmp_name']);
				$courseAdded = addCourse($xml);
				
			}
			header("location: http://34.213.198.190/COMP466TMA2/part2/index.php");
		?>
	<!-- Default content display for user -->
	<?php else : ?>
		<div id="contentDiv" class="flexContainer">
			<div id="sidebarDiv" class="boxShadow">
				<?php if(trim($_SESSION["role"]) == 'student') : ?>
				<nav>
					<ul>
						<li>Your Courses</li>
						<?php
							$dbCon = @dbConnect();
							$username = trim($_SESSION['username']);
							$query = "SELECT course_name, course_code, courses.course_id FROM courses 
								INNER JOIN sessions ON sessions.course_id=courses.course_id 
								INNER JOIN students ON sessions.student_id=students.student_id 
								INNER JOIN users ON users.user_id=students.user_id
								WHERE username='$username';";
							$dbCon = @dbConnect();
							$quizzes = "";
							if ($stmt = mysqli_prepare($dbCon, $query)) {
								if (mysqli_stmt_execute($stmt)) {
									mysqli_stmt_store_result($stmt);
									mysqli_stmt_bind_result($stmt, $courseName, $courseCode, $courseId);
									while (mysqli_stmt_fetch($stmt)) {
										echo "<li><a href='scripts/display_course.php?course=$courseId' class='registeredCourseLink'>$courseCode $courseName</li></a>";
										$quizzes .= "<li><a href='scripts/display_quiz.php?course=$courseId' class='courseQuizLink'>$courseCode $courseName</li></a>";
									}
								} else {
									error_log("Could not execute query to get user's registered courses.");
									error_log($dbCon->error);
									return false;
								}
							} else {
								error_log("Could not prepare query to get user's registered courses");
								error_log($dbCon->error);
								return false;
							}
							@dbClose($dbCon);
							echo "<li>&nbsp;</li><li>Your Quizzes</li>";
							echo $quizzes;
						?>
						<li><hr></li>
						<a href="" id="courseRegisterLink"><li>Register</li></a>
						<a href="" id="gradesLink"><li>Grades</li></a>					
					</ul>
				</nav>
				<?php else : ?>
					<nav>
						<ul>
							<li>Admin</li>
							<a href="" id="addCourseLink"><li>Add Course</li></a>
						</ul>
					</nav>
				<?php endif; ?>
			</div>
			<div id="userContentDiv" class="boxShadow">

			</div>
		</div>
	<?php endif; 
?>

