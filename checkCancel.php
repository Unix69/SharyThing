<?php

require_once 'myFunctions.php';
session_start();

if (!isset($_SESSION['gp_user_pg'])){
    header('Location: personalPage.php?msg=invalidU');
    exit;
}

$req_username = $_SESSION['gp_user_pg'];


$connection = my_db_connect();
mysqli_autocommit($connection,false);


 


//input control
 
     
       if(!deleteReservation($req_username,$connection)){
          mysqli_endwithrollback($connection, "personalPage.php?msg=rollbackT");
          }
        
        if(!rebalanceReservations($req_username, $connection)){
          mysqli_endwithrollback($connection, "personalPage.php?msg=rollbackT");
          }
          
          
    mysqli_endwithcommit($connection, "personalPage.php");
        
?>
