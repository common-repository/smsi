<?php

class Txty extends SmsiSecurity{

  /**
  *Retieves our API info we need to connect with the Txty API
  *@param $key : string : What we want returned
  *@return string
  */
  public static function getApiInfo( $key )
  {
    global $wpdb;
    $xmlData = self::readXmlDocument();
    $provider = $wpdb->get_results("SELECT * FROM smsi_provider_details")[0];

		$dec = self::decrypt( $provider->provider_api_key, base64_decode( $xmlData->key )  );

    $returnVals = array(
      'user' => $provider->provider_username,
      'key' => rtrim( $dec, "\0" ),
    );

    return $returnVals[ $key ];
  }

  /**
  *Obtains an API key on settings fillout
  *@param $username : string : Our Txty username
  *@param $password : int : Our Txty password
  *@return string
  */
  public static function obtainKey( $username, $password )
  {
    $params = array(
      'user' => $username,
      'pass' => $password,
      'description' => "Smsi",
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://login.txty.dk/api/4/user/newapikey/login.json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_POST,           1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params,'&'));
    $output = json_decode( curl_exec($ch) );
    curl_close($ch);

    if( $output->status != "error" ){

      $key = $output->newapikey->key;
      return self::encrypt( $key );

    }
    else{

      if( $output->error == "USER_NOT_FOUND" ){
        SmsiExceptionHandling::logEvent( $output->error, 1 );

        return 0;
      }

      SmsiExceptionHandling::logEvent( $output->error, 2 );

      return 0;

    }

  }

  /**
  *Sends Our SMS
  *@param $message : text : The message we want to send
  *@param $to : int : The phone number we want to send to.
  *@return void
  */
  public static function sendSms( $message, $phone )
  {

    $params = array(
      'user' => self::getApiInfo( 'user' ),
      'key' => self::getApiInfo( 'key' ),
      'msisdn' => $phone,
      'sender' => get_bloginfo( 'name', 'raw' ),
      'text' => $message
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://login.txty.dk/api/4/sms/api.json?");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params,'&'));
    $output = json_decode( curl_exec($ch) );
    curl_close($ch);

    if( $output->status == "error" ){
      SmsiExceptionHandling::logEvent( $output->error, 3 );
    }

  }

}
