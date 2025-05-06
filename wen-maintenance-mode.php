<?php
/*
 * Plugin Name:  WEN Maintenance Mode
 * Description:  Simple plugin for adding maintenance mode
 * Version:      1.5
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


// Add cron schedule
add_filter('cron_schedules', 'wmm_add_every_minute_cron_schedule');
function wmm_add_every_minute_cron_schedule($schedules) {
    $schedules['every_minute'] = array(
        'interval' => 60, // in seconds
        'display'  => __('Every Minute')
    );
    return $schedules;
}

function wmm_plugin_activate(){
    register_uninstall_hook( __FILE__, 'wmm_plugin_uninstall' );

    if( get_option( 'wmm_content_heading' ) === false )
	    update_option( 'wmm_content_heading', __( 'Temporarily Down For Maintenance', 'wen-maintenance-mode' ) );

	if( get_option( 'wmm_content' ) === false )
	    update_option( 'wmm_content', __( 'We are performing scheduled maintenance. Will be back online shortly.', 'wen-maintenance-mode' ) );
	// Set every minute cron schedule to check and disable the maintenance mode.
	wmm_setup_cron_events();
}
register_activation_hook( __FILE__, 'wmm_plugin_activate' );

// Disable maintenance mode on plugin deactivation
function wmm_plugin_deactivate() {
    update_option( 'wmm_enabled', '0' );

    // Remove cronjon on plugin deactivation.
    $timestamp = wp_next_scheduled('wmm_check_disable_maintenance');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'wmm_check_disable_maintenance');
    }
}
register_deactivation_hook( __FILE__, 'wmm_plugin_deactivate' );
 
// plug it in
add_action( 'plugins_loaded', 'wmm_require_files' );
function wmm_require_files() {
	$wmm = new WMM_Admin();
	$wmm->init();

	// Set every minute cron schedule to check and disable the maintenance mode.
	wmm_setup_cron_events();
}


// Configure maintenance mode cronjobs
function wmm_setup_cron_events(){
	if (!wp_next_scheduled('wmm_check_disable_maintenance')) {
        wp_schedule_event(time(), 'every_minute', 'wmm_check_disable_maintenance'); 
    }
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

// Add maintenance mode status in admin bar menu
add_action( 'admin_bar_menu', 'wmm_maintenance_status', 999 );
function wmm_maintenance_status( $wp_admin_bar ) {
	$maintenance_status = get_option( 'wmm_enabled' );
	$maintenance_on = __( 'Maintenance Mode : ON', 'wen-maintenance-mode' );
	$maintenance_off = __( 'Maintenance Mode : OFF', 'wen-maintenance-mode' );
	if ( $maintenance_status == '1' ) {
		$maintenance_status_title = $maintenance_on;
	} else {
		$maintenance_status_title = $maintenance_off;
	}

	$maintenance_status_html = '<a class="ab-item maintenance-status" data-on="'. $maintenance_on .'" data-off="'. $maintenance_off .'" href="'. home_url( '/wp-admin/options-general.php?page=wen-maintenance-mode' ) .'">' . $maintenance_status_title . '</a>';

    $wp_admin_bar->add_node( array(
        'id'    => 'wmm-maintenance-status',
        'title' => $maintenance_status_html,
        'meta'  => array(
            'title' => __('Maintenance Mode', 'wen-maintenance-mode'),
        ),
    ));
}


// Trigger cronjob to disable maintenance mode when timer has crossed.
add_action('wmm_check_disable_maintenance', 'wmm_check_and_disable_maintenance');
add_action('init', 'wmm_check_and_disable_maintenance');
function wmm_check_and_disable_maintenance(){
   $disable_maintenance_on = get_option('wmm_disable_on');
	if ( $disable_maintenance_on ) {
		$current_time = current_datetime()->format('Y-m-d H:i:s');

		$disable_maintenance_time =  strtotime($disable_maintenance_on);
		$current_time_str =  strtotime($current_time);

		$time_diff = $disable_maintenance_time - $current_time_str;
		if ( $time_diff <= 0 ) {
			delete_option('wmm_disable_on');
    		update_option( 'wmm_enabled', '0' );
		}
	}
}

