/*
	File: content.js
	Purpose: Logic for displaying content to user
	Assignment: COMP466 TMA2
	Date: July 24, 2021
	Copyright: Brad Edwards, 2021
*/

window.addEventListener('load', init);

// Handles user request to add a new course
// Returns: void
function addCourseClickHandler(e) {
	// Don't error out by accident
	e.preventDefault();

	var div = document.getElementById('userContentDiv');
	var address = getCurrentAddress() + "scripts/content.php?action=addCourse";
	var request = new XMLHttpRequest();
	request.open("GET", address, true);
	// Set up async call
	request.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			div.innerHTML = request.responseText;
		}
	}
	request.send();
}

// Returns the current window url location
// returns: Current window url as a string
function getCurrentAddress() {
	return window.location.href.substring(0, window.location.href.lastIndexOf('/')+1);
}

// Initializes the content management scripts
// Returns: void
function init() {
	// Only shows for instructors
	if (document.getElementById('addCourseLink') != null) {
		document.getElementById('addCourseLink').addEventListener('click', addCourseClickHandler);	
	} else {
		// Add student specific listeners
		document.getElementById('courseRegisterLink').addEventListener('click', registerCourseClickHandler);
	}
}

// Handles user request to register in a new course
// Returns: void
function registerCourseClickHandler(e) {
	// Dont error out by accident
	e.preventDefault();

	var div = document.getElementById('userContentDiv');
	var address = getCurrentAddress() + "scripts/course_register.php";
	var request = new XMLHttpRequest();
	request.open("GET", address, true);
	request.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			div.innerHTML = request.responseText;
			console.log(request.responseText);
			console.log("froggies");
		}
	}
	request.send();
}