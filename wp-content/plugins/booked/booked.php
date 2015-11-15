<?php

/*
Plugin Name: Booked (Share On Theme123.Net)
Description: Booking made simple.
Tags: appointments, booking
Author URI: http://www.boxystudio.com
Author: Boxy Studio
Donate link: http://www.boxystudio.com/#coffee
Requires at least: 4.0
Tested up to: 4.1.1
Version: 1.4.8
*/

// Generate the default stylesheets if needed
$upload_dir = wp_upload_dir();
$main_upload_dir = $upload_dir['basedir'];
$booked_upload_dir = $upload_dir['basedir'] . '/booked';
if (!is_dir($booked_upload_dir) && is_writable($main_upload_dir)) {
	wp_mkdir_p($booked_upload_dir);
	
	$color_theme_file = dirname(__FILE__) . '/css/color-theme.css';
	$new_file = $booked_upload_dir . '/color-theme.css';
	$color_theme_content = file_get_contents($color_theme_file);
	file_put_contents($new_file, $color_theme_content);
}
// END Generate

define('BOOKED_PLUGIN_URL', WP_PLUGIN_URL . '/booked');
define('BOOKED_PLUGIN_DIR', dirname(__FILE__));
define('BOOKED_STYLESHEET_DIR', get_stylesheet_directory());
define('BOOKED_PLUGIN_TEMPLATES_DIR', dirname(__FILE__) . '/templates/');
define('BOOKED_UPLOADS_DIR', $upload_dir['baseurl'] . '/booked');

require_once('wp-updates-plugin.php');
new WPUpdatesPluginUpdater_763( 'http://wp-updates.com/api/2/plugin', plugin_basename(__FILE__));

