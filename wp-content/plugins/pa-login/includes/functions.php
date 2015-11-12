<?php

/**
 * @file
 * Callback functions for actions and filters and other
 * functions used inside of them.
 */

/**
 * Generates and outputs the HTML for the settings page
 * 
 * The function is used as a callback function in add_options_page()
 */
function palo_options_page_html() {
	global $palo_options, $palo_textdomain;
	?>
	<div class="wrap">
		<h2><?php _e( 'PA Login & Access Settings', $palo_textdomain ); ?></h2>
		<p><?php // _e( '<img src="https://lh4.ggpht.com/H0xw6Aflpd04ss7WZaYArUKRS4M1_92GX7tfttxJLUD0kSOpjNF8UKoXroFxSX5EVQ=w300" height="64" class="alignright" /> Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.', $palo_textdomain ); ?></p>
		<form action="options.php" method="post">
			<?php settings_fields( 'palo_options_group' ); ?>
			<?php do_settings_sections( 'palo_settings' ); ?>
			<?php submit_button(); ?>
		</form>

	</div>
	<div class="wrap">
		<hr />
		<blockquote><pre><?php //echo substr( print_r( $palo_options, true ), 5 ); ?></pre></blockquote>
	</div>
	<?php
}

/**
 * Outputs input fields for settings fields
 * 
 * The function is used as a default callback function in add_settings_field()
 * @param array $args parameters that were given to palo_add_settings_field(), 
 * 	if the first arguement ends with _color, a color picker will be used
 */
function palo_setting_html( $args ) {
	global $palo_options, $palo_textdomain;

	$field_html_id = "palo_setting_${args['field_id']}";
	$field_html_name = "palo_options[palo_setting_${args['field_id']}]";
	$field_html_value = '';
	$use_color_picker = '_color' === substr( $field_html_id, -6 );
	$use_textarea = '_code' === substr( $field_html_id, -5 );

	if ( ! empty ( $palo_options["palo_setting_${args['field_id']}"] ) ) {
		$field_html_value = $palo_options["palo_setting_${args['field_id']}"];
	}
	if ( $use_textarea ) {
		printf( '<textarea id="%s" name="%s" cols="50" rows="8" class="large-text code" >%s</textarea>', $field_html_id, $field_html_name, $field_html_value);
	} else {
		printf( '<input id="%s" name="%s" size="40" type="text" value="%s" %s/>', $field_html_id, $field_html_name, $field_html_value, $use_color_picker ? 'class="palo-color-picker"' : '' );
	}
}

/**
 * Outputs input fields for setting groups
 * 
 * The function is used as a default callback function in add_settings_field() for groups
 */
function palo_setting_group_html( $args ) {
	global $palo_options, $palo_textdomain;

	foreach ( $args[ 'field_ids' ] as $field_id ) {

		$field_html_id = "palo_setting_$field_id";
		$field_html_name = "palo_options[palo_setting_$field_id]";
		$field_html_value = assign_if_exists ( 'palo_setting_' . $field_id, $palo_options, '' );

		/**
		 * I tought this was required for settings to be saved, it's not
		 */
		palo_input_form_field( $field_html_name, 'id=' . $field_html_id . '&label=' . $field_id . '&p=1' . '&value=' . $field_html_value );
	}
}

/**
 * Generates and outputs the HTML for the registration section
 * 
 * The function is used as a callback function in add_settings_section(),
 * it doesn't output anything yet.
 */
function palo_section_register_html() {
	global $palo_textdomain;
	echo '<h4>' . __("Customize the content of new user registration email.", $palo_textdomain ) . '</h4>';
}

/**
 * Generates and outputs the HTML for the "Restrict Access" setting
 * 
 * The function is used as a callback function in add_settings_field(),
 */
function palo_setting_restrict_access_html() {
	global $palo_options;
	?>
	<input id='palo_restrict_access' name='palo_options[palo_restrict_access]' type='checkbox' value='1' <?php checked( isset( $palo_options['palo_restrict_access'] ) ); ?> /> Restrict WP dashboard access for subscriber level users
	<?php
}

/**
 * Generates and outputs the HTML for the "Allow password on registration" setting
 * 
 * The function is used as a callback function in add_settings_field(),
 */
function palo_setting_password_on_registration_html() {
	global $palo_options;
	?>
	<input id='palo_password_on_registration' name='palo_options[palo_password_on_registration]' type='checkbox' value='1' <?php checked( isset( $palo_options['palo_password_on_registration'] ) ); ?> /> Allow users to set password during registration
	<?php
}

