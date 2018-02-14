<?php
	session_start();
		include "header.php";
		if(!isset($_SESSION['username'])) {
			echo $notloggedin;
	} else {
	IF ($_POST['recid']) { $_SESSION['recid'] = $_POST['recid']; }

	switch ($_POST['actn']) {
		case "down":
			$_SESSION['actn'] = $_POST['actn'];
			show_download();
			break;
		case "up":
			show_upload();
			break;
		case "loadfile":
			load_file();
			break;
		default:
			echo "You fool! You cannot access this file without using the force!";
	}

	echo "<br>
			<table border=0 cellspacing=2 cellpadding=0><tr><td><form action='index.php' method='post'><input type='submit' value='HOME'></form></td>
			<td><form action='list.php' method='post'><input type='submit' value='List'></form></td></tr></table>";
	include "footer.php";
	}


	// DEFINE FUNCTIONS
	function show_download() {
			$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
				$sql = "SELECT `id_files`, `entryno`, `description`, `filename` FROM `hed_tbl_files` WHERE `entryno` = '".$_SESSION['recid']."' ORDER BY `id_files`";
				$result = $conn->query($sql);
				
			if ($result->num_rows > 0) {
						echo "<table class=st1 cellspacing=0 cellpadding=3>
						<tr height=20><th colspan=7 align='center'>Files on database for record ".$_POST['recid']."</th></tr><tr><th>FileID</th><th>EntryNO</th><th>Description</th><th>File Name</th><th align='center' colspan=2>Action</th></tr>";
						while ($row = $result->fetch_assoc()) {
							echo "<tr><td>".$row['id_files']."</td><td>".$row['entryno']."</td><td>".$row['description']."</td><td>".$row['filename']."</td>
							<td><form action='file.php' method='post'><input type='hidden' name='actn' value='download'><input type='hidden' name='file_id' value='".$row['id_files']."'><input type='hidden' name='entryno' value='".$row['entryno']."'><input type='submit' value='Download'></form></td>
							<!-- <td><form action='file.php' method='post'><input type='hidden' name='actn' value='edit'><input type='submit' value='Edit'></form></td> -->
							<td><form action='file.php' method='post'><input type='hidden' name='actn' value='delete'><input type='hidden' name='file_id' value='".$row['id_files']."'><input type='submit' value='Delete'></form></td>
							</tr>";
						}
				echo "	</table>";
			} else {
				echo "Zero files found for ".$_POST['recid']."<br>";
				$conn->close();
			}
	}
	
	function show_upload() {
			$_SESSION['actn'] = $_POST['actn'];
			echo "<FORM METHOD='post' actn='documents.php' ENCTYPE='multipart/form-data'>
		 			<INPUT TYPE='hidden' NAME='MAX_FILE_SIZE' VALUE='1000000' />
					 <INPUT TYPE='hidden' NAME='actn' VALUE='loadfile' />
					 <table border='1' cellpadding='0' cellspacing='0'><tr><td>
					 <TABLE BORDER='0' CELLPADDING='0' CELLSPACING='2' BGCOLOR='#cccccc'>
					  <TR>
					   <TD valign='top'><b><font color='#000000'>Document: </TD>
					   <TD><SELECT NAME='txtDescription' ><option value='Due Care Agreement'>Due Care Agreement</option><option value='Rentworks Report'>Rentworks Report</option><option value='ARC Report'>ARC Report</option><option value='SAPS Report'>SAPS Report</option></SELECT></TD>
					  </TR>
					  <TR>
					   <TD><b><font color='#000000'>File: </TD>
					   <TD><font color='#000000'><INPUT TYPE='file' NAME='binFile' ID='binFile' /></TD>
					  </TR>
					  <TR>
					   <TD COLSPAN='2' ALIGN='center'><INPUT TYPE='submit' VALUE='Upload' /></TD>
					  </TR>
					 </TABLE>
					 </td></tr></table>
					</FORM>";
	}

	function load_file() {
		$fileName = $_FILES['binFile']['name'];
		$tmpName = $_FILES['binFile']['tmp_name'];
		$fileSize = $_FILES['binFile']['size'];
		$fileType = $_FILES['binFile']['type'];
		$recno = ($_SESSION['recid']);
		$txtDescription = ($_POST["txtDescription"]);
		$MAX_FILE_SIZE = ($_POST["MAX_FILE_SIZE"]);
		
		if (isset($fileName) && $fileName != "none") {
			$data = addslashes(fread(fopen($tmpName, "r"), filesize($tmpName)));
			$strDescription = addslashes(nl2br($txtDescription));
			$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
				if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
			$sql = "INSERT INTO `hed_tbl_files` (entryno, description, bin_data, filename, filesize, filetype) VALUES ('$recno', '$strDescription', '$data', '$fileName', '$fileSize', '$fileType')";
			$result = $conn->query($sql);
			echo "File ".$_FILES['binFile']['name']." was saved to record number ".$_SESSION['recid']."<br>";
		} else {
			print "There is a Problem.<br>The FileName is $fileName<br>";
		}
		$conn->close();
	}
	
?>