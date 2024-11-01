<?php

class SmsiWoocommerceIntegration{

  public function __construct()
  {
    defined( 'ABSPATH' ) or die( 'Oops! dont look like your are allowed to be here silly!' );

  }

  /**
  *Gets name of the currently enabled provider
  *@param $order_id : int : Id of the order we are working with this is opptional
  *@return void
  */
  public static function getActiveProvider( $order_id = null )
	{
		global $wpdb;

		$provider = $wpdb->get_results("SELECT * FROM smsi_provider_details")[0]->sms_provider;

    self::transportToRightProvider( $provider, $order_id );
	}

  /**
  *Fetches our dial code
  *@param $countryCode : String : Our country code from which we should fetch the dial code
  *@return int
  */
  private static function getDialCode( $countryCode )
  {
    global $wpdb;
    $code = $wpdb->get_results("SELECT * FROM smsi_dial_codes WHERE country_code = '$countryCode'")[0];

    return $code->dial_code;
  }

  /**
  *Populates user defined variables
  *@param $orderObj : Object : Our order object
  *@param $message : Text : Our message we want to replace in.
  *@return string
  */
  private static function fillUserCreatedVariables( $orderObj, $message )
  {
    $variables = array(
      '{firstname}',
      '{fullname}',
      '{ordernr}',
      '{orderdate}',
      '{paymenttype}'
    );

    $values = array(
      $orderObj->billing_first_name,
      $orderObj->billing_first_name." ".$orderObj->billing_last_name,
      $orderObj->id,
      $orderObj->order_date,
      $orderObj->payment_method,
    );
    $finalString = str_replace( $variables, $values, $message );

    return $finalString;
  }

  /**
  *Fires the sendSms of the currently enabled provider
  *@param $provider : String : Name of the provider
  *@param $order_id : int : Id of the order we are working with this is opptional
  *@return void
  */
  private static function transportToRightProvider( $provider, $order_id = null )
  {
    $message = get_option("woocommerce_settings_tab_smsi_description_".current_action());

    if(!is_null( $order_id )){
      $order = new WC_Order( $order_id );
    }
    else{
      $order = new WC_Order( get_the_ID() );
    }

    if(get_post_meta( $order->id, 'follow_by_smsi', true ) == 1 && $message != "" && $message != null){
      $phone = self::getDialCode( $order->billing_country ).$order->billing_phone;

      $provider::sendSms( self::fillUserCreatedVariables( $order, $message ), $phone );
    }

  }

  /**
  *Updates The order with the follow_by_smsi meta if Customer chooses it
  *@param $orderId : int : Order Passed from out hook
  *@return void
  */
  public static function SmsOnOrder( $orderId )
  {
    if ($_POST['enableSms']) {
      update_post_meta( $orderId, 'follow_by_smsi', esc_attr($_POST['enableSms']));
    }
  }

}