/**
 * Generates and outputs the HTML for the Captcha settings
 * 
 * The function is used as a callback function in add_settings_field(),
 */
function palo_setting_captcha_html() {
	global $palo_options, $palo_textdomain;
	
	$option_id = 'palo_captcha';
	$name = "palo_options[$option_id]";

	$checkboxes = array(
		'PALO_CAPTCHA_LOGIN' => __( 'Login', $palo_textdomain ),
		'PALO_CAPTCHA_REGISTER' => __( 'Register', $palo_textdomain ),
		'PALO_CAPTCHA_RESET' => __( 'Password Reset', $palo_textdomain ),
	);

	foreach ( $checkboxes as $value => $label ) {
		if ( empty($palo_options[ 'palo_captcha' ] ) ) {
			$check_it = false;
		} else {
			$check_it = assign_if_exists( $value, $palo_options[ 'palo_captcha' ] ) ? 'checked' : '';
		}
		printf( '<label class="button"><span class="hidden"><input type="checkbox" name="%s[%s]" value="on" %s /></span>%s</label> ', $name, $value, $check_it, $label );
	}
}

/**
 * Generates and outputs the HTML for the "Redirect After Login" setting
 * 
 * The function is used as a callback function in add_settings_field(),
 */
function palo_setting_login_behaviour_html() {
	global $palo_options, $palo_textdomain;
	
	$option_id = 'palo_login_behavior';
	$name = "palo_options[$option_id]";

	if ( ! empty ( $palo_options[ $option_id ] ) ) {
		$checked = $palo_options[ $option_id ];
	} else {
		$checked = false;
	}
	
	$radios = array(
		'PALO_REDIRECT_DEFAULT' => __( 'Dashboard', $palo_textdomain ),
		'PALO_REDIRECT_HOME'	=> __( 'Homepage', $palo_textdomain ),
	//	'PALO_REDIRECT_CURRENT' => __( 'Current Page', $palo_textdomain ),
		'PALO_REDIRECT_URL'	 => __( 'Custom URL', $palo_textdomain ),
	);

	foreach ($radios as $value => $label) {
		/**
		 * We should we check this radio if the condition is met
		 * 
		 * At least one of these conditions is met:
		 * - This choice has been set it the past
		 * - (or) No choices has been set *and* this choice ends with "_DEFAULT"
		 */
		$check_it = $value === $checked || ! $checked && preg_match( '/_DEFAULT$/', $value )? 'checked' : '';
		printf( '<label class="button"><span class="hidden"><input type="radio" name="%s" value="%s" %s /></span>%s</label> ', $name, $value, $check_it, $label );
	}
}

/**
 * Generates and outputs the HTML for the "Access Control › Redirect To" setting
 * 
 * The function is used as a callback function in add_settings_field(),
 */
function palo_setting_access_behaviour_html() {
	global $palo_options, $palo_textdomain;
	
	$option_id = 'palo_access_behavior';
	$name = "palo_options[$option_id]";

	if ( ! empty ( $palo_options[ $option_id ] ) ) {
		$checked = $palo_options[ $option_id ];
	} else {
		$checked = false;
	}
	
	$radios = array(
		'PALO_REDIRECT_DEFAULT' => __( 'Login Page', $palo_textdomain ),
		'PALO_REDIRECT_URL'	 => __( 'Custom URL', $palo_textdomain ),
	);

	foreach ($radios as $value => $label) {
		/**
		 * We should we check this radio if the condition is met
		 * 
		 * At least one of these conditions is met:
		 * - This choice has been set it the past
		 * - (or) No choices has been set *and* this choice ends with "_DEFAULT"
		 */
		$check_it = $value === $checked || ! $checked && preg_match( '/_DEFAULT$/', $value )? 'checked' : '';
		printf( '<label class="button"><span class="hidden"><input type="radio" name="%s" value="%s" %s /></span>%s</label> ', $name, $value, $check_it, $label );
	}
}

/**
 * Generates and outputs the HTML for the "Redirect After Logout" setting
 * 
 * The function is used as a callback function in add_settings_field(),
 */
