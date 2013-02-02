<?php
	$classID = $_POST["classID"];
	$email = $_POST["netid"];
	mysql_connect("localhost", "root", "root");
	mysql_select_db("latebell");
	
	
	//is this class ID in the database yet? If not, add it.
	$result = mysql_query("SELECT * FROM classes
		WHERE classID='$classID'");
	$array = mysql_fetch_array($result);
	if (!$array) {
		$insertClassID = "INSERT INTO classes
			(classID)
			VALUES
			('$classID')";
		$result = mysql_query($insertClassID);
		if (!$result) {
			echo("Data could not be submitted. Please try again later.");
		}
	}
	
	$insertNetID = "INSERT INTO netids 
		(netid, classID)
		VALUES
		('$netid', '$classID')";
	$result = mysql_query($insertNetID);
	if (!$result) {
		echo("Data could not be submitted. Please try again later.");
	}
?>