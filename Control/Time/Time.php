<?php






static class Time implements ITime {

	define("DEBUG_TIME", 1);

	private Mutex $__Time_lock; 
	
	//Public Interface Core API
	public function Get_Date_Time() {
        return __Get_Date_Time();
    }

    public static function Adjust_Hour($Hour){
		return __Adjust_Hour($Hour);
   }

	
	static function __construct() {

		if(DEBUG_TIME){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Create $__CLASS__ object";
		}
	
        
		if(DEBUG_TIME){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Created $__CLASS__ object";
		}

	}

	static function __destruct() {
		if(DEBUG_TIME){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Destroy $__CLASS__ object";
		}
	}

    //Public Core Data Access API

	//Private Core Control API
   	private static function __Get_Date_Time($Format=""){

        if($Format == ""){
            $date = date('Y/m/d H:i:s');
        } else {
            $date = date($Format);
        }

        return $date;
	
	}

   private static function __Adjust_Hour($Hour){

		if(DEBUG_TIME) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Adjust Hour $Hour";
		}

        $inthour = (int) $Hour;
        
        if($Hour > $inthour + 0.59 && $Hour < $inthour + 1.01){
            $Hour = $inthour + 1;
        }

		if(DEBUG_TIME) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Hour Adjusted";
		}

		return $Hour;
   }

}

?>