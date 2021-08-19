<?php 

/*
	File: validateUrl.php
	Purpose: Checks url is valid and is working.
	Assignment: COMP466 TMA2
	Date: June 9, 2021
	Copyright: Brad Edwards, 2021
*/
	$valid = true;
	$code = 0;

	// Strip URL from parameter down for curl to use
	$url = "http://" . str_replace("https://", "", str_replace("http://", "", trim($_GET['check'])));
	// Clean out user input nasties
	$url = filter_var($url, FILTER_SANITIZE_URL);

	// Is it even a properly formatted URL?
	if (!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
		$valid = false;
	} 

	// Looks like a URL, is it for an active site?
	if ($valid) {
		// Init curl and use to check URL is up
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Follow redirects since everything points from http -> https these days
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		// Short timeout for snappy response. Having the client-side code run async and 
		// not care how long it takes would be the cool kid thing to do.
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$response = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		echo $code;
	}
	
	// Client-side code checks for 200 to confirm URL is valid and up.
	echo $code;
?>