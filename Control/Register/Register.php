<?php

class Register implements IRegister {

	define("DEBUG_REGISTER", 1);
 

    public static $Register_Wrong_Credenzials = -2;
    public static $Register_Insert_Fail = -1;
    public static $Register_Already_Used = 1;
    public static $Register_Inserted = 0;

    public static $Check_Not_Exists = -1;
    public static $Check_Exists = 0;



	private string $__Username;
	private string $__Password;
    private string $__Confirm_Password
	
	private Mutex $__Register_lock;

	//Public Interface Core API
	public function Register($User, $Password, $Confirm_Password) {
        return __Register($User, $Password, $Confirm_Password);
    }

	public function Cancel($User, $Password) {
		__Cancel($User, $Password);
	}

	public function Check($User, $Password, $Confirm_Password) {
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
			print "[$timestamp][$__CLASS__][$__METHOD__] Created $__CLASS__ object with parameters : ";
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
   	private function __Register($Session, $Database, $Username="", $Password="", $Confirm_Password=""){
		Mutex::lock($this->__Register_lock);
		if(DEBUG_REGISTER) {
           $timestamp = Time::Get_Date_Time();
           print "[$timestamp][$__CLASS__][$__METHOD__] Commit all uncommited operations on Database $Database";
        }
        
        if(!issset($Username) || $Username == ""){
            $username=HTTP::POST("username");
        } else{
            $username=$Username;
        }

        if(!issset($Password) || $Password == ""){
            $password=HTTP::POST("password");
        } else{
            $password=$Password;
        }
        
        if(!issset($Confirm_Password) || $Confirm_Password == ""){
            $confirm_password=HTTP::POST("confirmpassword");
        } else{
            $confirm_password=$Confirm_Password;
        }


        $this->__Username = $username;
		$this->__Password = $password;
        $this->__Confirm_Password = $confirm_password;


        if ( ( isset($username) && isset($password) isset($confirm_password)) && ( $username != '' && $password != '' && $confirm_password != '' ) ){
	
            $Database->Connect();
            $username = Database::Real_Escape_String($Database->Connection(), sanitizeString($username));
            $password = md5(Database::Real_Escape_String($Database->Connection(), sanitizeString($password)));
            $confirm_password = md5(Database::Real_Escape_String($Database->Connection(), sanitizeString($confirm_password)));

            if (!Database::Validate_Credenzials($username, $password, $confirm_password)) {
                Mutex::unlock($this->__Register_lock);
                return Register::$Register_Wrong_Credenzials;
            }

            $sql = "SELECT * FROM users WHERE username='$username'"
            $output = $Database->QuerySingle($sql, "", "");
            
            if ($output == "") {
                // ok, insert into database
                $insert = "INSERT INTO users (username, password) VALUES('" . $username ."' , '" .  $password . "' )"
                $result = $Database->Insert($insert, "", "");
                if ($result) {
                    $Database->Close_Connection();
                    Mutex::unlock($this->__Register_lock);
                    return Register::$Register_Insert_Fail;
                }
                
                $Database->Close_Connection();
                $Session->Start();
                $Session->Refresh($username);
                Mutex::unlock($this->__Register_lock);
                return Register::$Register_Inserted;
            } else {
                $Database->Close_Connection();
                return Register::$Register_Already_Used;
             }
        }
        else {
            Mutex::unlock($this->__Register_lock);
            return Register::$Register_Wrong_Credenzials;
        }
	}

   
    private function __Cancel($Username, $Password){
        Mutex::lock($this->__Register_lock);
        if(DEBUG_REGISTER) {
		    $timestamp = Time::Get_Date_Time();
		    print "[$timestamp][$__CLASS__][$__METHOD__] Cancel Registration";
	    }
		
        if($Username == ""){
            $username=HTTP::POST("username");
        } else{
            $username=$Username;
        }

        if($Password == ""){
            $password=HTTP::POST("password");
        } else{
            $password=$Password;
        }

        if($Database->Query_Single("SELECT username FROM assignment WHERE username='" . $req_username ."'") != ""){ 
            $sql = "DELETE FROM assignment WHERE username='" .  $req_username . "'";
        } else {
            return(false);
        }
              
        if(!mysqli_query($connection,$sql)){
            mysqli_endwithrollback($connection, "personalPage.php?msg=rollbackT");
        }
                  
        if(mysqli_num_rows(mysqli_query($connection, "SELECT username FROM reservation WHERE username='" . $req_username ."'")) != 0){ 
                $sql = "DELETE FROM reservation WHERE username='" .  $req_username . "'";
        } else {
            return(false);
        }
                        
        if(!mysqli_query($connection,$sql)){
            mysqli_endwithrollback($connection, "personalPage.php?msg=rollbackT");
        }

        Mutex::unlock($this->__Register_lock);
        return(true);

   }

   private function __Check($Username, $Password, $Confirm_Password){
	    Mutex::lock($this->__Register_lock);
		if(DEBUG_REGISTER) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Check Registration";
		}

        if(!issset($Username) || $Username == ""){
            $username=HTTP::POST("username");
        } else{
            $username=$Username;
        }

        if(!issset($Password) || $Password == ""){
            $password=HTTP::POST("password");
        } else{
            $password=$Password;
        }
        
        if(!issset($Confirm_Password) || $Confirm_Password == ""){
            $confirm_password=HTTP::POST("confirmpassword");
        } else{
            $confirm_password=$Confirm_Password;
        }


        $this->__Username = $username;
		$this->__Password = $password;
        $this->__Confirm_Password = $confirm_password;


        if ( ( isset($username) && isset($password) && isset($confirm_password)) && ( $username != '' && $password != '' && $confirm_password != '' ) ){
	
            $Database->Connect();
            $username = Database::Real_Escape_String($Database->Connection(), sanitizeString($username));
            $password = md5(Database::Real_Escape_String($Database->Connection(), sanitizeString($password)));
            $confirm_password = md5(Database::Real_Escape_String($Database->Connection(), sanitizeString($confirm_password)));

            if (!Database::Validate_Credenzials($username, $password, $confirm_password)) {
                if(DEBUG_REGISTER) {
                    $timestamp = Time::Get_Date_Time();
                    print "[$timestamp][$__CLASS__][$__METHOD__] Wrong Credenzials";
                }
                Mutex::unlock($this->__Register_lock);
                return Register::$Register_Wrong_Credenzials;
            }

            $sql = "SELECT * FROM users WHERE username='$username'"
            $output = $Database->QuerySingle($sql, "", "");
            $Database->Close_Connection();
            
            if ($output == "") {
                // ok, insert into database
                if(DEBUG_REGISTER) {
                    $timestamp = Time::Get_Date_Time();
                    print "[$timestamp][$__CLASS__][$__METHOD__] Registration exists";
                }
                Mutex::unlock($this->__Register_lock);
                return Register::$Check_Exists;
            } else {
                if(DEBUG_REGISTER) {
                    $timestamp = Time::Get_Date_Time();
                    print "[$timestamp][$__CLASS__][$__METHOD__] Registration doesn't exist";
                }
                Mutex::unlock($this->__Register_lock);
                return Register::$Check_Not_Exists;
             }
        }

        Mutex::unlock($this->__Register_lock);
        return Register::$Register_Wrong_Credenzials;		
	}









}

?>