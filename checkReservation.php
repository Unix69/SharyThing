<?php

require_once 'myFunctions.php';
session_start();

if (!isset($_SESSION['gp_user_pg'])){
    header('Location: personalPage.php?msg=invalidU');
    exit;
}


if ( !isset($_POST['min']) ){
   header('Location: personalPage.php?msg=invalidT');
    exit;
}

    $req_username=0;
    $input=0;

$req_username = $_SESSION['gp_user_pg'];
$input = sanitizeString( $_POST['min']);

$connection = my_db_connect();
mysqli_autocommit($connection,false);

if(!validateRequest($input)){
    mysqli_endwithrollback($connection, "personalPage.php?msg=refusedR");
}

    handelNewRequest($input, $req_username, $connection);
    mysqli_endwithcommit($connection, "personalPage.php");
        
?>
