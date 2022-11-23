<?php





function mySessionCheck(){
	session_start();
	$idle=time();
	
	$new_s = false;
	$allowed_idle = 120; // 2 minutes

if  ( isset($_SESSION['gp_user_pg']) ) {
	if ( isset($_SESSION['gp_timer_pg']) ) {
		$t = $_SESSION['gp_timer_pg'];
		$idle = time() - $t;
	} else {
		$new_s = true;
	}
	if ( $idle < $allowed_idle ) {
		$_SESSION['gp_timer_pg'] = time(); // update use count timer
		return 1;
	} else if ( $new_s == true || $idle >= $allowed_idle ){
		$_SESSION=array();
     if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 3600*24, $params["path"],$params["domain"], $params["secure"], $params["httponly"]);
      }
      session_destroy();
      header('HTTP/1.1 307 temporary redirect');
      header('Location: loginPage.php');
   	  return 0;
	}
	}
	else {
	// guest user
		$_SESSION['gp_guest_pg'] = 'guest';
		return 2;
	}
}



 

function validateCredenzial($usr, $psw, $cpsw){
if (!filter_var($usr, FILTER_VALIDATE_EMAIL)){
    return(false);
}

if(!preg_match( "/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{3,}/", $psw)){
   return(false);
}

if($cpsw != $psw){
   return(false);
}
			
return(true);

}





function validateRequest($min){
return(preg_match( "/^\d+$/" ,$min) && intval($min) > 0 && intval($min) < 180);
}




function HTTPtoHTTPS(){

     if ( !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
         //https request
     } else {
         $redirect = 'https://' . $_SERVER['HTTP_HOST'] .
         $_SERVER['REQUEST_URI'];
         header('HTTP/1.1 301 Moved Permanently');
         header('Location: ' . $redirect);
         exit;
     }

}



function portExchangeHTTPtoHTTPS(){

if ($_SERVER['SERVER_PORT'] != 443) { // https port
	header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	exit;
}

}

function GetDateTime($format=""){
	if($format == ""){
		$date = date('Y/m/d H:i:s');
	} else {
		$date = date($format);
	}
	return $date;
}




function myCookieCheck(){
	setcookie("tryCookie", "try");
	
	if ( !isset( $_COOKIE['tryCookie'] )) {
		return false;
	}
	
	return true;
}

function sanitizeString($var)
{
		$var = strip_tags($var);
		$var = htmlentities($var);
		$var = stripcslashes($var);
		return($var);
}

// database management functions

function my_db_connect(){
        //$connection = mysqli_connect("localhost","s244405","nashitys", "s244405");
	$connection = mysqli_connect("127.0.0.1","root","", "resdb");
	if( ! $connection ) {
		die('Connect error (' . mysqli_connect_errno() . ')' . mysqli_connect_error());
	} 	
	return $connection;
}







function getAvaiable($connection){
$result=mysqli_query($connection,"SELECT * FROM assignment FOR UPDATE");
	 $rows=mysqli_fetch_array($result);  
	 if(mysqli_num_rows($result) == 0){
	    mysqli_endwithrollback($connection, "personalPage.php?msg=rollbackT");
}


$result=mysqli_query($connection,"SELECT sum(duration) FROM assignment FOR UPDATE");
	 $rows=mysqli_fetch_array($result);  
	 if(mysqli_num_rows($result) == 0){
	    mysqli_endwithrollback($connection, "personalPage.php?msg=rollbackT");
	  }
	  $avaiable=180-$rows[0];
 mysqli_free_result($result);
 return($avaiable);

}





function getObtainedFromUser($connection,$user){
$result=mysqli_query($connection,"SELECT duration FROM assignment WHERE username='".$user."' FOR UPDATE");
	 $rows=mysqli_fetch_array($result);  
	 if(mysqli_num_rows($result) == 0){
	    return (0);
	 }
	 $obtained=$rows[0];
 mysqli_free_result($result);
 return($obtained);
}





















