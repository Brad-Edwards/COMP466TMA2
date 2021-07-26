<?php
	/*
	File: content.php
	Purpose: Display LMS content to logged in user
	Date: July 13, 2021
	Copyright: Brad Edwards, 2021
	*/

	// Load database methods
	require_once 'database_manager.php'; ?>

	<!-- Every version of content needs the script -->
	<script src="/COMP466TMA2/part2/scripts/content.js"></script>

	<!-- User clicked to add a course -->
	<?php if (isset($_GET['action']) && trim($_GET['action']) == 'addCourse') : ?>
		<div id="addCourseFormDiv" class="boxShadow">
			<h2>Add Course</h2>
			<form enctype="multipart/form-data" id="addCourseForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" target="_blank">
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
			error_log("New Run");
			if ($_FILES['courseFile']['error'] == UPLOAD_ERR_OK
				&& is_uploaded_file($_FILES['courseFile']['tmp_name'])) {
				$xmlString = strtr(file_get_contents($_FILES['courseFile']['tmp_name']), array("\n" => '', "\t" => '', "\r" => ''));

				$xml = simplexml_load_file($_FILES['courseFile']['tmp_name']);

				$output = "";
				foreach ($xml->sections[0]->section AS $section) {
					echo $section->asXML();
				}

				echo $output;

				/*$reader = new XMLReader();
				$reader->open($_FILES['courseFile']['tmp_name']);
				$reader->read();
				$reader->setParserProperty(XMLReader::VALIDATE, true);
				
				if ($reader->isValid() === true) {
					error_log("Valid XML");
					$element = $reader->expand();
					$dom = new DOMDocument();
					$dom->appendChild($element);
					$dom->normalizeDocument();
				}
				$reader->close();

				$xpath = $newDOMXpath($dom);

				$elements = $xpath->query("section");
				foreach ($elemens as $element) {
					echo "<p>" . $element->nodeValue . "</p>";
				}*/

			}
			//header("location: http://34.213.198.190/COMP466TMA2/part2/index.php");
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
							// Get list of courses user is registered for
							$query = "SELECT "
						?>
						<li><hr></li>
						<a href=""><li>Register</li></a>
						<a href=""><li>Grades</li></a>					
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

