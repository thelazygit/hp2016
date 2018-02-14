<?php

	session_start();
	if($_POST['actn']) { $_SESSION['actn'] = $_POST['actn']; }
	include "header.php";
	If (!isset($_SESSION['username'])) {
		echo $notloggedin;
	} else {

	switch ($_POST['actn']) {
		case "download":
			download_pdf($_POST['file_id'], $_POST['entryno']);
			$actn="down";
			break;
		case "edit":
			echo "Edit file";
			$actn="edit";
			break;
		case "delete":
			file_delete($_POST['file_id']);
			break;
		default:
			echo "Something is wrong";
	}
	echo "<br>
			<table border=0 cellspacing=0=2 celpadding=0>
			<tr><td><form action='list.php' method='post'><input type='submit' value='List'></form></td>
			<td><form action='documents.php' method='post'><input type='hidden' name='recid' value='".$_SESSION['recid']."'><input type='hidden' name='actn' value='".$_SESSION['actn']."'><input type='submit' value='Back'></form></td></tr>
			</table>";
	
	unset($_SESSION['recid']);
	}

	function file_delete($file_id) {
		if (!($_POST['yes'])) {
			echo "	You are about to detele a document from the database. This is irriversible! To proceed click...<br><br>
					<form action='file.php' method='post'>
						<input type='hidden' name='file_id' value='$file_id'>
						<input type='hidden' name='actn' value='delete'>
						<input type='hidden' name='yes' value='yebo'>
						<table>
							<tr>
								<td><input type='submit' value='Delete'></form></td>
								<td><form action='index.php' method='post'><input type='submit' value='Cancel'></form></td>
							</tr>
						</table>
			";
		} else {
		$conn = new mysqli('localhost', 'archp2', 'archp2@@', 'hp_lease_2016');
			if ($conn->connect_error) { // Check server connection
					die("Connection failed: " . $conn->connect_error);
				}
				$sql = "DELETE FROM `hed_tbl_files` WHERE `id_files` = ".$file_id."";
				$result=$conn->query($sql);
				echo "File ".$_POST['file_id']." was deleted from database.";
		}
	}

	function download_pdf($file_id, $entryno) {
			$conn = new mysqli("localhost", "archp2", "archp2@@", "hp_lease_2016");
					if ($conn->connect_error) { // Check server connection
						die("Connection failed: " . $conn->connect_error);
					}

			$sql = "select * from hed_tbl_files where id_files='$file_id'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			$bytes = $row[bin_data];
			$filename=$row[filename];
			$filetype="$row[filetype]";
			header("Content-type: $filetype");
			header("Content-disposition: attachment; filename=$filename");
			print $bytes;
			echo "Download pdf for lease record no $entryno<br>";
		}

	include "footer.php";
	$conn->close();
?>