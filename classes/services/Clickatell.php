<?php

class Clickatell extends SmsiSecurity{

  /**
  *Reads our encrypted API info and returns it
  *@return string
  */
  private static function getApiInfo()
  {
    global $wpdb;
    $xmlData = self::readXmlDocument();
    $provider = $wpdb->get_results("SELECT * FROM smsi_provider_details")[0];

		$dec = self::decrypt( $provider->provider_auth_key, base64_decode( $xmlData->key )  );

    return rtrim( $dec, "\0" );
  }


  /**
  *Sends Our SMS
  *@param $message : text : The message we want to send
  *@param $to : int : The phone number we want to send to.
  *@return void
  */
  public static function sendSms( $message, $to )
  {
    $authKey = self::getApiInfo();

    $to = "[\"$to\"]";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.clickatell.com/rest/message");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_POST,           1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,     "{\"text\":\"$message\",\"to\":$to}");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Authorization:bearer $authKey",
      "X-Version:1",
      "Content-Type:application/json",
      "Accept:application/json"
    ));
    $output = json_decode( curl_exec($ch) );
    curl_close($ch);

    if( isset($output->error) ){
      SmsiExceptionHandling::logEvent( $output->error->description." Error code: ".$output->error->code, 3 );
    }
  }

}
