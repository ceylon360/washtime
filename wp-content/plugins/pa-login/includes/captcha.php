<?php

add_action( 'init', 'palo_action_session_start' );

/**
 * Show captcha in registration page if enabled in settings
 */
if ( ! empty( $palo_options[ 'palo_captcha' ][ 'PALO_CAPTCHA_REGISTER' ] ) ) {
	add_action( 'register_form','palo_captcha_field', 999);
	add_action( 'registration_errors', 'palo_action_captcha_verify_register', 10, 3);
}

/**
 * Show captcha in login page if enabled in settings
 */
if ( ! empty( $palo_options[ 'palo_captcha' ][ 'PALO_CAPTCHA_LOGIN' ] ) ) {
	
	add_action( 'login_form', 'palo_captcha_field' );

	if ( ! function_exists( 'wp_authenticate' ) ) {
		
		/**
		 * Our wp_authenticate pluggable check the captcha.
		 * 
		 * It also removed the strange default behaviour or not showing
		 * an error message when only the username is provided
		 */
		function wp_authenticate($username, $password) {
			
			global $palo_options, $palo_textdomain;

			$username = sanitize_user($username);
			$password = trim($password);
			$captcha_challenge;
			$captcha_answer;

			$user = apply_filters( 'authenticate', null, $username, $password );

			/**
			 * Do nothing if no post data have been provided
			 */
			if ( empty( $_POST ) ) {
				return $user;
			}

			/**
			 * Force errors on missing username or password
			 */
			if ( $username == null || $password == null ) {
				$user = new WP_Error( 'authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.', $palo_textdomain) );
			}
			
			if ( ! palo_captcha_test() ) {
				if ( ! empty ( $user ) && is_wp_error( $user ) ) {
					$user->add('palo_captcha', '<strong>' . __( 'ERROR', $palo_textdomain ) . '</strong>: ' . __( $palo_options[ 'palo_captcha_error_msg' ], $palo_textdomain ) );
				} else {
					$user = new WP_Error('palo_captcha', '<strong>' . __( 'ERROR', $palo_textdomain ) . '</strong>: ' . __( $palo_options[ 'palo_captcha_error_msg' ], $palo_textdomain ) );
				}
			}

			if ( ! empty ( $user ) && is_wp_error( $user ) ) {
				do_action( 'wp_login_failed', $username );
			}

			return $user;
		}
	}
}

/**
 * Show captcha in password reset page if enabled in settings
 */
if ( ! empty( $palo_options[ 'palo_captcha' ][ 'PALO_CAPTCHA_RESET' ] ) ) {
	add_action( 'lostpassword_form', 'palo_captcha_field' );
	add_action( 'lostpassword_post', 'palo_action_captcha_reset_die' );
	add_filter( 'login_errors', 'palo_filter_captcha_reset_error' );
}

function palo_action_captcha_verify_register($errors, $sanitized_user_login, $user_email) {

	global $palo_options, $palo_textdomain;

	if ( ! palo_captcha_test() ) {
		$errors->add( 'palo_captcha', '<strong>' . __( 'ERROR', $palo_textdomain ) . '</strong>: ' . __( $palo_options[ 'palo_captcha_error_msg' ], $palo_textdomain ) );
	}

	return $errors;
}

/**
 * Generates a wp_die screen with the url for wp-login.php:lostpassword.
 * 
 * The url has the tracker palo-login if applicable
 */
function palo_action_captcha_reset_die() {
	
	global $palo_options, $palo_textdomain;

	if ( ! palo_captcha_test() ) {
		$url = palo_append_qs( wp_login_url(), 'action=lostpassword' );
		wp_die( __( $palo_options[ 'palo_captcha_error_msg' ], $palo_textdomain ) . '</p><p><strong><a class="button button-large" href="' . $url . '">' . __( 'Try again', $palo_textdomain ) . '</a></strong>');
	}
}

function palo_filter_captcha_reset_error($errors) {

	global $palo_options, $palo_textdomain;

	if ( ! empty ( $_GET['action'] ) && $_GET['action'] === 'lostpassword' ) {
		return $errors . '<strong>' . __( 'ERROR', $palo_textdomain ) . '</strong>: ' . __( $palo_options[ 'palo_captcha_error_msg' ], $palo_textdomain );
	} else {
		return $errors;
	}
}
