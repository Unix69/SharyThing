
<?php


interface ILog {
   //Public Database Interface Core API
   public function In();
   public function Out($Session);
   public function Check($Database, $Session);
   public static function Validate_Credenzials($Username, $Password, $ConfirmPassword);
  
}


?>