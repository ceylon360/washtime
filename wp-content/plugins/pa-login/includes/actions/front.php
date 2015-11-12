<?php

/**
 * Saves the profile form after submission
 * 
 * Mesasges (notices and errors) are stored on the helper and are later 
 * used by the shortcode when the form is generated
 */
function palo_action_frontend_profile_save() {

	global $palo_helper, $palo_textdomain;

	if ( empty( $_POST[ 'palo_action' ] ) ) {
		return;
	}

	$user = wp_get_current_user();
	$user = get_userdata( $user->ID );

	/**
	 * Verify nonce and continue only if it's valid
	 */
	$nonce = assign_if_exists ( '_wpnonce', $_POST );
	if ( ! wp_verify_nonce( $nonce, 'update-profile_' . $user->ID ) ) {
		$palo_helper[ 'profile' ][ 'error' ][] = __( 'The form expired, please try again.', $palo_textdomain );
		return;
	}

	/**
	 * Verify email
	 */
	if ( empty( $_POST[ 'email' ] ) ) {
		$palo_helper[ 'profile' ][ 'error' ][] = __( 'The email address is required.', $palo_textdomain );
	} else {
		if ( ! is_email( esc_attr( $_POST[ 'email' ] ) ) ) {
			$palo_helper[ 'profile' ][ 'error' ][] = __( 'The email address is not valid.', $palo_textdomain );
		} else if ( email_exists( esc_attr( $_POST[ 'email' ] ) ) ) {
			if ( email_exists( esc_attr( $_POST[ 'email' ] ) ) != $user->ID ) {
				$palo_helper[ 'profile' ][ 'error' ][] = __( 'The email you entered is used by another user.', $palo_textdomain );
			}
		}
	}

	/**
	 * Verify nickname
	 */
	if ( empty( $_POST[ 'nickname' ] ) ) {
		$palo_helper[ 'profile' ][ 'error' ][] = __( 'The nickname is required.', $palo_textdomain );
	}

	/**
	 * Verify passwords
	 */
	$pass1 = assign_if_exists( 'pass1', $_POST);
	$pass2 = assign_if_exists( 'pass2', $_POST);
	if ( $pass1 || $pass2 ) {
		if ( $pass1 !== $pass2 ) {
			$palo_helper[ 'profile' ][ 'error' ][] = __( 'Passwords do not match.', $palo_textdomain );
		}
	}

	/**
	 * Exit if errors
	 */
	if ( ! empty( $palo_helper[ 'profile' ][ 'error' ] ) ) {
		return;
	}

	/**
	 * Save
	 */
	update_user_meta( $user->ID, 'nickname', esc_attr( $_POST[ 'nickname' ] ) );
	update_user_meta( $user->ID, 'last_name', esc_attr( $_POST[ 'last_name' ] ) );
	update_user_meta( $user->ID, 'first_name', esc_attr( $_POST[ 'first_name' ] ) );
	update_user_meta( $user->ID, 'description', esc_attr( $_POST[ 'description' ] ) );
	wp_update_user( array ( 
		'ID' => $user->ID,
		'user_url' => esc_attr( $_POST[ 'url' ] ),
		'user_email' => esc_attr( $_POST[ 'email' ] ),
		'display_name' => esc_attr( $_POST[ 'display_name' ] ),
	) );
	if ( $pass1 || $pass2 ) {
		wp_update_user( array( 'ID' => $user->ID, 'user_pass' => esc_attr( $_POST[ 'pass1' ] ) ) );
	}

	/**
	 * Leave this here, otherwise it won't take effect
	 */
	update_user_meta( $user->ID, 'display_name', esc_attr( $_POST[ 'display_name' ] ) );

	$palo_helper[ 'profile' ][ 'notice' ][] = __( 'Profile updated successfully.', $palo_textdomain );
}

/**
 * Registers the frontend stylesheet and script files
 */
