<?php

include ('simple_html_dom.php');

$test1 = "collinfacebook";
$test2 = "Something";

$ch = curl_init();

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, 'api:key-9tz230ztjxso5jkdownxif6b68kxsjy0');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v2/class-hunter.mailgun.org/messages');
curl_setopt($ch, CURLOPT_POSTFIELDS, array('from' => 'ClassHunter <collin@class-hunter.mailgun.org>', 'to' => $test1.'@gmail.com', 'subject' => 'Class Captured -- Enroll Now!', 'text' => 'Good news! ' . $test2 . " has space for new enrollments. Don't miss your opportunity to sign up! Happy hunting,\n\n-PrincetonClassHunter.com"));

$result = curl_exec($ch);
curl_close($ch);

$mysqli = new mysqli("localhost", "root", "root", "latebell");
if ($mysqli -> connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli -> connect_errno . ") " . $mysqli -> connect_error;
	exit();
}

$query = "SELECT * FROM uniqueids";
$results = $mysqli -> query($query);
while (($row = $results -> fetch_array()) != NULL) {
	$uniqueID = $row[0];
	$url = substr($uniqueID, 0, 6);

	//Now we will be scraping!
	$term = "1134";

	$url2 = "http://registrar.princeton.edu/course-offerings/course_details.xml?courseid=" . $url . "&term=" . $term;
	
	$html = file_get_html($url2);

	//If we don't make a connection, throw an error.
	if ($html -> find('body') == NULL) {
		exit();
	}

	$siteString = $html -> find('table', 0) -> plaintext;

	$section = substr($uniqueID, 6);
	$tableRow = substr($siteString, strpos($siteString, $section));

	$enrollments = substr($tableRow, strpos($tableRow, "Enrolled:"));
	$enrolled = substr($enrollments, 9, (strpos($enrollments, "Limit:") - 11));
	$enrollments = substr($enrollments, strpos($enrollments, "Limit:"));
	$limit = substr($enrollments, 6, strpos($enrollments, "\n") - 6);

	if ((int)$enrolled < (int)$limit) {

		$query = "SELECT netid FROM assoc WHERE uniqueid = '$uniqueID'";
		$collin = $mysqli -> query($query);
		while (($netid = $collin -> fetch_array()) != NULL) {

			$id = $netid[0];

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, 'api:key-9tz230ztjxso5jkdownxif6b68kxsjy0');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v2/class-hunter.mailgun.org/messages');
			curl_setopt($ch, CURLOPT_POSTFIELDS, array('from' => 'ClassHunter <collin@class-hunter.mailgun.org>', 'to' => $uniqueID . '@princeton.edu', 'subject' => 'Class Captured -- Enroll Now!', 'text' => 'Good news! ' . $id . " has space for new enrollments. Don't miss your opportunity to sign up! Happy hunting,\n\n-PrincetonClassHunter.com"));

			$result = curl_exec($ch);
			curl_close($ch);

			$query = "DELETE FROM assoc WHERE netid = '$id' AND uniqueid = '$uniqueID'";
			$delete = $mysqli -> query($query);
		}
	}
}
?>