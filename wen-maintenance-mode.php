<?php
/*
 * Plugin Name:  WEN Maintenance Mode
 * Description:  Simple plugin for adding maintenance mode
 * Version:      1.2
 * Author:       Web Experts Nepal
 * Author URI:   https://www.webexpertsnepal.com/
 * Text Domain:  wen-maintenance-mode
*/
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WEN_PLUGIN_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WEN_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

require_once WEN_PLUGIN_PLUGIN_PATH . '/inc/classes/admin.php';

// Delete all option data when plugin is uninstalled
function wmm_plugin_uninstall(){
	global $wpdb;

	$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE 'wmm_%'" );

	foreach( $plugin_options as $option ) {
	    delete_option( $option->option_name );
	}
}

function wmm_plugin_activate(){
    register_uninstall_hook( __FILE__, 'wmm_plugin_uninstall' );

    if( get_option( 'wmm_content_heading' ) === false )
	    update_option( 'wmm_content_heading', __( 'Temporarily Down For Maintenance', 'wen-maintenance-mode' ) );

	if( get_option( 'wmm_content' ) === false )
	    update_option( 'wmm_content', __( 'We are performing scheduled maintenance. Will be back online shortly.', 'wen-maintenance-mode' ) );
}
register_activation_hook( __FILE__, 'wmm_plugin_activate' );

// Disable maintenance mode on plugin deactivation
function wmm_plugin_deactivate() {
    update_option( 'wmm_enabled', '0' );
}
register_deactivation_hook( __FILE__, 'wmm_plugin_deactivate' );
 
// plug it in
add_action( 'plugins_loaded', 'wmm_require_files' );
function wmm_require_files() {
	$wmm = new WMM_Admin();
	$wmm->init();
}

/* 
 * load maintenance page 
 * if not logged in and maintenance mode is enabled
*/
add_action( 'template_redirect', 'wmm_maintenance_mode' );
function wmm_maintenance_mode(){
	if( !is_user_logged_in() && get_option( 'wmm_enabled' ) ){
		$protocol = 'HTTP/1.0';

		if ( $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1' ) {
			$protocol = 'HTTP/1.1';
		}

		header( $protocol . ' 503 Service Unavailable', true, 503 );
		header( 'Retry-After: 3600' );

		require_once WEN_PLUGIN_PLUGIN_PATH . '/inc/views/index.php';
		die();
	}
}