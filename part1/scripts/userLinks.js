/*
	File: userLinks.js
	Purpose: Handle client-side logic for displaying and managing user links.
	Assignment: COMP466 TMA2
	Date: June 9, 2021
	Copyright: Brad Edwards, 2021
*/

window.addEventListener("load", init);

// Adds a url to the user's bookmarks if it is valid or reports the result if not
// param: valid - boolean, true if the url is valid
// param: url - string, url to add to the user's bookmarks
// returns: void
function addBookmark(isValid, url) {
	if (isValid) {
		setAddBookmarkError("");
	} else {
		setAddBookmarkError("Please enter a valid url.");
		return;
	}

	// Add bookmark to user list
	// Security? Need to clean user inputs?
	var addUrl = getCurrentAddress() + "/scripts/bookmarker.php?add=" + url;	
	
	var request = new XMLHttpRequest();
	request.open("GET", addUrl, true);
	// Set up async call
	request.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			if (isNaN(parseInt(request.responseText))) {
				window.alert("Could not add new bookmark\n" + request.responseText);
				reloadUserLinks();
			} else if (parseInt(request.responseText.trim()) > 0) {
				reloadUserLinks();
			} else {
				window.alert("Could not add new bookmark. It might already exist.");
				reloadUserLinks();							
			}
		}
	}	
	request.send();
}

// Adds edit and delete event handlers to buttons
// returns: void
// NOTE: Needed so init and reloading links functions can share attaching code
function attachEvents() {
	// Attach edit bookmark function to all edit bookmark buttons and delete bookmark to all delete buttons
	// Every row of user bookmarks should have an edit and delete button. Should always be same number.
	var editButtons = document.getElementsByClassName("edit_bookmark_button");
	var deleteButtons = document.getElementsByClassName("delete_bookmark_button");
	for (var i = 0; i < editButtons.length; i++) {
		editButtons[i].addEventListener("click", onEditOkBookmarkClicked);
		deleteButtons[i].addEventListener("click", onDeleteBookmarkClicked);
	} 
}

// Edits the url of a user's bookmark
// param: isValid - boolean, whether the new address is valid
// param: newAddress - new url for bookmark
// returns: void
function editBookmark(isValid, newAddress) {

	// Done if new url is invalid
	if (!isValid) {
		window.alert("The new url for your bookmark is not valid.");
		return;
	}

	// Done if new url is empty (should be invalid though?)
	if (newAddress == '') {
		reloadUserLinks();
		return;
	}

	// Need old address for change request
	var openEditFields = document.getElementsByClassName("editBookmarkField");
	var oldAddress;
	for (var i = 0; i < openEditFields.length; i++) {
		if (openEditFields[i].value == newAddress) {
			oldAddress = openEditFields[i].placeholder;
		}
	}
	
	// Done if the old and new urls are the same.
	if (oldAddress == newAddress) {
		reloadUserLinks();
		return;
	}

	// Send change request
	var changeUrl = getCurrentAddress() + "scripts/bookmarker.php?old=" 
		+ oldAddress + "&new=" + newAddress;
	var request = new XMLHttpRequest();
	request.open("GET", changeUrl, true);
	// Set up async call
	request.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			if (isNaN(parseInt(request.responseText.trim()))) {
				window.alert("Could not edit bookmark\n" + request.responseText);
			} else if (parseInt(request.responseText.trim()) > 0) {
				reloadUserLinks();
			} 			
		}
	}	
	request.send();
}

// Returns the current window url location
// returns: Current window url as a string
function getCurrentAddress() {
	return window.location.href.substring(0, window.location.href.lastIndexOf('/')+1);
}

// Sets up user links scripts
// returns: void
function init() {
	// Attach add bookmark function to add bookmark form
	document.getElementById("addBookmarkForm").addEventListener("submit", onAddBookmarkFormSubmitted);
	attachEvents();
}

// Handles the user submitting a new url to bookmark
// param: e - Form submit event
// returns: void
function onAddBookmarkFormSubmitted(e) {
	// Don't error out by accident
	e.preventDefault();

	var url = document.getElementById("newBookmarkAddress").value.trim();
	// Validate and send callback to handle validation result
	validateBookmark(url, addBookmark);
}

