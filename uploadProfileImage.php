<?php

require_once 'myFunctions.php';
require_once 'header.php';

if (!isset($_SESSION['gp_user_pg'])){
    header('Location: personalPage.php?msg=invalidU');
    exit;
}

if(!isset($_FILES['profileimage'])){
header('Location: personalPage.php?msg=invalidT');
    exit;
}

$req_username=$_SESSION['gp_user_pg'];
$uploaddir = $req_username.'/';
$uploadfile = $uploaddir . basename($_FILES['profileimage']['name']);


$dir  = './'.$req_username;
            $files = scandir($dir);
            if(count($files) > 0 && count($files) == 3){
              unlink($dir.'/'.$files[2]);
            }

$f=move_uploaded_file($_FILES['profileimage']['tmp_name'], $uploadfile);

if($f){
echo'<font color="#00ff00">load successfully profile image</font>';
}
else{
echo'<font color="red">miss load profile image</font>';
}

echo'<br><br>go to <a href="personalPage.php">personal page</a>';





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
