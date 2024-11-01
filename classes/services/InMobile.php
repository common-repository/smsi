<?php

class InMobile extends SmsiSecurity{

  private static function getApiInfo()
  {
    global $wpdb;
    $xmlData = self::readXmlDocument();
    $provider = $wpdb->get_results("SELECT * FROM smsi_provider_details")[0];

		$dec = self::decrypt( $provider->provider_api_key, base64_decode( $xmlData->key )  );

    return rtrim( $dec, "\0" );
  }


  /**
  *Sends Our SMS
  *@param $message : text : The message we want to send
  *@param $to : int : The phone number we want to send to.
  *@return void
  */
  public static function sendSms( $message, $phone )
  {
    $apiKey = self::getApiInfo();

    $params = array(
      'apiKey' => $apiKey,
      'sendername' => get_bloginfo( 'name', 'raw' ),
      'text' => $message,
      'flash' => false,
      'recipients' => $phone
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://mm.inmobile.dk/Api/V2/Get/SendMessages");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params,'&'));
    $output = curl_exec($ch);

    curl_close($ch);

    if( stripos($output,"error") >= 0 ){
      SmsiExceptionHandling::logEvent( "An unkown error happend during sending.", 3 );
    }
  }

}