function palo_setting_logout_behaviour_html() {
	global $palo_options, $palo_textdomain;
	
	$option_id = 'palo_logout_behavior';
	$name = "palo_options[$option_id]";

	if ( ! empty ( $palo_options[ $option_id ] ) ) {
		$checked = $palo_options[ $option_id ];
	} else {
		$checked = false;
	}
	
	$radios = array(
		'PALO_REDIRECT_DEFAULT' => __( 'Login Page', $palo_textdomain ),
		'PALO_REDIRECT_HOME'	=> __( 'Homepage', $palo_textdomain ),
	//	'PALO_REDIRECT_CURRENT' => __( 'Current Page', $palo_textdomain ),
		'PALO_REDIRECT_URL'	 => __( 'Custom URL', $palo_textdomain ),
	);

	foreach ($radios as $value => $label) {
		/**
		 * We should we check this radio if the condition is met
		 * 
		 * At least one of these conditions is met:
		 * - This choice has been set it the past
		 * - (or) No choices has been set *and* this choice ends with "_DEFAULT"
		 */
		$check_it = $value === $checked || ! $checked && preg_match( '/_DEFAULT$/', $value )? 'checked' : '';
		printf( '<label class="button"><span class="hidden"><input type="radio" name="%s" value="%s" %s /></span>%s</label> ', $name, $value, $check_it, $label );
	}
}

/**
 * Generates and outputs the HTML for the "Background Colour" setting
 * 
 * The function instructs WordPress to load it's color picker and is
 * used as a callback function in add_settings_field().
 */
function palo_setting_background_image_html() {
	global $palo_options, $palo_textdomain;

	$background_image = assign_if_exists( 'palo_background_image', $palo_options );

	?>

	<input id="palo_background_image" name="palo_options[palo_background_image]" type="text" value="<?php echo esc_url_raw($background_image); ?>" placeholder="<?php _e( 'Insert Image URL or&hellip;', $palo_textdomain );?>"/><a id="palo_background_image_button" class="button">
	<?php _e('Select Image', $palo_textdomain )
	; ?></a>
	<?php 

	/**
	 * Image and removal button
	 */
	?>
	<p <?php if( ! $background_image ) { echo 'class="hidden"'; } ?>>
		<img src="<?php echo $background_image; ?>" style="max-height: 100px; max-width: 100px;" /><br /><a href="#" class="palo-remove-image"><?php _e( 'Remove Image', $palo_textdomain ); ?></a>
	</p>
	<?php
}

/**
 * Generates and outputs the HTML for the "Login Settings › Custom URL" setting
 * 
 * The function is used as a callback function in add_settings_field(),
 */
function palo_setting_login_url_html() {
	global $palo_options;

	$option_id = 'palo_login_url';
	printf( '<input id="%s" name="palo_options[%s]" size="40" type="text" value="%s" />', $option_id, $option_id, empty ( $palo_options[ $option_id ] ) ? '' : esc_url_raw( $palo_options[ $option_id ] ) );
}

/**
 * Generates and outputs the HTML for the "Logout Settings › Custom URL" setting
 * 
 * The function is used as a callback function in add_settings_field(),
 */
function palo_setting_logout_url_html() {
	global $palo_options;

	$option_id = 'palo_logout_url';
	printf( '<input id="%s" name="palo_options[%s]" size="40" type="text" value="%s" />', $option_id, $option_id, empty ( $palo_options[ $option_id ] ) ? '' : esc_url_raw( $palo_options[ $option_id ] ) );
}

/**
 * Generates and outputs the HTML for the "Access Control › Custom URL" setting
 * 
 * The function is used as a callback function in add_settings_field(),
 */
function palo_setting_access_url_html() {
	global $palo_options;

	$option_id = 'palo_access_url';
	printf( '<input id="%s" name="palo_options[%s]" size="40" type="text" value="%s" />', $option_id, $option_id, empty ( $palo_options[ $option_id ] ) ? '' : esc_url_raw( $palo_options[ $option_id ] ) );
}

/**
 * Generates and outputs the HTML for the "Logout Settings › Custom URL" setting
 * 
 * The function is used as a callback function in add_settings_field(),
 */
function palo_setting_registration_email_message_html() {

	global $palo_options, $palo_textdomain;

	$option_id = 'palo_registration_email_message';

	$palo_options[ $option_id ] = trim( assign_if_exists($option_id, $palo_options) );

	/**
	 * Output
	 */
	echo '<p>' . __( 'Add new user registration email template: %username%, %password%, %loginlink%. Leave blank to use default template', $palo_textdomain ) . '</p>';

	printf( '<textarea class="large-text code" cols="50" rows="8" id="%s" name="palo_options[%s]" >%s</textarea>', $option_id, $option_id, htmlspecialchars( $palo_options[ $option_id ] ) );
}

/**
 * Generates and outputs the HTML for the "Access Control" group
 *
 * The function is used as a callback function in add_settings_field(),
 */