// Handles the user clicking to delete a bookmark from their list
// param: e - Button click event
// returns: void
function onDeleteBookmarkClicked(e) {
	// Don't error out by accident
	e.preventDefault();
	// delete button -> td - delete button -> tr input/edit/delete -> td input field -> input field -> address
	var address = e.currentTarget.parentNode.parentNode.children[0].children[0].innerHTML;
	var url = getCurrentAddress() + "scripts/bookmarker.php?remove=" + address;
	var request = new XMLHttpRequest();
	request.open("GET", url, true);
	// Set up async call
	request.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			if (isNaN(parseInt(request.responseText))) {
				window.alert("Could not remove bookmark\n" + request.responseText);
				reloadUserLinks();
			} else if (parseInt(request.responseText.trim()) > 0) {
				reloadUserLinks();			
			} 			
		}
	}	
	request.send();
}

// Handles the user clicking OK to edit a bookmark in their list
// param: e - Button click event
// returns: void
function onEditOkBookmarkClicked(e) {
	// Don't error out by accident
	e.preventDefault();

	// Change bookmark url link to an input box
	// edit button -> td w/ edit button -> tr -> td containing url link
	var bookmarkField = e.currentTarget.parentNode.parentNode.children[0];
	// td containing url link -> a -> url
	var currentAddress = bookmarkField.children[0].innerHTML;
	bookmarkField.innerHTML = "<input type='text' class='editBookmarkField'" 
	+ " placeholder='" + currentAddress + "'><input id='editOkButton' type='button' value='OK'>" 
	+ "<input id='editCancelButton' type='button' value='Cancel'>";
	bookmarkField.children[0].value = currentAddress;
	bookmarkField.children[0].focus();
	bookmarkField.children[1].addEventListener('click', onEditButtonClicked);
	bookmarkField.children[2].addEventListener('click', reloadUserLinks);
}

// Handles the user clicking to submit a new address for a bookmark
// param: e - Button click event
// returns: void
function onEditButtonClicked(e) {
	// Don't error out by accident
	e.preventDefault();

	// cancel button -> td w/ cancel button -> tr -> input td -> input field -> url
	var url = e.currentTarget.parentNode.parentNode.children[0].children[0].value.trim();
	// Validate and send callback to handle validation result
	validateBookmark(url, editBookmark);
}

// Reloads the user's bookmarks
// returns: void
function reloadUserLinks() {
	// Reload user bookmarks
	var reloadUrl = getCurrentAddress();
	var reloadRequest = new XMLHttpRequest();
	url = getCurrentAddress() + "userLinks.php";			
	reloadRequest.open("GET", reloadUrl, true);
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

// Sets the add bookmark error message
// param: message - error message to display to the user
// returns: void
function setAddBookmarkError(message) {
	document.getElementById('addBookmarkErrorMessage').innerHTML = message;
}

// Validates a URL using the server
// param: url - url of Marked server
// param: callbackFunc - function to call when validation result comes in. Function should take 
// a boolean (whether url was valid) and the url that was validated as a string.
// returns: void
// note: The assignment required I use JavaScript in the URL validation process.  I could have 
// done this using XMLHttpRequest directly to the user's URL, but then I would have had to allow
// CORS in the php headers for the page, which is a big security risk. Instead I used JavaScript
// to send an XMLHttpRequest to the server for validation. Either way it's an AJAX thing going on.
function validateBookmark(url, callbackFunc) {

	// Use location.href since I dunno whether the tutor will be using this code on my site
	// or locally on their machine
	validateUrl = getCurrentAddress() + "scripts/validateUrl.php?check=" + url;
	var request = new XMLHttpRequest();
	request.open("GET", validateUrl, true);
	// Set up async call
	request.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			if (request.response.includes("200")) {
				callbackFunc(true, url);
			} else {
				callbackFunc(false, url);
			}
		}
	}	
	request.send();
}