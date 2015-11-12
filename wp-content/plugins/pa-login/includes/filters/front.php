<?php

/**
 * Adds success or error messages above the profile form
 * 
 * @param string $content The content of the page 
 * @return string The new modified content with messages first
 */
function palo_filter_frontend_profile_messages( $content ) {

	global $palo_helper;

	$notices_html = '';
	$errors_html = '';

	if ( ! empty( $palo_helper[ 'profile' ][ 'notice' ] ) ) {
		foreach ( $palo_helper[ 'profile' ][ 'notice' ] as $notice ) {
			$notices_html .= sprintf( '<p class="palo-message palo-notice">%s</p>', $notice );
		}
	}

	if ( ! empty( $palo_helper[ 'profile' ][ 'error' ] ) ) {
		foreach ( $palo_helper[ 'profile' ][ 'error' ] as $error ) {
			$notices_html .= sprintf( '<p class="palo-message palo-error">%s</p>', $error );
		}
	}

	return  $notices_html . $errors_html . $content;
}

/**
 * Sets the right href and class attributes for the modal link in menus
 */
function palo_filter_frontend_modal_link_atts( $atts, $item, $args ) {

	/**
	 * Ony apply to modal login or register
	 */
	if( in_array( $atts[ 'href'], array( '#pa_modal_login', '#pa_modal_register' ) ) ) {
		/**
		 * Add trigger if not logged in
		 */
		if ( ! is_user_logged_in() ) {
			$atts[ 'class' ] =  assign_if_exists( 'class', $atts ) . ' palo-modal-login-trigger';
		}
		/**
		 * Modify links
		 */
		if( '#pa_modal_login' == $atts[ 'href'] ) {
			if ( is_user_logged_in() ) {
				$atts[ 'href' ] = wp_logout_url();
			} else {
				$atts[ 'href' ] = wp_login_url();
				$atts[ 'data-palo-modal' ] = palo_append_qs( wp_login_url(), 'palo-login=1');
			}
		} else if ( '#pa_modal_register' == $atts[ 'href'] ) {
			$atts[ 'href' ] = wp_registration_url();
			$atts[ 'data-palo-modal' ] = palo_append_qs( wp_registration_url(), 'palo-login=1');
		}
	}    
    return $atts;
}

/**
 * Sets the right label for the modal link in menus
 */
function palo_filter_frontend_modal_link_label( $items ) {
	foreach ( $items as $i => $item ) {
		if( '#pa_modal_login' === $item->url ) {
			$item_parts = explode( ' // ', $item->title );
			if ( is_user_logged_in() ) {
				$items[ $i ]->title = array_pop( $item_parts );
			} else {
				$items[ $i ]->title = array_shift( $item_parts );
			}
		}
	}
	return $items;    
}

/**
 * Hides the modal registation link in menus for logged in users
 */
function palo_filter_frontend_modal_link_register_hide( $items ) {
	foreach ( $items as $i => $item ) {
		if( '#pa_modal_register' === $item->url ) {
			if ( is_user_logged_in() ) {
				unset( $items[ $i ] );
			}
		}
	}
	return $items;    
}
