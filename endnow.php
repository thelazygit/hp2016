<?php
	session_start();
	##include "header.php";
	?>

		<!DOCTYPE html>
		<html>
		<head>
			<title>HP Lease Database</title>
			<meta http-equiv="refresh" content="2;URL='loginform.php'" />
			<style>
				table.st1 tr:nth-child(2n+3) {color: #000; background: #CCC; font-size: 12px; font-family: Verdana, Arial, Helvetica, sans-serif;}
				table.st1 tr:nth-child(2n+2) {color: #000; background: #FFF; font-size: 12px; font-family: Verdana, Arial, Helvetica, sans-serif;}
				table.st1 th {color: #FFF; background: #369; font-size: 12px;}
				table.st1 a {color: #000; }
				body {
					margin-top: 15px;
					margin-left: 30px;
					background-color: #1A2B77;
					color: #ffffff;
					font-family: verdana, sans-serif;
				}
				h3 {
					color: #003366;
					text-shadow: 2px 1px white;
				}
				.curPage {
						font-weight:bold;
						font-size:16px;
				}
				a {
					color: #000000;
				}
				.button {
					border-radius: 2px;
				}
				fieldset {
					width: 500px;
				    display: block;
				    margin-left: 2px;
				    margin-right: 2px;
				    padding-top: 0.35em;
				    padding-bottom: 0.625em;
				    padding-left: 0.75em;
				    padding-right: 0.75em;
				    border: 2px groove (internal value);
				}
			</style>
			<script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
			<script>
		    	webshims.setOptions('forms-ext', {types: 'date'});
				webshims.polyfill('forms forms-ext');
			</script>
		</head>
		<body>
	<?php
	If (!isset($_SESSION['username'])) {
		echo "You have not even logged into the system. Please log in before continuing.<form action='index.php' method='post'><input type='submit' value='LOGIN'>";
	} else {

	echo "<h3>Good bye ". $_SESSION['username'] ."!</h3>This is the end of your session, you will need to log in again to continue.";
	

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 
include "footer.php";
}
?>