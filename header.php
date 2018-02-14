<?php
	$notloggedin = "You need to authenticate in order to access the system.<table><tr><td><form action='index.php' method='post'><input type='submit' value='LOGIN'></td></tr></table>";
?>

<!DOCTYPE html>
<html>
<head>
	<title>HP Lease Database</title>
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
<!-- #8cb3d9  -->