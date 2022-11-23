<?php

interface ISession {
	//Public Session Interface Core API
    public function Start();
    public function Refresh($Username);
    public function Unset();
	public function Check();
    public function Destroy();
    public function Out();
    public function SESSION();
 }
 
 ?>