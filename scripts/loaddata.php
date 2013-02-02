<?php
	$netid = $_POST['netid'];
	
	$mysqli = new mysqli("localhost", "root", "root", "latebell");
	
	if (mysqli_connect_errno()) {
		echo 'mysqlError';
		exit();
	}
	
	$urls = array();
	$sections = array();
	$i = 0;

	
	//check to see if user is already in database.
	$query = "SELECT uniqueid FROM assoc WHERE netid = '$netid'";
	$result = $mysqli -> query($query);
	$row = $result -> fetch_assoc();
	
	if ($row == NULL) {
		echo 'noClasses';
		exit();
	}
	else{
		do {
			$uniqueID = $row['uniqueid'];
			$urls[$i] = substr($uniqueID,0,6);
			$sections[$i] = substr($uniqueID,6);
			$i++;
			$row = $result -> fetch_assoc();
		} while($row != NULL);
	}
	
	
	$returnData = array();
	$returnData['urls'] = $urls;
	$returnData['sections'] = $sections;
	
	echo json_encode($returnData);
	
	
?>