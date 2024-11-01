<?php

class SmsiWoocommerceConfiguration{

  public function __construct()
  {
    defined( 'ABSPATH' ) or die( 'Oops! dont look like your are allowed to be here silly!' );
  }

  /**
  *Adds our custom tab to woocommerce settings array
  *@param $settings_tabs : array : Array of the currently avaiable tabs
  *@return array
  */
  public static function addSettingsTab( $settings_tabs )
  {
        $settings_tabs['woocommerce_smsi_settings'] = __( 'Smsi Settings', 'woocommerce-settings-tab-smsi' );
        return $settings_tabs;
  }

  /**
  *Stores our custom tab to woocommerce settings
  *@return void
  */
  public static function settingsTab()
  {

    woocommerce_admin_fields( self::getSettings() );
  }

  /**
  *Updates data in our woocommerce custom fields
  *@return void
  */
  public static function updateSettings()
  {
    woocommerce_update_options( self::getSettings() );
  }

  /**
  *Makes our custom fields
  *@return filter
  */
  public static function getSettings()
  {
    $settings = array(
       'section_title_failed' => array(
           'name'     => __( 'Message for order status: Failed', 'woocommerce-settings-tab-smsi' ),
           'type'     => 'title',
           'desc'     => '',
           'id'       => 'woocommerce_settings_tab_smsi_section_title_failed'
       ),
       'description_failed' => array(
           'name' => __( 'Message', 'woocommerce-settings-tab-smsi' ),
           'type' => 'textarea',
           'desc' => __( 'Here you can define the text of the SMS leave blank if you dont want to send SMS on this state <br> you can use the following variables: <br> {firstname} - Translates to the customers firstname <a class="smsiFailed">Add variable {firstname}</a> <br> {fullname} - Translates to the customers fullname <a class="smsiFailed">Add variable {fullname}</a> <br> {ordernr} - Translates to Order Number <a class="smsiFailed">Add variable {ordernr}</a> <br> {orderdate} - Translates to the orderdate <a class="smsiFailed">Add variable {orderdate}</a> <br> {paymenttype} - Translates to the payment type used <a class="smsiFailed">Add variable {paymenttype}</a> <br><br> example of use: Hello {firstname}. NOTE the brackets needs to be there else it wont work', 'woocommerce-settings-tab-demo' ),
           'id'   => 'woocommerce_settings_tab_smsi_description_woocommerce_order_status_failed',
           'class' => 'smsiFailedTextArea'
       ),
       'section_end_failed' => array(
            'type' => 'sectionend',
            'id' => 'woocommerce_settings_tab_smsi_section_end_failed'
       ),

       'section_title_hold' => array(
           'name'     => __( 'Message for order status: On Hold', 'woocommerce-settings-tab-smsi' ),
           'type'     => 'title',
           'desc'     => '',
           'id'       => 'woocommerce_settings_tab_smsi_section_title_hold'
       ),
       'description_hold' => array(
           'name' => __( 'Message', 'woocommerce-settings-tab-smsi' ),
           'type' => 'textarea',
           'desc' => __( 'Here you can define the text of the SMS leave blank if you dont want to send SMS on this state <br> you can use the following variables: <br> {firstname} - Translates to the customers firstname <a class="smsiHold">Add variable {firstname}</a> <br> {fullname} - Translates to the customers fullname <a class="smsiHold">Add variable {fullname}</a> <br> {ordernr} - Translates to Order Number <a class="smsiHold">Add variable {ordernr}</a> <br> {orderdate} - Translates to the orderdate <a class="smsiHold">Add variable {orderdate}</a> <br> {paymenttype} - Translates to the payment type used <a class="smsiHold">Add variable {paymenttype}</a> <br><br> example of use: Hello {firstname}. NOTE the brackets needs to be there else it wont work', 'woocommerce-settings-tab-demo' ),
           'id'   => 'woocommerce_settings_tab_smsi_description_woocommerce_order_status_on-hold',
           'class' => 'smsiHoldTextArea'
       ),
       'section_end_hold' => array(
            'type' => 'sectionend',
            'id' => 'woocommerce_settings_tab_smsi_section_end_hold'
       ),

       'section_title_processing' => array(
           'name'     => __( 'Message for order status: Processing', 'woocommerce-settings-tab-smsi' ),
           'type'     => 'title',
           'desc'     => '',
           'id'       => 'woocommerce_settings_tab_smsi_section_title_processing'
       ),
       'description_processing' => array(
           'name' => __( 'Message', 'woocommerce-settings-tab-smsi' ),
           'type' => 'textarea',
           'desc' => __( 'Here you can define the text of the SMS leave blank if you dont want to send SMS on this state <br> you can use the following variables: <br> {firstname} - Translates to the customers firstname <a class="smsiProcessing">Add variable {firstname}</a> <br> {fullname} - Translates to the customers fullname <a class="smsiProcessing">Add variable {fullname}</a> <br> {ordernr} - Translates to Order Number <a class="smsiProcessing">Add variable {ordernr}</a> <br> {orderdate} - Translates to the orderdate <a class="smsiProcessing">Add variable {orderdate}</a> <br> {paymenttype} - Translates to the payment type used <a class="smsiProcessing">Add variable {paymenttype}</a> <br><br> example of use: Hello {firstname}. NOTE the brackets needs to be there else it wont work', 'woocommerce-settings-tab-demo' ),
           'id'   => 'woocommerce_settings_tab_smsi_description_woocommerce_order_status_processing',
           'class' => 'smsiProcessingTextArea'
       ),
       'section_end_processing' => array(
            'type' => 'sectionend',
            'id' => 'woocommerce_settings_tab_smsi_section_end_processing'
       ),


       'section_title_completed' => array(
           'name'     => __( 'Message for order status: Completed', 'woocommerce-settings-tab-smsi' ),
           'type'     => 'title',
           'desc'     => '',
           'id'       => 'woocommerce_settings_tab_smsi_section_title_completed'
       ),
       'description_completed' => array(
           'name' => __( 'Message', 'woocommerce-settings-tab-smsi' ),
           'type' => 'textarea',
           'desc' => __( 'Here you can define the text of the SMS leave blank if you dont want to send SMS on this state <br> you can use the following variables: <br> {firstname} - Translates to the customers firstname <a class="smsiCompleted">Add variable {firstname}</a> <br> {fullname} - Translates to the customers fullname <a class="smsiCompleted">Add variable {fullname}</a> <br> {ordernr} - Translates to Order Number <a class="smsiCompleted">Add variable {ordernr}</a> <br> {orderdate} - Translates to the orderdate <a class="smsiCompleted">Add variable {orderdate}</a> <br> {paymenttype} - Translates to the payment type used <a class="smsiCompleted">Add variable {paymenttype}</a> <br><br> example of use: Hello {firstname}. NOTE the brackets needs to be there else it wont work', 'woocommerce-settings-tab-demo' ),
           'id'   => 'woocommerce_settings_tab_smsi_description_woocommerce_order_status_completed',
           'class' => 'smsiCompletedTextArea'
       ),
       'section_end_completed' => array(
            'type' => 'sectionend',
            'id' => 'woocommerce_settings_tab_smsi_section_end_completed'
       ),


       'section_title_refunded' => array(
           'name'     => __( 'Message for order status: Refunded', 'woocommerce-settings-tab-smsi' ),
           'type'     => 'title',
           'desc'     => '',
           'id'       => 'woocommerce_settings_tab_smsi_section_title_refunded'
       ),
       'description_refunded' => array(
           'name' => __( 'Message', 'woocommerce-settings-tab-smsi' ),
           'type' => 'textarea',
           'desc' => __( 'Here you can define the text of the SMS leave blank if you dont want to send SMS on this state <br> you can use the following variables: <br> {firstname} - Translates to the customers firstname <a class="smsiRefunded">Add variable {firstname}</a> <br> {fullname} - Translates to the customers fullname <a class="smsiRefunded">Add variable {fullname}</a> <br> {ordernr} - Translates to Order Number <a class="smsiRefunded">Add variable {ordernr}</a> <br> {orderdate} - Translates to the orderdate <a class="smsiRefunded">Add variable {orderdate}</a> <br> {paymenttype} - Translates to the payment type used <a class="smsiRefunded">Add variable {paymenttype}</a> <br><br> example of use: Hello {firstname}. NOTE the brackets needs to be there else it wont work', 'woocommerce-settings-tab-demo' ),
           'id'   => 'woocommerce_settings_tab_smsi_description_woocommerce_order_status_refunded',
           'class' => 'smsiRefundedTextArea'
       ),
       'section_end_refunded' => array(
            'type' => 'sectionend',
            'id' => 'woocommerce_settings_tab_smsi_section_end_refunded'
       ),


       'section_title_cancelled' => array(
           'name'     => __( 'Message for order status: Cancelled', 'woocommerce-settings-tab-smsi' ),
           'type'     => 'title',
           'desc'     => '',
           'id'       => 'woocommerce_settings_tab_smsi_section_title_cancelled'
       ),
       'description_cancelled' => array(
           'name' => __( 'Message', 'woocommerce-settings-tab-smsi' ),
           'type' => 'textarea',
           'desc' => __( 'Here you can define the text of the SMS leave blank if you dont want to send SMS on this state <br> you can use the following variables: <br> {firstname} - Translates to the customers firstname <a class="smsiCancelled">Add variable {firstname}</a> <br> {fullname} - Translates to the customers fullname <a class="smsiCancelled">Add variable {fullname}</a> <br> {ordernr} - Translates to Order Number <a class="smsiCancelled">Add variable {ordernr}</a> <br> {orderdate} - Translates to the orderdate <a class="smsiCancelled">Add variable {orderdate}</a> <br> {paymenttype} - Translates to the payment type used <a class="smsiCancelled">Add variable {paymenttype}</a> <br><br> example of use: Hello {firstname}. NOTE the brackets needs to be there else it wont work', 'woocommerce-settings-tab-demo' ),
           'id'   => 'woocommerce_settings_tab_smsi_description_woocommerce_order_status_cancelled',
           'class' => 'smsiCancelledTextArea'
       ),
       'section_end_cancelled' => array(
            'type' => 'sectionend',
            'id' => 'woocommerce_settings_tab_smsi_section_end_cancelled'
       ),


       'section_title_pending' => array(
           'name'     => __( 'Message for order status: Pending', 'woocommerce-settings-tab-smsi' ),
           'type'     => 'title',
           'desc'     => '',
           'id'       => 'woocommerce_settings_tab_smsi_section_title_pending'
       ),
       'description_pending' => array(
           'name' => __( 'Message', 'woocommerce-settings-tab-smsi' ),
           'type' => 'textarea',
           'desc' => __( 'Here you can define the text of the SMS leave blank if you dont want to send SMS on this state <br> you can use the following variables: <br> {firstname} - Translates to the customers firstname <a class="smsiPending">Add variable {firstname}</a> <br> {fullname} - Translates to the customers fullname <a class="smsiPending">Add variable {fullname}</a> <br> {ordernr} - Translates to Order Number <a class="smsiPending">Add variable {ordernr}</a> <br> {orderdate} - Translates to the orderdate <a class="smsiPending">Add variable {orderdate}</a> <br> {paymenttype} - Translates to the payment type used <a class="smsiPending">Add variable {paymenttype}</a> <br><br> example of use: Hello {firstname}. NOTE the brackets needs to be there else it wont work', 'woocommerce-settings-tab-demo' ),
           'id'   => 'woocommerce_settings_tab_smsi_description_woocommerce_order_status_pending',
           'class' => 'smsiPendingTextArea'
       ),
       'section_end_pending' => array(
            'type' => 'sectionend',
            'id' => 'woocommerce_settings_tab_smsi_section_end_pending'
       ),
   );

   return apply_filters( 'woocommerce_smsi_settings', $settings );
  }

  /**
  *Adds the sms option to the checkout page
  *@param $checkout : object
  *@return void
  */
  public static function addCheckOutSmsOption( $checkout )
  {
     echo '<div id="my_custom_checkout_field"><p style="margin: 0 0 8px;">Do you want to follow your order through SMS?</p>';

     woocommerce_form_field( 'enableSms', array(
     'type'  => 'checkbox',
     'class' => array( 'enableSms-checkbox form-row-wide' ),
     'label' => __( 'Yes' ),
     ), $checkout->get_value( 'inscription_checkbox' ) );

     echo '</div>';

  }

  /**
  *Adds the show sms status to our admin edit order page
  *@param $order : object
  *@return void
  */
  public static function showSmsChoice( $order )
  {
    if(get_post_meta( $order->id, 'follow_by_smsi', true ) == 1){
      echo '<p><strong>Follow with SMS:</strong> Ja</p>';
    }
    else{
      echo '<p><strong>Follow with SMS:</strong> Nej</p>';
    }

  }

}
