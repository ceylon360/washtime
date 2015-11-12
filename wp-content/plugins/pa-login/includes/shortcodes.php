<?php

/**
 * Creates the output for [pa_profile]
 * 
 * @return string the shortcode generated HTML code
 */
function palo_shortcode_frontend_profile_form() {

	global $palo_helper, $palo_textdomain;

	$palo_helper[ 'enqueue_front_css' ] = true;

	/**
	 * Load user
	 */
	$user = wp_get_current_user();

	/**
	 * Fallback for guest users
	 */
	if ( ! $user->ID ) {
		return sprintf( '<a href="%s">%s</a>', wp_login_url(), __( 'Login to view profile page', $palo_textdomain ) );
	}

	/**
	 * Reload user to force reading any info saved during init
	 */
	$user = get_userdata( $user->ID );

	$user_bio = get_the_author_meta( 'description', $user->ID );

	$user_public_display = array();
	$user_public_display[ 'display_nickname' ]  = $user->nickname;
	$user_public_display[ 'display_username' ]  = $user->user_login;


	if ( ! empty( $user->first_name ) ) {
		$user_public_display[ 'display_firstname' ] = $user->first_name;
	}

	if ( ! empty( $user->last_name ) ) {
		$user_public_display[ 'display_lastname' ] = $user->last_name;
	}

	if ( ! empty( $user->first_name ) && ! empty( $user->last_name ) ) {
		$user_public_display[ 'display_firstlast' ] = $user->first_name . ' ' . $user->last_name;
		$user_public_display[ 'display_lastfirst' ] = $user->last_name . ' ' . $user->first_name;
	}

	if ( ! in_array( $user->display_name, $user_public_display ) ) {
		// Only add this if it isn't duplicated elsewhere}
		$user_public_display = array( 'display_displayname' => $user->display_name ) + $user_public_display;
	}

	$user_public_display = array_map( 'trim', $user_public_display );
	$user_public_display = array_unique( $user_public_display );

	/**
	 * Caprute form
	 */
	ob_start();
	?>
	<div id="palo-profile">
 		<div class="palo-avatar-img"><?php echo get_avatar( $user->user_email, 72 ); ?></div>
		<div class="palo-avatar-info">
			<p><a href="http://gravatar.com/"><?php _e( 'Change Avatar', $palo_textdomain ); ?></a></p>
			<p><?php _e( "Note: Avatar is auto taken in Gravatar.com. If you insert you registered email in our email section then your uploaded avatar will be displayed in this section", $palo_textdomain ); ?></p>
		</div>
		<form style="clear: both;" action="<?php the_permalink(); ?>" method="post">
			<div class="palo-first">
				<?php palo_input_form_field( 'first_name', 'id=palo_first_name&p=1&label=' . urlencode( __( "First Name", $palo_textdomain ) ) . '&value=' . urlencode( $user->first_name ) ); ?>
				<?php palo_input_form_field( 'last_name', 'id=palo_last_name&p=1&label=' . urlencode( __( "Last Name", $palo_textdomain ) ) . '&value=' . urlencode( $user->last_name ) ); ?>
				<?php palo_input_form_field( 'nickname', 'id=palo_nickname&p=1&label=' . urlencode( __( "Nickame", $palo_textdomain ) ) . '&required=1&value=' . urlencode( $user->nickname ) ); ?>
				<?php palo_select_form_field(
					'display_name',
					'id=palo_display_name&p=1&label=' . urlencode( __( "Display name publicly as", $palo_textdomain ) ) . '&selected=' . urlencode( $user->display_name ),
					$user_public_display
				); ?>
				<?php palo_input_form_field( 'email', 'id=palo_email&type=email&required=1&p=1&label=' . urlencode( __( "Email", $palo_textdomain ) ) . '&value=' . urlencode( $user->user_email ) ); ?>
			</div>
			<div class="palo-last">
				<?php palo_input_form_field( 'pass1',	  'id=palo_pass&type=password&p=1&label=' . urlencode( __( "New Password", $palo_textdomain ) ) ); ?>
				<?php palo_input_form_field( 'pass2',	  'id=palo_pass2&type=password&p=1&label=' . urlencode( __( "Repeat New Password", $palo_textdomain ) ) ); ?>
				<?php palo_input_form_field( 'url',		'id=palo_website&p=1&label=' . urlencode( __( "Website", $palo_textdomain ) )	. '&value=' . urlencode( $user->user_url ) ); ?>
				<?php palo_input_form_field( 'description', 'id=palo_description&type=textarea&p=1&label=' . urlencode( __( "Biographical Info", $palo_textdomain ) ) . '&value=' . urlencode( $user_bio ) ); ?>
			</div>
			<div class="palo-profile-submit">
				<?php wp_nonce_field( 'update-profile_' . $user->ID ); ?>
				<?php palo_input_form_field( 'palo_action',   'type=hidden&value=update_profile' ); ?>
				<?php palo_submit_form_button( __('Update Profile', $palo_textdomain) ); ?>
			</div>
		</form>
	</div>
	<?php

	/**
	 * End for capture and return form
	 */
	return ob_get_clean();
}

