<?php
	/*
	File: display_course.php
	Purpose: Display user's course content
	Date: August 1, 2021
	Copyright: Brad Edwards, 2021
	*/

	@session_start();

	require_once 'database_manager.php';

	// Get course to display
	if (!isset($_GET['course'])) {
		echo "Sorry, could not load your course right now. Try again later.";
		return false;
	}

	$courseId = trim($_GET['course']);
	// Get primary course content
	$query = "SELECT * FROM courses WHERE course_id='$courseId';";
	$db = @dbConnect();
	$content = "";
	if ($stmt = mysqli_prepare($db, $query)) {
		if (mysqli_stmt_execute($stmt)) {
			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $courseId, $courseName, $courseCode, $courseSummary, $courseIntro, $courseContent);
			mysqli_stmt_fetch($stmt);
			$content .= "<h1>$courseCode $courseName</h1><h2>Summary</h2><p>$courseSummary</p><h2>Introduction</h2><p>$courseIntro</p>$courseContent";
			echo $content;
		} else {
			error_log("Could not execute sql stmt for course data.");
			error_log($db->connect);
			return false;
		}
	} else {
		error_log("Could not prepare query for course data.");
		error_log($db->error);
		return false;
	}
?>