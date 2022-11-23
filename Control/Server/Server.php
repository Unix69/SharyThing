<?php

class Register implements IRegister {

	define("DEBUG_REGISTER", 1);
 
	private string $__Username;
	private string $__Password;
    private string $__Confirm_Password
	
	private Mutex $__Register_lock;

	//Public Interface Core API
	public function Register($User, $Password, $Confirm_Password) {
        return __Register($User, $Password, $Confirm_Password);
    }

	public static function Cancel($User, $Password) {
		return __Cancel($User, $Password);
	}

	public function Cancel() {
		return __Cancel();
	}

	public static function Check($User, $Password) {
		return __Check($User, $Password);
	}

	public function Check() {
		return __Check();
	}

	
	function __construct($User, $Password, $Confirm_Password) {

		if(DEBUG_REGISTER){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Create $__CLASS__ object";
		}

		$this->__Register_lock = Mutex::create();
		$this->__Username = $Username;
		$this->__Password = $Password;
        $this->__Confirm_Password = $Confirm_Password;
        
		if(DEBUG_LOG){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Created $__CLASS__ object with parameters : Host $Host, User $User, Password $Password, Database $Database";
		}

	}

	function __destruct() {
		if(DEBUG_REGISTER){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Destroy $__CLASS__ object";
		}
        $this->__Username = null;
		$this->__Password = null;
		$this->__Confirm_Password = null;
	}

    //Public Core Data Access API
    public function User($User) {
		$this->__User = $User;
	}

	public function Password($Password) {
		$this->__Password = $Password;
	}

    public function Confirm_Password($Confirm_Password) {
		$this->__Confirm_Password = $Confirm_Password;
	}


	public function Confirm_Password() {
		return $this->__Confirm_Password;
	}

	public function Username() {
		return $this->__Username;
	}

	public function Password() {
		return $this->__Password;
	}

	//Private Core Control API
   	private function __Register($User, $Password, $Confirm_Password){
		Mutex::lock($this->__Register_lock);
		if(DEBUG_REGISTER) {
           $timestamp = Time::Get_Date_Time();
           print "[$timestamp][$__CLASS__][$__METHOD__] Commit all uncommited operations on Database $Database";
        }

        $this->__Username = $Username;
		$this->__Password = $Password;
        $this->__Confirm_Password = $Confirm_Password;
		
        Mutex::unlock($this->__Register_lock);
		return true;
	
	}

   
    private static function __Cancel($User, $Password){
        
        if(DEBUG_REGISTER) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Cancel Registration";
		}

		if(DEBUG_REGISTER) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Registration Canceled";
		}
		
		return true;
        
   }

   private function __Cancel(){
		Mutex::lock($this->__Register_lock);
		if(DEBUG_REGISTER) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Cancel Registration";
		}

		if(DEBUG_REGISTER) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Registration Canceled";
		}

		Mutex::unlock($this->__Register_lock);

		return true;
	}

   private static function __Check($User, $Password){
	    Mutex::lock($this->__Register_lock);
		if(DEBUG_REGISTER) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Check Registration";
		}
        
        if(DEBUG_REGISTER) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Registration Checked";
		}

        Mutex::unlock($this->__Register_lock);

		return true;
		
		
	}

	private function __Check(){
	    Mutex::lock($this->__Register_lock);
		if(DEBUG_REGISTER) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Check Registration";
		}
        
        if(DEBUG_REGISTER) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Registration Checked";
		}

        Mutex::unlock($this->__Register_lock);
		
		return true;
	}









}

?>