function palo_setting_group_access_control_html ($args) {
	global $palo_options, $palo_textdomain;
	
	$option_id = 'palo_access_action';
	$name = "palo_options[$option_id]";
	$post_types = get_post_types( array( 'public' => true ) );
	/**
	 * Exclude Media as it's most of the time
	 * not used directly
	 */
	unset( $post_types[ 'attachment' ] );

	$radios = array(
		'PALO_ACCESS_ACTION_DEFAULT' => __( 'Authorize All Content', $palo_textdomain ),
		'PALO_ACCESS_ACTION_BLOCK' => __( 'Block All Content', $palo_textdomain ),
	);

	if ( ! empty ( $palo_options[ $option_id ] ) ) {
		$checked = $palo_options[ $option_id ];
	} else {
		$checked = false;
	}

	echo '<h4 class="palo-field-label">' . __( 'Restrict access for non logged in users', $palo_textdomain ) . '</h4>';
	echo '<p>';

	foreach ($radios as $value => $label) {
		/**
		 * We should we check this radio if the condition is met
		 * 
		 * At least one of these conditions is met:
		 * - This choice has been set it the past
		 * - (or) No choices has been set *and* this choice ends with "_DEFAULT"
		 */
		$check_it = $value === $checked || ! $checked && preg_match( '/_DEFAULT$/', $value )? 'checked' : '';
		
		printf( '<label class="button"><span class="hidden"><input type="radio" name="%s" value="%s" %s /></span>%s</label> ', $name, $value, $check_it, $label );
	}

	echo '</p>';

	/**
	 * Print one dropdown of exception for each post types (including custom)
	 */
	echo '<h4>' . __( 'Except', $palo_textdomain ) . '</h4>';
	foreach ($post_types as $post_type => $post_type_args ) {

		$post_type_obj = get_post_type_object( $post_type );
		$post_type_name = $post_type_obj->labels->singular_name;

		$exceptions = array();
		$saved_exceptions = assign_if_exists ( 'palo_access_exceptions_' . $post_type, $palo_options, array() );

		/**
		 * Add taxonomy/terms options
		 */
		$taxonomies = get_object_taxonomies( $post_type );
		foreach ( $taxonomies as $taxonomy ) {
			$taxonomy_obj = get_taxonomy( $taxonomy );
			$taxonomy_name = $taxonomy_obj->labels->singular_name;
			$terms = get_categories("taxonomy=$taxonomy&type=$post_type"); 
			foreach ($terms as $term) {
				$exceptions[ 'taxonomies' ][ "$taxonomy:$term->term_id" ] = "[$taxonomy_name] $term->name";
			}
		}

		/**
		 * Add posts options
		 */
		$the_query = new WP_Query( "post_type=$post_type&posts_per_page=-1" );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$exceptions[ 'posts' ][ get_the_ID() ] = get_the_ID() . ': ' . ( get_the_title() ? get_the_title() : __('[Untitled]', $palo_textdomain) );
			}
		}
		wp_reset_postdata();

		/**
		 * Output <select>
		 */
		echo '<p>';
		printf(
			'<label for="%s"><strong>%s</strong> <em>(%s)</em></label><br />',
			"palo_access_exceptions_$post_type",
			$post_type_name,
			$post_type
		);
		reset( $exceptions );
		printf(
			'<select multiple autocomplete="off" name="%s" id="%s" class="palo_select2" style="width:%s;">',
			"palo_options[palo_access_exceptions_$post_type][]",
			"palo_access_exceptions_$post_type",
			"400px"
		);
		printf(
			'<option value="_all_" %s >%s</option>',
			in_array( '_all_', $saved_exceptions ) ? 'selected' : '',
			__( 'All', $palo_textdomain )
		);
		if ( ! empty( $exceptions[ 'taxonomies' ] ) ) {
			printf( '<optgroup label="%s">', __( 'Taxonomies', $palo_textdomain ) );
			foreach ( $exceptions[ 'taxonomies' ] as $k => $v ) {
				$selected = ( in_array( $k, $saved_exceptions ) ) ? 'selected="selected"' : '';
				printf( '<option value="%s" %s >%s</option>', $k, $selected, $v );
			}
			printf( '</optgroup>' );
		}
		if ( ! empty( $exceptions[ 'posts' ] ) ) {
			printf( '<optgroup label="%s">', __( 'Posts', $palo_textdomain ) );
			foreach ( $exceptions[ 'posts' ] as $k => $v ) {
				$selected = ( in_array( $k, $saved_exceptions ) ) ? 'selected="selected"' : '';
				printf( '<option value="%s" %s >%s</option>', $k, $selected, $v );
			}
			printf( '</optgroup>' );
		}
		
		echo '</select>';
		echo '</p>';
	}
}

