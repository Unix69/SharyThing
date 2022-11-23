
<?php

interface ITime {
   //Public Time Interface Core API
   public function Get_Date_Time();
   private static function Adjust_Hour($Hour);
}

require_once("Time.php")

?>