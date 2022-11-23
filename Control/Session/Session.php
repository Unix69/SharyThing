<?php



class Session implements ISession {

	define("DEBUG_SESSION", 1);
	private Mutex $__Session_lock;

	//Public Interface Core API
	private static function SESSION($Attribute){
		return __SESSION($Attribute);
    }
	
	function __construct($Host, $User, $Password, $Database) {

		if(DEBUG_SESSION){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Create $__CLASS__ object";
		}

		$this->__Session_lock = Mutex::create();
		
        
		if(DEBUG_SESSION){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Created $__CLASS__ object with parameters : Host $Host, User $User, Password $Password, Database $Database";
		}

	}

	function __destruct() {
		if(DEBUG_SESSION){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Destroy $__CLASS__ object";
		}
	}

    //Public Core Data Access API
    public function Connection() {
		return $this->__Connection;
	}

	public function Host() {
		return $this->__Host;
	}

	public function User() {
		return $this->__User;
	}

	public function Password() {
		return $this->__Password;
	}

	//Private Core Control API
	private static function __SESSION($Attribute){
		Mutex::lock($this->__Session_lock);

        if(DEBUG_SESSION) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Session by attribute $Attribute";
		}
		Mutex::unlock($this->__Session_lock);
		return $_SESSION[$Attribute;
    }


    private function __Start(){
		Mutex::lock($this->__Session_lock);

        if(DEBUG_SESSION) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Start $__CLASS__";
		}

		session_start();
		Mutex::unlock($this->__Session_lock);
    }

	private function __Refresh($Username){
		Mutex::lock($this->__Session_lock);
        if(DEBUG_SESSION) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Unset $__CLASS__";
		}

		$_SESSION['gp_timer_pg'] = time();
		$_SESSION['gp_user_pg'] = $Username;
		
		Mutex::unlock($this->__Session_lock);
    }


    private function __Unset(){
		Mutex::lock($this->__Session_lock);
        if(DEBUG_SESSION) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Unset $__CLASS__";
		}

		session_unset();
		Mutex::unlock($this->__Session_lock);
    }

	private function __Destroy(){
		Mutex::lock($this->__Session_lock);
        if(DEBUG_SESSION) {
            $timestamp = Time::Get_Date_Time();
            print "[$timestamp][$__CLASS__][$__METHOD__] Destroy $__CLASS__";
        }
		
		session_destroy();
		Mutex::unlock($this->__Session_lock);
    }

   private function __Out(){
		Mutex::lock($this->__Session_lock);

		if(DEBUG_SESSION) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Close $__CLASS__";
		}

		session_start();
		session_unset();
		session_destroy();
	
		Mutex::unlock($this->__Session_lock);
	}


   private function __Check(){
		Mutex::lock($this->__Session_lock);

		if(DEBUG_SESSION) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Check $__CLASS__";
		}

		session_start();
		$idle=time();
	
		$new_s = false;
		$allowed_idle = 120; // 2 minutes

    	if  ( isset($_SESSION['gp_user_pg']) ) {
	    	if ( isset($_SESSION['gp_timer_pg']) ) {
		    	$t = $_SESSION['gp_timer_pg'];
		    	$idle = time() - $t;
	    	} else {
		    	$new_s = true;
	    	}
	
        	if ( $idle < $allowed_idle ) {
		    	$_SESSION['gp_timer_pg'] = time(); // update use count timer
		    	return 1;
	    	} else if ( $new_s == true || $idle >= $allowed_idle ){
		    	$_SESSION=array();
            	if (ini_get("session.use_cookies")) {
                	$params = session_get_cookie_params();
                	setcookie(session_name(), '', time() - 3600*24, $params["path"],$params["domain"], $params["secure"], $params["httponly"]);
            	}
            	session_destroy();
            	header('HTTP/1.1 307 temporary redirect');
            	header('Location: loginPage.php');
				Mutex::unlock($this->__Session_lock);
   	        	return 0;
	    	}
		} else {
		// guest user
			$_SESSION['gp_guest_pg'] = 'guest';
			Mutex::unlock($this->__Session_lock);
			return 2;
		}

	}

   
    private function __Autocommit($Autocommit){
	Mutex::lock($this->__CRUD_lock);

	if(DEBUG_DB) {
		$timestamp = Time::Get_Date_Time();
		print "[$timestamp][$__CLASS__][$__METHOD__] Set autocommit on Database $Database to $Autocommit";
	}
	
	mysqli_autocommit($this->__Connection, $Autocommit);
	
	Mutex::unlock($this->__CRUD_lock);
   }

   private function __Query($Query){
		
	    Mutex::lock($this->__CRUD_lock);

		if(DEBUG_DB) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Query operation on Database $this->__Database";
		}
	
        

		$query = $Query->__Sql;
		$operation = $Query->__Operation;
		$returntype = $Query->__Return;
		$rollbacknodata = $Query->__RollbackNoData;
		$rollbackerr = $Query->__RollbackErr;
		$Query->Start_Execute();
		$result=mysqli_query($this->__Connection, $query);
		$this->__Storage->Push_Query($Query);
        Mutex::unlock($this->__CRUD_lock);

		$Query->End_Execute($result);


		if(DEBUG_DB) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Commit all uncommited operations on Database $this->__Database";
		}
		


		if(mysqli_num_rows($result) < 0){
			$Query->__end_execute();
			mysqli_free_result($result);
			$this->__End_With_Rollback($rollbackerr);
		}
		
		if($operation == Operation::CREATE || $operation == Operation::UPDATE || $operation == Operation::DELETE){
			if(!$result){
				mysqli_free_result($result);	
				$this->__End_With_Rollback($rollbackerr);
			}
			return "";
		} else if($operation == Operation::READ && $returntype == Return::SINGLE)){
			if(mysqli_num_rows($result) == 0){
				$this->__End_With_Rollback($rollbackerr);
			} 
			$rows = mysqli_fetch_array($result);  
			$output = $rows[0];		
			mysqli_free_result($result);
		    return $output;
		} else if($operation == Operation::READ && $returntype == Return::MULTIPLE)){
			if(mysqli_num_rows($result) == 0){
				$this->__End_With_Rollback($rollbackerr);
			}
			$outputs = Array();
			$i = 0;
			while($i < mysqli_num_rows($result)){
				$rows = mysqli_fetch_array($result);
				$outputs[$i++] = $rows[0];
			 }
			 mysqli_free_result($result);
			 return $outputs;
		} else{
			return "";
		}
	}









}

?>