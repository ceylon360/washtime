<?php

/**
 * Plugin Name: PA Login & Access
 * Plugin URI: http://pressapps.co/plugins/
 * Description: Login and member access WordPress plugin
 * Author: PressApps Team
 * Text Domain: pressapps
 * Version: 1.1.1
 */

/**
 * @file
 * Palo Login main file
 */

/**
 * The plugin textdomain
 * @global string $palo_textdomain
 */
$palo_textdomain = 'pressapps';

/**
 * Since we are not using a class, we need some sort
 * of way to allow functions to trasnmit data
 * 
 * @global array $palo_helper
 */
$palo_helper = array();

/**
 * The Palo login main plugin file path relative to wp-content/plugins
 * @global string $palo_basename
 */
$palo_basename = plugin_basename( __FILE__ );

/**
 * The Palo Login directory
 * @global string $palo_dir
 */
$palo_dir = plugin_dir_path( __FILE__ );

/**
 * The Palo Login directory URL
 * @global string $palo_dir
 */
$palo_dir_url = plugin_dir_url( __FILE__ );

/**
 * The directory where Palo Login actions, filter and other functions reside
 * @global string $palo_includes_dir
 */
$palo_includes_dir = $palo_dir . 'includes/';

/**
 * The directory where Palo Login  stylesheets reside
 * @global string $palo_styles_dir
 */
$palo_styles_dir = $palo_dir_url . 'assets/css/';

/**
 * The directory where Palo Login Javascript files reside
 * @global string $palo_scripts_dir
 */
$palo_scripts_dir = $palo_dir_url . 'assets/js/';

/**
 * The Palo login options, we make theme available from the beginning
 * and make sur the variable is always an array
 * 
 * @global array $palo_options
 */
$palo_options = get_option( 'palo_options' );
if ( empty( $palo_options ) ) {
	$palo_options = array();
}
$palo_options[ 'palo_captcha_error_msg' ] = __( 'Captcha verification failed' );

/**
 * Global actions
 */
require_once $palo_includes_dir . 'actions/global.php';

/**
 * Add global code
 */
require_once $palo_includes_dir . 'global.php';

/**
 * Add frontend shortcodes, actions and filters
 */
if ( ! is_admin() ) {
	require_once $palo_includes_dir . 'front.php';
}

/**
 * Add dashboard actions and filters
 */
if ( is_admin() ) {
	require_once $palo_includes_dir . 'admin.php';
}

/**
 * Add login, password reset and registration pages actions and filters
 */
if ( $GLOBALS['pagenow'] === 'wp-login.php' ) {
	require_once $palo_includes_dir . 'login.php';
}

/**
 * Load filters
 */
require_once $palo_includes_dir . 'filters.php';