/**
 * Creates the output for [pa_modal_login]
 * 
 * @return string the shortcode generated HTML code
 */
function palo_shortcode_frontend_modal_login( $atts = array() ) {

	$atts = shortcode_atts( array(
		'login_text' => __( 'Login', $palo_textdomain ),
		'logout_text' => __( 'Logout', $palo_textdomain ),
	), $atts );

	/**
	 * Logged in user will see a normal link and not logged in 
	 * users will see a normal login that has additional Javascript
	 * functions attached to it
	 */
	if( is_user_logged_in() ) {
		$link = sprintf( '<a href="%s">%s</a>', wp_logout_url(), $atts[ 'logout_text' ] );
	} else {
		$link = sprintf(
			'<a href="%s" data-palo-modal="%s" class="palo-modal-login-trigger">%s</a>',
			wp_login_url(),
			palo_append_qs( wp_login_url(), 'palo-login=1' ),
			$atts[ 'login_text' ]
		);
	}
	return $link;
}

/**
 * Creates the output for [pa_modal_register]
 * 
 * @return string the shortcode generated HTML code
 */
function palo_shortcode_frontend_modal_register( $atts = array() ) {

	global $palo_textdomain;

	$atts = shortcode_atts( array(
		'register_text' => __( 'Register', $palo_textdomain ),
		'registered_text' => __( 'You are already registered', $palo_textdomain ),
	), $atts );

	/**
	 * Logged in user will see a normal link and not logged in 
	 * users will see a normal login that has additional Javascript
	 * functions attached to it
	 */
	if( is_user_logged_in() ) {
		$link = $atts[ 'registered_text' ];
	} else {
		$link = sprintf(
			'<a href="%s" data-palo-modal="%s" class="palo-modal-login-trigger">%s</a>',
			wp_registration_url(),
			palo_append_qs( wp_registration_url(), 'palo-login=1'),
			$atts[ 'register_text' ]
		);
	}
	return $link;
}

/**
 * Creates the output for [pa_login]
 * 
 * @return string the shortcode generated HTML code
 */
function palo_shortcode_frontend_login_form() {
	
	global $palo_helper;

	$palo_helper[ 'enqueue_front_css' ] = true;

	/**
	 * Cut the form before the remember me box and inject the login_form action output
	 */
	$login_form_parts  = explode( '<p class="login-remember">', wp_login_form( array('echo' => false) ));
	ob_start();
	do_action( 'login_form' );
	$output = $login_form_parts[0] . ob_get_clean() . $login_form_parts[1];

	/**
	 * Replace the id
	 */
	$output = str_replace('<form name="loginform" id="loginform"', '<form id="palo-loginform"', $output );

	return $output;
}

/**
 * Creates the output for [pa_register]
 * 
 * @return string the shortcode generated HTML code
 */
