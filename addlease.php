<?php
	session_start();
	include "header.php";

	if(!isset($_SESSION['username'])) {
		echo $notloggedin;
	} else {
		if (($_POST['action']) == "save") {
			save_lease($_POST['area'], $_POST['location'], $_POST['department'], $_POST['building'], $_POST['firstname'], $_POST['lastname'], $_POST['schedno'], $_POST['month_p'], $_POST['year_p'], $_POST['month_due'], $_POST['year_due'], $_POST['option'], $_POST['serial'], $_POST['model'], $_POST['dis_sno'], $_POST['keyno'], $_POST['pcname'], $_POST['comments']);
		} else {
			create_lease();
		}
	}
	
	function save_lease($area, $location, $department, $building, $firstname, $lastname, $schedno, $month_p, $year_p, $month_due, $year_due, $option, $serial, $model, $dis_sno, $keyno, $pcname, $comments) {
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
	} // Installment set
		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
			if ($dis_sno == "") {
				$sql = "insert into hp_lease_equip ( upd_date, upd_by, institute, area, location, department, building, firstname, lastname, schedno, month_p, year_p, month_due, year_due, `option`, `serial`, model, dis_sno, keyno, pcname, installment, comments ) values ( NOW(), '".$_SESSION['username']."', '".$_SESSION['institute']."', '$area', '$location', '$department', '$building', '$firstname', '$lastname', '$schedno', '$month_p', '$year_p', '$month_due', '$year_due', '$option', '$serial', '$model', NULL, '$keyno', '$pcname', $installment, '$comments' )";
			} else {
				$sql = "insert into hp_lease_equip ( upd_date, upd_by, institute, area, location, department, building, firstname, lastname, schedno, month_p, year_p, month_due, year_due, `option`, `serial`, model, dis_sno, keyno, pcname, installment, comments ) values ( NOW(), '".$_SESSION['username']."', '".$_SESSION['institute']."', '$area', '$location', '$department', '$building', '$firstname', '$lastname', '$schedno', '$month_p', '$year_p', '$month_due', '$year_due', '$option', '$serial', '$model', '$dis_sno', '$keyno', '$pcname', $installment, '$comments' )";
			}
		$result = $conn->query($sql);
		echo "Saved new lease detail to database.<br><br><table><tr><td><form action='index.php' method='post'><input type='submit' value='HOME'></form></td><td><form action='list.php' method='post'><input type='submit' value='List'></form></td></tr></table>";
	}

	function create_lease() {
		echo "<fieldset><table width-'650'>NOTE: Some equipment may already have been added to the database if it formed part of a bulk delivery.<br><br>Please first search the database for the serial numbers to ensure that you are not loading equipment that is already there.</table></fieldset><br>
			<form action='addlease.php' method='post'>
				<fieldset><legend>Add New Lease Record</legend>
				<input type='hidden' name='action' value='save'>
				<table width='650' border=0 cellpadding=5>
						<tr>
							<th>Area</th>
							<td><select name='area'><option value='BETHL'>Bethlehem<option value='NELSP'>Nelspruit<option value='POTCH'>Potchefstroom<option value='PTA'>Pretoria<option value='RDPLT'>Roodeplaat<option value='RUSTB'>Rustenburg<option value='STB'>Stellenbosch</select></td>
						</tr>
						<tr>
							<th>Location</th>
							<td><input type='text' name='location'></td>
						</tr>
						<tr>
							<th>Department</th>
							<td><input type='text' name='department'></td>
						</tr>
						<tr>
							<th>Building</th>
							<td><input type='text' name='building'></td>
						</tr>
						<tr>
							<th>Firstname</th>
							<td><input type='text' name='firstname'></td>
						</tr>
						<tr>
							<th>Lastname</th>
							<td><input type='text' name='lastname'></td>
						</tr>
						<tr>
							<th>Lease Schedule</th>
							<td><input type='text' name='schedno'></td>
						</tr>
						<tr>
							<th>Purchase Month</th>
							<td><select name='month_p'><option value='January'>January<option value='February'>February<option value='March'>March<option value='April'>April<option value='May'>May<option value='June'>June<option value='July'>July<option value='August'>August<option value='September'>September<option value='October'>October<option value='November'>November<option value='December'>December</select></td>
						</tr>
						<tr>
							<th>Purchase Year</th>
							<td><select name='year_p'><option value='2016'>2016<option value='2017'>2017<option value='2018'>2018<option value='2019'>2019<option value='2020'>2020<option value='2021'>2021<option value='2022'>2022<option value='2023'>2023<option value='2024'>2024<option value='2025'>2025</select></td>
						</tr>
						<tr>
							<th>Replace Month</th>
							<td><select name='month_due'><option value='January'>January<option value='February'>February<option value='March'>March<option value='April'>April<option value='May'>May<option value='June'>June<option value='July'>July<option value='August'>August<option value='September'>September<option value='October'>October<option value='November'>November<option value='December'>December</select></td>
						</tr>
						<tr>
							<th>Replace year</th>
							<td><select name='year_due'><option value='2019'>2019<option value='2020'>2020<option value='2021'>2021<option value='2022'>2022<option value='2023'>2023<option value='2024'>2024<option value='2025'>2025<option value='2026'>2026<option value='2027'>2027</select></td>
						</tr>
						<tr>
							<th>Option</th>
							<td><select name='option'><option value='Option1'>Option 1<option value='Option2'>Option 2<option value='Laptop1'>Notebook 1<option value='Laptop2'>Notebook 2<option value='Laptop3'>Notebook 3<option value='Laptop4'>Notebook 4<option value='Printer1'>Printer1<option value='Printer2'>Printer2<option value='Printer3'>Printer3<option value='Printer4'>Printer4<option value='Printer5'>Printer5<option value='Scanner1'>Scanner1<option value='LED-Extra'>Additional Monitor<option value='Dataprojector1'>Dataprojector1</select></td>
						</tr>

						<tr>
							<th>Serial Number</th>
							<td><input type='text' name='serial'></td>
						</tr>
						<tr>
							<th>Model Number</th>
							<td><input type='text' name='model'></td>
						</tr>
						<tr>
							<th>Display SN</th>
							<td><input type='text' name='dis_sno'></td>
						</tr>
						<tr>
							<th>Key Number</th>
							<td><input type='text' name='keyno'></td>
						</tr>
						<tr>
							<th>PC Name</th>
							<td><input type='text' name='pcname'></td>
						</tr>
						<tr>
							<th>Comments</th>
							<td><textarea name='comments' rows='5' cols='50'></textarea></td>
						</tr>
						<tr>
							<td colspan='2' align='center'><input type='submit' value='Add'></td>
						</tr>
				</table>
			</form></fieldset>
			<table><tr><td><form action='index.php' method='post'><input type='submit' value='HOME'></form></td><td><form action='list.php' method='post'><input type='submit' value='List'></form></td></tr></table>";
	}
	include "footer.php";
	$conn->close();
?>