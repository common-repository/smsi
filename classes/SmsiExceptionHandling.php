<?php

class SmsiExceptionHandling{

  public function __construct(){
		defined( 'ABSPATH' ) or die( 'Oops! dont look like your are allowed to be here silly!' );
	}

  /**
	*Logs errors
  *@param $errorCode : string : The error code we want to log
  *@param $severity : int : How severe the error is
	*@return void
	*/
  public static function logEvent( $errorCode, $severity )
  {
    global $wpdb;
    $wpdb->query($wpdb->prepare("INSERT INTO smsi_error_log (error_code, severity, created_at) VALUES (%s, %d, %s)",$errorCode, $severity, date('Y-m-d H:i:s')));
  }


}
