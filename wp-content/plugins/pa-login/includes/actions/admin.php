<?php

/**
 * @file
 * Actions (functions) used in the administration pages
 */

/**
 * Adds the admin page and it's menu under the Settings menu
 */
function palo_action_add_admin_page() {
	global $palo_textdomain;
	add_options_page( __( 'Custom Plugin Page', $palo_textdomain ), __( 'PA Login & Access', $palo_textdomain ), 'manage_options', 'palo_settings', 'palo_options_page_html' );
}

/**
 * Adds the admin settings page and check access
 * 
 * The function adds the different section of the settings page, 
 * it also redirects subscribers to the homepage when applicable
 */
function palo_action_admin_init(){

	global $current_user, $palo_options, $palo_textdomain;

	/**
	 * Make sure global is set, if not set it.
	 */
	get_currentuserinfo();

	/**
	 * Redirect to homepage if the setting "Restrict Admin Access" 
	 * is on and the user is a subscriber only
	 */
	if ( !empty( $palo_options[ 'palo_restrict_access' ] ) && current_user_is_subscriber_only() ) {
		wp_redirect( home_url() );
		exit;
	}

	/**
	 * Add sections and fields
	 */
	register_setting( 'palo_options_group', 'palo_options' );
	// Login settings
	palo_add_settings_field( 'login_behaviour', __( 'Redirect After Login', $palo_textdomain ), 'login', 'Login Settings' );
	palo_add_settings_field( 'login_url', __( 'Custom URL', $palo_textdomain ), 'login' );
	palo_add_settings_field( 'logout_behaviour', __( 'Redirect After Logout', $palo_textdomain ), 'login' );
	palo_add_settings_field( 'logout_url', __( 'Custom URL', $palo_textdomain ), 'login' );
	palo_add_settings_field( 'password_on_registration', __( 'Users Generated Password', $palo_textdomain ), 'login' );
	palo_add_settings_field( 'captcha', __( 'Enable Math Captcha', $palo_textdomain ), 'login' );
	// Registration email
	palo_add_settings_field( 'registration_email_subject', __( 'Subject', $palo_textdomain ), 'register', 'Registration Email' );
	palo_add_settings_field( 'registration_email_message', __( 'Content', $palo_textdomain ), 'register' );
	// Access Control settings
	palo_add_settings_group( 'access_control', __( 'Restrict Front End Access', $palo_textdomain ), array( 'action', 'exceptions' ), 'access', 'Access Control' );
	palo_add_settings_field( 'access_behaviour', __( 'Redirect To', $palo_textdomain ), 'access' );
	palo_add_settings_field( 'access_url', __( 'Custom URL', $palo_textdomain ), 'access' );
	palo_add_settings_field( 'restrict_access', __( 'Restrict Admin Access', $palo_textdomain ), 'access' );
	// Styling	
	palo_add_settings_separator( __( 'Login Page', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'background_image', __( 'Background Image', $palo_textdomain ), 'style', 'Styling' );
	palo_add_settings_field( 'background_color', __( 'Background Color', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'form_background_color', __( 'Form Background Color', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'text_color', __( 'Text Color', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'link_color', __( 'Links & Message Color', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'button_color', __( 'Button Text Color', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'button_background_color', __( 'Button Background Color', $palo_textdomain ), 'style' );
	palo_add_settings_separator( __( 'Modal Login', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'modal_background_color', __( 'Background Color', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'modal_text_color', __( 'Text Color', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'modal_link_color', __( 'Links Color', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'modal_button_color', __( 'Button Text Color', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'modal_button_background_color', __( 'Button Background Color', $palo_textdomain ), 'style' );
	palo_add_settings_separator( __( 'General', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'front_custom_css_code', __( 'Enter custom CSS for the front-end', $palo_textdomain ), 'style' );
	palo_add_settings_field( 'login_custom_css_code', __( 'Enter custom CSS for WP login page and modal login', $palo_textdomain ), 'style' );

	// Add the modal link metabbox in the Edit Menus page
	add_meta_box('palo_metabox_modal_link', __('PA Modal Link', $palo_textdomain ), 'palo_callback_metabox_modal_link', 'nav-menus', 'side', 'high');
}

/**
 * Loads (register and enqueue) the Javascript used on the Palo settings and the menus configuration page
 */
function palo_action_load_scripts() {

	global $palo_textdomain, $palo_scripts_dir;
	$screen = get_current_screen();

	wp_register_script( 'palo-admin', $palo_scripts_dir . 'admin.js' );
	wp_enqueue_script( 'palo-admin' );

	switch ( $screen->base ) {
	case 'settings_page_palo_settings':
		wp_enqueue_media();
		wp_register_script( 'palo-admin-settings', $palo_scripts_dir . 'admin-settings.js', array( 'wp-color-picker' ) );
		wp_enqueue_script( 'palo-admin-settings' );
		wp_register_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.6/select2.min.js', 'jquery' );
		wp_enqueue_script( 'select2' );
		break;
	case 'nav-menus':
		$strings = array(
			'label_login' => __( 'Login Label', $palo_textdomain ),
			'label_logout' => __( 'Logout Label', $palo_textdomain ),
		);
		wp_register_script( 'palo-admin-nav-menus', $palo_scripts_dir . 'admin-nav-menus.js', array( 'jquery' ) );
		wp_enqueue_script( 'palo-admin-nav-menus' );
		wp_localize_script( 'palo-admin-nav-menus', 'palo_strings', $strings );
		break;
	default:
		# code...
		break;
	}
}

/**
 * Loads (register and enqueue) the Javascript used on the Palo settings page
 */
function palo_action_load_styles() {

	global $palo_styles_dir;
	$screen = get_current_screen();

	if ( 'settings_page_palo_settings' === $screen->base ) {
		wp_register_style( 'palo-admin', $palo_styles_dir . 'admin.css', array( 'wp-color-picker' ) );
		wp_enqueue_style( 'palo-admin' );
		wp_register_style( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.6/select2.min.css', 'jquery' );
		wp_enqueue_style( 'select2' );
	}
}

/**
 * Print the meta box containg the modal link
 */
function palo_callback_metabox_modal_link() {

	global $palo_textdomain;
	?>
	<div id="posttype-palo-modal-link" class="posttypediv">
		<div id="tabs-panel-palo-modal-link" class="tabs-panel tabs-panel-active">
			<ul id ="palo-modal-link-checklist" class="categorychecklist form-no-clear">
				<li>
					<label class="menu-item-title">
					<input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> <?php _e('Login', $palo_textdomain ); ?> / <?php _e('Logout', $palo_textdomain ); ?>
					</label>
					<input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
					<input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="<?php _e('Login', $palo_textdomain ); ?> // <?php _e('Logout', $palo_textdomain ); ?>">
					<input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="#pa_modal_login">
				</li>
					<li>
					<label class="menu-item-title">
					<input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> <?php _e('Register', $palo_textdomain ); ?>
					</label>
					<input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
					<input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="<?php _e('Register', $palo_textdomain ); ?>">
					<input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="#pa_modal_register">
				</li>
			</ul>
		</div>
		<p class="button-controls">
			<span class="add-to-menu">
				<input type="submit" class="button-secondary submit-add-to-menu right" value="<?php _e( 'Add to Menu', $palo_textdomain ); ?>" name="add-post-type-menu-item" id="submit-posttype-palo-modal-link">
				<span class="spinner"></span>
			</span>
		</p>
	</div>
	<?php
}
