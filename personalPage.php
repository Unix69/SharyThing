<?php


require_once 'header.php';
require_once 'myFunctions.php';

//prelevo dati database
$connection=my_db_connect();
mysqli_autocommit($connection, false);



$users=getUsers($connection);
$start=getStart($connection);
$end=getEnd($connection);
$requested=getAllRequest($connection);
$obtained=getObtained($connection);


if(isset($_SESSION['gp_user_pg'])){

$uri = setProfileImage($_SESSION['gp_user_pg']);
  echo '<img src="'.$uri.'" alt="profileimage" style="width:700px;height:200px;background-color:blue;border: solid; border-color:blue; display: inline-block;">';
  echo'<br><input type="button" value="+" onClick="updateImage()" style="width: 5px; heigth: 5px;">';
  echo '<hr><p><h1><form id="imageForm" method="post" enctype="multipart/form-data" action="uploadProfileImage.php" class="myformstyle" style="visibility: hidden;" >
  <input type="file" name="profileimage">||<input style="width: 5px; heigth: 5px;" border="solid" type="submit" value="+"></form></h1></p>';
  $user=$_SESSION['gp_user_pg'];
  $request=getLastRequestFromUser($connection, $user);
  $obtain=getObtainedFromUser($connection, $user);

echo'<div id="personalheader"><br><h2>'.$user.'</h2><br></div><hr>';
}


mysqli_commit($connection);
mysqli_autocommit($connection, true);
mysqli_close($connection);




if( isset($_GET['msg'])){
  if($_GET['msg']=='rollbackT'){
  echo '<br><p><font color="#ff0000">Transaction rollback. Retry</font></p>';
  } else if($_GET['msg']=='invalidI'){
  echo '<br><p><font color="#ff0000">Invalid Input. Retry</font></p>';
  } else if($_GET['msg']=='refusedR'){
  echo '<br><p><font color="#ff0000">Request refused. Retry</font></p>';
  } else if($_GET['msg']=='invalidT'){
  echo '<br><p><font color="#ff0000">Invalid offer. Retry</font></p>';
  } else if($_GET['msg']=='invalidU'){
  echo '<br><p><font color="#ff0000">Invalid user. Retry</font></p>';
  }
}


if( isset($_SESSION['gp_user_pg']) ){


if($request != 0 && $obtain != 0){
echo '<br><br><div id="yourdisplay">';
echo'<br><font size="3"> Your last request is : </font>';
echo '<label><span title="this is your last request"><font color="blue"> '.$request.' min</font></span></label>';
echo'<font size="3"> And you obtain : </font>';
echo '<label><span title="this is your obtained"><font color="blue"> '.$obtain.' min</font></span></label><br>';
echo '</div>';
}


} else {
	echo "<br><p>You must <a href='loginPage.php'>login</a> or <a href='registrationPage.php'>register</a> in order to make a request</p>";
}


//set view variable
$heigth = 100 + count($users)*40;


setCSSvariable($heigth);


if(count($users) != 0){

echo '<br><br><div id="reservations">';
echo '<br><font size="3" color="blue">Reservations</font><br><br><br>';
$sumR = 0;
$sumO = 0;
for($i = 0; $i < count($users); $i++){
$sumO += $obtained[$i];
$sumR += $requested[$i];




echo'  <label>
       <span title="this is the user"><font color="blue">- '.$users[$i].'</font></span>
       <span title="this is the start"> - Start hour <font color="blue">'.number_format((float)$start[$i], 2, '.', '').'</font></span>
       <span title="this is the end"> - End hour <font color="blue">'.number_format((float)$end[$i], 2, '.', '').'</font></span>
       <span title="this is the requested time"> - Requested min <font color="blue">'.$requested[$i].'</font></span>
       <span title="this is the obtained time"> - Obtained min <font color="blue">'.$obtained[$i].'</font> ;</span>
       </label><br>';
}
echo '</div>';





echo'<br><br><hr><br><p><label> - Requested total <font color="blue">'.$sumR.'</font> min - Assigned total <font color="blue">'.$sumO.'</font> min</label></p>';

if( isset($_SESSION['gp_user_pg']) && $request != 0 && $obtain != 0){
       echo'<p><font color="red">You cannot require other reservations - You must </font> <a href="checkCancel.php">cancel last request</a><p>';
} else if( isset($_SESSION['gp_user_pg']) && $request == 0 && $obtain == 0){
    if($sumR > 180){
       echo'<p><font color="red">You cannot require other reservations</font><p>';
     }
}

}//close count if


echo '<br><br><hr>';


if( isset($_SESSION['gp_user_pg']) ){

if(compareEntry($users, $user)){
echo <<<FORMCANC_
	<form method="post" action="checkCancel.php" class="myformstyle" onsubmit="return confirm('Are you sure to cancel you last reservation?');">
	<br>
	<input type="submit" value="Cancel Reservation">
	</form>

FORMCANC_;
} else {
echo <<<FORMREQ_
	<form method="post" action="checkReservation.php" class="myformstyle" onsubmit="return validateReservation();">
	<label><span id="checkReservation"> Request min </span><input type="text" placeHolder="minutes" id="min" name="min" title="Insert minutes for your new Reservation"></label>
    <label><div id="checkErr"></div></label>
	<br>
	<input type="submit" value="Request">
	</form>

FORMREQ_;
}

}


echo <<<NALL_
<hr>
             <br>
             <br>
             <br>
             <br>
             <br>		
				</div><!--Main-->		
</body>
</html>
NALL_;
?>