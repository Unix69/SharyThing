<?php

interface IHTTP {
	//Public static HTTP Interface Core API
	public static function Redirect($Location);
	public static function GET($Attribute);
	public static function POST($Attribute);
	public static function Exchange_Port_From_HTTP_To_HTTPS();
	public static function Exchange_URL_From_HTTP_To_HTTPS();
 }
 
 ?>