function getUsers($connection){
 $result=mysqli_query($connection,"SELECT username FROM reservation FOR UPDATE");
 $users = Array();
 $i = 0;
 while($row = mysqli_fetch_array($result)){
     $users[$i++] = $row[0];
 }
 mysqli_free_result($result);
 return($users);
}




function getStart($connection){
 $result=mysqli_query($connection,"SELECT start FROM assignment FOR UPDATE");
 $start = Array();
 $i = 0;
 while($row = mysqli_fetch_array($result)){
     $start[$i++] = $row[0];
 }
 mysqli_free_result($result);
 return($start);

}



function getAllRequest($connection){
$result=mysqli_query($connection,"SELECT reservation_min FROM reservation FOR UPDATE");
$requests = Array();
$i = 0;
 while($i < mysqli_num_rows($result)){
     $row = mysqli_fetch_array($result);
     $requests[$i++] = $row[0];
 }
 mysqli_free_result($result);
 return($requests);
}


function adjustHour($hour){
if($hour > 14.59 && $hour < 15.01)
   $hour = 15.00;
else if($hour > 15.59 && $hour < 16.01)
   $hour = 16.00;
else if($hour > 16.59 && $hour < 17.01)
   $hour = 17.00;
return ($hour);
}


function getObtained($connection){
 $obtained = Array();
 $result=mysqli_query($connection,"SELECT duration FROM assignment");
 $i = 0;
 while($row = mysqli_fetch_array($result)){
     $obtained[$i++] = $row[0];
 }
 
 mysqli_free_result($result);
 return($obtained);
}


function getEnd($connection){
 $start = Array();
 $end = Array();
 $duration = Array();
 $start=getStart($connection);
 $duration=getObtained($connection);
 
 for($i = 0; $i < count($duration); $i++){
       $end[$i] = $start[$i] + floor($duration[$i]/60) + ($duration[$i]%60)/100;
       $end[$i] = adjustHour($end[$i]);
             
 }

 return($end);

}







function compareEntry($users, $user){
for($i = 0; $i < count($users); $i++){

    if($users[$i] == $user){
       return(true); 
    }
}
return(false);
}


function getLastRequestFromUser($connection,$user){
$result=mysqli_query($connection,"SELECT reservation_min FROM reservation WHERE username='".$user."' FOR UPDATE");
	 $rows=mysqli_fetch_array($result);  
	 $lastRequest=$rows[0];
 mysqli_free_result($result);
 return($lastRequest);

}



function updateReservation($req_username, $input, $connection){

 if(mysqli_num_rows(mysqli_query($connection, "SELECT username FROM reservation WHERE username='" . $req_username ."'")) == 0){ 
        $sql = "INSERT INTO reservation (username, reservation_min) VALUES('" . $req_username ."' , '" .  $input . "' )";
      } else {
        $sql = "UPDATE reservation SET reservation_min ='" .  $input . "' WHERE username='" . $req_username ."'";
             }
	   if(!mysqli_query($connection,$sql)){
		   mysqli_endwithrollback($connection, "personalPage.php?msg=rollbackT");
	   }
}




function assignmentInsert($req_username, $connection, $start, $duration){
  if(mysqli_num_rows(mysqli_query($connection, "SELECT username FROM assignment WHERE username='" . $req_username ."'")) == 0){ 
        $sql = "INSERT INTO assignment (username, start, duration) VALUES('" . $req_username ."' , '" .  $start . "', '" . $duration ."' )";
      } else {
        $sql = "UPDATE assignment SET start ='" .  $start . "', duration ='" .  $duration . "' WHERE username='" . $req_username ."'";
             }
	   if(!mysqli_query($connection,$sql)){
		   mysqli_endwithrollback($connection, "personalPage.php?msg=rollbackT");
	   }
}


function getFirstFreeAssignment($connection){
  $result=mysqli_query($connection,"SELECT max(start) FROM assignment");
	 $rows=mysqli_fetch_array($result);  
	 if(mysqli_num_rows($result) == 0 || $rows[0] == 0){
	    $start=14.00;
	  }
	  else{
           $start=$rows[0];
      }
      
   $result=mysqli_query($connection,"SELECT duration FROM assignment WHERE start='" . $start ."'");
	 $rows=mysqli_fetch_array($result);  
	 if(mysqli_num_rows($result) == 0 || $rows[0] == 0){
	    $duration = 0;
	  }
	  else{
           $duration = $rows[0];
      }
 
 mysqli_free_result($result);
  
  return($start + floor($duration/60) + ($duration%60)/100);
}