function palo_action_frontend_scripts() {
	global $palo_scripts_dir, $palo_styles_dir, $palo_helper, $palo_options;

	wp_register_style( 'palo-front', $palo_styles_dir . 'front.css' );
	wp_register_script( 'palo-front', $palo_scripts_dir . 'frontend.js', array( 'jquery' ), false, true );

	wp_enqueue_style( 'palo-front' );
	wp_enqueue_script( 'palo-front' );

	/**
	 * Add the modal window HTML/CSS if not already done
	 */
	if ( ! is_user_logged_in() ) {
		
		// wp_auth_check_html() with <p> tags removed and correct URL
		ob_start();
		wp_auth_check_html();
		$wp_auth_check_html = ob_get_clean();
		$wp_auth_check_html = preg_replace( '/<p>.*<\/p>/s', '', $wp_auth_check_html );
		$wp_auth_check_html = str_replace( 'interim-login=1', 'palo-login=1', $wp_auth_check_html );
		echo $wp_auth_check_html;

		wp_enqueue_style( 'wp-auth-check' );
		$text_color = assign_if_exists( 'palo_setting_modal_text_color', $palo_options );
		?>
			<style>
				#wp-auth-check-wrap #wp-auth-check {
					padding-top: 0 !important
				}
				#wp-auth-check-wrap .wp-auth-check-close:before {
					color: <?php echo $text_color; ?> !important;
				}
			</style>
			<script type="text/javascript">
				jQuery( document ).ready( function($) {
					$( 'a[href="#pa_modal_login"]' )
						.attr( 'href', '<?php echo wp_login_url() ?>' )
						.attr( 'data-palo-modal', '<?php echo palo_append_qs( wp_login_url(), "palo-login=1"); ?>'.replace( '&amp;', '&' ) )
						.addClass( 'palo-modal-login-trigger' )
					;
					$( 'a[href="#pa_modal_register"]' )
						.attr( 'href', '<?php echo wp_registration_url() ?>' )
						.attr( 'data-palo-modal', '<?php echo palo_append_qs( wp_registration_url(), "palo-login=1") ?>'.replace( '&amp;', '&' ) )
						.addClass( 'palo-modal-login-trigger' )
					;
				} );
			</script>
		<?php
	} else {
		?>
		<script type="text/javascript">
			jQuery( document ).ready( function($) {
				$( 'a[href="#pa_modal_login"]' ).attr( 'href', '<?php echo wp_logout_url() ?>'.replace( '&amp;', '&' ) );
			} );
		</script>
		<?php
	}
}

/**
 * Redirect if this page is restricted
 */
function palo_action_frontend_access_control() {

	/**
	 * Do not check access on non-posts
	 */
	if ( ! is_singular() ) {
		return;
	}

	/**
	 * Do not check access for logged in users
	 */
	if ( is_user_logged_in() ) {
		return;
	}

	global $palo_options, $post;
	$action = assign_if_exists( 'palo_access_action', $palo_options );
	$excluded = false;

	$post_type = $post->post_type;
	$post_type_taxonomies = get_object_taxonomies($post_type);
	$post_type_exceptions = assign_if_exists ( 
		'palo_access_exceptions_' . $post_type,
		$palo_options,
		array()
	);

	foreach ($post_type_taxonomies as $taxonomy) {
		$post_terms[ $taxonomy ] = get_the_terms( $post->ID, $taxonomy );
		if ( ! empty( $post_terms[ $taxonomy ] ) ) {
			foreach ( $post_terms[ $taxonomy ] as $term ) {
				$post_terms[ $taxonomy ][ $term->term_id ] = $term->name;
			}
		}
	}

	// Check if "All" excluded
	if ( in_array( '_all_', $post_type_exceptions ) ) {
		$excluded = true;
	}

	// If the post type is not excluded, check if post is excuded by ID
	if ( ! $excluded ) {
		if ( in_array( $post->ID, $post_type_exceptions ) ) {
			$excluded = true;
		}
	}

	// If the post type is not excluded, check if post is excuded by taxonomy term
	if ( ! $excluded ) {
		if ( ! empty( $post_terms ) ) {
			foreach ( $post_terms as $taxonomy => $terms ) {
				if ( ! empty( $terms ) ) {
					foreach ( $terms as $term_id => $term_name ) {
						if ( in_array( "$taxonomy:$term_id", $post_type_exceptions ) ) {
							$excluded = true;
						}
					}
				}
			}
		}
	}

	/**
	 * Allow or block
	 * 
	 * This is how it works
	 *     - Block if:
	 *         - action != block AND post == excluded
	 *         - action == block
	 *     - Allow if:
	 *         - action == block AND post == excluded
	 *         - action != block
	 * 
	 */
	if ( 
		( 'PALO_ACCESS_ACTION_BLOCK' !== $action && $excluded ) ||
		( 'PALO_ACCESS_ACTION_BLOCK' === $action && !$excluded ) 
	) {
		// Where to redirect
		if( 'PALO_REDIRECT_URL' === assign_if_exists( 'palo_access_behavior', $palo_options ) ) {
			$access_url = assign_if_exists( 'palo_access_url', $palo_options );
			// If URL is empty, use login URL
			if( ! $access_url ) {
				$access_url = wp_login_url();
			}
		} else {
			$access_url = wp_login_url();
		}
		// Redirect
		palo_redirect( $access_url );
	}
}