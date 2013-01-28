<?php
    $classID = $_POST["classID"];
    mysql_connect("localhost","root","root");
	mysql_select_db("latebell");
	$order = "INSERT INTO classes
		(classID)
		VALUES
		('$classID')"; 
	$result = mysql_query($order); 
	if($result){
		echo("<br>Input data is succeed");
	} else{
		echo("<br>Input data is fail");
	}
?>