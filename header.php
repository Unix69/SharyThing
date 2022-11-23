<?php

require_once 'myFunctions.php';

HTTPtoHTTPS();
portExchangeHTTPtoHTTPS();
$cookieEnabled = myCookieCheck();
$session_return = mySessionCheck();

	
if ( !$cookieEnabled ){
	echo <<<NOCOOKIE_
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Reservation System</title>
	<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
	</head>	
	<body>
		<div id="header">
			<h1>Welcome to the Reservation System online system</h1>
		</div><!--Header-->
		<div id="tableContainer">
		<div id="tableRow">				
			<div id="sidebar">
			</div><!--Sidebar-->
	<div id="main">	
	<p>Cookies must be enabled in order to navigate the website</p>
	<p><a href='index.php'>Accept</a></p>
	</div><!--Main-->
	</body>
	</html>
	NOCOOKIE_;
	exit;
}
	
if ( $session_return == 1 ) { 
	$user_logged = $_SESSION['gp_user_pg'];
	echo <<<ALLOWED_
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Reservation System</title>
	<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="client.js">
	</script>
	<noscript>Your browser does not support JavaScript! The website wouldn't function correctly</noscript>
	
	</head>
	
	<body>

		<div id="header">
			<h1>Welcome to the Reservation System online system</h1>			
			<a href='logOut.php'>Log Out</a>
			<p>Currently logged in as <i>$user_logged</i> </p>
			</div><!--Header-->
				
			<div id="sidebar">
				<ul>
				<p>Navigation bar</p>
				<li><a href="index.php"/>HOME</a><li>
				<li><a href="loginPage.php"/>Log In</a><li>
				<li><a href="personalPage.php"/>Personal Page</a><li>
				</ul>
			</div><!--Sidebar-->
		
			<div id="main">
	 

	ALLOWED_;
	}
else if ( $session_return == 0 ) {
	echo <<<NOTALLOWED_
	
	 <!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Reservation System</title>
	<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
	<script type="text/javascript" src="client.js">
	</script>
	<noscript>INFO: Javascript is currently disabled on your browser.</noscript>
	</head>
	
	<body>

		<div id="header">
		<h1>Welcome to the Reservation System online system</h1>		
		<a href='registrationPage.php'>Register a FREE account</a>
		</div><!--header-->
		
				
			<div id="sidebar">
				<ul>
				<p>Navigation bar</p>
				<li><a href="index.php"/>HOME</a><li>
				<li><a href="loginPage.php"/>Log In</a><li>
				<li><a href="personalPage.php"/>Personal Page</a><li>
				<li><a href="registrationPage.php"/>Registration Page</a><li>
				</ul>
			</div><!--Sidebar-->
		
			<div id="main">

NOTALLOWED_;
	} else if ( $session_return == 2 ) {	
	echo <<<GUEST_
	
	 <!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Reservation System</title>
	<link href="styles.css" rel="stylesheet" type="text/css" media="screen" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script type="text/javascript" src="client.js">
	</script>
	<noscript>Your browser does not support JavaScript! The website wouldn't function correctly</noscript>
	
	</head>
	
	<body>

		<div id="header">
		<h1>Welcome to the Reservation System system</h1>	
		<a href='registrationPage.php'>Register a FREE account</a>
		</div><!--header-->
		
				
			<div id="sidebar">
				<ul>
				<p>Navigation bar</p>
				<li><a href="personalPage.php?msg=guest"/>Reservation view</a><li>
				<li><a href="loginPage.php"/>Log In</a><li>
				<li><a href="registrationPage.php"/>Registration Page</a><li>
				</ul>
			</div><!--Sidebar-->
		
			<div id="main">

GUEST_;
	}
?>