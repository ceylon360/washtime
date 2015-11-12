<?php

/**
 * @file
 * Actions (functions) used in the login and registration pages
 */

/**
 * Outputs the CSS of the core login and registration pages.
 */
function palo_action_login_css() {
	global $palo_options, $palo_styles_dir;

	$login_custom_css_code = assign_if_exists( 'palo_setting_login_custom_css_code', $palo_options );
	$modal = assign_if_exists ( 'palo-login', $_REQUEST ) || assign_if_exists ( 'interim-login', $_REQUEST );

	/**
	 * Enqueue login.css
	 */
	wp_register_style( 'palo-login', $palo_styles_dir . 'login.css' );
	wp_enqueue_style( 'palo-login' );

	/**
	 * Hide Wordpress logo
	 */
	$css = '';

	if( $modal ) {
		/**
		 * Build the CSS for the wp-login.php page in modal window
		 */
		$background_color = assign_if_exists( 'palo_setting_modal_background_color', $palo_options );
		$text_color = assign_if_exists( 'palo_setting_modal_text_color', $palo_options );
		$link_color = assign_if_exists( 'palo_setting_modal_link_color', $palo_options );
		$button_color = assign_if_exists( 'palo_setting_modal_button_color', $palo_options );
		$button_background_color = assign_if_exists( 'palo_setting_modal_button_background_color', $palo_options );

		/**
		 * Merge form with background
		 */
		$css .= '.login form { background: none; box-shadow: none; padding: 10px 40px!important; } ';

		if ( $background_color ) {
			$css .= 'body.login { background:none; } ';
			$css .= 'html { background-color: ' . $background_color . ' !important; } ';
		}
		if ( $text_color ) { 
			$css .= '.login label, #reg_passmail, div.updated, .login .message, .login #login_error a, div.error, .login #login_error { color: ' . $text_color . '; } ';
		}
		if ( $link_color ) {
			$css .= '#nav { color: ' . sprintf('rgba(%s, %s)', palo_hex2rgb( $link_color ), .25 ) . '; } '; 
			$css .= '.login #backtoblog a, .login #backtoblog a:hover, .login #nav a, .login #nav a:hover, .login #nav { color: ' . $link_color . ' } ';
		//	$css .= '.login #backtoblog a, .login #nav a { opacity: 1; transition: opacity .5s ease; } .login #backtoblog a:hover, .login #nav a:hover { opacity: .5; } ';
		}
		if ( $button_color || $button_background_color ) {
			$css .= '#wp-submit { ';
			if ( $button_color ) {
				$css .= 'color: ' . $button_color . '; ';
			}
			if ( $button_background_color ) {
				$css .= 'background: ' . $button_background_color . '; ';
			}
			$css .= '} ';
		}
	} else {
		/**
		 * Build the CSS for the wp-login.php page in normal conditions
		 */
		$background_image = assign_if_exists( 'palo_background_image', $palo_options );
		$background_color = assign_if_exists( 'palo_setting_background_color', $palo_options );
		$form_background_color = assign_if_exists( 'palo_setting_form_background_color', $palo_options );
		$text_color = assign_if_exists( 'palo_setting_text_color', $palo_options );
		$link_color = assign_if_exists( 'palo_setting_link_color', $palo_options );
		$button_color = assign_if_exists( 'palo_setting_button_color', $palo_options );
		$button_background_color = assign_if_exists( 'palo_setting_button_background_color', $palo_options );

		if ( $background_color || $background_image ) {
			$css .= 'body.login { background:none; } ';
			$css .= 'html { ';
			if ( $background_color ) {
				$css .= 'background-color: ' . $background_color . '; ';
			}
			if ( $background_image ) {
				$css .= 'background: url("' . $background_image . '") no-repeat center center fixed; ';
				$css .= 'background-size: cover; ';
			}
			$css .= '} ';
		}
		if ( $text_color ) { 
			$css .= '.login label, #reg_passmail { color: ' . $text_color . '; } ';
		}
		if ( $form_background_color ) {
			$css .= '.login form { background: ' . $form_background_color . '; } ';
		}
		if ( $link_color ) {
			$css .= '#nav{ color: ' . sprintf('rgba(%s, %s)', palo_hex2rgb( $link_color ), .25 ) . '; } '; 
			$css .= '.login #backtoblog a, .login #backtoblog a:hover, .login #nav a, .login #nav a:hover, .login #nav, div.updated, .login .message, .login #login_error a, div.error, .login #login_error { color: ' . $link_color . ' } ';
		//	$css .= '.login #backtoblog a, .login #nav a { opacity: 1; transition: opacity .2s ease; } .login #backtoblog a:hover, .login #nav a:hover { opacity: .7; } ';
		}
		if ( $button_color || $button_background_color ) {
			$css .= '.login #wp-submit { ';
			if ( $button_color ) {
				$css .= 'color: ' . $button_color . '; ';
			}
			if ( $button_background_color ) {
				$css .= 'background: ' . $button_background_color . '; ';
			}
			$css .= '} ';
		}
	}

	/**
	 * Prepend custom code
	 */
	$css .= "\n" . $login_custom_css_code;

	/**
	 * Output CSS
	 */
	printf( '<style type="text/css">%s</style>%s', "\n$css\n", "\n" );
}

/**
 * Output the CSS of the frontend for profile and forms shortcodes
 */
function palo_action_front_css() {
	
	global $palo_helper, $palo_options;

	if(
		! empty ( $palo_helper[ 'enqueue_front_css' ] ) 
		&& 
		$css = assign_if_exists( 'palo_setting_front_custom_css_code', $palo_options )
	) {
		echo "<style>$css</style>";
	}
}

/**
 * Adds a hidden field that helps us know wp-login.php is in a modal window
 */
function palo_action_hidden_tracker_field() {
	if( ! empty( $_REQUEST[ 'palo-login' ] ) ) {
		palo_input_form_field( 'palo-login',   'type=hidden&value=1' );
	}
}