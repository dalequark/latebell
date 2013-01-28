<?php
	include('simple_html_dom.php');  
	$html = new simple_html_dom();  
	$html->load_file('http://registrar.princeton.edu/course-offerings/course_details.xml?courseid=000004&term=1134.com/');  
	# get an element representing the second paragraph  
	$element = $html->find("p");  
	# modify it  
	echo($element[2]);
	echo("test");
?>