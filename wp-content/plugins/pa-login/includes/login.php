<?php

/**
 * @file
 * 
 * The login, password reset & registration  pages
 */

require_once $palo_includes_dir . 'actions/login.php';
require_once $palo_includes_dir . 'filters/login.php';
require_once $palo_includes_dir . 'functions.php';
require_once $palo_includes_dir . 'captcha.php';

add_action( 'login_enqueue_scripts', 'palo_action_login_css' );
add_action( 'wp_footer',             'palo_action_front_css' );
add_action( 'wp_logout',             'palo_filter_logout_redirect' );
add_action( 'register_form',         'palo_action_hidden_tracker_field' );
add_action( 'lostpassword_form',     'palo_action_hidden_tracker_field' );
add_action( 'login_form',            'palo_action_hidden_tracker_field' );
add_filter( 'login_redirect',        'palo_filter_login_redirect', 10, 3 );
add_filter( 'login_url',             'palo_filter_login_tracker' );
add_filter( 'register_url',          'palo_filter_login_tracker' );
add_filter( 'lostpassword_url',      'palo_filter_login_tracker' );

if ( assign_if_exists( 'palo_password_on_registration', $palo_options ) ) {
	add_action( 'register_form',         'palo_password_field' );
	add_action( 'registration_errors',   'palo_action_password_verify', 10, 3 );
	add_action( 'user_register',         'palo_action_register', 100 );
	add_filter( 'gettext',               'palo_filter_remove_password_msg' );
	add_filter( 'wp_mail',               'palo_filter_user_password_in_email' );
}

/**
 * Verifies both password aren't empty and are the same
 */
function palo_action_password_verify( $errors, $sanitized_user_login, $user_email ) {

	global $palo_options, $palo_textdomain;

	if ( empty( $_POST[ 'palo_password' ] ) || empty( $_POST[ 'palo_password_2' ] ) ) {
		$errors->add( 'palo_password', '<strong>' . __( 'ERROR', $palo_textdomain ) . '</strong>: ' . 'Password missing' );
	} else if ( $_POST[ 'palo_password' ] !== $_POST[ 'palo_password_2' ] ) {
		$errors->add( 'palo_password', '<strong>' . __( 'ERROR', $palo_textdomain ) . '</strong>: ' . "Passwords don't match" );
		return $errors;
	}

	return $errors;
}

/**
 * Removes the message about the password on the registration form
 */
function palo_filter_remove_password_msg( $text ) {

	if ( $text == 'A password will be e-mailed to you.' ) {
		$text = '';
	}
	return $text;
}

/**
 * Sets the user password 
 */
function palo_action_register( $user_id ){

	$userdata = array();

	$userdata['ID'] = $user_id;
	$userdata['user_pass'] = $_POST['palo_password'];
	wp_update_user( $userdata );
}

/**
 * Use a custom user notification when password on registration is active
 */
if ( ! function_exists( 'wp_new_user_notification' ) ) {
	function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
	
		global $palo_options;

		$password_on_registration_enabled = (bool) assign_if_exists( 'palo_password_on_registration', $palo_options );

		$custom_subject = trim( assign_if_exists( 'palo_setting_registration_email_subject', $palo_options ) );
		$custom_message = trim( assign_if_exists( 'palo_registration_email_message', $palo_options ) );

		$user = get_userdata( $user_id );
		
		if ( $password_on_registration_enabled ) {
			$plaintext_pass = $_POST[ 'palo_password' ];
		}

		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

		$subject = sprintf(__('[%s] New User Registration', 'pressapps'), $blogname);

		$message = sprintf(__('New user registration on your site %s:', 'pressapps'), $blogname) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s', 'pressapps'), $user->user_login) . "\r\n\r\n";
		$message .= sprintf(__('E-mail: %s', 'pressapps'), $user->user_email) . "\r\n";


		@wp_mail(get_option('admin_email'), $subject, $message);

		if ( empty($plaintext_pass) )
		return;

		if ( $custom_subject ) {
			$subject = $custom_subject;
		} else {
			$subject = sprintf(__('[%s] Your username and password', 'pressapps'), $blogname );
		}

		if ( $custom_message ) {
			$message = $custom_message;
			$message = str_replace( 
				array( '%username%', '%password%', '%loginlink%' ), 
				array( $user->user_login, $plaintext_pass, wp_login_url() ), 
				$message
			);
		} else {
			$message = sprintf(__('Username: %s', 'pressapps'), $user->user_login) . "\r\n";
			$message .= sprintf(__('Password: %s', 'pressapps'), $plaintext_pass) . "\r\n";
			$message .= wp_login_url() . "\r\n";
		}

		@wp_mail( $user->user_email, $subject, $message );

		/**
		 * Login after registration
		 */
		if ( $password_on_registration_enabled ) {
			$creds['user_login'] = $_POST['user_login'];
			$creds['user_password'] = $_POST['palo_password'];
			$creds['remember'] = true;
			wp_signon( $creds, false );
		}

		/**
		 * Redirect after login
		 */
		if ( $password_on_registration_enabled ) {
			/**
			 * Where to redirect, replace empty URLs with home_url();
			 */
			$palo_login_behavior = assign_if_exists( 'palo_logout_behavior', $palo_options, 'PALO_REDIRECT_DEFAULT' );
			$palo_login_url = trim( assign_if_exists( 'palo_login_url', $palo_options ) );
			$palo_login_url = $palo_login_url ? esc_url_raw( $palo_login_url ) : home_url();

			/**
			 * Redirect
			 */
			switch ( $palo_login_behavior ) {
			case 'PALO_REDIRECT_URL' :
				palo_redirect( $palo_login_url );
				break;
			case 'PALO_REDIRECT_CURRENT' :
				/* Todo */
				break;
			default :
				palo_redirect( home_url() );
			}
		}
	}
}

/**
 * Removes slashes added to the password by WordPress
 */
function palo_filter_user_password_in_email( $args ) {

	/**
	 * If it's not an registration email
	 */
	if ( empty ( $_POST['palo_password'] ) ) {
		return $args;
	}
	
	$password = $_POST[ 'palo_password' ];
	$password_unslashed = wp_unslash( $password );

	$args['message'] = str_replace($password, $password_unslashed, $args['message']);

	return $args;
}
