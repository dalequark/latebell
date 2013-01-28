<?php
    $classID = $_POST["classID"];
	$email = $_POST["email"];
    mysql_connect("localhost","root","root");
	mysql_select_db("latebell");
	
	
	//Checks to make sure we have received a five-digit int for class number.
	if(!is_numeric($classID) | strlen($classID) != 5){
		echo("<br>Not a valid classID.");
		return;	
	}
	
	//is this class ID in the database yet? If not, add it.
	$result = mysql_query("SELECT * FROM classes
	WHERE classID='$classID'");
	$array = mysql_fetch_array($result);
	if(!$array){
		$insertClassID = "INSERT INTO classes
		(classID)
		VALUES
		('$classID')";
		$result = mysql_query($insertClassID); 
		if($result){
			echo("<br>Input data is succeed");
		} else{
			echo("<br>Input data is fail");
		}
	}
	
	$insertEmail = "INSERT INTO emails 
	(email, classID)
	VALUES
	('$email', '$classID')";  				
	$result = mysql_query($insertEmail);
	if($result){
		echo("<br>Input data is succeed (email)");
	} else{
		echo("<br>Input data is fail (email)");
	}
	
?>