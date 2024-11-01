<?php

class SmsiSettings extends SmsiSecurity{

	public function __construct()
	{
		defined( 'ABSPATH' ) or die( 'Oops! dont look like your are allowed to be here silly!' );

	}

	/**
	*Saves our settings
	*@return void
	*/
	public static function storeSettings()
	{
    define('constantCheck', true);
    global $wpdb;

		$wpdb->query('TRUNCATE TABLE smsi_provider_details');

		$provider = $_POST['currentSmsProvider'];
		$providerUsername = ($_POST['gatewayUsername']) ? $_POST['gatewayUsername'] : null;
		$providerPassword = ($_POST['gatewayPassword']) ? $_POST['gatewayPassword'] : null;
		$providerApiKey = ($_POST['gatewayApiKey']) ? self::encrypt( $_POST['gatewayApiKey'] ) : null;
		$providerAuthKey = ($_POST['gatewayAuthId']) ? self::encrypt( $_POST['gatewayAuthId'] ) : null;

		if( $provider == "Txty" ){
			$providerApiKey = Txty::obtainKey( $providerUsername, $providerPassword );
			$providerPassword = null;

			if($providerApiKey == 0){
				wp_redirect( $_SERVER['HTTP_REFERER']."&status=wrongpassword" );
				die();
			}
		}



    $wpdb->query($wpdb->prepare("INSERT INTO smsi_provider_details (sms_provider, provider_username, provider_password, provider_api_key, provider_auth_key) VALUES (%s, %s, %s, %s, %s)",$provider, $providerUsername, $providerPassword, $providerApiKey, $providerAuthKey));

		wp_redirect( str_replace( "&status=wrongpassword", "", $_SERVER['HTTP_REFERER']) );
		die();
	}

	/**
	*Sends a test SMS to the user
	*
	*/
	public static function sendTestSms()
	{

	}


}
