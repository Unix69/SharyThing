<?php


require_once 'header.php';
require_once 'myFunctions.php';


	if( isset($_GET['msg']) ){
		if ( $_GET['msg']=='loggedOut' ){
			echo "<p>Correctly logged out.<br>Hope to see you soon.</p>";
			unset($_GET['msg']);
			exit;
		}
	}
	


	echo '
	
	<central><h2><font color="#000000">Reservation System</font></h2></central>
	<br>
	<p>Use sidebar for navigation:<br></p>
	<font color="#c0c0c0">
	<p>
	<label><a href="personalPage.php"/>Requests View</a> to check the current offer</label><br>
    <label><a href="loginPage.php"/>Log In</a> to login yourself</label><br>
	<label><a href="registrationPage.php"/>Registration Page</a> to register yourself</label><br>
	<br></p>
	</font>
	<p>The session time is 2minutes</p>
	
	
	<br>
	<p>This site use cookies and Javascript<br>so enable them on your Browser and enjoy yourself</p>';




	echo <<<END_
		</div><!--Main-->
	</body>
	</html>
END_;
?>