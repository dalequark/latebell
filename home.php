<?php /*

//
// phpCAS simple client
//

// import phpCAS lib
include_once ('scripts/CAS.php');

phpCAS::setDebug();

// initialize phpCAS
phpCAS::client(CAS_VERSION_2_0, 'fed.princeton.edu', 443, 'cas');

// no SSL validation for the CAS server
phpCAS::setNoCasServerValidation();

// force CAS authentication
phpCAS::forceAuthentication();

// at this step, the user has been authenticated by the CAS server
// and the user's login name can be read with phpCAS::getUser().

// logout if desired
if (isset($_REQUEST['logout'])) {
	phpCAS::logout();
}

$netid = phpCAS::getUser();

// for this test, simply print that the authentication was successful
 
 */
 $netid = 'damarkow';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title charset="UTF-8">cl&#228sshunter</title>
		<meta name="author" content="RustyDroid" />
		<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="style.css">
		<script src="scripts/jquery_library.js"></script>
	</head>
	<body>
		<div id="netid"><?php echo $netid ?></div>
		<div class="row-fluid">
			<div class="span2"></div>
			<div class="span8">
				<div class="hero-unit" id="banner">
					<h1 charset="UTF-8">cl&#228sshunter</h1>
					<p>
						Start hunting classes, <?php echo $netid ?>!
					</p>
				</div>
			</div>
			<div class="span2"></div>
		</div>

		<div class="row-fluid">
			<div class="span2"></div>
			<div class="span2">
				<input type="text" id="classID">
			</div>
			<div class="span1"></div>
			<div class="span5" >
				<div class="alert alert-info" id="errormessage">
					Enter classID to start watching classes!
				</div>
			</div>
			<div class="span2"></div>
		</div>

		<div class="row-fluid">
			<div class="span2"></div>
			<div class="span8">
				<table class="table table-striped">
					<thead>
						<tr>
							<th><strong>Classes</strong></th>
							<th><strong>Section</strong></th>
							<th><strong>Enrollment</strong></th>
						</tr>
					</thead>
					<tbody id="classTable"></tbody>
				</table>
			</div>
			<div class="span2"></div>
		</div>
		<div class="row-fluid">
			<div class="span4"></div>
			<div class="span3 offset1">
				<button class="btn btn-large" onclick="submit()">
					Submit
				</button><a href="?logout=">
		<button class="btn btn-large">Logout</button>
 			</a>
			</div>
			<div class="span4"></div>
		</div>
		<script src="scripts/home.js"></script>
	</body>
</html>
