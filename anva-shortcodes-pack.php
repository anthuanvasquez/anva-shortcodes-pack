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
		add_shortcode( 'dropcap', 'anva_shortcode_dropcap' );
		add_shortcode( 'button', 'anva_shortcode_button' );
		add_shortcode( 'toggle', 'anva_shortcode_toggle' );

		/**
		 * Columns
		 */
		add_shortcode( 'column_six', 			'anva_shortcode_column_six' );
		add_shortcode( 'column_six_last', 		'anva_shortcode_column_six_last' );
		add_shortcode( 'column_four', 			'anva_shortcode_column_four' );
		add_shortcode( 'column_four_last', 		'anva_shortcode_column_four_last' );
		add_shortcode( 'column_three', 			'anva_shortcode_column_three' );
		add_shortcode( 'column_three_last', 	'anva_shortcode_column_three_last' );
		add_shortcode( 'column_two', 			'anva_shortcode_column_two' );
		add_shortcode( 'column_two_last', 		'anva_shortcode_column_two_last' );
		add_shortcode( 'column_one', 			'anva_shortcode_column_one' );
		add_shortcode( 'column_one_last', 		'anva_shortcode_column_one_last' );

		/**
		 * To change.
		 */
		add_shortcode( 'dropcap', 'anva_dropcap' );
		add_shortcode( 'button', 'anva_button' );
		add_shortcode( 'toggle', 'anva_toggle' );
		add_shortcode( 'counter', 'anva_counter' );
		add_shortcode( 'column', 'anva_column' );
		add_shortcode( 'slides', 'anva_slides' );
		add_shortcode( 'quote', 'quote' );
		add_shortcode( 'small_content', 'small_content' );
		add_shortcode( 'pre', 'pre' );
		add_shortcode( 'button', 'button' );
		add_shortcode( 'social_icons', 'social_icons' );
		add_shortcode( 'social_share', 'social_share' );
		add_shortcode( 'highlight', 'highlight' );
		add_shortcode( 'one_half', 'one_half' );
		add_shortcode( 'one_half_bg', 'one_half_bg' );
		add_shortcode( 'one_half_last', 'one_half_last' );
		add_shortcode( 'one_third', 'one_third' );
		add_shortcode( 'one_third_bg', 'one_third_bg' );
		add_shortcode( 'one_third_last', 'one_third_last' );
		add_shortcode( 'two_third', 'two_third' );
		add_shortcode( 'two_third_bg', 'two_third_bg' );
		add_shortcode( 'two_third_last', 'two_third_last' );
		add_shortcode( 'one_fourth', 'one_fourth' );
		add_shortcode( 'one_fourth_bg', 'one_fourth_bg' );
		add_shortcode( 'one_fourth_last', 'one_fourth_last' );
		add_shortcode( 'one_fifth', 'one_fifth' );
		add_shortcode( 'one_fifth_last', 'one_fifth_last' );
		add_shortcode( 'one_sixth', 'one_sixth' );
		add_shortcode( 'one_sixth_last', 'one_sixth_last' );
		add_shortcode( 'pre', 'pre' );
		add_shortcode( 'map', 'map' );
		add_shortcode( 'video', 'video' );
		add_shortcode( 'grid_gallery', 'grid_gallery' );
		add_shortcode( 'masonry_gallery', 'masonry_gallery' );
		add_shortcode( 'image', 'image' );
		add_shortcode( 'teaser', 'teaser' );
		add_shortcode( 'grid_portfolio', 'grid_portfolio' );
		add_shortcode( 'filter_portfolio', 'filter_portfolio' );
		add_shortcode( 'promo_box', 'promo_box' );
		add_shortcode( 'alert_box', 'alert_box' );
		add_shortcode( 'tab', 'tab' );
		add_shortcode( 'ver_tab', 'ver_tab' );
		add_shortcode( 'tab', 'tab' );
		add_shortcode( 'accordion', 'accordion' );
		add_shortcode( 'service_vertical', 'service_vertical' );
		add_shortcode( 'service_columns', 'service_columns' );
		add_shortcode( 'divider', 'divider' );
		add_shortcode( 'team', 'team' );
		add_shortcode( 'testimonial_slider', 'testimonial_slider' );
		add_shortcode( 'lightbox', 'lightbox' );
		add_shortcode( 'youtube', 'youtube' );
		add_shortcode( 'vimeo', 'vimeo' );
		add_shortcode( 'animate_counter', 'animate_counter' );
		add_shortcode( 'animate_circle', 'animate_circle' );
		add_shortcode( 'animate_bar', 'animate_bar' );
		add_shortcode( 'pricing', 'pricing' );
		add_shortcode( 'gallery_slider', 'gallery_slider' );
		add_shortcode( 'audio', 'audio' );
		add_shortcode( 'jwplayer', 'jwplayer' );
		add_shortcode( 'googlefont', 'googlefont' );
		add_shortcode( 'one_half', 'one_half' );
		add_shortcode( 'one_half_last', 'one_half_last' );
		add_shortcode( 'one_half_bg', 'one_half_bg' );
		add_shortcode( 'one_third', 'one_third' );
		add_shortcode( 'one_third_last', 'one_third_last' );
		add_shortcode( 'one_third_bg', 'one_third_bg' );
		add_shortcode( 'two_third', 'two_third' );
		add_shortcode( 'two_third_bg', 'two_third_bg' );
		add_shortcode( 'two_third_last', 'two_third_last' );
		add_shortcode( 'one_fourth', 'one_fourth' );
		add_shortcode( 'one_fourth_bg', 'one_fourth_bg' );
		add_shortcode( 'one_fourth_last', 'one_fourth_last' );
		add_shortcode( 'one_fifth', 'one_fifth' );
		add_shortcode( 'one_fifth_last', 'one_fifth_last' );
		add_shortcode( 'one_sixth', 'one_sixth' );
		add_shortcode( 'one_sixth_last', 'one_sixth_last' );
		add_shortcode( 'gallery', 'gallery' );
		add_shortcode( 'image', 'image' );
		add_shortcode( 'tab', 'tab' );
		add_shortcode( 'ver_tab', 'ver_tab' );
		add_shortcode( 'tab', 'tab' );
		add_shortcode( 'accordion', 'accordion' );
		add_shortcode( 'pp_pre', 'pp_pre' );

	}

}
add_action( 'after_setup_theme', 'anva_shortcodes_init' );
