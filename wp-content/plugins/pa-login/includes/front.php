<?php

/**
 * @file
 * 
 * Front-end tilities, shortcodes and functions 
 */

require_once $palo_includes_dir . 'login.php';
require_once $palo_includes_dir . 'actions/front.php';
require_once $palo_includes_dir . 'filters/front.php';
require_once $palo_includes_dir . 'shortcodes.php';
require_once $palo_includes_dir . 'functions.php';

add_action( 'wp_footer',             'palo_action_frontend_scripts' );
add_action( 'init',             'palo_action_frontend_profile_save' );
add_action( 'wp',               'palo_action_frontend_access_control' );
add_filter( 'wp_nav_menu_objects', 'palo_filter_frontend_modal_link_label' );
add_filter( 'wp_nav_menu_objects', 'palo_filter_frontend_modal_link_register_hide' );
add_filter( 'nav_menu_link_attributes', 'palo_filter_frontend_modal_link_atts', 10, 3 );
add_filter( 'the_content',      'palo_filter_frontend_profile_messages', 10, 2);
add_shortcode( 'pa_login',      'palo_shortcode_frontend_login_form' );
add_shortcode( 'pa_register',   'palo_shortcode_frontend_register_form' );
add_shortcode( 'pa_reset',      'palo_shortcode_frontend_reset_form' );
add_shortcode( 'pa_forgotten',  'palo_shortcode_frontend_reset_form' );
add_shortcode( 'pa_profile',    'palo_shortcode_frontend_profile_form' );
add_shortcode( 'pa_modal_login',    'palo_shortcode_frontend_modal_login' );
add_shortcode( 'pa_modal_register', 'palo_shortcode_frontend_modal_register' );