if(!class_exists('booked_plugin')) {
	class booked_plugin {
		/**
		 * Construct the plugin object
		 */
		public function __construct() {
			
			$this->booked_screens = array('booked-pending','booked-appointments','booked-settings','booked-addons');
		
			require_once(sprintf("%s/post-types/booked_appointments.php", BOOKED_PLUGIN_DIR));
			$booked_appointments_post_type = new booked_appointments_post_type();
			
			require_once(sprintf("%s/includes/general-functions.php", BOOKED_PLUGIN_DIR));
			require_once(sprintf("%s/includes/shortcodes.php", BOOKED_PLUGIN_DIR));
			require_once(sprintf("%s/includes/ajax/admin-loaders.php", BOOKED_PLUGIN_DIR));
			require_once(sprintf("%s/includes/ajax/admin-actions.php", BOOKED_PLUGIN_DIR));
			require_once(sprintf("%s/includes/ajax/fe-loaders.php", BOOKED_PLUGIN_DIR));
			require_once(sprintf("%s/includes/ajax/fe-actions.php", BOOKED_PLUGIN_DIR));
			require_once(sprintf("%s/includes/profiles.php", BOOKED_PLUGIN_DIR));
			require_once(sprintf("%s/includes/widgets.php", BOOKED_PLUGIN_DIR));

			add_action('admin_init', array(&$this, 'admin_init'), 9);
			add_action('admin_menu', array(&$this, 'add_menu'));
			add_action('init', array(&$this, 'remove_admin_bar'));
				
			add_action('admin_enqueue_scripts', array(&$this, 'admin_styles'));
			add_action('admin_enqueue_scripts', array(&$this, 'admin_scripts'));
			add_action('manage_users_custom_column', array(&$this, 'booked_add_custom_user_columns'), 15, 3);
			add_filter('manage_users_columns', array(&$this, 'booked_add_user_columns'), 15, 1);
			add_filter('user_contactmethods', array(&$this, 'booked_phone_numbers'));
			add_action('wp_enqueue_scripts', array(&$this, 'front_end_scripts'));
			add_action('admin_menu', array(&$this, 'booked_add_pending_appt_bubble' ));
			add_action('admin_menu', array(&$this, 'booked_add_new_addons_bubble' ));
			add_action('admin_notices', array(&$this, 'booked_pending_notice' ));
			add_action('parent_file', array(&$this, 'booked_tax_menu_correction'));
			
			add_action( 'booked_custom_calendars_add_form_fields', array(&$this, 'booked_calendars_add_custom_fields'), 10, 2 );
			add_action( 'booked_custom_calendars_edit_form_fields', array(&$this, 'booked_calendars_edit_custom_fields'), 10, 2 );
			add_action( 'create_booked_custom_calendars', array(&$this, 'booked_save_calendars_custom_fields'), 10, 2 );
			add_action( 'edited_booked_custom_calendars', array(&$this, 'booked_save_calendars_custom_fields'), 10, 2 );
			
			add_action('init', array(&$this, 'init'),10);
			add_action('wp_head', array(&$this, 'inline_scripts'),9);
			add_action('admin_head', array(&$this, 'admin_inline_scripts'));

		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate() {
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate() {
			// Do nothing
		} // END public static function deactivate

		public function admin_init() {
			
			// Set up the settings for this plugin
			require_once(sprintf("%s/includes/admin-functions.php", BOOKED_PLUGIN_DIR));
			require_once(sprintf("%s/includes/dashboard-widget.php", BOOKED_PLUGIN_DIR));
			$this->init_settings();
			
			$new_addons = $this->booked_new_addons();
			
			if ($new_addons):
				add_action('admin_notices', array(&$this, 'booked_addons_notice' ));
			endif;
			
		} // END public static function activate
		
		public function booked_new_addons(){
			$json_array = get_transient( 'booked_addons_json' );
			if (!$json_array):
				$json_array = @file_get_contents('http://boxystudio.com/?addons_category=4');
				set_transient( 'booked_addons_json', $json_array, 300 );
			endif;
			
			$new_addons = 0;
			
			if (!$json_array): $json_array = array(); else : $json_array = json_decode($json_array,true); endif;
			if (!empty($json_array)):
				foreach($json_array as $product):
					$plugin_slug = $product['plugin_slug'];
					if (!get_option('booked_addon_viewed_'.$plugin_slug)):
						$new_addons++;
					endif;
				endforeach;
			endif;
			
			return $new_addons;
		}
		
		public function init() {
			require_once(sprintf("%s/includes/functions.php", BOOKED_PLUGIN_DIR));
		}
		
		static function plugin_settings() {
			$plugin_options = array(
				'booked_profile_page',
				'booked_login_redirect_page',
				'booked_appointment_success_redirect_page',
				'booked_timeslot_intervals',
				'booked_appointment_buffer',
				'booked_appointment_limit',
				'booked_cancellation_buffer',
				'booked_new_appointment_default',
				'booked_hide_google_link',
				'booked_hide_end_times',
				'booked_light_color',
				'booked_dark_color',
				'booked_button_color',
				'booked_email_logo',
				'booked_default_email_user',
				'booked_registration_email_subject',
				'booked_registration_email_content',
				'booked_approval_email_content',
				'booked_approval_email_subject',
				'booked_cancellation_email_content',
				'booked_cancellation_email_subject',
				'booked_appt_confirmation_email_content',
				'booked_appt_confirmation_email_subject',
				'booked_admin_appointment_email_content',
				'booked_admin_appointment_email_subject',
				'booked_admin_cancellation_email_content',
				'booked_admin_cancellation_email_subject'
			);

			return $plugin_options;
		}

		public function init_settings() {
			$plugin_options = $this->plugin_settings();
			foreach($plugin_options as $option_name) {
				register_setting('booked_plugin-group', $option_name);
			}
		}
		
		
		public function booked_phone_numbers($profile_fields) {
			$profile_fields['booked_phone'] = __('Phone Number','booked');
			return $profile_fields;
		}


		/**********************
		ADD MENUS FUNCTION
		**********************/
		
		public function add_menu() {
			add_menu_page( __('Appointments','booked'), __('Appointments','booked'), 'manage_options', 'booked-appointments', array(&$this, 'admin_calendar'), 'dashicons-calendar-alt', 58 );
			add_submenu_page('booked-appointments', __('Pending','booked'), __('Pending','booked'), 'manage_options', 'booked-pending', array(&$this, 'admin_pending_list'));
			add_submenu_page('booked-appointments', __('Calendars','booked'), __('Calendars','booked'), 'manage_options', 'edit-tags.php?taxonomy=booked_custom_calendars');
			add_submenu_page('booked-appointments', __('Settings','booked'), __('Settings','booked'), 'manage_options', 'booked-settings', array(&$this, 'plugin_settings_page'));
			add_submenu_page('booked-appointments', __('Add-Ons','booked'), __('Add-Ons','booked'), 'manage_options', 'booked-addons', array(&$this, 'booked_addons_page'));
		}
		
		// Move Taxonomy (custom calendars) to Appointments Menu
		public function booked_tax_menu_correction($parent_file) {
		    global $current_screen;
		    $taxonomy = $current_screen->taxonomy;
		    if ($taxonomy == 'booked_custom_calendars')
		        $parent_file = 'booked-appointments';
		    return $parent_file;
		}

		// Booked Settings
		public function plugin_settings_page() {
			if(!current_user_can('manage_options')) {
				wp_die(__('You do not have sufficient permissions to access this page.', 'booked'));
			}
			include(sprintf("%s/templates/settings.php", BOOKED_PLUGIN_DIR));
		}
		
		// Booked Add-Ons
		public function booked_addons_page() {
			if(!current_user_can('manage_options')) {
				wp_die(__('You do not have sufficient permissions to access this page.', 'booked'));
			}
			include(sprintf("%s/templates/add-ons.php", BOOKED_PLUGIN_DIR));
		}
		
		// Booked Pending Appointments List
		public function admin_pending_list() {
			if(!current_user_can('manage_options')) {
				wp_die(__('You do not have sufficient permissions to access this page.', 'booked'));
			}
			include(sprintf("%s/templates/pending-list.php", BOOKED_PLUGIN_DIR));
		}
		
		// Booked Appointment Calendar
		public function admin_calendar() {
			if(!current_user_can('manage_options')) {
				wp_die(__('You do not have sufficient permissions to access this page.', 'booked'));
			}
			include(sprintf("%s/templates/admin-calendar.php", BOOKED_PLUGIN_DIR));
		}
		
		// Add New Add-Ons Bubble
		public function booked_add_new_addons_bubble() {
		
		  	global $submenu;
		  	
		  	$new_addons = $this->booked_new_addons();
		
			foreach ( $submenu as $key => $value ) :
				if ( $key == 'booked-appointments' ) :
					if ( $new_addons ) { $submenu[$key][4][0] .= " <span style='position:relative; top:1px; margin:-2px 0 0 2px' class='update-plugins count-$new_addons' title='$new_addons'><span style='padding:0 6px 0 4px; min-width:7px; text-align:center;' class='update-count'>" . $new_addons . "</span></span>"; }
					return;
				endif;
			endforeach;
		
		}

		// Add Pending Appointments Bubble
		public function booked_add_pending_appt_bubble() {
		
		  	global $submenu;
		
		  	$pending = booked_pending_appts_count();
		
			foreach ( $submenu as $key => $value ) :
				if ( $key == 'booked-appointments' ) :
					if ( $pending ) { $submenu[$key][1][0] .= " <span style='position:relative; top:1px; margin:-2px 0 0 2px' class='update-plugins count-$pending' title='$pending'><span style='padding:0 6px 0 4px; min-width:7px; text-align:center;' class='update-count'>" . $pending . "</span></span>"; }
					return;
				endif;
			endforeach;
		
		}
		
		public function booked_pending_notice() {
			
			if (current_user_can('manage_options')):
		
				$pending = booked_pending_appts_count();
				$page = (isset($_GET['page']) ? $page = $_GET['page'] : $page = false);
				if ($pending && $page != 'booked-pending'):
					
					echo '<div class="update-nag">';
						echo sprintf( _n( 'There is %s pending appointment.', 'There are %s pending appointments.', $pending, 'booked' ), $pending ).' <a href="'.get_admin_url().'admin.php?page=booked-pending">'._n('View Pending Appointment','View Pending Appointments',$pending,'booked').'</a>';
					echo '</div>';
				
				endif;
			
			endif;
		
		}
		
		public function booked_addons_notice() {
			
			if (current_user_can('manage_options')):
		
				$page = (isset($_GET['page']) ? $page = $_GET['page'] : $page = false);
				if ($page != 'booked-addons'):
					
					echo '<div class="update-nag">';
						echo __( 'New <strong>Booked Add-Ons</strong> are available!', 'booked' ).' <a href="'.get_admin_url().'admin.php?page=booked-addons">'.__('View Available Add-Ons','booked').'</a>';
					echo '</div>';
				
				endif;
			
			endif;
		
		}
		
		/**********************
		ADD USER FIELD TO CALENDAR TAXONOMY PAGE
		**********************/
		public function booked_calendars_add_custom_fields($tag) {
		    
		    ?><div class="form-field">
				<label for="term_meta[notifications_user_id]"><?php _e('Email notifications sent to:'); ?></label>
				<select name="term_meta[notifications_user_id]" id="term_meta[notifications_user_id]">
				<option value=""><?php _e('Default','booked'); ?></option><?php
					
					$all_users = get_users();
					$allowed_users = array();
					foreach ( $all_users as $user ):
					    $wp_user = new WP_User($user->ID);
					    if ( !in_array( 'subscriber', $wp_user->roles ) ):
					        array_push($allowed_users, $user);
					    endif;
					endforeach;
					
					if(!empty($allowed_users)) :
						foreach($allowed_users as $u) :
							$user_id = $u->ID;
							$username = $u->data->user_login;
							$email = $u->data->user_email; ?>
							<option value="<?php echo $email; ?>"><?php echo $email; ?> (<?php echo $username; ?>)</option><?php
						endforeach;
					endif;
					
				?></select>
				<p><?php _e('This will use your setting from the Booked Settings panel by default.'); ?></p>
			</div><?php
				
		}
		
		public function booked_calendars_edit_custom_fields($tag) {
		    
		    $t_id = $tag->term_id;
		    $term_meta = get_option( "taxonomy_$t_id" );
		    $selected_value = $term_meta['notifications_user_id'];
		    
		    $all_users = get_users();
			$allowed_users = array();
			foreach ( $all_users as $user ):
			    $wp_user = new WP_User($user->ID);
			    if ( !in_array( 'subscriber', $wp_user->roles ) ):
			        array_push($allowed_users, $user);
			    endif;
			endforeach; ?>
			
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="term_meta[notifications_user_id]"><?php _e('Email notifications sent to:'); ?></label>
				</th>
				<td>
					<select name="term_meta[notifications_user_id]" id="term_meta[notifications_user_id]">
						<option value=""><?php _e('Default','booked'); ?></option>
						<?php if(!empty($allowed_users)) :
							foreach($allowed_users as $u) :
								$user_id = $u->ID;
								$username = $u->data->user_login;
								$email = $u->data->user_email; ?>
								<option value="<?php echo $email; ?>"<?php echo ($selected_value == $email ? ' selected="selected"' : ''); ?>><?php echo $email; ?> (<?php echo $username; ?>)</option>
							<?php endforeach;
	
						endif; ?>
					</select><br>
					<span class="description"><?php _e('This will use your setting from the Booked Settings panel by default.'); ?></span>
				</td>
			</tr><?php
		}
		
		/**********************
		SAVE USER FIELD FROM CALENDAR TAXONOMY PAGE
		**********************/
		public function booked_save_calendars_custom_fields( $term_id ) {
		    if ( isset( $_POST['term_meta'] ) ) {
				$t_id = $term_id;
				$term_meta = get_option( "taxonomy_$t_id" );
				$cat_keys = array_keys( $_POST['term_meta'] );
				foreach ( $cat_keys as $key ) {
					if ( isset ( $_POST['term_meta'][$key] ) ) {
						$term_meta[$key] = $_POST['term_meta'][$key];
					}
				}
				update_option( "taxonomy_$t_id", $term_meta );
			}
		}
		
		
		/**********************
		HIDE ADMIN BAR FROM SUBSCRIBERS
		**********************/
		public function remove_admin_bar(){
			global $current_user;
		
			if ( isset($current_user->roles[0]) && $current_user->roles[0]=='subscriber' ) {
				add_filter('show_admin_bar', '__return_false');
			}
		}
		
		
		/**********************
		ADD USER COLUMN FOR APPOINTMENT COUNTS
		**********************/
		
		public function booked_add_user_columns( $defaults ) {
			$defaults['booked_appointments'] = __('Appointments', 'booked');
			return $defaults;
		}
		public function booked_add_custom_user_columns($value, $column_name, $id) {
			if( $column_name == 'booked_appointments' ) {
			
				$args = array(
					'posts_per_page'   	=> -1,
					'meta_key'   	   	=> '_appointment_timestamp',
					'orderby'			=> 'meta_value_num',
					'order'            	=> 'ASC',
					'meta_query' => array(
						array(
							'key'     => '_appointment_timestamp',
							'value'   => strtotime(date('Y-m-d H:i:s')),
							'compare' => '>=',
						),
					),
					'author'		   	=> $id,
					'post_type'        	=> 'booked_appointments',
					'post_status'      	=> 'publish',
					'suppress_filters'	=> true );
				
				$appointments = get_posts($args);
				$count = count($appointments);
				
				$appointments = array_slice($appointments, 0, 5);
				$time_format = get_option('time_format');
				$date_format = get_option('date_format');
				
				ob_start();
				
				if ($count){
			
					echo '<strong>'.$count.' '._n('Upcoming Appointment','Upcoming Appointments',$count,'booked').':</strong>';
					
					echo '<span style="font-size:12px;">';
					
					foreach($appointments as $appointment):
						$timeslot = get_post_meta($appointment->ID, '_appointment_timeslot',true);
						$timeslot = explode('-',$timeslot);
						$timestamp = get_post_meta($appointment->ID, '_appointment_timestamp',true);
						echo '<br>' . date_i18n($date_format,$timestamp) . ' @ ' . date($time_format,strtotime($timeslot[0])) . '&ndash;' . date($time_format,strtotime($timeslot[1]));
					endforeach;
					
					if ($count > 5):
						$diff = $count - 5;
						echo '<br>...'.__('and','booked').' '.$diff.' '.__('more','booked');
					endif;
					
					echo '</span>';
				
				}
				
				return ob_get_clean();
			}
			
		}
		
		
		/**********************
		ADMIN SCRIPTS/STYLES
		**********************/

		public function admin_scripts() {
			
			$current_page = (isset($_GET['page']) ? $_GET['page'] : false);
			$screen = get_current_screen();
			
			wp_enqueue_script('jquery');
			if ($current_page == 'booked-settings' || $screen->id == 'dashboard'):
				wp_enqueue_script('booked-serialize', BOOKED_PLUGIN_URL . '/js/jquery.serialize.js', array(), '1.0');
			endif;
			
			if (in_array($current_page,$this->booked_screens) || $screen->id == 'dashboard'):
				wp_enqueue_media();
				wp_enqueue_script('wp-color-picker');
				wp_enqueue_script('jquery-ui');
				wp_enqueue_script('jquery-ui-sortable');
				wp_enqueue_script('jquery-ui-datepicker');
				wp_enqueue_script('spin-js', BOOKED_PLUGIN_URL . '/js/spin.min.js', array(), '1.0');
				wp_enqueue_script('spin-jquery', BOOKED_PLUGIN_URL . '/js/spin.jquery.js', array(), '1.0');
				wp_enqueue_script('chosen', BOOKED_PLUGIN_URL . '/js/chosen/chosen.jquery.min.js', array(), '1.0');
				wp_enqueue_script('booked-calendar-popup', BOOKED_PLUGIN_URL . '/js/jquery.bookedCalendarPopup.js', array(), '1.0');
				wp_enqueue_script('booked-admin', BOOKED_PLUGIN_URL . '/js/admin-functions.js', array(), '1.0');
			endif;
		}

		public function admin_styles() {
			$current_page = (isset($_GET['page']) ? $_GET['page'] : false);
			$screen = get_current_screen();
			if (in_array($current_page,$this->booked_screens) || $screen->id == 'dashboard'):
				wp_enqueue_style('wp-color-picker');
				wp_enqueue_style('booked-gf', '//fonts.googleapis.com/css?family=Open+Sans:600,400|Montserrat:700,400&subset=cyrillic,cyrillic-ext,latin,greek-ext,greek,latin-ext,vietnamese', array(), '1.0');
				wp_enqueue_style('booked-fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array(), '1.0');
				wp_enqueue_style('chosen', BOOKED_PLUGIN_URL . '/js/chosen/chosen.min.css', array(), '1.0');
				wp_enqueue_style('booked-animations', BOOKED_PLUGIN_URL . '/css/animations.css', array(), '1.0.0');
				wp_enqueue_style('booked-admin', BOOKED_PLUGIN_URL . '/css/admin-styles.css', array(), '1.0');
			endif;
		}
		
		public function admin_inline_scripts() {
			
			$current_page = (isset($_GET['page']) ? $_GET['page'] : false);
			$screen = get_current_screen();
			
			if (in_array($current_page,$this->booked_screens) || $screen->id == 'dashboard'): ?>
			
				<script type="text/javascript"><?php
				
					$time_format = get_option('time_format');
					if (substr($time_format,0,1) === 'g' || substr($time_format,0,1) === 'h'):
						$time_format = 'h:i A';
					elseif (substr($time_format,0,1) === 'G' || substr($time_format,0,1) === 'H'):
						$time_format = 'H:i';
					else :
						$time_format = 'h:i A';
					endif;
					
					?>
					
					// Set some defaults
					var timeFormat = '<?php echo $time_format; ?>';
					var timeInterval = 60;
					
					// Language Variables used in Javascript
					var i18n_slot 						= '<?php echo esc_js(__('slot','booked')); ?>',
						i18n_slots 						= '<?php echo esc_js(__('slots','booked')); ?>',
						i18n_add						= '<?php echo esc_js(__('Add','booked')); ?>',
						i18n_time_error					= '<?php echo esc_js(__('The "End Time" needs to be later than the "Start Time".','booked')); ?>',
						i18n_bulk_add_confirm			= '<?php echo esc_js(__('Are you sure you want to add those bulk time slots?','booked')); ?>',
						i18n_all_fields_required		= '<?php echo esc_js(__('All fields are required.','booked')); ?>',
						i18n_single_add_confirm			= '<?php echo esc_js(__('You are about to add the following time slot(s)','booked')); ?>',
						i18n_to							= '<?php echo esc_js(__('to','booked')); ?>',
						i18n_all_day					= '<?php echo esc_js(__('All day','booked')); ?>',
						i18n_timeslot_added				= '<?php echo esc_js(__('Time slot added!','booked')); ?>',
						i18n_choose_customer			= '<?php echo esc_js(__('Please choose a customer.','booked')); ?>',
						i18n_fill_out_required_fields 	= '<?php echo esc_js(__('Please fill out all required fields.','booked')); ?>',
						i18n_confirm_ts_delete			= '<?php echo esc_js(__('Are you sure you want to delete this time slot?','booked')); ?>',
						i18n_confirm_cts_delete			= '<?php echo esc_js(__('Are you sure you want to delete this custom time slot?','booked')); ?>',
						i18n_confirm_appt_delete		= '<?php echo esc_js(__('Are you sure you want to cancel this appointment?','booked')); ?>',
						i18n_appt_required_fields		= '<?php echo esc_js(__('A first name and an email address are required fields.','booked')); ?>',
						i18n_confirm_appt_approve		= '<?php echo esc_js(__('Are you sure you want to approve this appointment?','booked')); ?>';
						
				</script>
			
			<?php endif;
			
		}

		
		/**********************
		FRONT-END SCRIPTS/STYLES
		**********************/

		public function front_end_scripts() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('spin-js', BOOKED_PLUGIN_URL . '/js/spin.min.js', array(), '1.0', true);
			wp_enqueue_script('spin-jquery', BOOKED_PLUGIN_URL . '/js/spin.jquery.js', array(), '1.0', true);
			wp_enqueue_script('booked-functions', BOOKED_PLUGIN_URL . '/js/functions.js', array(), '1.0', true);
		}

		public static function front_end_styles() {
			wp_enqueue_style('booked-gf', '//fonts.googleapis.com/css?family=Open+Sans:600,400|Montserrat:700,400&subset=cyrillic,cyrillic-ext,latin,greek-ext,greek,latin-ext,vietnamese', array(), '1.0');	
			wp_enqueue_style('booked-fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array(), '1.0');
			wp_enqueue_style('booked-animations', BOOKED_PLUGIN_URL . '/css/animations.css', array(), '1.0.0');
			wp_enqueue_style('booked-styles', BOOKED_PLUGIN_URL . '/css/styles.css', array(), '1.0.0');
			wp_enqueue_style('booked-colors',BOOKED_UPLOADS_DIR . '/color-theme.css', array(), '1.0.0');
			wp_enqueue_style('booked-responsive', BOOKED_PLUGIN_URL . '/css/responsive.css', array(), '1.0.0');
		}

		public function inline_scripts() { ?>
			
			<script type="text/javascript"><?php
				
				$time_format = get_option('time_format');
				if (substr($time_format,0,1) === 'g' || substr($time_format,0,1) === 'h'):
					$time_format = 'h:i A';
				elseif (substr($time_format,0,1) === 'G' || substr($time_format,0,1) === 'H'):
					$time_format = 'H:i';
				else :
					$time_format = 'h:i A';
				endif;
				
				$profile_page = get_option('booked_appointment_success_redirect_page') ? get_option('booked_appointment_success_redirect_page') : get_option('booked_profile_page');
				if ($profile_page): ?>
					var profilePage = '<?php echo get_permalink($profile_page); ?>';
					<?php
				else : ?>
					var profilePage = false;
					<?php
				endif; ?>
				
				// Set some defaults
				var timeFormat = '<?php echo $time_format; ?>';
				var timeInterval = 60;
				
				var i18n_confirm_appt_delete 		= '<?php echo esc_js(__('Are you sure you want to cancel this appointment?','booked')); ?>',
					i18n_please_wait 				= '<?php echo esc_js(__('Please wait...','booked')); ?>',
					i18n_wrong_username_pass 		= '<?php echo esc_js(__('Wrong username/password combination.','booked')); ?>',
					i18n_request_appointment		= '<?php echo esc_js(__('Request Appointment','booked')); ?>',
					i18n_fill_out_required_fields 	= '<?php echo esc_js(__('Please fill out all required fields.','booked')); ?>',
					i18n_appt_required_fields		= '<?php echo esc_js(__('A first name and an email address are required fields.','booked')); ?>';

			</script>
			
		<?php }
		
	} // END class booked_plugin
} // END if(!class_exists('booked_plugin'))

if(class_exists('booked_plugin')) {

	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('booked_plugin', 'activate'));
	register_deactivation_hook(__FILE__, array('booked_plugin', 'deactivate'));

	// instantiate the plugin class
	$booked_plugin = new booked_plugin();

	// Add a link to the settings page onto the plugin page
	if(isset($booked_plugin)) {
		// Add the settings link to the plugins page
		function booked_settings_link($links) {
			$settings_link = '<a href="admin.php?page=booked-settings">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}

		$plugin = plugin_basename(__FILE__);
		add_filter("plugin_action_links_$plugin", 'booked_settings_link');

		// TODO load depending on STYLE settings
		$plugin_styling = get_option('booked_plugin_styling');
		$disable_responsive_layouts = get_option('booked_disable_plugin_styling');
		add_action('wp_enqueue_scripts', array('booked_plugin', 'front_end_styles'));
	}
}

// Localization
function booked_local_init(){
	$domain = 'booked';
    $locale = apply_filters('plugin_locale', get_locale(), $domain);
    load_textdomain($domain, WP_LANG_DIR.'/booked/'.$domain.'-'.$locale.'.mo');
    load_plugin_textdomain($domain, FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
}
add_action('after_setup_theme', 'booked_local_init');