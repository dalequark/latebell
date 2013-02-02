<?php

//
// phpCAS simple client
//

// import phpCAS lib
include_once('CAS.php');

 

phpCAS::setDebug();

 

// initialize phpCAS
phpCAS::client(CAS_VERSION_2_0,'fed.princeton.edu',443,'cas');

 

// no SSL validation for the CAS server
phpCAS::setNoCasServerValidation();

 

// force CAS authentication
phpCAS::forceAuthentication();

 

// at this step, the user has been authenticated by the CAS server
// and the user's login name can be read with phpCAS::getUser().

$netid = phpCAS::getUser();

if (isset($_REQUEST['logout'])) {
 	phpCAS::logout();
}

echo $netid;

?>

