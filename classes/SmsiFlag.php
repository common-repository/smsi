<?php

class SmsiFlag extends SmsiBuildHtml{

	public function __construct(){
		defined( 'ABSPATH' ) or die( 'Oops! dont look like your are allowed to be here silly!' );
	}

	/**
	*Builds providers table or takes it down depending on activation or deactivation.
	*@param $action String: is the action we want this function to perform.
	*@return void
	**/
	private static function smsiProviderDetails($action)
	{
		global $wpdb;

   		$charset_collate = $wpdb->get_charset_collate();

   		if($action == "up"){

	   		$sql = "CREATE TABLE IF NOT EXISTS `smsi_provider_details` (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  sms_provider varchar(255),
			  provider_username varchar(500) NULL,
				provider_password varchar(500) NULL,
        provider_api_key varchar(500) NULL,
				provider_auth_key varchar(500) NULL,
			  UNIQUE KEY id (id)
			) $charset_collate;";
			dbDelta( $sql );
		}
		else{
			$wpdb->query("DROP TABLE `smsi_provider_details`");
		}

	}


	/**
	*Builds dial codes table or takes it down depending on activation or deactivation.
	*@param $action String: is the action we want this function to perform.
	*@return void
	**/
	private static function smsiDialCodesTable($action)
	{
		global $wpdb;

   		$charset_collate = $wpdb->get_charset_collate();

   		if($action == "up"){

	   		$sql = "CREATE TABLE IF NOT EXISTS `smsi_dial_codes` (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  country_code varchar(255),
			  dial_code varchar(500) NULL,
			  UNIQUE KEY id (id)
			) $charset_collate;";
			dbDelta( $sql );
		}
		else{
			$wpdb->query("DROP TABLE `smsi_dial_codes`");
		}

	}


	/**
	*Builds error table or takes it down depending on activation or deactivation.
	*@param $action String: is the action we want this function to perform.
	*@return void
	**/
	private static function smsiErrorTable($action)
	{
			global $wpdb;

   		$charset_collate = $wpdb->get_charset_collate();

   		if($action == "up"){

	   		$sql = "CREATE TABLE IF NOT EXISTS `smsi_error_log` (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  error_code varchar(500) NULL,
				severity tinyint(1),
				created_at timestamp,
			  UNIQUE KEY id (id)
			) $charset_collate;";
			dbDelta( $sql );
		}
		else{
			$wpdb->query("DROP TABLE `smsi_error_log`");
		}

	}


	/**
	*Populates dial codes table
	*@return void
	**/
	private static function smsiDialCodes()
	{
		global $wpdb;

		$codes = json_decode( file_get_contents(SMSI__PLUGIN_DIR.'etc/phone_codes.json') );
		foreach( $codes as $code ){
			$wpdb->query($wpdb->prepare("INSERT INTO smsi_dial_codes (country_code, dial_code) VALUES (%s, %d)",$code->code, str_replace( "+","",$code->dial_code ) ));
		}

	}

	/**
	*Check if we have any active providers
	*@return void
	**/
	public static function checkActiveProvider()
	{
		global $wpdb;
		$rows = $wpdb->get_var("SELECT COUNT(id) FROM smsi_provider_details");

		if( $rows > 0){
			return 1;
		}

		else{
			return 0;
		}
	}

	/**
	*Initialises all admin hooks we need
	*@return void
	*/
	public static function initAdminHooks(){
    //Register menues
		add_action( 'admin_menu', array( "SmsiFlag", 'registerMenu' ));
    add_action(  'admin_post_save_settings', array( "SmsiSettings", "storeSettings" ) );

		//Our custom tab
		add_filter( 'woocommerce_settings_tabs_array', array( "SmsiWoocommerceConfiguration", "addSettingsTab"), 50 ); //Make tab
		add_action( 'woocommerce_settings_tabs_woocommerce_smsi_settings', array( "SmsiWoocommerceConfiguration", "settingsTab")); //Make Content for tab
		add_action( 'woocommerce_update_options_woocommerce_smsi_settings', array( "SmsiWoocommerceConfiguration", "updateSettings")); //Save Content


		if( self::checkActiveProvider() > 0 ){
			//Checkoout Radio box
			add_action( 'woocommerce_admin_order_data_after_billing_address', array( "SmsiWoocommerceConfiguration", "showSmsChoice"));

			//Hook into Order statues
			add_action( 'woocommerce_settings_tabs_woocommerce_smsi_settings', array('SmsiWoocommerceIntegration', "getActiveProvider"));
			add_action( 'woocommerce_order_status_on-hold', array('SmsiWoocommerceIntegration', "getActiveProvider"),2147483647);
			add_action( 'woocommerce_order_status_pending', array('SmsiWoocommerceIntegration', "getActiveProvider"));
			add_action( 'woocommerce_order_status_failed', array('SmsiWoocommerceIntegration', "getActiveProvider"));
			add_action( 'woocommerce_order_status_processing', array('SmsiWoocommerceIntegration', "getActiveProvider"));
			add_action( 'woocommerce_order_status_completed', array('SmsiWoocommerceIntegration', "getActiveProvider"));
			add_action( 'woocommerce_order_status_refunded', array('SmsiWoocommerceIntegration', "getActiveProvider"));
			add_action( 'woocommerce_order_status_cancelled', array('SmsiWoocommerceIntegration', "getActiveProvider"));
		}

    //Enqueue assets
		self::enqueueAssest();
	}

	/**
	*Initialises all public hooks we need
	*@return void
	*/
	public static function initPublicHooks(){
		if( self::checkActiveProvider() > 0 ){
	    //Modify Checkou page
			add_action( 'woocommerce_after_order_notes', array( "SmsiWoocommerceConfiguration", "addCheckOutSmsOption")); //Make Checkout SMS option
			add_action('woocommerce_checkout_update_order_meta', array('SmsiWoocommerceIntegration', "SmsOnOrder"));

			//Hook into Order statues
			add_action( 'woocommerce_settings_tabs_woocommerce_smsi_settings', array('SmsiWoocommerceIntegration', "getActiveProvider"));
			add_action( 'woocommerce_order_status_on-hold', array('SmsiWoocommerceIntegration', "getActiveProvider"),2147483647);
			add_action( 'woocommerce_order_status_pending', array('SmsiWoocommerceIntegration', "getActiveProvider"));
			add_action( 'woocommerce_order_status_failed', array('SmsiWoocommerceIntegration', "getActiveProvider"));
			add_action( 'woocommerce_order_status_processing', array('SmsiWoocommerceIntegration', "getActiveProvider"));
			add_action( 'woocommerce_order_status_completed', array('SmsiWoocommerceIntegration', "getActiveProvider"));
			add_action( 'woocommerce_order_status_refunded', array('SmsiWoocommerceIntegration', "getActiveProvider"));
			add_action( 'woocommerce_order_status_cancelled', array('SmsiWoocommerceIntegration', "getActiveProvider"));
		}
		//Enqueue assets
		self::enqueueAssest();

	}

	/**
	*Enqueues all our assets meaning CSS and Javascript
	*@return void
	**/
	public static function enqueueAssest(){
		//Name, path,
		wp_register_style( 'main-smsi-style', plugins_url("/smsi/css/smsi.css"));
		wp_enqueue_style( 'main-smsi-style' );

		wp_register_script( 'main-smsi-script', plugins_url("/smsi/js/script.js"));
		wp_enqueue_script( 'main-smsi-script' );
	}

	/**
	*Removes our applications hooks.
	*@return void
	*/
	public static function removeHooks(){
		remove_action( 'admin_menu', array( "SmsiFlag", 'registerMenu' ));
	}

	/**
	*Registers our applications menu inside WP admin
	*@return void
	*/
	public static function registerMenu(){
		add_menu_page( "smsi", "Smsi Settings", "manage_options", "smsi", array( "SmsiFlag", 'buildSettingsPage' ));
		add_submenu_page( "smsi", "Smsi Error log", "Smsi Error log", "manage_options", "smsi-error-log", array( "SmsiFlag", 'buildErrorLogPage' ));
		add_submenu_page( "smsi", "Smsi Help / Contact / About", "Smsi Help / Contact / About", "manage_options", "smsi-help", array( "SmsiFlag", 'buildContactPage' ));
	}

	/**
	*Boots up the plugin on activation.
	*@return void
	*/
	public static function up(){

		if ( class_exists( 'Woocommerce' ) ) {
			self::initAdminHooks();
			self::smsiProviderDetails("up");
			self::smsiDialCodesTable("up");
			self::smsiErrorTable("up");
			self::smsiDialCodes();
			self::initPublicHooks();
		}
		else{
			wp_die("Sorry, but you can't run this plugin, without having Woocommerce installed and activated.");
		}

	}


	/**
	*Takes down our plugin on deactivation.
	*@return void
	*/
	public static function down(){
    self::initAdminHooks();
    self::initPublicHooks();
    self::smsiProviderDetails( "down" );
		self::smsiDialCodesTable( "down" );
		self::smsiErrorTable("down");
	}

}
