<?php

/**
 * @file
 * Palo Login filters
 */

/**
 * Redirects user after login
 *
 * @param string $redirect_to URL to redirect to.
 * @param string $request URL the user is coming from.
 * @param object $user Logged user's data
 * @return string
 */
function palo_filter_login_redirect( $redirect_to, $request, $user ) {

	global $palo_options;

	/**
	 * Exlude adminsistrators
	 * 
	 * TODO: Mimic exact WP default behaviour 
	 */
	if( $user && is_object( $user ) && is_a( $user, 'WP_User' ) && $user->has_cap( 'administrator' ) ) { 	
		if ($redirect_to) {
			palo_redirect( $redirect_to );
		} else {
			palo_redirect( admin_url() );
		}
	} 

	$palo_login_behavior = assign_if_exists( 'palo_login_behavior', $palo_options, 'PALO_REDIRECT_DEFAULT' );
	$palo_login_url = assign_if_exists( 'palo_login_url', $palo_options, home_url() );
	$redirect_to_value = assign_if_exists( 'redirect_to', $_GET );
	if ( $redirect_to_value ) {
		$referer = $redirect_to_value;
	} else {
		$referer = assign_if_exists( 'HTTP_REFERER', $_SERVER, $redirect_to_value );
	}
	$referer_no_query_string = preg_replace( '/\?.*/', '', $referer );
	
	/**
	  * Perform the redirect depending on the option
	  */ 
	switch ( $palo_login_behavior ) {
	case 'PALO_REDIRECT_HOME' :
		if (  ! is_a( $user, 'WP_Error' )  ) {
			wp_redirect( home_url() );
			exit;
		}
		break;
	case 'PALO_REDIRECT_URL' :
		if ( is_a( $user, 'WP_User' ) ) {
			palo_redirect( esc_url_raw( $palo_login_url ) );
		}
		break;
	case 'PALO_REDIRECT_CURRENT' :
		/* Todo */
	default : 
		return $redirect_to;
	}
}

/**
 * Redirects user after logout
 */
function palo_filter_logout_redirect( $is_admin = false ) {

	global $palo_options;

	if ( $is_admin || empty( $palo_options['palo_logout_behavior'] ) ) {
		$palo_logout_behavior = 'PALO_REDIRECT_DEFAULT';
	} else {
		$palo_logout_behavior = $palo_options['palo_logout_behavior'];
	}
	$referer = array_key_exists( 'HTTP_REFERER', $_SERVER ) ? $_SERVER[ 'HTTP_REFERER' ] : '';

	/**
	 * Perform the redirect
	 */
	switch ( $palo_logout_behavior ) {
	case 'PALO_REDIRECT_HOME' :
		wp_redirect( home_url() );
		exit();
	case 'PALO_REDIRECT_URL' :
		wp_redirect( esc_url_raw( $palo_options['palo_logout_url'] ) );
		exit();
	case 'PALO_REDIRECT_CURRENT' :
		if ( $referer ) {
			wp_redirect( esc_url_raw( $referer ) );
		} else {
			wp_redirect( home_url() );
		}
		exit();
	default :
		wp_redirect( wp_login_url() );
		/* Nothing */;
	}
}
