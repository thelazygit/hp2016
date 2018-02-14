<?php
	session_start();
	
	if(!isset($_SESSION['username'])) {
		echo "You are not authenticated";
	} else {

	$filename = "HP_Lease_" .$_SESSION['institute']. "_" . date('Ymd') . ".xls";
	$sql = substr($_SESSION['query'],0,-11);
	
	
	header('Content-type:application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=$filename");

?>
<html>
<head><title>HP Leased Equipment</title></head>
<body>
<?php
$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
$result = $conn->query($sql);


print "
		<table cellpadding='0' cellspacing='0' border='1' bgcolor='#ffffff'>
		<tr>
			<td align='center' colspan='12'><font size='+3'><b>ARC Leased HP Equipment</b></font></td>
		</tr>
		<tr>
			<th bgcolor='#CCCCCC'>Entry No</th>
			<th bgcolor='#CCCCCC'>Location</th>
			<th bgcolor='#CCCCCC'>Area</td>
			<th bgcolor='#CCCCCC'>Department</th>
			<th bgcolor='#CCCCCC'>Building</th>
			<th bgcolor='#CCCCCC'>Name</th>
			<th bgcolor='#CCCCCC'>Option</th>
			<th bgcolor='#CCCCCC'>Serial No</th>
			<th bgcolor='#CCCCCC'>Model No</th>
			<th bgcolor='#CCCCCC'>Display SN</th>
			<th bgcolor='#CCCCCC'>Key No</th>
			<th bgcolor='#CCCCCC'>PC Name</th>
		</tr>";
while($row = $result->fetch_assoc()) {
 print "<tr>";
  foreach ($row as $col_value) {
   print "<td>$col_value</td>";
  }
 print "</tr>";
}
print "</table></body></html>";
}
?>
