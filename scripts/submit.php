<?php
$netid = $_POST['netid'];
$uniqueIDs = $_POST['uniqueIDs'];
$mysqli = new mysqli("localhost", "root", "root", "latebell");

if (mysqli_connect_errno()) {
	echo 1;
	exit();
}

//check to see if user is already in database.
$query = "SELECT netid FROM netids WHERE netid = '$netid'";
$result = $mysqli -> query($query);
$row = $result -> fetch_assoc();

//if not add user.
if ($row == NULL) {
	$query = "INSERT INTO netids 
				(netid) 
			VALUES ('$netid')";
	$mysqli -> query($query);
	
}

//delete all past user data
	$query = "DELETE FROM assoc WHERE netid='$netid'";
	$mysqli -> query($query);
	
//add classes if they'r enot being tracked, and add 
//user data to assoc table
foreach ($uniqueIDs as $id) {
	
	$query = "SELECT uniqueid FROM uniqueids WHERE uniqueid = '$id'";
	$result = $mysqli -> query($query);
	$row = $result -> fetch_assoc();
	if ($row == NULL) {
		$query = "INSERT INTO uniqueids 
				(uniqueid) 
			VALUES ('$id')";
		$mysqli -> query($query);
	} 
	
	$query = "INSERT INTO assoc 
				(netid, uniqueid) 
			VALUES ('$netid', '$id')";
	$mysqli -> query($query);
}

$mysqli->close();
?>