<?php

require_once 'IStack.php';

 class Stack implements IStack{
    define("DEBUG_STACK", 1);

    private Mutex $__Data_lock;
    private array $__Data;
    private $__Current;


    public function Data(){
        return $this->__Data;
    }

	public function Current(){
        return $this->__Current;
    }

	public function From_To($IndexFrom, $IndexTo){
        return __From_To($IndexFrom, $IndexTo);
    }

	public function At($Index){
        return __At($Index);
    }

	public function Pop(){
        return __Pop();
    }

	public function Push($Data){
        __Push($Data);
    }

    public function Front(){
        return __Front();
    }

	public function Back(){
        return __Back();
    }

    public function Size(){
        return __Size();
    }



    function __construct() {

        if(DEBUG_STACK){
            $timestamp = GetDateTime();
            print "[$timestamp][$__CLASS__][$__METHOD__] Create $__CLASS__ object";
        }

        $this->__Data_lock = Mutex::create();
        $this->__Data = array();
        $this->__Current = -1;

        if(DEBUG_STACK){
            $timestamp = GetDateTime();
            print "[$timestamp][$__CLASS__][$__METHOD__] Created $__CLASS__ object with parameters : Host $Host, User $User, Password $Password, Database $Database";
        }

    }

    function __destruct() {
        if(DEBUG_STACK){
            $timestamp = GetDateTime();
            print "[$timestamp][$__CLASS__][$__METHOD__] Destroy $__CLASS__ object";
        }
    }

    private function __Push($Data){
        Mutex::lock($this->__Data_lock);

        if(DEBUG_STACK){
            $timestamp = GetDateTime();
            print "[$timestamp][$__CLASS__][$__METHOD__] Push Data";
        }

        $index = $this->__Current + 1;

        while(isset($this->__Data[$index])){
            $index = ($this->__Current++) + 1; 
        }

        $this->__Data[$index] =  $Data

        Mutex::unlock($this->__Data_lock);

    }
    
    private function __Pop(){

        Mutex::lock($this->__Data_lock);


        if(DEBUG_STACK){
            $timestamp = GetDateTime();
            print "[$timestamp][$__CLASS__][$__METHOD__] Pop Data";
        }

        if($this->__Current < 0){
            return null; 
        } else if(!isset($this->__Queries[$this->__Current])){
            return null; 
        }
        

        $index = $this->__Current--;
        $data = $this->__Data[$index];
        $this->__Data[$index] = null;

        Mutex::unlock($this->__Data_lock);


        return $data;

    }

    private function __Front(){

        Mutex::lock($this->__Data_lock);


        if(DEBUG_STACK){
            $timestamp = GetDateTime();
            print "[$timestamp][$__CLASS__][$__METHOD__] Front";
        }

        if($this->__Current < 0){
            return null; 
        } else if(!isset($this->__Queries[$this->__Current])){
            return null; 
        }
        

        $index = 0;
        $data = $this->__Data[$index];

        Mutex::unlock($this->__Data_lock);


        return $data;

    }

    private function __Back(){

        Mutex::lock($this->__Data_lock);


        if(DEBUG_STACK){
            $timestamp = GetDateTime();
            print "[$timestamp][$__CLASS__][$__METHOD__] Back";
        }

        if($this->__Current < 0){
            return null; 
        } else if(!isset($this->__Queries[$this->__Current])){
            return null; 
        }
        

        $index = $this->__Current ;
        $data = $this->__Data[$index];

        Mutex::unlock($this->__Data_lock);


        return $data;

    }

    private function __Size(){

        Mutex::lock($this->__Data_lock);

        if(DEBUG_STACK){
            $timestamp = GetDateTime();
            print "[$timestamp][$__CLASS__][$__METHOD__] Size";
        }

        if($this->__Current < 0){
            return 0; 
        }

        $size = 1 + $this->__Current;
        Mutex::unlock($this->__Data_lock);

        return $size;

    }


    private function __At($Index){

        Mutex::lock($this->__Data_lock);


        if(DEBUG_STACK){
            $timestamp = GetDateTime();
            print "[$timestamp][$__CLASS__][$__METHOD__] At $Index";
        }

        if($Index > $this->__Current || $Index < $this->__Current){
            return null; 
        } else if(!isset($this->__Data[$Index])){
            return null; 
        }

        $output = $this->__Data[$Index];
        Mutex::unlock($this->__Data_lock);
        
        return $output;
    }

    private function __From_To($IndexFrom, $IndexTo){
        
        Mutex::lock($this->__Data_lock);


        if(DEBUG_STACK){
            $timestamp = GetDateTime();
            print "[$timestamp][$__CLASS__][$__METHOD__] Query at $Index";
        }

        if($IndexFrom > $this->__Current || $IndexFrom < 0){
            return null; 
        }

        if($IndexTo > $this->__Current || $IndexTo < 0){
            return null; 
        }

        array $outputs = array();  
        $i = 0;
        foreach ( $this->__Data as $data) {
            $outputs[$i++] = $data;
        }

        Mutex::unlock($this->__Data_lock);
        return $outputs;
        
    }




}

?>