/**
 * Checks if the current user is a subscriber only
 * @return bool
 */
function current_user_is_subscriber_only() {
	$current_user = wp_get_current_user();
	return ( array( 'subscriber' ) === $current_user->roles );
}

/**
 * Helper function
 * 
 * Wrap $name and $section_name in __(), no need to pass the textdomain,
 * the function takes care of that.
 * 
 */
function palo_add_settings_field( $field_id, $field_name, $section_id = 'default', $section_name = null, $args = array() ) {

	$_args = get_defined_vars();

	global $palo_textdomain, $palo_helper;

	$field_id = "palo_setting_{$field_id}";
	$field_name = __( $field_name, $palo_textdomain );
	$section_id = "palo_section_{$section_id}";

	if ( function_exists( "{$field_id}_html" ) ) {
		$field_callback = "{$field_id}_html";
	} else {
		$field_callback = 'palo_setting_html';
		$args = $_args;
	}

	/**
	 * Create the section if needed
	 */
	if ( ! empty( $section_name ) ) {
		$section_name = __( $section_name, $palo_textdomain );
	}
	$section_callback = function_exists( "{$section_id}_html" ) ? "{$section_id}_html" : null;
	if ( empty ( $palo_helper[ 'sections' ] ) || ! in_array( $section_id, $palo_helper[ 'sections' ] ) ) {
		add_settings_section( $section_id, $section_name, $section_callback, 'palo_settings' );
		$palo_helper[ 'sections' ][] = $section_id;
	}

	/**
	 * Register the setting
	 */
	add_settings_field( $field_id, $field_name, $field_callback, 'palo_settings', $section_id, $args);
}

/**
 * Registers and outputs a group of fields that constitute a setting
 */
function palo_add_settings_group( $group_id, $group_name, $field_ids, $section_id = 'default', $section_name = null ) {

	$_args = get_defined_vars();

	global $palo_textdomain, $palo_helper;

	$group_id = "palo_setting_group_{$group_id}";
	$group_name = __( $group_name, $palo_textdomain );
	$section_id = "palo_section_{$section_id}";

	$group_callback = function_exists( "{$group_id}_html" ) ? "{$group_id}_html" : 'palo_setting_group_html';

	/**
	 * Create the section if needed
	 */
	if ( ! empty( $section_name ) ) {
		$section_name = __( $section_name, $palo_textdomain );
	}
	$section_callback = function_exists( "{$section_id}_html" ) ? "{$section_id}_html" : null;
	if ( empty ( $palo_helper[ 'sections' ] ) || ! in_array( $section_id, $palo_helper[ 'sections' ] ) ) {
		add_settings_section( $section_id, $section_name, $section_callback, 'palo_settings' );
		$palo_helper[ 'sections' ][] = $section_id;
	}
	
	/**
	 * Add the field
	 */
	add_settings_field( $group_id, $group_name, $group_callback, 'palo_settings', $section_id, $_args );
}

function palo_add_settings_separator( $title, $section_id ) {

	global $palo_textdomain;

	add_settings_field(
		'palo_' . mt_rand( 100000, 999999 ),
		'<h4 class="palo-sub-section" >' . __( $title, $palo_textdomain ) . '</h4>',
		'palo_hr',
		'palo_settings',
		'palo_section_' . $section_id
	);
}

function assign_if_exists( $key, $array, $default = '' ) {
	
	if ( array_key_exists( $key, $array ) ) {
		return $array[ $key ];
	} else {
		return $default;
	}
}

function palo_redirect( $url ) {
	wp_redirect( $url );
	exit;
}

/**
 * Excludes admins from logout redirects
 */
if ( ! function_exists( 'wp_logout' ) ) {
	function wp_logout() {

		$is_admin = current_user_can( 'manage_options' );

		wp_clear_auth_cookie();
		if ( $is_admin ) {
			remove_action( 'wp_logout', 'palo_filter_logout_redirect' );
		}
		do_action( 'wp_logout' );
	}
}

/**
 * Encodes a string
 * 
 * Note, not fully tested use only for basic encoding
 * 
 * @param  string The source string
 * @return string The encoded string
 */
