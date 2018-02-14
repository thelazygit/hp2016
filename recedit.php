<?php
	session_start();

	include "header.php";

	If (!isset($_SESSION['username'])) {
		echo $notloggedin;
	} else {

	switch($_POST['rec_edit']) {
		case "edit":
			edit_record($_POST['recid']);
			break;
		case "delete":
			delete_record($_POST['recid']);
			break;
		case "save":
			save_record($_POST['recid'], $_POST['location'], $_POST['department'], $_POST['building'], $_POST['firstname'], $_POST['lastname'], $_POST['option'], $_POST['serial'], $_POST['model'], $_POST['dis_sno'], $_POST['pcname'], $_POST['keyno'], $_POST['comments']);
			break;
		default:
			echo "No dammit! Go back to Home and try again.";
	}
	echo "<br>
			<table border=0 cellspacing=0=2 celpadding=0>
			<tr><td><form action='list.php' method='post'><input type='submit' value='List'></form></td>
			<td><form action='index.php' method='post'><input type='submit' value='Home'></form></td></tr>
			</table>";
	}
	include "footer.php";

	function edit_record($recid) {
		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
		$sql = "select * from hp_lease_equip where entryno = $recid.";
		$result = $conn->query($sql);
		while($row = $result->fetch_assoc()) {
			echo "<form action='recedit.php' method='post'>
				<input type='hidden' name='rec_edit' value='save'>
				<input type='hidden' name='recid' value=\"".$row['entryno']."\">
				<fieldset><legend>Edit Lease Record</legend>
				<table cellspacing=9 cellpadding=2>
					<tr>
						<th>Location</th>
						<td><input type='text' name='location' value='".$row['location']."'></td>
					</tr>
					<tr>
						<th>Department</th>
						<td><input type='text' name='department' value='".$row['department']."'></td>
					</tr>
					<tr>
						<th>Building</th>
						<td><input type='text' name='building' value='".$row['building']."'></td>
					</tr>
					<tr>
						<th>Firstname</th>
						<td><input type='text' name='firstname' value='".$row['firstname']."'></td>
					</tr>
					<tr>
						<th>Lastname</th>
						<td><input type='text' name='lastname' value='".$row['lastname']."'></td>
					</tr>
					<tr>
						<th>Option</th>
						<td><input type='text' name='option' value='".$row['option']."'></td>
					</tr>
					<tr>
						<th>Serial No</th>
						<td><input type='text' name='serial' value='".$row['serial']."'></td>
					</tr>
					<tr>
						<th>Model No</th>
						<td><input type='text' name='model' value='".$row['model']."'></td>
					</tr>
					<tr>
						<th>Display SN</th>
						<td><input type='text' name='dis_sno' value='".$row['dis_sno']."'></td>
					</tr>
					<tr>
						<th>PC Name</th>
						<td><input type='text' name='pcname' value='".$row['pcname']."'></td>
					</tr>
					<tr>
						<th>Key No</th>
						<td><input type='text' name='keyno' value='".$row['keyno']."'></td>
					</tr>
					<tr>
						<th>Installment</th>
						<td><input type='text' name='installment' value='".$row['installment']."'></td>
					</tr>
					<tr>
						<th>Comment</th>
						<td><textarea name='comments' cols='30' rows='4'>".$row['comments']."</textarea></td>
					</tr>
					<tr>
						<td align='center'><input type='submit' value='Save'></form></td>
					</tr>
				</table></fieldset>";
		}
					

	}

	function save_record($recid, $location, $department, $building, $firstname, $lastname, $option, $serial, $model, $dis_sno, $pcname, $keyno, $comments) {
		switch ($option) { // Base installment on option selected
		case "Option1":
			$installment = 488.00;
			break;
		case "Option2":
			$installment = 766.00;
			break;
		case "Laptop1":
			$installment = 700.00;
			break;
		case "Laptop2":
			$installment = 1070.00;
			break;
		case "Laptop3":
			$installment = 860.00;
			break;
		case "Laptop4":
			$installment = 1050.00;
			break;
		case "Printer1":
			$installment = 130.00;
			break;
		case "Printer2":
			$installment = 125.00;
			break;
		case "Printer3":
			$installment = 1300.00;
			break;
		case "Printer4":
			$installment = 170.00;
			break;
		case "Printer5":
			$installment = 240.00;
			break;
		case "Scanner1":
			$installment = 57.00;
			break;
		case "Dataprojector1":
			$installment = 850.00;
			break;
		case "LED-Extra":
			$installment = 98.00;
			break;
		default:
			$installment = 0.00;
	}
		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
		if ($dis_sno == "") {
			$sql = "update hp_lease_equip set upd_date = NOW(), upd_by = '".$_SESSION['username']."', location = '$location', department='$department', building='$building', firstname = '$firstname', lastname = '$lastname', `option` = '$option', `serial` = '$serial', model = '$model', dis_sno = NULL, pcname = '$pcname', keyno = '$keyno', installment = '$installment', comments = '$comments' where entryno = '$recid'";
		} else {
			$sql = "update hp_lease_equip set upd_date = NOW(), upd_by = '".$_SESSION['username']."', location = '$location', department='$department', building='$building', firstname = '$firstname', lastname = '$lastname', `option` = '$option', `serial` = '$serial', model = '$model', dis_sno = '$dis_sno', pcname = '$pcname', keyno = '$keyno', installment = '$installment', comments = '$comments' where entryno = '$recid'";
		}
		$result = $conn->query($sql);
		echo "Record $recid has been updated.";
	}

	function delete_record($recid) {
		if (!($_POST['yes'])) {
			echo "
					You are about to delete a record from the database. To proceed click...<br><br>
					<form action='recedit.php' method='post'>
						<input type='hidden' name='yes' value='yebo'>
						<input type='hidden' name='rec_edit' value='delete'>
						<input type='hidden' name='recid' value='$recid'>
						<table>
							<tr>
								<td><input type='submit' value='Delete'></form></td>
								<td><form action='index.php' method='post'><input type='submit' value='Cancel'></form></td>
							</tr>
						</table>
						
			";
		} else {
		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
			$sql = "CALL saveDisplay($recid, '".$_SESSION['username']."')";
			$result = $conn->query($sql);
			echo "<p>Record $recid has been deleted from the database. For your convenience, it there was a LCD monitor, the LCD monitor has been added as a new record under ICT department, ICT Stores.</p>
					<u><b>IMPORTANT</b></u>: If you want to assign the LCD monitor to another PC, you MUST edit the monitor record and add the PC,<br>as you cannot add the monitor record to a PC record. This is a quirk that will  be addressed another time.<br>If the PC record already exists, delete the record and add the pc detail to the monitor record.";
		}
	}

	$conn->close();
?>