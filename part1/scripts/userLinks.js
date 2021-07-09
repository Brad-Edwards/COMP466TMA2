/*
	File: userLinks.js
	Purpose: Handle client-side logic for displaying and managing user links.
	Assignment: COMP466 TMA2
	Date: June 9, 2021
	Copyright: Brad Edwards, 2021
*/

// Adds a URL to the user's list
// param: e - submit event
// returns: void
function addBookmark(e) {
	// Don't error out into a post by accident
	e.preventDefault();

	var url = getCurrentAddress();
	// Do not submit an invalid form value
	if (!validateBookmark(url, addValidBookmark)) {
		e.preventDefault();
		return;
	}
}

// Attaches events to edit and delete buttons
// returns: void
function attachEvents() {
	// Attach edit bookmark function to all edit bookmark buttons and delete bookmark to all delete buttons
	// Every row of user bookmarks should have an edit and delete button. Should always be same number.
	var editButtons = document.getElementsByClassName("edit_bookmark_button");
	var deleteButtons = document.getElementsByClassName("delete_bookmark_button");
	for (var i = 0; i < editButtons.length; i++) {
		editButtons[i].addEventListener("click", editBookmark);
		deleteButtons[i].addEventListener("click", deleteBookmark);
	}
}

// Adds a validated URL to the user's bookmark list
// returns: void
function addValidBookmark() {
	console.log("sending request");
	// Add bookmark to user list
	// Security? Need to clean user inputs?
	var url = getCurrentAddress() + "/scripts/bookmarker.php?add=";	
	if (arguments.length == 0) {
		url += document.getElementById('newBookmarkAddress').value
	} else {
		url += arguments[0];
	}
	
	var request = new XMLHttpRequest();
	request.open("GET", url, true);
	// Set up async call
	request.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			if (isNaN(parseInt(request.responseText))) {
				window.alert("Could not add new bookmark\n" + request.responseText);
				reloadUserlinks();
				return false;
			} else if (parseInt(request.responseText.trim()) > 0) {
				return true;
				reloadUserlinks();
			} else {
				return false;
				reloadUserlinks();
				window.alert("Could not add new bookmark. It might already exist.");
			}
		}
	}	
	request.send();
}

// Deletes a URL from the user's list
// param: ???
// returns: void
function deleteBookmark(e) {
	// Don't error out into an invalid state
	e.preventDefault();
	var address = e.currentTarget				// Delete button
					.parentNode					// td tag containing delete button
					.parentNode					// tr tag containing url, edit button and delete button
					.firstChild					// td tag containing url
					.firstChild					// Text input box
					.innerHTML;						// URL to delete
	// Add bookmark to user list
	// Security? Need to clean user inputs?
	var url = getCurrentAddress() + "/scripts/bookmarker.php?remove=" + address;
	var request = new XMLHttpRequest();
	request.open("GET", url, true);
	// Set up async call
	request.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			if (isNaN(parseInt(request.responseText))) {
				console.log(request.responseText);
				window.alert("Could not remove bookmark\n" + request.responseText);
				reloadUserlinks();
				return false;
			} else if (parseInt(request.responseText.trim()) > 0) {
				console.log(request.responseText);
				reloadUserlinks();
				return true;				
			} 			
		}
	}	
	request.send();
}

// Edits a bookmark in the user's list
// param: e - submit event
// returns: void
function editBookmark(e) {
	// Don't error out by accident
	e.preventDefault();
	console.log("edit button clicked");
	var addressCell = e.currentTarget 	// edit button
					.parentNode		// td tag containing edit button
					.parentNode		// tr tag containing url, edit button and delete button
					.firstChild;	// td tag containing url
	var currentAddress = addressCell.firstChild	// a tag
						.innerHTML;				// url of bookmark
	var url = getCurrentAddress();
	// change bookmark row cell contents to a text input field
	var oldCellContents = addressCell.innerHTML;
	addressCell.innerHTML = "<input type='text' id='editingBookmark'" placeholder='" + currentAddress + "'><input id='editOkButton' type='button' value='OK'>";
	addressCell.lastChild.addEventListener('click', checkBookmarkEdit);
}

function changeBookmark(url) {
	var editInput = document.getElementsByClassName
}

function checkBookmarkEdit(e) {
	// Don't error out by accident
	e.preventDefault();

	var inputBox = e.currentTarget				// OK button
					.previousElementSibling;	// Input box
	var oldAddress = inputBox.placeholder;		
	var newAddress = inputBox.value;

	// Only validate if there is a change
	if (oldAddress != newAddress) {
		if (!validateBookmark(newAddress,changeBookmark)) {
			window.alert("Edited bookmark is not valid.");			
		} else {
			var editInput = document.getElementById("editingBookmark");
			var addressCell = editInput.parentNode; // td tag
			if (deleteBookmark(oldAddress)) {

			} else {
				add
			}
		}
		reloadUserlinks();
	} else {

	}
}

// Returns the current window url location
// returns: Current window url as a string
function getCurrentAddress() {
	return window.location.href.substring(0, window.location.href.lastIndexOf('/')+1);
}

// Runs initialization code on page load
// return: void
function init() {
	// Attach add bookmark function to add bookmark button
	document.getElementById("addBookmarkForm").addEventListener("submit", addBookmark);
	// Attach events to user links edit/delete buttons.
	attachEvents();
}

// Reloads the user's bookmarks
// returns: void
function reloadUserlinks() {
	// Reload user bookmarks
	var reloadUrl = getCurrentAddress();
	var reloadRequest = new XMLHttpRequest();
	url = getCurrentAddress() + "userLinks.php";			
	reloadRequest.open("GET", url, true);
	reloadRequest.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			var tempDoc = document.implementation.createHTMLDocument("tempDoc");
			tempDoc.write(reloadRequest.responseText);
			// Replace current links with updated links
			document.getElementById("userLinksDiv").innerHTML = tempDoc.getElementById("userLinksDiv").innerHTML;
			attachEvents();
		}
	}
	reloadRequest.send();
}

// Sets the add new bookmark error message
// param: message - the message to display
// return: void
function setBookmarkError(message) {
	document.getElementById('addBookmarkErrorMessage').innerHTML = message;
}

// Validates a URL using the server
// param: url - url of Marked server
// returns: true if URL is valid and up, false otherwise
// note: The assignment required I use JavaScript in the URL validation process.  I could have 
// done this using XMLHttpRequest directly to the user's URL, but then I would have had to allow
// CORS in the php headers for the page, which is a big security risk. Instead I used JavaScript
// to send an XMLHttpRequest to the server for validation. Either way it's an AJAX thing going on.
function validateBookmark(url, callbackFunc) {

	// Use location.href since I dunno whether the tutor will be using this code on my site
	// or locally on their machine
	url += "/scripts/validateUrl.php?check=" + document.getElementById('newBookmarkAddress').value;
	var request = new XMLHttpRequest();
	request.open("GET", url, true);
	// Set up async call
	request.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			if (request.response.includes("200")) {
				setBookmarkError("");
				callbackFunc(url);
				return true;
			} 
			setBookmarkError("Please enter a valid URL");
			return false;
		}
	}	
	request.send();
}

window.addEventListener("load", init);