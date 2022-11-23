<?php

require_once 'header.php';



if ( ! isset($_GET['msg'])) {
	echo "<p>Please insert your desired credentiauhls below.</p>";
} 
else {
	if (  $_GET['msg'] == 'alreadyUsed' ){
		echo "<p>Please choose another username</p>";
	} else if ( $_GET['msg'] == 'wrongCredenzial' ){
		echo "<h1>They cannot be empty</h1>";	
	} else if ( $_GET['msg'] == 'noMatch' ){
		echo "<p>Passwords don't match.</p>";
	} else if( $_GET['msg'] == 'insertionFail' || $_GET['msg'] == 'commitFail' ){
                echo "<p>System can not create account</p><p>Try another time</p>";
        } else if ( $_GET['msg'] == 'alreadyR' ){
		echo "<h1>They cannot be empty</h1>";	
	}
        
}


echo <<<FORM_

	<form method="post" action="checkRegistration.php" class="myformstyle" onsubmit="return validateInput();">
	<label><span class="glyphicon glyphicon-user" id="checkUserName"> Username </span><input type="text" onkeyup="onChangeEmail()" placeHolder="E-mail Address" id="username" name="username" title="Insert Username Here"></label>
	<label><span class="glyphicon glyphicon-lock" id="checkPassword"> Password </span><input type="password" onkeyup="onChangePassword()" placeHolder="Password" id="password" name="password" title="Insert Password Here"></label>
	<label><span class="glyphicon glyphicon-lock" id="checkConfirmPassword"> Confirm </span><input type="password" onkeyup="onChangeConfirmPassword()" placeHolder="Confirm Password" id="confirmpassword" name="confirmpassword" title="Insert the same password inserted above" ></label>
    <label><div id="checkErr"></div></label>
	<button type="button" id="eye" onclick="hideShow()" title="Show/Hide Password">
    <img src="https://cdn0.iconfinder.com/data/icons/feather/96/eye-16.png"/>
    </button>
	<br>
	<br>
	<input type="submit" value="Register" name="tryReg" onclick="validateInput()">
	</form>
FORM_;




echo <<< END_
        </div>


		<!--Main--></div>
		<!-- TableRow -->
		</div>	<!--TableContainer -->
		</body>
        </html>
END_;

?>