<?php
	session_start();
	include "header.php";
	
	If (!isset($_SESSION['username'])) {
		echo $notloggedin;
	} else {

		switch ($_POST['action']) {
			case "showall":
				show_all();
				break;
			case "new":
				add_new();
				break;
			case "edit":
				edit_warranty($_POST['fid']);
				break;
			case "delete":
				delete_record($_POST['fid']);
				break;
			case "saveupdate":
				save_update($_POST['fid'], $_SESSION['username'], $_POST['heatid'], $_POST['dcxref'], $_POST['serial'], $_POST['modelno'], $_POST['user_name'], $_POST['userlocation'], $_POST['userdept'], $_POST['f_desc'], $_POST['f_stat'], $_POST['eta_date'], $_POST['comm']);
				break;
			case "save_new":
				save_new($_SESSION['username'], $_SESSION['institute'], $_POST['heatid'], $_POST['dcxref'], $_POST['serial'], $_POST['modelno'], $_POST['user_name'], $_POST['userlocation'], $_POST['userdept'], $_POST['f_desc'], $_POST['f_stat']);
				break;
			default:
				list_open();
		}
	}

	function list_open() {
		echo "Open Warranty Calls</br>";

		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT * FROM `faultrep` WHERE `f_stat` != 'repaired' AND `institute` LIKE \"".$_SESSION['institute']."\" AND `dele` is NULL ORDER BY `fault_id`";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					//Show the list of lease applications
					echo "<table class=st1 cellspacing=0 cellpadding=5>
							<tr><th>Fault<br>ID</th><th>Change<br>Date</th><th>Updated<br>By</th><th>Institute</th><th>Heat ID</th><th>HP Ref</th><th>Serial<br>No</th><th>Model<br>No</th><th>User</th><th>Location</th><th>Department</th><th>Fault<br>Description</th><th>Fault<br>Status</th><th>Part/Rep ETA</th><th>Comments</th><th colspan=2>Modify</th></tr>";
					while($row = $result->fetch_assoc()) {
						echo "<tr><td>".$row['fault_id']."</td><td>".$row['upd_date']."</td><td>".$row['ub_name']."</td><td>".$row['institute']."</td><td>".$row['heat_id']."</td><td>".$row['hp_ref']."</td><td>".$row['ser_nr']."</td><td>".$row['mod_nr']."</td><td>".$row['u_name']."</td><td>".$row['u_loc']."</td><td>".$row['u_dept']."</td><td>".$row['f_desc']."</td><td>".$row['f_stat']."</td><td>".$row['eta_date']."</td><td>".$row['comm']."</td>
								<td><form method='post' action='warranty.php'><input type='hidden' name='action' value='edit'><input type='hidden' name='fid' value=\"".$row['fault_id']."\"><input type='submit' value='Edit'></form></td>
								<td><form method='post' action='warranty.php'><input type='hidden' name='action' value='delete'><input type='hidden' name='fid' value=\"".$row['fault_id']."\"><input type='submit' value='Delete'></form></td></tr>";
					} 
					echo "</table>";

				} else {
					// Inform the user that there are no open warranty calls.
					echo "There are currently no open warranty calls.";
				}
				echo "	<table>
						<tr><td><form action='index.php' method='post'><input type='submit' value='HOME'></form></td>
						<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='new'><input type='submit' value='Add New'></form></td>
						<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='showall'><input type='submit' value='Show All'></form></tr>
						</table>";
		//echo "Show open warranty calls";
	}

	function show_all() {
		echo "All Warranty Calls</br>";

		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
				
			$sql = "SELECT * FROM `faultrep` WHERE `institute` LIKE \"".$_SESSION['institute']."\" AND `dele` is NULL ORDER BY `fault_id`";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				//Show the list of lease applications
				echo "<table class=st1 cellspacing=0 cellpadding=5>
						<tr><th>Fault<br>ID</th><th>Change<br>Date</th><th>Updated<br>By</th><th>Institute</th><th>Heat ID</th><th>HP Ref</th><th>Serial<br>No</th><th>Model<br>No</th><th>User</th><th>Location</th><th>Department</th><th>Fault<br>Description</th><th>Fault<br>Status</th><th>Part/Rep ETA</th><th><b>Comments</th><th colspan=2>Modify</th></tr>";
					while($row = $result->fetch_assoc()) {
						echo "<tr><td>".$row['fault_id']."</td><td>".$row['upd_date']."</td><td>".$row['ub_name']."</td><td>".$row['institute']."</td><td>".$row['heat_id']."</td><td>".$row['hp_ref']."</td><td>".$row['ser_nr']."</td><td>".$row['mod_nr']."</td><td>".$row['u_name']."</td><td>".$row['u_loc']."</td><td>".$row['u_dept']."</td><td>".$row['f_desc']."</td><td>".$row['f_stat']."</td><td>".$row['eta_date']."</td><td>".$row['comm']."</td>
								<td><form method='post' action='warranty.php'><input type='hidden' name='action' value='edit'><input type='hidden' name='fid' value=\"".$row['fault_id']."\"><input type='submit' value='Edit'></form></td>
								<td><form method='post' action='warranty.php'><input type='hidden' name='action' value='delete'><input type='hidden' name='fid' value=\"".$row['fault_id']."\"><input type='submit' value='Delete'></form></td></tr>";
					} 
					echo "</table>";

				} else {
					// Inform the user that there are no open leases
					echo "There are currently no open leases.";
				}
				echo "	<table>
						<tr><td><form action='index.php' method='post'><input type='submit' value='HOME'></form></td>
						<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='new'><input type='submit' value='Add New'></form></td>
						<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='showopen'><input type='submit' value='Show Open'></form></tr>
						</table>";
	}

	function delete_record($fid) {
		if (!isset($_POST['yes'])) {
			echo "
					You are about to delete warranty record $fid. This action is irriversible! Click to proceed...<br><br>
					<form action='warranty.php' method='post'>
						<input type='hidden' name='action' value='delete'>
						<input type='hidden' name='fid' value='$fid'>
						<input type='hidden' name='yes' value='yebo'>
						<table>
							<tr>
								<td><input type='submit' value='Delete'></form></td>
								<td><form action='index.php' method='post'><input type='submit' value='Cancel'></form></td>
							</tr>
						</table>
					
			";
		} else {

		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connetc_error) {
					die("Connection failed: " . $conn->connect_error);
				}
		$sql = "DELETE FROM faultrep WHERE fault_id = $fid";
		$result = $conn->query($sql);
		echo "Warranty record deleted.
				<table>
					<tr><td><form action='index.php' method='post'><input type='submit' value='HOME'></form></td>
					<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='new'><input type='submit' value='Add New'></form></td>
					<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='showopen'><input type='submit' value='Show Open'></form></tr>
				</table>";
		}
	}

	function edit_warranty($faultid) {
		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
		$sql = "SELECT * FROM faultrep WHERE fault_id = $faultid";
		$result = $conn->query($sql);

		while($row = $result->fetch_assoc()) {
			echo "<form name='warranty_edit' action='warranty.php' method='post'>
				<input type='hidden' name='action' value='saveupdate'>
				<input type='hidden' name='fid' value=\"".$row['fault_id']."\">
				<fieldset><legend>Edit Warranty Call</legend>
				<table cellspacing=9 cellpadding=2>
					<tr>
						<th>Heat Call ID</th>
						<td><input type='text' name='heatid' size=25 value=\"".$row['heat_id']."\"></td>
					</tr>
					<tr>
						<th>DCX Call Ref</th>
						<td><input type='text' name='dcxref' size=25 value=\"".$row['hp_ref']."\"></td>
					</tr>
					<tr>
						<th>Serial No</th>
						<td><input type='text' name='serial' size=25 value=\"".$row['ser_nr']."\"></td>
					</tr>
					<tr>
						<th>Model No</th>
						<td><input type='text' name='modelno' size=25 value=\"".$row['mod_nr']."\"></td>
					</tr>
					<tr>
						<th>User Name</th>
						<td><input type='text' name='user_name' size=25 value=\"".$row['u_name']."\"></td>
					</tr>
					<tr>
						<th>User Location</th>
						<td><input type='text' name='userlocation' size=25 value=\"".$row['u_loc']."\"></td>
					</tr>
					<tr>
						<th>User Department</th>
						<td><input type='text' name='userdept' size=25 value=\"".$row['u_dept']."\"></td>
					</tr>
					<tr>
						<th>Fault Description</th>
						<td><textarea name='f_desc' cols='30' rows='4'>".$row['f_desc']."</textarea></td>
					</tr>
					<tr>
						<th>Latest Call Status</th>
						<td>".$row['f_stat']."</td>
					</tr>
					<tr>
						<th>New Call Status</th>
						<td><select name='f_stat'><option value='Call logged'>Call logged<option value='Awaiting parts'>Awaiting parts<option value='Repaired'>Repaired</select></td>
					</tr>
					<tr>
						<th>Part / Rep ETA</th>
						<td>
							<input type='text' name='eta_date' value=\"".$row['eta_date']."\">
							<a href=\"javascript:void(0)\" onclick=\"if(self.gfPop)gfPop.fPopCalendar(document.warranty_edit.eta_date);return false;\" HIDEFOCUS><img class=\"PopcalTrigger\" align=\"absmiddle\" src=\"images/cal03.jpg\" width=\"30\" height=\"30\" border=\"0\" alt=\"\"></a>
						</td>
							<iframe width=174 height=189 name=\"gToday:normal:agenda.js\" id=\"gToday:normal:agenda.js\" src=\"./script/ipopeng.htm\" scrolling=\"no\" frameborder=\"0\" style=\"visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;\">
							</iframe>
					</tr>
					<tr>
						<th>Comment</th>
						<td><textarea name=\"comm\" cols=\"30\" rows=\"3\">".$row[comm]."</textarea></td>
					</tr>
					<tr>
						<td colspan=2 align='center'><input type='submit' value='Save'></td>
					</tr>
				</table>
				</fieldset>
			</form>
			<table>
				<tr><td><form action='index.php' method='post'><input type='submit' value='HOME'></form></td>
				<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='showopen'><input type='submit' value='Show Open'></form></td>
				<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='showall'><input type='submit' value='Show All'></form></tr>
			</table>";
		}
	}

	// Create new warranty call record
	function add_new() {
		echo "<form action='warranty.php' method='post'>
				<input type='hidden' name='action' value='save_new'>
				<fieldset><legend>Add New Warranty Call:</legend>
				<table cellspacing=0 cellpadding=2>
					<tr>
						<th>Heat Call ID</th>
						<td><input type='text' name='heatid' size=25 autofocus></td>
					</tr>
					<tr>
						<th>DCX Call Ref</th>
						<td><input type='text' name='dcxref' size=25></td>
					</tr>
					<tr>
						<th>Serial No</th>
						<td><input type='text' name='serial' size=25></td>
					</tr>
					<tr>
						<th>Model No</th>
						<td><input type='text' name='modelno' size=25></td>
					</tr>
					<tr>
						<th>User Name</th>
						<td><input type='text' name='user_name' size=25></td>
					</tr>
					<tr>
						<th>User Location</th>
						<td><input type='text' name='userlocation' size=25></td>
					</tr>
					<tr>
						<th>User Department</th>
						<td><input type='text' name='userdept' size=25></td>
					</tr>
					<tr>
						<th>Fault Description</th>
						<td><textarea name='f_desc' cols='30' rows='4'></textarea></td>
					</tr>
					<tr>
						<th>Call Status</th>
						<td><select name='f_stat'><option value='Call logged'>Call logged<option value='Awaiting parts'>Awaiting parts<option value='Repaired'>Repaired</select></td>
					</tr>
					<tr>
						<td colspan=2 align='center'><input type='submit' value='Save'></td>
					</tr>
				</table>
				</fieldset>
			</form>

			<table>
				<tr><td><form action='index.php' method='post'><input type='submit' value='HOME'></form></td>
				<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='showopen'><input type='submit' value='Show Open'></form></td>
				<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='showall'><input type='submit' value='Show All'></form></tr>
			</table>";
	}

	function save_new($username, $institute, $heat_id, $hp_ref, $ser_nr, $mod_nr, $u_name, $u_loc, $u_dept, $f_desc, $f_stat) {
		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
		$sql = "insert into faultrep ( ub_name, institute, heat_id, hp_ref, ser_nr, mod_nr, u_name, u_loc, u_dept, f_desc, f_stat ) values ('$username', '$institute', '$heat_id', '$hp_ref', '$ser_nr', '$mod_nr', '$u_name', '$u_loc', '$u_dept', '$f_desc', '$f_stat')";
		$result = $conn->query($sql);
		echo "New warranty call saved.<br><br>
				<table>
				<tr><td><form action='index.php' method='post'><input type='submit' value='HOME'></form></td>
				<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='showopen'><input type='submit' value='Show Open'></form></td>
				<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='showall'><input type='submit' value='Show All'></form></tr>
			</table>
		";
	}

	function save_update($fid, $updby, $heatid, $hpref, $serno, $modno, $uname, $uloc, $udept, $fdesc, $fstat, $etadate, $comm) {
		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
		//$sql = "insert into faultrep ( ub_name, institute, heat_id, hp_ref, ser_nr, mod_nr, u_name, u_loc, u_dept, f_desc, f_stat ) values ('$username', '$institute', '$heat_id', '$hp_ref', '$ser_nr', '$mod_nr', '$u_name', '$u_loc', '$u_dept', '$f_desc', '$f_stat')";
		if ($etadate == '') {
			$etadate = date("Y-m-d");
		}
		$sql = "UPDATE faultrep SET ub_name = '$updby', heat_id = '$heatid', hp_ref = '$hpref', ser_nr = '$serno', mod_nr = '$modno', u_name = '$uname', u_loc = '$uloc', u_dept = '$udept', f_desc = '$fdesc', f_stat = '$fstat', eta_date = '$etadate', comm = '$comm' WHERE fault_id = $fid";
		$result = $conn->query($sql);
		echo "Update of warranty call saved.<br><br>
				<table>
				<tr><td><form action='index.php' method='post'><input type='submit' value='HOME'></form></td>
				<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='showopen'><input type='submit' value='Show Open'></form></td>
				<td><form action='warranty.php' method='post'><input type='hidden' name='action' value='showall'><input type='submit' value='Show All'></form></tr>
			</table>
		";

	}




	include "footer.php";
	$conn->close();

?>
