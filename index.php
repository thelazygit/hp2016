<?php
	
	// New Lease DB Main landing page / HOME

	session_start();
	
	if (!isset($_POST['userid'])) {
		if (!isset($_SESSION['institute'])) {
				header('Location:loginform.php');
			} else {
				show_main();
			}
	} else {
			$_SESSION['userid'] = $_POST['userid'];
			$_SESSION['password'] = $_POST['password'];
			$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT id, institute, username FROM logins WHERE userid = '".$_SESSION['userid']."' AND pswd = '".$_SESSION['password']."'";
				$result = $conn->query($sql);
				if ($result->num_rows < 1) { 
						header('Location:loginform.php');
					} else {
						while($row = $result->fetch_assoc()) {
							$_SESSION['institute'] = $row['institute'];
							$_SESSION['username'] = $row['username'];
						}
						show_main();
					}
			$conn->close();
	}

	
	
	// Main page upon login
	function show_main() {
		include "header.php";
		echo "
				<h3>Welcome ". $_SESSION['username'] ." from ". $_SESSION['institute'] ."!</h3>Today is ".date("l").", ".date("Y-m-d").".</br><br>
					<img src=\"images/main.png\" width=\"640\" height=\"640\" border=\"0\" alt=\"Lease Management System\" usemap=\"#Map\">
					<map name=\"Map\">
						<area shape=\"rect\" coords=\"18,18,292,292\" href=\"list.php\" target=\"_self\" alt=\"Lease Database\">
						<area shape=\"rect\" coords=\"345,18,620,292\" href=\"products.php\" target=\"_self\" alt=\"Product List\">
						<area shape=\"rect\" coords=\"18,345,292,620\" href=\"newlease.php\" target=\"_self\" alt=\"New Lease Application\">
						<area shape=\"rect\" coords=\"345,345,620,620\" href=\"warranty.php\" target=\"_self\" alt=\"HP Warranty Call\">
					</map></br></br>
					<table><tr><td>
						<form method=\"post\" action=\"chpwd.php\"><input type=\"submit\" value=\"Change Password\"></form>
					</td>
					<td>
						<form method=\"post\" action=\"endnow.php\"><input type=\"submit\" value=\"EXIT\"></form>
					</td></tr></table>";
		
		include "footer.php";
	}

	
	
?>