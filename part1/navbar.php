<!--
	File: database_manager.php
	Purpose: Database connection management
	Assignment: COMP466 TMA2
	Date: May 18, 2021
	Copyright: Brad Edwards, 2021
-->

<!DOCTYPE html>

<html>
	<head>
		<div id="navbar" class="flexcontainer">
			<a id="navbarTitle" href="/COMP486TMA2/part1/"><h2>Marked</h2></a>
			<?php 
				// Login or logout button depending on login state
				if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true) {
					echo "<a id='navbarLogout' href='index.php?logout=true'><h4>Logout<h4></a>";
				} else {
					// Don't display login/register on login and registration pages
					if (!$credentialPage) {
						echo "<a id='navbarLogin' href='login.php'><h4>Login/Register</h4></a>";
					}					
				}
			?>
		</div>		
	</head>
</html>