function palo_encode_string($string) {
	$chars = str_split($string);
	$seed = mt_rand(0, (int)abs(crc32($string) / strlen($string)));

	foreach( $chars as $key => $char ) {
		$ord = ord( $char );

		//ignore non-ascii chars
		if ( $ord < 128 ) {
			//pseudo "random function"
			$r = ( $seed * ( 1 + $key ) ) % 100;

			if ( $r > 60 && $char !== '@') {
				// plain character (not encoded), if not @-sign	
			} else if ( $r < 45 ) {
				//hexadecimal
				$chars[$key] = '&#x'.dechex($ord).';';
			} 
			else {
				//decimal (ascii)
				$chars[$key] = '&#'.$ord.';';
			}
		}
	}

	return implode( '', $chars );
}

/**
 * Generates a random equation and its result
 * 
 * @return array An array containing the 1st operand, the 2nd operand,
 * the operator and the result
 */
function palo_captcha_equation() {

	global $palo_textdomain;

	$rnd = mt_rand( 0, 1 );

	$operations = array(
		'addition',
		// 'division',
		// 'multiplication',
		'subtraction',
	);

	$operators = array(
		'addition' => $rnd ? '+' : __( 'plus', $palo_textdomain ),
		'division' => $rnd ? '÷' : __( 'devided by', $palo_textdomain ),
		'multiplication' => $rnd ? '×' : __( 'multiplied by', $palo_textdomain ),
		'subtraction' => $rnd ? '−' : __( 'minus', $palo_textdomain ),
	);

	//operation
	$operation_rnd = $operations[ mt_rand( 0, count( $operations ) - 1 ) ];
	$equation[3] = $operators[ $operation_rnd ];

	//place where to put empty input
	$rnd_input = mt_rand( 0, 2 );

	//which random operation
	switch($operation_rnd) {
		case 'addition':
			if ($rnd_input === 0 ) {
				$equation[0] = mt_rand(1, 10);
				$equation[1] = mt_rand(1, 89);
			} else if ($rnd_input === 1 ) {
				$equation[0] = mt_rand(1, 89);
				$equation[1] = mt_rand(1, 10);
			} else if ($rnd_input === 2 ) {
				$equation[0] = mt_rand(1, 9);
				$equation[1] = mt_rand(1, 10 - $equation[0]);
			}

			$equation[2] = $equation[0] + $equation[1];
			break;
		case 'subtraction':
			if ($rnd_input === 0 ) {
				$equation[0] = mt_rand(2, 10);
				$equation[1] = mt_rand(1, $equation[0] - 1);
			} else if ($rnd_input === 1 ) {
				$equation[0] = mt_rand(11, 99);
				$equation[1] = mt_rand(1, 10);
			} else if ($rnd_input === 2 ) {
				$equation[0] = mt_rand(11, 99);
				$equation[1] = mt_rand($equation[0] - 10, $equation[0] - 1);
			}

			$equation[2] = $equation[0] - $equation[1];
			break;
		case 'multiplication':
			if ($rnd_input === 0 ) {
				$equation[0] = mt_rand(1, 10);
				$equation[1] = mt_rand(1, 9);
			} else if ($rnd_input === 1 ) {
				$equation[0] = mt_rand(1, 9);
				$equation[1] = mt_rand(1, 10);
			} else if ($rnd_input === 2 ) {
				$equation[0] = mt_rand(1, 10);
				$equation[1] = ($equation[0] > 5 ? 1 : ($equation[0] === 4 && $equation[0] === 5 ? mt_rand(1, 2) : ($equation[0] === 3 ? mt_rand(1, 3) : ($equation[0] === 2 ? mt_rand(1, 5) : mt_rand(1, 10)))));
			}

			$equation[2] = $equation[0] * $equation[1];
			break;
		case 'division':
			if ($rnd_input === 0 ) {
				$divide = array(2 => array(1, 2), 3 => array(1, 3), 4 => array(1, 2, 4), 5 => array(1, 5), 6 => array(1, 2, 3, 6), 7 => array(1, 7), 8 => array(1, 2, 4, 8), 9 => array(1, 3, 9), 10 => array(1, 2, 5, 10));
				$equation[0] = mt_rand(2, 10);
				$equation[1] = $divide[$equation[0]][mt_rand(0, count($divide[$equation[0]]) - 1)];
			} else if ($rnd_input === 1 ) {
				$divide = array(1 => 99, 2 => 49, 3 => 33, 4 => 24, 5 => 19, 6 => 16, 7 => 14, 8 => 12, 9 => 11, 10 => 9);
				$equation[1] = mt_rand(1, 10);
				$equation[0] = $equation[1] * mt_rand(1, $divide[$equation[1]]);
			} else if ($rnd_input === 2 ) {
				$divide = array(1 => 99, 2 => 49, 3 => 33, 4 => 24, 5 => 19, 6 => 16, 7 => 14, 8 => 12, 9 => 11, 10 => 9);
				$equation[2] = mt_rand(1, 10);
				$equation[0] = $equation[2] * mt_rand(1, $divide[$equation[2]]);
				$equation[1] = (int)($equation[0] / $equation[2]);
			}

			if (!isset($equation[2]) ) {
				$equation[2] = (int)($equation[0] / $equation[1]);
			}
			break;
	}

	return $equation;
}

