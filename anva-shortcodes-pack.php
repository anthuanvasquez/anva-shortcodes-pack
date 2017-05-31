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
define( 'ANVA_SHORTCODES_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'ANVA_SHORTCODES_PLUGIN_DEBUG', true );

/**
 * Run Shortcodes.
 *
 * @since 1.0.0
 */
function anva_shortcodes_init() {

	// General functions.
	include_once( ANVA_SHORTCODES_PLUGIN_DIR . '/includes/general.php' );

	// Check is anvaframework is running.
	if ( ! defined( 'ANVA_FRAMEWORK_VERSION' ) ) {
		add_action( 'admin_notices', 'anva_shortcodes_warning' );
		add_action( 'admin_init', 'anva_shortcodes_disable_nag' );
		return;
	}

	// Add shortcode generator.
	if ( is_admin() ) {
		include_once( ANVA_SHORTCODES_PLUGIN_DIR . '/includes/class-anva-shortcodes-generator.php' );

		// Run generator.
		Anva_Shortcodes_Generator::instance();
	}

	// Include shortcodes.
	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

		include_once( ANVA_SHORTCODES_PLUGIN_DIR . '/includes/shortcodes.php' );

		/**
		 * Components
		 */
		add_shortcode( 'toggle', 'anva_shortcode_toggle' );
		add_shortcode( 'accordion_wrap', 'anva_shortcode_accordion_wrap' );
		add_shortcode( 'accordion', 'anva_shortcode_accordion' );
		add_shortcode( 'dropcap', 'anva_shortcode_dropcap' );
		add_shortcode( 'button', 'anva_shortcode_button' );
		add_shortcode( 'counter', 'anva_shortcode_counter' );
		add_shortcode( 'column', 'anva_shortcode_column' );

		/**
		 * Columns
		 */
		add_shortcode( 'column_six', 'anva_shortcode_column_six' );
		add_shortcode( 'column_six_last', 'anva_shortcode_column_six_last' );
		add_shortcode( 'column_four', 'anva_shortcode_column_four' );
		add_shortcode( 'column_four_last', 'anva_shortcode_column_four_last' );
		add_shortcode( 'column_three', 'anva_shortcode_column_three' );
		add_shortcode( 'column_three_last', 'anva_shortcode_column_three_last' );
		add_shortcode( 'column_two', 'anva_shortcode_column_two' );
		add_shortcode( 'column_two_last', 'anva_shortcode_column_two_last' );
		add_shortcode( 'column_one', 'anva_shortcode_column_one' );
		add_shortcode( 'column_one_last', 'anva_shortcode_column_one_last' );

	}

}
add_action( 'after_setup_theme', 'anva_shortcodes_init' );
