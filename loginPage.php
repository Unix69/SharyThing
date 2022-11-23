<?php

require_once 'header.php';

if( ! isset($_SESSION['gp_user_pg']) ){

if ( !isset($_GET['msg']) ){
	echo "<p>You can login using your credentials.<p>";
} else {
	if ( $_GET['msg'] == 'notOkLog' ){
		echo "<p>Wrong Username or Password.<p>";
	} else if ( $_GET['msg'] == 'emptyField' ){
		echo "<p>Invalid Input.<p>";
	} else if ( $_GET['msg'] == 'timeE' ){
		echo "<br><p>You must <a href='loginPage.php'>login</a> or <a href='registrationPage.php'>register</a> in order to make an offer</p>";
	}  
}

echo <<<ALL1_
				
				<div id="loginDIV">
				<p>
				<center>
				<form action="checkLogin.php" method="POST" class="myformstyle" onsubmit="return validateInputL();">
				<label><span class="glyphicon glyphicon-user" id="checkUserName"> Username </span><input placeHolder="E-mail" onkeyup="onChangeEmail()"  type="text" name="name" id="username" title="Insert your Username here"></label>
				<label><span class="glyphicon glyphicon-lock" id="checkPassword"> Password </span><input  placeHolder="Password" onkeyup="onChangePassword()" type="password" name="psw" id="password" title="Insert your password here"></label>
                <label><div id="checkErr"></div></label>
                <button type="button" id="eye" onclick="hideShow()" title="Show/Hide Password">
                <img src="https://cdn0.iconfinder.com/data/icons/feather/96/eye-16.png"/>
                </button>
	            <br>
	            <br>
				<input type="submit" value="LogIn">
				</form>
				<center>
				</p>
				<p>New User? Register a new account for free <a href="registrationPage.php">HERE</a>.</p>
				</div>
				</div><!--Main-->

</body>
</html>




ALL1_;
} else {
	$thisU = $_SESSION['gp_user_pg'];
	echo "<p>You are already logged in as $thisU.<br><br>Aren't you $thisU? You can <a href='logOut.php'> log out</a>.</p>";
}
?>