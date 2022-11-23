<?php


require_once '../Stack/IStack.php';
require_once 'IDatabase.php';
require_once 'IQuery.php';
require_once 'IStorage.php';



public class Database implements IDatabase {

	define("DEBUG_DB", 1);
 
    class Storage implements IStorage {

	    define("DEBUG_STORAGE", 1);

        private Stack $__Queries;

		//Public Core Data Access API
        public function Queries(){
            return __Queries();
        }

        function __construct() {
		
		    if(DEBUG_STORAGE){
			    $timestamp = Time::Get_Date_Time();
			    print "[$timestamp][$__CLASS__][$__METHOD__] Create $__CLASS__ object";
		    }

		    $this->__Queries = new Stack();
	    }   


	    function __destruct() {
		    if(DEBUG_STORAGE){
		    	$timestamp = Time::Get_Date_Time();
	        	print "[$timestamp][$__CLASS__][$__METHOD__] Destroy $__CLASS__ object";
	    	}
    	}

        public function Push_Query($Query){
             __Push_Query($Query);
        }

        public function Push_Query($Query){
			if(!($Query instanceof Query)){
				return(true);
			}
            $this->__Queries->Push($Query);
        }

        private function __Queries(){
            return $this->__Queries;
        }

    }

    class Query implements IQuery{

        define("DEBUG_QUERY", 1);
    
        public enum Operation { 
            CREATE, 
            READ, 
            UPDATE, 
            DELETE
        };
    
        public enum Return { 
            SINGLE, 
            MULTIPLE, 
            NOTHING 
        };
    
        public string $__RollbackNoData;
        public string $__RollbackErr;
        public string $__Sql;
        public Operation $__Operation;
        public Return $__ReturnType;
        public date $__Start;
        public date $__End;
        public var $__Result;

		//Public Interface Core API
        function Start_Execute() {
            __Start_Execute()
        }
    
        function End_Execute($Result) {
            __End_Execute();
        }
        
        function __construct($Sql, $Operation, $ReturnType, $RollbackNoData, $RollbackErr) {
            
            if(DEBUG_QUERY){
                $timestamp = Time::Get_Date_Time();
                print "[$timestamp][$__CLASS__][$__METHOD__] Create $__CLASS__ object";
            }
    
            $this->__Sql = $Sql;
            $this->__Operation = $Operation;
            $this->__ReturnType = $ReturnType;
            $this->__RollbackNoData = $RollbackNoData;
            $this->__RollbackErr = $RollbackErr;
            $this->__Start = Time::Get_Date_Time();
            $this->__End = null;
            $this->__Result = null;
    
            
            if(DEBUG_QUERY){
                $timestamp = Time::Get_Date_Time();
                print "[$timestamp][$__CLASS__][$__METHOD__] Created $__CLASS__ object with parameters : Sql $Sql, Operation $Operation, Return Type $ReturnType, Rollback No Data $RollbackNoData, Rollback Error $RollbackErr";
            }
        }
    
    
        function __destruct() {
            if(DEBUG_QUERY){
                $timestamp = Time::Get_Date_Time();
                print "[$timestamp][$__CLASS__][$__METHOD__] Destroy $__CLASS__ object";
            }
        }
    
        function __Start_Execute() {
            if(DEBUG_QUERY){
                $timestamp = Time::Get_Date_Time();
                print "[$timestamp][$__CLASS__][$__METHOD__] Start execute query";
            }
    
            if(DEBUG_QUERY){
                $timestamp = Time::Get_Date_Time();
                print "[$timestamp][$__CLASS__][$__METHOD__] Execute $__CLASS__ with Sql $this->__Sql, Operation $this->__Operation, Return Type $this->__ReturnType, Rollback No Data $this->__RollbackNoData, Rollback Error $this->__RollbackErr";
            }
        }
    
        function __End_Execute($Result) {
            if(DEBUG_QUERY){
                $timestamp = Time::Get_Date_Time();
                print "[$timestamp][$__CLASS__][$__METHOD__] End execute query";
            }
            $this->__End = Time::Get_Date_Time();
            $this->__Result = $Result;
        }
        
    
    }

	public const static $Type = "Relational";
	public const static $DBMS = "MySql";

	private $__Connection;
	private string $__Database;
	private string $__Host;
	private string $__User;
	private string $__Password;
	private Storage $__Storage;
	private Mutex $__CRUD_lock;

	//Public Interface Core API
	public static function Real_Escape_String($Connection, $String) {
		return Database::__Real_Escape_String($String);
	}


	public function Connect($Host, $User, $Password, $Database) {
		$this->__Connection = __Connect($Host, $User, $Password, $Database);
	}

	public function Close_Connection() {
		__Close_Connection();
	}

	public function Commit() {
		__Commit();
	}

	public function Autocommit($Autocommit) {
		__Autocommit($Autocommit);
	}

	public function Autocommit() {
		return $this->Autocommit;
	}

	public function End_With_Commit($CommitPage) {
		__End_With_Commit($CommitPage);
	}

	public function End_With_Rollback($RollbackPage) {
		__End_With_Rollback($RollbackPage);
	}

    public function Query_Single($Query, $RollbackNoData="", $RollbackErr="") {
		return __Query(new Query($Query, Operation::READ, Return::SINGLE, $RollbackNoData, $RollbackErr));
   	}

	public function Query_Array($Query, $RollbackNoData="", $RollbackErr="") {
	    return __Query(new Query($Query, Operation::READ, Return::MULTIPLE, $RollbackNoData, $RollbackErr));
	}