function setProfileImage($user){
$dir  = './'.$user;
            $files = scandir($dir);
            if(count($files) > 0 && count($files) == 3){
              return($dir.'/'.$files[2]);
            }
}


function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}


function login_check(){
if ( isset( $_POST['name'] ) && isset( $_POST['psw'] ) ){
	if ( $_POST['name']!='' && $_POST['psw'] != '' ){
		$connection = my_db_connect();
		// ok
		if(!validateCredenzial($_POST['name'], $_POST['psw'], $_POST['psw'])){
		   header('Location: personalPage.php?msg=okLog');
		   exit;
		}
		
		$user = mysqli_real_escape_string($connection, sanitizeString($_POST['name']));
		$psw = md5(mysqli_real_escape_string($connection, sanitizeString($_POST['psw'])));
		$rows = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM users WHERE username='$user' AND password='$psw'"));
		if ( $rows != 0 ) {
			// ok, existing username and password
			session_start();
			$_SESSION['gp_timer_pg'] = time();
			$_SESSION['gp_user_pg'] = $user;
			mysqli_close($connection);
			header('Location: personalPage.php?msg=okLog');
            }
			
		else {
			// not ok
			mysqli_close($connection);
			header('Location: loginPage.php?msg=notOkLog');
		}
		
	} else {
		header('Location: loginPage.php?msg=emptyField');
	}
} else {
	header('Location: loginPage.php?msg=emptyField');
}



}



function insert_User(){
if ( ( isset($_POST['username']) && isset($_POST['password']) ) && ( $_POST['username'] != '' && $_POST['password'] != '' ) ){
	
	$connection = my_db_connect();
	       
	// sanitize input
	$req_username = mysqli_real_escape_string($connection, sanitizeString($_POST['username']));
	$req_password = mysqli_real_escape_string($connection,sanitizeString($_POST['password']));
	$req_confirmpsw = mysqli_real_escape_string($connection,sanitizeString($_POST['confirmpassword']));
	
	
	
       	if (!validateCredenzial($req_username, $req_password, $req_confirmpsw)) {
		     header('Location: registrationPage.php?msg=wrongCredenzial');
		     exit;
	    }
	    
	
	$req_password = md5($req_password);
	
    
	if (mysqli_num_rows(mysqli_query($connection, "SELECT * FROM users WHERE username='$req_username'")) == 0) {
		// ok, insert into database
		if ( mysqli_query($connection,"INSERT INTO users (username, password) VALUES('" . $req_username ."' , '" .  $req_password . "' )")) {
                mysqli_close($connection);
	            header('Location: registrationPage.php?msg=commitFail');
                    }
			mysqli_close($connection);
			session_start();
			
			$path = $req_username;
            
            if(!mkdir($path)) {
              header('Location: registrationPage.php?msg=wrongCredenzial');
	          exit;
            }
            
			$_SESSION['gp_timer_pg'] = time();
			$_SESSION['gp_user_pg'] = $req_username;
			header('Location: personalPage.php');
		} else {
		               mysqli_close($connection);
         	           header('Location: registrationPage.php?msg=alreadyUsed');
         	           exit;	
	 }
}
else {
	// redirect
	header('Location: registrationPage.php?msg=wrongCredenzial');
	exit;
}

}



function mysqli_endwithcommit($connection, $redirect){
if(!mysqli_commit($connection)){
   msqli_rollback($connection);
   mysqli_autocommit($connection,true);
   mysqli_close($connection);
   header('Location: '.$redirect);
   exit;
}


mysqli_autocommit($connection,true);
mysqli_close($connection);
header('Location: '.$redirect);
exit;

}

