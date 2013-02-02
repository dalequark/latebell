<?php /*
include ('simple_html_dom.php');

$html = file_get_html('other6.html');

$tablerows = $html -> find('tr');

mysql_connect("localhost", "root", "root");
mysql_select_db("latebell");

foreach ($tablerows as $data) {

	$classID = $data -> find('strong', 0);
	$url = $data -> find('a', 0);

	if (!($classID == null || $url == null)) {

		//get rid of crap around urls.
		$url = substr($url, 37, 6);

		//get rid of strong tags around classID.
		$classID = substr($classID, 8, 5);
		
		echo $classID . " " . $url . "<br>";

		
		 //insert the data into our classid table.
		 $insertData = "INSERT INTO classids
		 (classid, url)
		 VALUES
		 ('$classID', '$url')";

		 $result = mysql_query($insertData);

		 if(!$result){
		 echo("<br>FATAL ERROR AT " + $i);
		 }
		 
	}

}
*/