	public function Create($Query, $RollbackErr="") {
		return __Query(new Query($Query, Operation::CREATE, Return::SINGLE, "", $RollbackErr));
	}

	public function Insert($Query, $RollbackErr="") {
		return __Query(new Query($Query, Operation::INSERT, Return::SINGLE, "", $RollbackErr));
	}


	public function Update($Query, $RollbackErr="") {
		return __Query(new Query($Query, Operation::UPDATE, Return::SINGLE, "", $RollbackErr));
	}

	public function Delete($Query, $RollbackErr="") {
		return __Query(new Query($Query, Operation::DELETE, Return::SINGLE, "", $RollbackErr));
	}

	public function Queries() {
		__Queries();
	}

	function __construct($Host, $User, $Password, $Database) {

		if(DEBUG_DB){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Create $__CLASS__ object";
		}

		$this->__CRUD_lock = Mutex::create();
		$this->__Connection = null;
		$this->__Host = $Host;
		$this->__User = $User;
		$this->__Password = $Password;
		$this->__Database = $Database;
        $this->__Storage = new Storage();
        
		if(DEBUG_DB){
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Created $__CLASS__ object with parameters : Host $Host, User $User, Password $Password, Database $Database";
		}

	}

	function __destruct() {
		if(DEBUG_DB){
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
	public static function __Real_Escape_String($Connection, $String) {
		return mysqli_real_escape_string($Connection, $String);
	}

	private function __Connect(){
		//$connection = mysqli_connect("localhost","s244405","nashitys", "s244405");
	   $connection = mysqli_connect($this->__Host, $this->__User, $this->__Password, $this->__Database);
	   if( ! $connection ) {
		   $timestamp = Time::Get_Date_Time();
		   die("[$timestamp][$__CLASS__][$__METHOD__] Connect error (" . mysqli_connect_errno() . ") " . mysqli_connect_error());
	   }

	   if(DEBUG_DB) {
		   $timestamp = Time::Get_Date_Time();
		   print "[$timestamp][$__CLASS__][$__METHOD__] Connection with Database $Database enstablished";
		}

	   $this->__Connection = $connnection; 
	   return $connnection;
    }

	private function __End_With_Rollback($RollbackPage){
		if(DEBUG_DB) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] End uncommitted operations on Database $Database with Rollback to $RollbackPage";
		}

        mysqli_rollback($this->__Connection);
         
        mysqli_autocommit($this->__Connection,true);
        mysqli_close($this->__Connection);
        
        header('Location: '.$RollbackPage);
        exit;
    }

	private function __End_With_Commit($CommitPage){
        if(!mysqli_commit($this->__Connection)){
            msqli_rollback($this->__Connection);
            mysqli_autocommit($this->__Connection,true);
            mysqli_close($this->__Connection);
            header('Location: '.$CommitPage);
            exit;
         }
         
         
         mysqli_autocommit($this->__Connection,true);
         mysqli_close($this->__Connection);
         header('Location: '.$CommitPage);
         exit;
    }

   private function __Close_Connection(){
		Mutex::lock($this->__CRUD_lock);

		if(DEBUG_DB) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Close connection with Database $Database";
		}

		mysqli_close($this->__Connection);
		Mutex::unlock($this->__CRUD_lock);
   }

	private function __Commit(){
		Mutex::lock($this->__CRUD_lock);

		if(DEBUG_DB) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Commit all uncommited operations on Database $Database";
		}

		mysqli_commit($this->__Connection);
	
		Mutex::unlock($this->__CRUD_lock);
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
		
		$this->__Storage->Push_Query($Query);
		$Query->Start_Execute();

		$result=mysqli_query($this->__Connection, $query);
		
		$Query->End_Execute($result);
		Mutex::unlock($this->__CRUD_lock);
		
		$operation = $Query->__Operation;
		$returntype = $Query->__Return;
		$rollbacknodata = $Query->__RollbackNoData;
		$rollbackerr = $Query->__RollbackErr;

		if(DEBUG_DB) {
			$timestamp = Time::Get_Date_Time();
			print "[$timestamp][$__CLASS__][$__METHOD__] Commit all uncommited operations on Database $this->__Database";
		}
		
		if(!$result){
			mysqli_free_result($result);
			if(isset($rollbackerr) && $rollbackerr != ""){
				$this->__End_With_Rollback($rollbackerr);
			} else{
				return "";
			}		
		} else if(mysqli_num_rows($result) < 0){
			$Query->__end_execute();
			mysqli_free_result($result);
			if(isset($rollbackerr) && $rollbackerr != ""){
				$this->__End_With_Rollback($rollbackerr);
			} else{
				return "";
			}	
		}
		
		if($operation == Operation::CREATE || $operation == Operation::INSERT || $operation == Operation::UPDATE || $operation == Operation::DELETE ){	
			return $result;
		} else if($operation == Operation::READ && $returntype == Return::SINGLE)){
			if(mysqli_num_rows($result) == 0){
				if(isset($rollbackerr) && $rollbackerr != ""){
					$this->__End_With_Rollback($rollbackerr);
				} else{
					return "";
				}	
			} 
			$rows = mysqli_fetch_array($result);  
			$output = $rows[0];		
			mysqli_free_result($result);
		    return $output;
		} else if($operation == Operation::READ && $returntype == Return::MULTIPLE)){
			if(mysqli_num_rows($result) == 0){
				if(isset($rollbacknodata) && $rollbacknodata != ""){
					$this->__End_With_Rollback($rollbacknodata);
				} else{
					return Array();
				}	
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

	private function __Queries(){
		return $this->__Storage->Queries();
	}









}

?>