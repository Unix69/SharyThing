<?php

require_once("ILog.php")
require_once("../Time/ITime.php")




class Log implements ILog {

	define("DEBUG_LOG", 1);
 
	private string $__Username;
	private string $__Password;
	private Mutex $__Log_lock;

	public static const $Login_User_Found = 1;
	public static const $Login_User_Not_Found = -1;
	public static const $Login_Empty_Error = -2;
	public static const $Login_Credenzial_Error = -3; 
	

	//Public Interface Core API
	public function In($Username, $Password) {
        return __In($Username, $Password);
    }

	public function Out() {
		__Out();
	}

	public function Check() {
		return __Check();
	}

	
	function __construct($Username, $Password) {

		if(DEBUG_LOG){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Create $__CLASS__ object";
		}

		$this->__Log_lock = Mutex::create();
		$this->__Username = $Username;
		$this->__Password = $Password;
        
		if(DEBUG_LOG){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Created $__CLASS__ object with parameters : User $Username, Password $Password";
		}

	}

	function __destruct() {
		if(DEBUG_LOG){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Destroy $__CLASS__ object";
		}
		$__Username = null;
		$__Password = null;
	}

    //Public Core Data Access API
    public function Username($Username) {
		$this->__Username = $Username;
	}

	public function Password($Password) {
		$this->__Password = $Password;
	}

	public function Username() {
		return $this->__Username;
	}

	public function Password() {
		return $this->__Password;
	}

	//Private Core Control API
   	private function __In(){
		Mutex::lock($this->__Log_lock);
		if(DEBUG_LOG) {
           $timestamp = Time::Get_Date_Time();
           print "[$timestamp][$__CLASS__][$__METHOD__] Login";
        }

		$loggedcheck = 0;
		$msg = HTTP::GET("msg");
		$sessionusername = Session::SESSION("gp_user_pg");
		
		
		if( ! isset($sessionusername) ){
			if ( !isset($msg) ){
				$loggedcheck = 0;
				if(DEBUG_LOG) {
					$timestamp = Time::Get_Date_Time();
					print "[$timestamp][$__CLASS__][$__METHOD__] New login";
				}
			} else {
				$loggedcheck = -1;
				if(DEBUG_LOG) {
					$timestamp = Time::Get_Date_Time();
					print "[$timestamp][$__CLASS__][$__METHOD__] Error login with msg $msg";
				}
			}			
		} else {
			$__Username = $sessionusername;
			if(DEBUG_LOG) {
				$timestamp = Time::Get_Date_Time();
				print "[$timestamp][$__CLASS__][$__METHOD__] Already logged in as user $__Username";
			}
			$loggedcheck = 1; 
		}

        Mutex::unlock($this->__Log_lock);
		return $loggedcheck;
	
	}

   
    private function __Out(){
        Mutex::lock($this->__Log_lock);
        if(DEBUG_LOG) {
		    $timestamp = Time::Get_Date_Time();
		    print "[$timestamp][$__CLASS__][$__METHOD__] Set autocommit on Database $Database to $Autocommit";
	    }
		session_start();
		session_unset();
		session_destroy();
		$__Username = null;
		$__Password = null;
        Mutex::unlock($this->__Log_lock);
   }

   private static function __Validate_Credenzials($Username, $Password, $ConfirmPassword){

		if(DEBUG_LOG) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Validate Crendenzials username $Username, password $Password, confirm password $ConfirmPassword";
		}	

		if (!filter_var($Username, FILTER_VALIDATE_EMAIL)){
			return false;
		}
	
		if(!preg_match( "/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{3,}/", $Password)){
	   		return false;
		}
	
		if($ConfirmPassword != $Password){
	   		return false;
		}
				
		return true;
   }

   private function __Check($Database, $Session){
	    Mutex::lock($this->__Log_lock);

		$result = 0;

		if(DEBUG_LOG) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Check Login";
		}

		$postuser = HTTP::POST("username");
		$postpassword = HTTP::POST("password");

        if ( isset($postuser) && isset($postpassword) ){
            if ( $postuser !='' && $postpassword != '' ){
				
                // ok
                if(!__Validate_Credenzials($postuser, $postpassword, $postpassword)){
					return Log::$Login_Credenzial_Error; 
                }
				
                $Database->Connect();
                $username = $Database->Real_Escape_String($Database->Connection(), sanitizeString($postuser));
                $password = md5($Database->Real_Escape_String($Database->Connection(), sanitizeString($postpassword)));
				$sql = "SELECT * FROM users WHERE username='" . $username . "' AND password='" . $password . "'";
                $output = $Database->QuerySingle($sql, "", "");
				$Database->Close_Connection();

				if ( $output != 0 ) {
                    // ok, existing username and password
					$Session->Start();
					$Session->Init($username);
					Mutex::unlock($this->__Log_lock);
                    return Log::$Login_User_Found;
                } else {
                    // not ok
					Mutex::unlock($this->__Log_lock);
					return Log::$Login_User_Not_Found;
                }
                
            } else {
				Mutex::unlock($this->__Log_lock);
				return Log::$Login_Empty_Error;
            }
        } else {
			Mutex::unlock($this->__Log_lock);
			return Log::$Login_Empty_Error;
        }
        
		if(DEBUG_LOG) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Login Checked";
		}

        Mutex::unlock($this->__Log_lock);
		

		
		
	}









}

?>