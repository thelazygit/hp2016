<?php
	session_start();
	include "header.php";
	if(!isset($_SESSION['username'])) {
		echo $notloggedin;
	} else {
	if ($_POST['curpass']) { // If the current password is set then
		if (($_POST['curpass']) == ($_SESSION['password'])) { // Check that the current password and the session passwords match, if the do then
			if (($_POST['newpass_a']) == "") { // Check that the new password field is not empty. If it is then
					show_form("New password field empty"); // Show the form with an appropriate error.
				} else { // OK the new password field is not empty.
					if (($_POST['newpass_a']) != ($_POST['newpass_b'])) { // now check that the new password field and the confirm fields match. If they don't then 
						show_form("New password and confirm new password mismatch"); // Show the form with an appropriate error.
					} else { // OK the new and confirm fields match, so
						change_pwd(); // Change the password
					}
				}
		} else { // The old password does not match the session password so
			show_form("Incorrect old password"); // Show the form with an appropriate error.
		}
	} else {
		show_form();
	}
	}
	
	function show_form($err) {
		echo "<h3>Password change for ".$_SESSION['username']."</h3>";	
			if ($err) { echo "ERROR: ".$err.". Try again<br>"; };
		echo "	<form action=\"chpwd.php\" method=\"post\">
					<table>
					<tr><td>Current Password :</td><td><input type=\"password\" name=\"curpass\" size=25></td></tr>
					<tr><td>New Password:</td><td><input type=\"password\" name=\"newpass_a\" size=25></td></tr>
					<tr><td>Confirm New Password :</td><td><input type=\"password\" name=\"newpass_b\" size=25></td></tr>
					<tr><td colspan=2 align=\"center\"><input type=\"submit\" value=\"Change Password\"></td></tr>
					</table>
					</form><form action=\"index.php\" method=\"post\"><input type=\"submit\" value=\"HOME\"></form>";
	}
	
	function change_pwd() {
		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
		$sql = "UPDATE logins SET pswd = \"" . $_POST['newpass_a'] . "\" WHERE userid = \"" . $_SESSION['userid'] . "\"";
		$result = $conn->query($sql);

		include "header.php";
		echo "Password changed. PLease close the browser and log in with the new password.";
		include "footer.php";
		$conn->close();
	}
	include "footer.php";
?>