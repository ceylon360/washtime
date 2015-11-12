<?php

/**
 * @file
 * The admin page
 */

require_once $palo_includes_dir . 'functions.php'; 
require_once $palo_includes_dir . 'actions/admin.php'; 

add_action( 'admin_init', 'palo_action_admin_init' );
add_action( 'admin_menu', 'palo_action_add_admin_page' );
add_action( 'admin_enqueue_scripts', 'palo_action_load_scripts' );
add_action( 'admin_enqueue_scripts', 'palo_action_load_styles' );
