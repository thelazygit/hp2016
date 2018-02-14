<?php
	session_start();

	include "header.php";
	If (!isset($_SESSION['username'])) {
		echo $notloggedin;
	} else {

		switch ($_POST['action']) {
			case "new":
				add_new();
				break;
			case "save":
				save_newlease($_POST['applicant'], $_POST['requestor'], $_POST['o_desc']);
				break;
			case "edit":
				edit_record($_POST['recid']);
				break;
			case "delete":
				delete_record($_POST['recid']);
				break;
			case "upload":
				upload_pdf($_POST['recid']);	
				break;	
			case "uploaddoc":
				upload_doc($_POST['entryno'], $_FILES['binFile']['name'], $_FILES['binFile']['tmp_name'], $_FILES['binFile']['size'], $_FILES['binFile']['type'], $_POST['txtDescription'], $_POST['MAX_FILE_SIZE']);
				break;
			case "download":
				download_pdf($_POST['recid']);
				break;
			default:
				list_open();
		}
	}
	function list_open() {
		echo "Open Lease Applications</br>";

		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
			$sql = "SELECT * FROM `workflow` WHERE `institute` LIKE '".$_SESSION['institute']."' ORDER BY req_nr";  
			$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					//Show the list of lease applications
					echo "<table class='st1' cellspacing=0 cellpadding=5>
							<tr>
								<th>Applicant</th>
								<th>Institute</th>
								<th>Requestor</th>
								<th>Order Description</th>
								<th>Documents</th>
								<th colspan=2>Edit</th>
							</tr>";
					while($row = $result->fetch_assoc()) {
						echo "
						<tr>
							<td>".$row['apl_user']."</td>
							<td>".$row['institute']."</td>
							<td>".$row['req_by']."</td>
							<td>".$row['ord_desc']."</td>";
							if (check_blob_record($row['req_nr']) == "no" ) { 
								echo "<td align='center'><form action='newlease.php' method='post'><input type='hidden' name='action' value='upload'><input type='hidden' value='".$row['req_nr']."' name='recid'><button class='button' type='submit'><img src='images/upload_img.png' width='15' height='15'></button></form></td>";
								} else { // Otherwise show up and download buttons
								echo "
								<td align='center'>
									<table>
										<tr>
											<td><form action='newlease.php' method='post'><input type='hidden' name='action' value='download'><input type='hidden' value='".$row['req_nr']."' name='recid'><button class='button' type='submit'><img src='images/download_img.png' width='15' height='15'></button></form></td>
											<!-- <td><form action='newlease.php' method='post'><input type='hidden' name='action' value='upload'><input type='hidden' value='".$row['req_nr']."' name='recid'><button class='button' type='submit'><img src='images/upload_img.png' width='15' height='15'></button></form>
											</td> -->
										</tr>
									</table>
								</td>";
							}
						echo "
							<td><form action='newlease.php' method='post'><input type='hidden' name='action' value='edit'><input type='hidden' name='recid' value='".$row['req_nr']."'><input type='submit' value='Edit'></form></td>
							<td><form action='newlease.php' method='post'><input type='hidden' name='action' value='delete'><input type='hidden' name='recid' value='".$row['req_nr']."'><input type='submit' value='Delete'></form></td>
						</tr>";
					} 
					echo "</table>";

				} else {
					// Inform the user that there are no open leases
					echo "There are currently no open leases.<br>";
				}
				
	}

	function add_new() {
		echo "
				<form action='newlease.php' method='post'>
				<input type='hidden' name='action' value='save'>
				<fieldset><legend>Add New lease Application</legend>
				<table cellpadding='1' cellspacing='0'>
					<tr>
						<th>Applicant</th>
						<td><input type='text' size='35' name='applicant' autofocus></td>
					</tr>
					<tr>
						<th>Requested By</th>
						<td><input type='text' size='35' name='requestor'></td>
					</tr>
					<tr>
						<th>Order Description</th>
						<td><textarea name='o_desc' cols='40' rows='4'></textarea>
					</tr>
					<tr>
						<td colspan='2' align='center'><input type='submit' value='Save'></td>
					</tr>
				</table>
				</fieldset>
				</form>
		";
	}

	function save_newlease($applicant, $requestor, $o_desc) {
		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
			if ($conn->connect_error) { // Check server connection
				die("Connection failed: " . $conn->connect_error);
			}
	    $sql = "INSERT INTO workflow 
	    		(upd_date, upd_by, apl_user, institute, req_by, ord_desc)
	    		VALUES 
	    		(now(), '".$_SESSION['username']."', '$applicant', '".$_SESSION['institute']."', '$requestor', '$o_desc')";
		$result = $conn->query($sql);
echo "New lease for $applicant, by $requestor for $o_desc saved to database. Please upload submissions.<br>";
	}

	function edit_record($recid) {
		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
	    $sql = "select apl_user, institute, req_by, ord_desc from workflow where req_nr = '$recid'";
		$result = $conn->query($sql);
		while($row = $result->fetch_assoc()) {
			echo "
			";
		 }
	}

	function delete_record($recid) { // action delete $_POST['recid']
		if (!($_POST['yes'])) {
			echo "
					<form action='newlease.php' method='post'>
						<input type='hidden' name='action' value='delete'>
						<input type='hidden' name='yes' value='yebo'>
						<input type='hidden' name='recid' value='$recid'>
						You are about to delete a new lease application record. This is irriversible! To proceed click... <br>
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
	    $sql = "CALL delLeaseApplication($recid)";
		$result = $conn->query($sql);
		echo "Records for lease application '$recid' / '".$recid."' have been deleted.<br>";
		}
	}

	function upload_pdf($recid) {
		echo "
			<form method='post' action='newlease.php' enctype='multipart/form-data'>
			<fieldset><legend>Upload lease application document:</legend>
			 <input type='hidden' name='MAX_FILE_SIZE' value='1000000' />
			 <input type='hidden' name='action' value='uploaddoc' />
			 <input type='hidden' name='entryno' value='$recid' />
			 
			 <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='2'>
			  <TR>
			   <th>Description: </th>
			   <TD><TEXTAREA NAME='txtDescription' ROWS='1' COLS='40'></TEXTAREA></TD>
			  </TR>
			  <TR>
			   <th>File: </th>
			   <TD><INPUT TYPE='file' NAME='binFile' ID='binFile' /></TD>
			  </TR>
			  <TR>
			   <TD COLSPAN='2' ALIGN='center'><INPUT TYPE='submit' VALUE='Upload' /></TD>
			  </TR>
			 </table>
			 </fieldset>
			</form>
			"; 
	}

	function upload_doc($entryno, $fileName, $tmpName, $fileSize, $fileType, $txtDescription, $MAX_FILE_SIZE) {
		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
		$data = addslashes(fread(fopen($tmpName, "r"), filesize($tmpName)));
    	$strDescription = addslashes(nl2br($txtDescription));
   		$sql = "insert into nla_tbl_files (`entryno`, `description`, `bin_data`, `filename`, `filesize`, `filetype`) VALUES ('$entryno', '$strDescription', '$data', '$fileName', '$fileSize', '$fileType')";
   		$result = $conn->query($sql);
		echo "Document uploaded to database.";
	}

	function download_pdf($entryno) {
		$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}

		$sql = "select * from nla_tbl_files where entryno=$entryno";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$bytes = $row[bin_data];
		$filename=$row[filename];
		$filetype="$row[filetype]";
		header("Content-type: $filetype");
		header("Content-disposition: attachment; filename=$filename");
		print $bytes;
		echo "Download pdf for lease application no $entryno<br>";
	}




	function check_blob_record($recid) { // Check if the files table contain documents connected to the pc/user
	    $conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
	    $query = "SELECT `entryno`, `filename` FROM `nla_tbl_files` WHERE `entryno` = $recid";
		$fresult = $conn->query($query);
	    if ($fresult->num_rows > 0) {
			return("yes");	
	    } else {
			return("no");
		}
	}
	echo "	<table>
						<tr><td><form action='index.php' method='post'><input type='submit' value='HOME'></form></td>
						<td><form action='newlease.php'><input type='submit' value='List All'></form></td><td><form action='newlease.php' method='post'><input type='hidden' name='action' value='new'><input type='submit' value='Add New'></form></td></tr>
						</table>";
	include "footer.php";
	$conn->close();
?>