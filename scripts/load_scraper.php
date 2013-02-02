	<?php
	
	include ('simple_html_dom.php');
	
	$url = $_POST['url'];
	
	$mysqli = new mysqli("localhost", "root", "root", "latebell");
	
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	
	//Now we will be scraping!
	$term = "1134";
	
	$url2 = "http://registrar.princeton.edu/course-offerings/course_details.xml?courseid=" . $url . "&term=" . $term;
	$html = file_get_html($url2);
	
	//If we don't make a connection, throw an error.
	if ($html->find('body') == NULL) {
		echo 1;
		exit();
	}

	$returnData = array();
	$sections = array();
	$times = array();
	$days = array();
	$enrollments = array();
	
	$returnData['className'] = $html -> find('h2', 1) -> plaintext;
	
	$tableRows = $html -> find('tr');
	$i = 0;
	
	foreach ($tableRows as $row) {
		if ($i > 0) {
			$j = 0;
			$tableData = $row -> find('td');
			foreach ($tableData as $data) {
				switch($j) {
					case 1 :
						$sections[($i - 1)] = $data -> plaintext;
						break;
	
					case 2 :
						$times[($i - 1)] = $data -> plaintext;
						break;
	
					case 3 :
						$days[($i - 1)] = $data -> plaintext;
						break;
	
					case 5 :
						$enrollments[($i - 1)] = $data -> plaintext;
						break;
				}
				$j++;
			}
	
		}
		$i++;
	}
	
	//concatenate url and sectionID and save in uniqueID array.
	$uniqueIDs = array();
	$j = 0;
	foreach ($sections as $section) {
		$uniqueIDs[$j] = $url . $section;
		$j++;
	}
	
	$returnData['sections'] = $sections;
	$returnData['times'] = $times;
	$returnData['days'] = $days;
	$returnData['enrollments'] = $enrollments;
	$returnData['uniqueIDs'] = $uniqueIDs;

	echo json_encode($returnData);
	?>