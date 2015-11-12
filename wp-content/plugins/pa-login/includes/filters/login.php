<?php

/**
 * Keep track of (palo-login=1) and (interim-login=1)
 */
function palo_filter_login_tracker( $url ) {
	if( ! empty ( $_REQUEST[ 'interim-login' ] ) ) {
		$url = palo_append_qs( $url, 'interim-login=1' );
	}
	if( ! empty ( $_REQUEST[ 'palo-login' ] ) ) {
		$url = palo_append_qs( $url, 'palo-login=1' );
	}
	return $url;
}
