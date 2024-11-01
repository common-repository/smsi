<?php
/**
 * @package Smsi
 */
/*
Plugin Name: Smsi
Description: Add SMS support for Woocommerce you can now choose a state where your customer should receive an SMS
Version: 1.0.0
Author: Patrick Sørup Hansen
Notes: The Author takes no responsibility for The loss of password or other information related to this plugin. We encrypt all passwords but can take not resposibility for any loss of data or breach of security at all!
*/
defined( 'ABSPATH' ) or die( 'Oops! dont look like your are allowed to be here silly!' );
define( 'SMSI__PLUGIN_DIR', plugin_dir_path( __FILE__ ));

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
require_once( SMSI__PLUGIN_DIR . '/classes/SmsiBuildHtml.php' );
require_once( SMSI__PLUGIN_DIR . '/classes/SmsiFlag.php' );
require_once( SMSI__PLUGIN_DIR . '/classes/SmsiSecurity.php' );
require_once( SMSI__PLUGIN_DIR . '/classes/SmsiExceptionHandling.php' );
require_once( SMSI__PLUGIN_DIR . '/classes/SmsiSettings.php' );
require_once( SMSI__PLUGIN_DIR . '/classes/SmsiWoocommerceIntegration.php' );
require_once( SMSI__PLUGIN_DIR . '/classes/SmsiWoocommerceConfiguration.php' );
require_once( SMSI__PLUGIN_DIR . '/classes/services/Clickatell.php' );
require_once( SMSI__PLUGIN_DIR . '/classes/services/InMobile.php' );
require_once( SMSI__PLUGIN_DIR . '/classes/services/Txty.php' );

register_activation_hook( __FILE__, array( 'SmsiFlag', 'up' ) );
register_deactivation_hook( __FILE__, array( 'SmsiFlag', 'down' ) );

if(is_admin()){
	add_action( 'init', array( 'SmsiFlag', 'initAdminHooks' ) );
}
else{
	add_action( 'init', array( 'SmsiFlag', 'initPublicHooks' ) );
}
