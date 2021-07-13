<!--
	File: navbar.php
	Purpose: Displays app navbar
	Assignment: COMP466 TMA2
	Date: July 10, 2021
	Copyright: Brad Edwards, 2021
-->

<!DOCTYPE html>

<html>
	<head>
		<div id="navbar" class="flexContainer boxShadow">
			<a id="navbarTitle" href="http://34.213.198.190/COMP466TMA2/part2/index.php"><h2>Studious</h2></a>
			<?php 
				// Login or logout button depending on login state
				if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
					echo "<a id='navbarLogout' href='index.php?logout=true'><h4>Logout</h4</a>";
				} else {
					// Don't display login/register on login and registration pages
					if (!$credentialPage) {
						echo "<a id='navbarLogin' href='scripts/login.php'><h4>Login/Register</h4></a>";
					}
				}
			?>
		</div>
	</head>
</html>