/**
 * Insert the captcha field in form and populates relevant session veriables
 * 
 * The field will have a random ID to make sure label clicks work
 * when we have multiple captchas on the same page
 */
function palo_captcha_field() {

	global $palo_options, $palo_textdomain;

	$rnd = mt_rand( 100, 999);

	/**
	 * The equation
	 */
	$equation = $_SESSION['palo_captcha_equation'];
	
	/**
	 * The question
	 */
	$question = sprintf( '%s %s %s', $equation[0], $equation[3], $equation[1] );
	$question = palo_encode_string($question);
	$question = str_replace( ' ', '&nbsp;', $question);

	/**
	 * Output the field
	 */

	?><p>
		<label for="palo_captcha_answer_<?php echo $rnd; ?>"><?php printf( __( '%s', $palo_textdomain ), $question ); ?></label>
		<input type="text" name="palo_captcha_answer" id="palo_captcha_answer_<?php echo $rnd; ?>"/>
	</p><?php
}

/**
 * Tests captcha
 * 
 * @return boolean True if the user input value for captcha is correct
 */
function palo_captcha_test() {

	global $palo_helper;

	if ( empty( $_POST[ 'palo_captcha_answer' ] ) || empty( $palo_helper[ 'palo_captcha_equation' ][2] ) ) {
		return false;
	}

 	return $_POST['palo_captcha_answer' ] === (string) $palo_helper[ 'palo_captcha_equation' ][2];
}

/**
 * Insert the password field
 */
function palo_password_field() {

	global $palo_options, $palo_textdomain;

	/**
	 * Output the field
	 */

	?><p>
		<label for="palo_password"><?php _e( 'Password', $palo_textdomain ); ?></label>
		<input type="password" class="input" name="palo_password" id="palo_password"/>
	</p>
	<p>
		<label for="palo_password_2"><?php _e( 'Confirm Password', $palo_textdomain ); ?></label>
		<input type="password" class="input" name="palo_password_2" id="palo_password_2"/>
	</p><?php
}

/**
 * Outputs a submit button
 * 
 * @param string $label The button text
 */
function palo_submit_form_button( $label ) {
	printf( '<input type="submit" value="%s">', $label );
}

/**
 * Outputs an input field
 * 
 * @param string $id The id and name of the HTML select element
 * @param string $args a query string with:
 * 	- label: urlencode'ed string
 * 	- p: 0 or 1
 * 	- required: 0 or 1
 *  - type: text, hidden, textarea, email or password
 *  - value: urlencode'ed string
 */
function palo_input_form_field( $name, $args = '' ) {

	global $palo_helper;

	$defaults = array(
		'id' => false,
		'label' => false,
		'p' => false,
		'required' => false,
		'type' => 'text',
		'value' => '',
	);

	/**
	 * Parse the arguments
	 */
	parse_str($args, $args);
	$args += $defaults;

	/**
	 * Sanitize arguments
	 */
	if ( ! in_array( $args[ 'type' ], array( 'text', 'hidden', 'textarea', 'email', 'password' ) ) ) {
		$args[ 'type' ] = 'text';
	}

	/**
	 * Generate and pritn the field html code
	 */
	if ( $args[ 'p' ] ) {
		echo '<p>';
	}
	if ( $args[ 'label' ] && $args[ 'id' ] ) {
		printf( 
			'<label for="%s">%s%s</label><br />',
			$args[ 'id' ],
			$args[ 'label' ], 
			$args [ 'required' ] ? ' (required)' : '' 
		);
	}
	switch ( $args[ 'type' ] ) {
		case 'textarea':
			printf(
				'<textarea name="%s" id="%s" cols="30" rows="5" >%s</textarea>',
				$name,
				$args[ 'id' ],
				$args[ 'value' ]
			);
			break;
		default:
			printf( '
				<input type="%s" name="%s" id="%s" value="%s" %s />', 
				$args[ 'type' ], 
				$name, 
				$args[ 'id' ], 
				$args[ 'value' ],
				$args[ 'required' ] ? 'required' : ''
			);
			break;
	}
	if ( $args[ 'p' ] ) {
		echo '</p>';
	}
}

