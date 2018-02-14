<?php
	
	session_start();
	include "header.php";

	if(!isset($_SESSION['username'])) {
		echo $notloggedin;
	} else {

	if (isset($_GET["page"])) { $page  = $_GET["page"]; $_SESSION['page'] = $_GET['page']; } else { $page=1; }; 
	$start_from = ($page-1) * 20;
	
	if (!isset($_POST["s_field"])) {
		if (!isset($_GET['page'])) {
			// Show the manual search form
			show_search_form();
		} else {
			run_query($_SESSION['s_field'],$_SESSION['s_value'],$_SESSION['o_field'],$_SESSION['o_type'],$start_from,$page);
		}
	} else {
		run_query($_POST['s_field'],$_POST['s_value'],$_POST['o_field'],$_POST['o_type'],$start_from,$page);
	}
	}

	
function check_blob_record($recid) { // Check if the files table contain documents connected to the pc/user
	    $conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
	    $query = "SELECT `entryno`, `filename` FROM `hed_tbl_files` WHERE `entryno` = $recid";
		$fresult = $conn->query($query);
	    if ($fresult->num_rows > 0) {
			return("yes");	
	    } else {
			return("no");
		}
		$conn->close();
	}

function show_search_form() {
		echo "Hi " . $_SESSION['username'] . "!<br>Please make your selections and complete the fields below.<br><br><br>";
			echo "<form action='mansrch.php' method='post'>
					<fieldset><legend>Manual Search</legend>
					<table border=0 cellpadding=5>
						<tr>
							<th width=13>Search field</th>
							<td><SELECT name='s_field'>
								<option value='department'>Department</option>
								<option value='dis_sno'>Display Serial</option>
								<option value='firstname'>First Name</option>
								<option value='institute'>Institute</option>
								<option value='location'>Location</option>
								<option value='lastname'>Last Name</option>
								<option value='option'>Option</option>
								<option value='pcname'>PC Name</option>
								<option value='serial'>Serial No</option>
								</SELECT>
							</td>
							<td><input type='text' size=35 name='s_value' autofocus></td>
						</tr><tr>
							<th>Sort field</th>
							<td><select name='o_field'>
								<option value='department'>Department</option>
								<option value='dis_sno'>DisplaySerial</option>
								<option value='institute'>Institute</option>
								<option value='lastname'>LastName</option>
								<option value='location'>Location</option>
								<option value='option'>Option</option>
								<option value='pcname'>PcName</option>
								<option value='serial'>SerialNumber</option>
								</select></td>
							<td><input type='radio' name='o_type' value='asc' checked>Ascending<input type='radio' name='o_type' value='desc'>Descending</td>
						<tr>
							<td colspan=3 align='center'><input type='submit' value='Search'></td>
						</tr>
					</table>
					</fieldset>
				</form>
				<form action='index.php' method='post'><input type='submit' value='HOME'></form>";
}