function mysqli_endwithrollback($connection, $redirect){
mysqli_rollback($connection);
 
mysqli_autocommit($connection,true);
mysqli_close($connection);

header('Location: '.$redirect);
exit;

}


function deleteReservation($req_username,$connection){
 if(mysqli_num_rows(mysqli_query($connection, "SELECT username FROM assignment WHERE username='" . $req_username ."'")) != 0){ 
        $sql = "DELETE FROM assignment WHERE username='" .  $req_username . "'";
             }
   else{
        return(false);
   }
   
	   if(!mysqli_query($connection,$sql)){
		   mysqli_endwithrollback($connection, "personalPage.php?msg=rollbackT");
	   }
	   
 if(mysqli_num_rows(mysqli_query($connection, "SELECT username FROM reservation WHERE username='" . $req_username ."'")) != 0){ 
        $sql = "DELETE FROM reservation WHERE username='" .  $req_username . "'";
         }
   else{
        return(false);
     }
     
     
	   if(!mysqli_query($connection,$sql)){
		   mysqli_endwithrollback($connection, "personalPage.php?msg=rollbackT");
	   }
  
  
  return(true);
  
}



function rebalanceReservations($req_username,$connection){
$requested=getAllRequest($connection);
$sumR = 0;
$newObtained = 0;
$users=getUsers($connection);
$newStart = 14.00;



for($i = 0;$i < count($requested); $i++){
   $sumR += $requested[$i];
}

if($sumR == 0){
   return(true);
}

else if($sumR < 180){
//alloc contigua
       
       for($i = 0 ; $i < count($users); $i++){
       $newStart = adjustHour($newStart + floor($newObtained/60) + (($newObtained%60)/100));
       $newObtained = $requested[$i];
       assignmentInsert($users[$i], $connection, $newStart, $newObtained);
       
       }
}
else
{   
       $avaiable = getAvaiable($connection);
       for($i = 0 ; $i < count($users); $i++){
         $newStart = adjustHour($newStart + floor($newObtained/60) + (($newObtained%60)/100));
         $newObtained = ($requested[$i] / $sumR) * 180;
         assignmentInsert($users[$i], $connection, $newStart, $newObtained);
       }
       
}
return(true);
}













function handelNewRequest($input, $req_username, $connection){
  $newObtained = 0;
  $avaiable = getAvaiable($connection);
  
    if($avaiable == 0){

      mysqli_endwithcommit($connection, "personalPage.php?msg=refusedR");

    } else if($avaiable >= $input){

    updateReservation($req_username, $input, $connection);
	$newStart = adjustHour(getFirstFreeAssignment($connection));
    $duration=$input;
	assignmentInsert($req_username, $connection, $newStart, $duration);

    } else {
       $duration=getAllRequest($connection);//block all requests
       $users=getUsers($connection);//block all user
	   $sumR = 180 - $avaiable + $input;
	   $newStart = 14.00;
	   //single update of reservation and multiple update of assigment
       for($i = 0 ; $i < count($users); $i++){
       $newStart = adjustHour($newStart + floor($newObtained/60) + (($newObtained%60)/100));
       $newObtained = ($duration[$i] / $sumR) * 180;
       assignmentInsert($users[$i], $connection, $newStart, $newObtained);
       }
       
       $newStart = adjustHour($newStart + floor($newObtained/60) + (($newObtained%60)/100));
       $newObtained = ($input / $sumR) * 180;
       updateReservation($req_username, $input, $connection);    
       assignmentInsert($req_username, $connection, $newStart, $newObtained);

	}




}


function setCSSvariable($heigth){

echo '<style>

#reservations {
    border-width:5px;  
    border-style:ridge;
    width: 750px;
    height: '.$heigth.'px;
    background-color: grey;
    color: white;
    -webkit-animation: mymove 5s infinite; /* Chrome, Safari, Opera */
    animation: mymove 5s infinite;
}

/* Chrome, Safari, Opera */
@-webkit-keyframes mymove {
    50% {box-shadow: 5px 7px 10px white;}
}

/* Standard syntax */
@keyframes mymove {
    50% {box-shadow: 5px 7px 10px white;}
}

</style>';

}














?>