function palo_shortcode_frontend_register_form() {

	global $palo_helper, $palo_textdomain;

	$palo_helper[ 'enqueue_front_css' ] = true;

	ob_start();
	?>
	<div id="palo-registerform">
		<form action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" method="post">
			<p>
				<label for="palo-form-login-user-login"><?php _e( 'Username', $palo_textdomain ); ?></label>
				<input type="text" class="input" name="user_login" id="palo-form-login-user-login"/>
			</p>
			<p>
				<label for="palo-form-register-user-email"><?php _e( 'E-mail', $palo_textdomain ); ?></label>
				<input type="text" class="input" name="user_email" id="palo-form-register-user-email"/>
			</p>
			<?php do_action('register_form'); ?>
			<p class="register-submit">
				<input type="submit" value="<?php _e( 'Register', $palo_textdomain ); ?>" />
			</p>
		</form>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Creates the output for [pa_reset] and it's alias [pa_forgotten]
 * 
 * @return string the shortcode generated HTML code
 */
function palo_shortcode_frontend_reset_form() {

	global $palo_helper, $palo_textdomain;

	$palo_helper[ 'enqueue_front_css' ] = true;

	ob_start();
	?>
	<div id="palo-lostpasswordform">
		<form action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" method="post">
			<p>
				<label for="palo-form-lostpassword-user-login"><?php _e( 'Username or E-mail', $palo_textdomain ); ?></label>
				<input type="text" class="input" name="user_login" id="palo-form-lostpassword-user-login"/>
			</p>
			<?php do_action('lostpassword_form'); ?>
			<p class="forgotten-submit">
				<input type="submit" value="<?php _e( 'Get New Password', $palo_textdomain ); ?>" />
			</p>
		</form>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Prints or returns a login form
 * 
 * Calling this function is the same as using the shortcode with the same tag name
 * 
 * @param boolean $return 
 * @return null|string The shortcode output if $return is true, null otherwise
 */
function pa_login( $return = false ) {
	if ( $return ) {
		return do_shortcode( '[pa_login]' );
	}
	echo do_shortcode( '[pa_login]' );
}

/**
 * Prints or returns a registration form
 * 
 * Calling this function is the same as using the shortcode with the same tag name
 * 
 * @param boolean $return 
 * @return null|string The shortcode output if $return is true, null otherwise
 */
function pa_register( $return = false ) {
	if ( $return ) {
		return do_shortcode( '[pa_register]' );
	}
	echo do_shortcode( '[pa_register]' );
}

/**
 * Prints or returns a password reset form
 * 
 * Calling this function is the same as using the shortcode with the same tag name
 * 
 * @param boolean $return 
 * @return null|string The shortcode output if $return is true, null otherwise
 */
function pa_reset( $return = false ) {
	if ( $return ) {
		return do_shortcode( '[pa_reset]' );
	}
	echo do_shortcode( '[pa_reset]' );
}

/**
 * This is an alias to pa_reset()
 */
function pa_forgotten( $return = false ) {
	return pa_reset( $return );
}

/**
 * Prints or returns a profile form
 * 
 * Calling this function is the same as using the shortcode with the same tag name
 * 
 * @param boolean $return 
 * @return null|string The shortcode output if $return is true, null otherwise
 */
function pa_profile( $return = false ) {
	if ( $return ) {
		return do_shortcode( '[pa_profile]' );
	}
	echo do_shortcode( '[pa_profile]' );
}

/**
 * Prints or returns a modal login link
 * 
 * Calling this function is the same as using the shortcode with the same tag name
 * 
 * @param boolean $return 
 * @return null|string The shortcode output if $return is true, null otherwise
 */
function pa_modal_login( $atts = array(), $return = false ) {
	if ( $return ) {
		return do_shortcode( '[pa_modal_login ' . palo_params_str( $atts ) . ']' );
	}
	echo do_shortcode( '[pa_modal_login ' . palo_params_str( $atts ) . ']' );
}

/**
 * Prints or returns a modal registration link
 * 
 * Calling this function is the same as using the shortcode with the same tag name
 * 
 * @param boolean $return 
 * @return null|string The shortcode output if $return is true, null otherwise
 */
function pa_modal_register( $atts = array(), $return = false ) {
	if ( $return ) {
		return do_shortcode( '[pa_modal_register ' . palo_params_str( $atts ) . ']' );
	}
	echo do_shortcode( '[pa_modal_register ' . palo_params_str( $atts ) . ']' );
}
