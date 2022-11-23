<?php

public static class HTTP implements IHHTP {

	define("DEBUG_HTTP", 1);
	private static Mutex $__HTTP_lock;

	//Public Interface Core API
	public static function Redirect($Location){
		__Redirect($Location);
	}

	public static function GET($Attribute) {
		return __GET($Attribute);
	}

	public static function POST($Attribute) {
		return __POST($Attribute);
	}

	public static function Exchange_Port_From_HTTP_To_HTTPS() {
		__Exchange_Port_From_HTTP_To_HTTPS();
	}

	public static function Exchange_URL_From_HTTP_To_HTTPS() {
		__Exchange_URL_From_HTTP_To_HTTPS();
	}

	
	static function __construct() {

		if(DEBUG_HTTP){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Create $__CLASS__ object";
		}

		HTTP::$__HTTP_lock = Mutex::create();
        
		if(DEBUG_HTTP){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Created $__CLASS__ object";
		}

	}

	static function __destruct() {
		if(DEBUG_HTTP){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Destroy $__CLASS__ object";
		}
	}

	//Private Static Core Control API
	private static function __POST($Attribute){
        Mutex::lock(HTTP::$__HTTP_lock);
		if(DEBUG_HTTP) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Post by attribute $Attribute";
		}
		$value = $_POST[$Attribute];
		Mutex::unlock(HTTP::$__HTTP_lock);
		return $value;
    }

   private static function __GET($Attribute){
		Mutex::lock(HTTP::$__HTTP_lock);
		if(DEBUG_HTTP) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Get by attribute $Attribute";
		}

		$value = $_GET[$Attribute];
		Mutex::unlock(HTTP::$__HTTP_lock);
		return $value;
   }

    private static function __Exchange_URL_From_HTTP_To_HTTPS(){
		Mutex::lock(HTTP::$__HTTP_lock);

		if(DEBUG_HTTP) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] URL exchange from http to https";
		}

		if ( !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
			//https request
			Mutex::unlock(HTTP::$__HTTP_lock);
			return true;
		} else {
			$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			Mutex::unlock(HTTP::$__HTTP_lock);
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . $redirect);
			exit;
		}
	}

   
    private static function __Exchange_Port_From_HTTP_To_HTTPS(){
		Mutex::lock(HTTP::$__HTTP_lock);
		if(DEBUG_HTTP) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Port exchange from http to https";
		}

		if ($_SERVER['SERVER_PORT'] != 443) { // https port
			header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			exit;
		}

		Mutex::unlock(HTTP::$__HTTP_lock);
	}

   private static function __Redirect($Location){
		if(DEBUG_HTTP){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Redirect at location $Location";
		}
		header("Location: $Location");
	}

}

?>