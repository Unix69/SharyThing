
<?php

interface IRegister {
    //Public Database Interface Core API
    public static function Register($User, $Password, $Confirm_Password);
    public static function Cancel($User, $Password);
    public static function Check($User, $Password, $Confirm_Password);
    public function Cancel();
    public function Check();
    public function User($User);
    public function Password($Password);
    public function Confirm_Password($Confirm_Password);
    public function Username();
    public function Password();
    public function Confirm_Password();
}

require_once("Register.php")


?>