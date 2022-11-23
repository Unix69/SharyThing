<?php

 
interface IServer {
	//Public Database Interface Core API
	public function Connection();
	public function Host();
	public function User();
	public function Password();
	public function Connect();	
	public function Close_Connection();
	public function Commit();	
	public function Autocommit($Autocommit);
	public function Autocommit();
	public function End_With_Commit($CommitPage);
	public function EndWithRollback($RollbackPage);
	public function QuerySingle($Query, $RollbackNoData="", $RollbackErr="");
	public function QueryArray($Query, $RollbackNoData="", $RollbackErr="");
	public function Create($Query, $RollbackErr="");
	public function Update($Query, $RollbackErr="");
	public function Delete($Query, $RollbackErr="");
	public function Queries();
	public static function Real_Escape_String($Connection, $String);
 }

 ?>