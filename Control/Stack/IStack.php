
<?php

interface IStack {
	//Public Database Interface Core API
	public function Data();
	public function Current();
	public function From_To($IndexFrom, $IndexTo);
	public function At($Index);
	public function Pop();
	public function Push($Data);
    public function Front();
	public function Back();
    public function Size();
	public function Empty();
 }


?>