function run_query($sfield,$svalue,$ofield,$otype,$start_from,$page) { // Run the query and show the results
		$_SESSION['s_field'] = $sfield;
		$_SESSION['s_value'] = $svalue;
		$_SESSION['o_field'] = $ofield;
		$_SESSION['o_type'] = $otype;

		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
					if ($conn->connect_error) { // Check server connection
						die("Connection failed: " . $conn->connect_error);
					}
			if (($_SESSION['institute']) == "IFVW") {
				$sql = "SELECT `entryno`, `location`, `area`, `department`, `building`, CONCAT(`firstname`, \" \", `lastname`) AS \"Name\", `option`, `serial`, `model`, `dis_sno`, `keyno`, `pcname` FROM `hp_lease_equip` WHERE `area` = 'stb' AND `dele` IS NULL AND `" . $sfield . "` LIKE \"%" . $svalue . "%\" ORDER BY `" . $ofield . "` " . $otype . " LIMIT $start_from, 20";
				$_SESSION['query'] = $sql;
				$count = "SELECT COUNT(entryno) AS total FROM `hp_lease_equip` WHERE `area` = \"stb\" AND `dele` IS NULL AND `" . $sfield . "` LIKE \"%" . $svalue . "%\" ";
			} elseif (($_SESSION['institute']) == "ARC") {
				$sql = "SELECT `entryno`, `location`, `area`, `department`, `building`, CONCAT(`firstname`, \" \", `lastname`) AS \"Name\", `option`, `serial`, `model`, `dis_sno`, `keyno`, `pcname` FROM `hp_lease_equip` WHERE `dele` IS NULL AND `" . $sfield . "` LIKE \"%" . $svalue . "%\" ORDER BY `" . $ofield . "` " . $otype . " LIMIT $start_from, 20";
				$_SESSION['query'] = $sql;
				$count = "SELECT COUNT(entryno) AS total FROM `hp_lease_equip` WHERE `dele` IS NULL AND `" . $sfield . "` LIKE \"%" . $svalue . "%\" ";
			} else {
				$sql = "SELECT `entryno`, `location`, `area`, `department`, `building`, CONCAT(`firstname`, \" \", `lastname`) AS \"Name\", `option`, `serial`, `model`, `dis_sno`, `keyno`, `pcname` FROM `hp_lease_equip` WHERE `institute` = \"" . $_SESSION['institute'] . "\" AND `dele` IS NULL AND `" . $sfield . "` LIKE \"%" . $svalue . "%\" ORDER BY `" . $ofield . "` " . $otype . " LIMIT $start_from, 20";
				$_SESSION['query'] = $sql;
				$count = "SELECT COUNT(entryno) AS total FROM `hp_lease_equip` WHERE `institute` = \"" . $_SESSION['institute'] . "\" AND `dele` IS NULL AND `" . $sfield . "` LIKE \"%" . $svalue . "%\" ";
			}		// if(($_SESSION['institute']) != "ARC") {}

				$result = $conn->query($sql);
				$count_result = $conn->query($count);
				$count_row = $count_result->fetch_assoc();;
				$total_pages = ceil($count_row["total"] / 20);

			echo "User: " . $_SESSION['username'] . ". Institute: " . $_SESSION['institute'] . ". - List of leased equipment</br><br>";
			if ($result->num_rows > 0) {
				echo "<br>";
				echo "<table class=st1 cellspacing=0 cellpadding=5>";
				echo "<tr><th>EntryNo</th><th>Location</th><th>Area</th><th>Department</th><th>Building</th><th>Name</th><th>Option</th><th>Serial</th><th>Model</th><th>MonSN</th><th>KeyNO</th><th>PC Name</th><th>Documents</th><th colspan=2>Edit</th></tr>";
				while($row = $result->fetch_assoc()) {
	        		echo "<tr><td>" . $row["entryno"]. "</td><td>" . $row["location"]. "</td><td>" . $row["area"]. "</td><td>" . $row["department"]. "</td><td>" . $row["building"]. "</td><td>" . $row["Name"]. "</td><td>" . $row["option"]. "</td><td>" . $row["serial"]. "</td><td>" . $row["model"]. "</td><td>" . $row["dis_sno"]. "</td><td>" . $row["keyno"]. "</td><td>" . $row["pcname"]. "</td>";

	        					if (check_blob_record($row["entryno"]) == "no" ) { // If no documents are available show the upload button
									echo "<td align=\"center\"><form action='documents.php' method='post'><input type='hidden' name='actn' value='up'><input type='hidden' value='".$row['entryno']."' name='recid'><button class='button' type='submit'><img src='images/upload_img.png' width=\"15\" height=\"15\"></button></form></td>";
									} else { // Otherwise show up and download buttons
									echo "<td align=\"center\"><table><tr><td><form action='documents.php' method='post'><input type='hidden' name='actn' value='down'><input type='hidden' value='".$row['entryno']."' name='recid'><button class='button' type='submit'><img src=\"images/download_img.png\" width=\"15\" height=\"15\"></button></form></td><td><form action='documents.php' method='post'><input type='hidden' name='actn' value='up'><input type='hidden' value='".$row['entryno']."' name='recid'><button class='button' type='submit'><img src=\"images/upload_img.png\" width=\"15\" height=\"15\"></button></form></td></tr></table></td>";
								}
							
							echo "<td><form action='recedit.php' method='post'><input type='hidden' name='rec_edit' value='edit'><input type='hidden' name='recid' value='".$row["entryno"]."'><input type='submit' value='Edit'></form></td>";
							echo "<td><form action='recedit.php' method='post'><input type='hidden' name='rec_edit' value='delete'><input type='hidden' name='recid' value='".$row["entryno"]."'><input type='submit' value='Delete'></form></td></tr>";
	    			}

	    		echo "<tr><td colspan=\"15\" align=\"center\">Pages: ";
					for ($i=1; $i<=$total_pages; $i++) {  // print links for all pages
	            		echo "<a href='mansrch.php?page=".$i."'";
	            		if ($i==$page)  echo " class='curPage'";
	            		echo ">".$i."</a> ";
						} 	
				echo "</td></tr>";
	    		echo "</table>";
				echo "<table><tr><td><form action=\"index.php\" method=\"post\"><input type=\"submit\" value=\"HOME\"></form></td><td><form action=\"mansrch.php\" method=\"post\"><input type=\"submit\" value=\"New Search\"></form></td><td><form action='addlease.php' method='post'><input type='submit' value='Add New'></form></td><td><form action='export.php' method='post'><input type='submit' value='Export'></form></td></tr></table>";

			} else {
				echo "No results found<br><br><br>";
				echo "<table><tr><td><form action=\"index.php\" method=\"post\"><input type=\"submit\" value=\"HOME\"></form></td><td><form action=\"mansrch.php\" method=\"post\"><input type=\"submit\" value=\"New Search\"></form></td></tr></table>";
			}
	}
	include "footer.php";
	// This is the end of the file
?>	