/**
 * Outputs a dropdow list
 * 
 * @param string $id The id and name of the HTML select element
 * @param string $args a query string with:
 * 	- label: urlencode'ed string
 * 	- p: 0 or 1
 * 	- required: 0 or 1
 *  - selected: urlencode'ed string
 * @param array $options Array of options for the select 
 */
function palo_select_form_field( $id, $args = '', $options = array() ) {

	$defaults = array(
		'label'	=> false,
		'p'		=> false,
		'required' => false,
		'selected' => false,
	);

	/**
	 * Parse the arguments
	 */
	parse_str($args, $args);
	$args += $defaults;

	/**
	 * Prepare HTML
	 */
	if ( $args[ 'p' ] ) {
		echo '<p>';
	}
	if ( $args[ 'label' ] ) {
		printf( '<label for="%s">%s</label><br />', $id, $args[ 'label' ] );
	}
	printf( '<select name="%s" id="%s">', $id, $id );
	foreach ( $options as $value => $label ) {
		printf( '
			<option %s>%s</option>',
			$label === $args [ 'selected' ] ? 'selected' : '',
			$label
		);
	}
	echo '</select>';
	if ( $args[ 'p' ] ) {
		echo '</p>';
	}
}

/**
 * Get terms limited to post type 
 * http://wordpress.stackexchange.com/a/14334
 *
 * @param $taxonomies - (string|array) (required) The taxonomies to retrieve terms from. 
 * @param $args  -  (string|array) all Possible Arguments of get_terms http://codex.wordpress.org/Function_Reference/get_terms
 * @param $post_type - (string|array) of post types to limit the terms to
 * @param $fields - (string) What to return (default all) accepts ID,name,all,get_terms. 
 * if you want to use get_terms arguments then $fields must be set to 'get_terms'
 */
function get_terms_by_post_type( $taxonomies ,$args, $post_type, $fields = 'all' ){
	$args = array(
		'post_type' => (array)$post_type,
		'posts_per_page' => -1
	);
	$the_query = new WP_Query( $args );
	$terms = array();
	while ($the_query->have_posts()){
		$the_query->the_post();
		$curent_terms = wp_get_object_terms( $post->ID, $taxonomy);
		foreach ($curent_terms as $t){
		  //avoid duplicates
			if (!in_array($t,$terms)){
				$terms[] = $c;
			}
		}
	}
	wp_reset_query();
	//return array of term objects
	if ($fields == "all")
		return $terms;
	//return array of term ID's
	if ($fields == "ID"){
		foreach ($terms as $t){
			$re[] = $t->term_id;
		}
		return $re;
	}
	//return array of term names
	if ($fields == "name"){
		foreach ($terms as $t){
			$re[] = $t->name;
		}
		return $re;
	}
	// get terms with get_terms arguments
	if ($fields == "get_terms"){
		$terms2 = get_terms( $taxonomies, $args );
		foreach ($terms as $t){
			if (in_array($t,$terms2)){
				$re[] = $t;
			}
		}
		return $re;
	}
}

/**
 * Converts color from hexadecimal to rgb
 * http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
 */
function palo_hex2rgb( $hex ) {
	$hex = str_replace("#", "", $hex);

	if(strlen($hex) == 3) {
		$r = hexdec( substr( $hex, 0, 1 ).substr( $hex, 0, 1 ) );
		$g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1 ) );
		$b = hexdec( substr( $hex, 2, 1 ).substr( $hex, 2, 1 ) );
	} else {
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
	}
	$rgb = array( $r, $g, $b );
	return implode( ", ", $rgb ); // returns the rgb values separated by commas
}

/**
 * Appends a query sting to a URL 
 * 
 * @param string $url The URL
 * @param string $qs  The query string without a leading ? or &
 * @return string The URL with the query string appended
 */
function palo_append_qs( $url, $qs ) {
	$query = parse_url( $url, PHP_URL_QUERY );
	if( $query ) {
		$url .= '&';
	} else {
		$url .= '?';
	}
	$url .= $qs;
	return $url;
}

/**
 * Prepares a HTML parameters list from an array
 * 
 * @param array $params The parameters as an array
 * @return string The parameters as a string
 */
function palo_params_str( $params ) {
	$params_str = '';
	foreach( $params as $parameter => $value ) {
		if( $value ) {
			$params_str .= sprintf( ' %s="%s"', $parameter, $value);
		}
	}
	return $params_str;
}


/**
 * Outputs a horizontal rule
 */
function palo_hr() { 
	echo '<hr />';
}

/**
 * Does nothing
 */
function palo_void() {
}
