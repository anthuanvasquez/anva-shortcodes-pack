<?php
/*
Plugin Name: Anva Shortcodes Pack
Description: This plugin works in conjuction with the Anva Framework to create Shortcodes for use with the framework to generate content.
Version: 1.0.0
Author: Anthuan Vásquez
Author URI: http://anthuanvasquez.net
License: GPL2
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ANVA_SHORTCODES_PLUGIN_VERSION', '1.0.0' );
define( 'ANVA_SHORTCODES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ANVA_SHORTCODES_PLUGIN_URI', plugin_url_path( __FILE__ ) );

// Include shortcodes
include_once( ANVA_SHORTCODES_PLUGIN_DIR . '/includes/shortcodes.php' );
include_once( ANVA_SHORTCODES_PLUGIN_DIR . '/includes/dropcap.php' );

/**
 * Shortcodes init
 *
 * @version 1.0.0
 */
function anva_shortcodes_init() {
	
	add_shortcode( 'button', 	'anva_button' );
	add_shortcode( 'toggle', 	'anva_toggle' );
	add_shortcode( 'counter',   'anva_counter' );	
	add_shortcode( 'column', 	'anva_column' );
	add_shortcode( 'slides', 	'anva_slides' );
	
}
add_action( 'after_setup_theme', 'anva_shortcodes_init' );
