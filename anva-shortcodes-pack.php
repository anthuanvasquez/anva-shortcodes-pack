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

/**
 * Run Shortcodes.
 *
 * @since 1.0.0
 */
function anva_shortcodes_init() {

	// Check is anvaframework is running.
	if ( ! ANVA_FRAMEWORK_VERSION ) {
		return;
	}

	// General functions.
	include_once( ANVA_SHORTCODES_PLUGIN_DIR . '/includes/general.php' );

	// Add shortcode generator.
	if ( is_admin() ) {
		include_once( ANVA_SHORTCODES_PLUGIN_DIR . '/includes/class-anva-shortcodes-generator.php' );
		include_once( ANVA_SHORTCODES_PLUGIN_DIR . '/includes/shortcodes-options.php' );

		// Run generator.
		Anva_Shortcodes_Generator::instance();
	}

	// Include shortcodes.
	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

		include_once( ANVA_SHORTCODES_PLUGIN_DIR . '/includes/shortcodes.php' );

		/**
		 * Add shortcode components.
		 */
		add_shortcode( 'dropcap', 'anva_dropcap' );
		add_shortcode( 'button', 'anva_button' );
		add_shortcode( 'toggle', 'anva_toggle' );
		add_shortcode( 'counter', 'anva_counter' ); 
		add_shortcode( 'column', 'anva_column' );
		add_shortcode( 'slides', 'anva_slides' );
		add_shortcode( 'quote', 'quote_func' );
		add_shortcode( 'tg_small_content', 'tg_small_content_func' );
		add_shortcode( 'pre', 'pre_func' );
		add_shortcode( 'button', 'tg_button_func' );
		add_shortcode( 'tg_social_icons', 'tg_social_icons_func' );
		add_shortcode( 'tg_social_share', 'tg_social_share_func' );
		add_shortcode( 'highlight', 'highlight_func' );
		add_shortcode( 'one_half', 'one_half_func' );
		add_shortcode( 'one_half_bg', 'one_half_bg_func' );
		add_shortcode( 'one_half_last', 'one_half_last_func' );
		add_shortcode( 'one_third', 'one_third_func' );
		add_shortcode( 'one_third_bg', 'one_third_bg_func' );
		add_shortcode( 'one_third_last', 'one_third_last_func' );
		add_shortcode( 'two_third', 'two_third_func' );
		add_shortcode( 'two_third_bg', 'two_third_bg_func' );
		add_shortcode( 'two_third_last', 'two_third_last_func' );
		add_shortcode( 'one_fourth', 'one_fourth_func' );
		add_shortcode( 'one_fourth_bg', 'one_fourth_bg_func' );
		add_shortcode( 'one_fourth_last', 'one_fourth_last_func' );
		add_shortcode( 'one_fifth', 'one_fifth_func' );
		add_shortcode( 'one_fifth_last', 'one_fifth_last_func' );
		add_shortcode( 'one_sixth', 'one_sixth_func' );
		add_shortcode( 'one_sixth_last', 'one_sixth_last_func' );
		add_shortcode( 'tg_pre', 'tg_pre_func' );
		add_shortcode( 'tg_map', 'tg_map_func' );
		add_shortcode( 'video', 'video_func' );
		add_shortcode( 'tg_grid_gallery', 'tg_grid_gallery_func' );
		add_shortcode( 'tg_masonry_gallery', 'tg_masonry_gallery_func' );
		add_shortcode( 'tg_image', 'tg_image_func' );
		add_shortcode( 'tg_teaser', 'tg_teaser_func' );
		add_shortcode( 'tg_grid_portfolio', 'tg_grid_portfolio_func' );
		add_shortcode( 'tg_filter_portfolio', 'tg_filter_portfolio_func' );
		add_shortcode( 'tg_promo_box', 'tg_promo_box_func' );
		add_shortcode( 'tg_alert_box', 'tg_alert_box_func' );
		add_shortcode( 'tg_tab', 'tg_tab_func' );
		add_shortcode( 'tg_ver_tab', 'tg_ver_tab_func' );
		add_shortcode( 'tab', 'tab_func' );
		add_shortcode( 'tg_accordion', 'tg_accordion_func' );
		add_shortcode( 'tg_service_vertical', 'tg_service_vertical_func' );
		add_shortcode( 'tg_service_columns', 'tg_service_columns_func' );
		add_shortcode( 'tg_divider', 'tg_divider_func' );
		add_shortcode( 'tg_team', 'tg_team_func' );
		add_shortcode( 'tg_testimonial_slider', 'tg_testimonial_slider_func' );
		add_shortcode( 'tg_lightbox', 'tg_lightbox_func' );
		add_shortcode( 'tg_youtube', 'tg_youtube_func' );
		add_shortcode( 'tg_vimeo', 'tg_vimeo_func' );
		add_shortcode( 'tg_animate_counter', 'tg_animate_counter_func' );
		add_shortcode( 'tg_animate_circle', 'tg_animate_circle_func' );
		add_shortcode( 'tg_animate_bar', 'tg_animate_bar_func' );
		add_shortcode( 'tg_pricing', 'tg_pricing_func' );
		add_shortcode( 'tg_gallery_slider', 'tg_gallery_slider_func' );
		add_shortcode( 'tg_audio', 'tg_audio_func' );
		add_shortcode( 'tg_jwplayer', 'tg_jwplayer_func' );
		add_shortcode( 'googlefont', 'googlefont_func' );
		add_shortcode( 'one_half', 'one_half_func' );
		add_shortcode( 'one_half_last', 'one_half_last_func' );
		add_shortcode( 'one_half_bg', 'one_half_bg_func' );
		add_shortcode( 'one_third', 'one_third_func' );
		add_shortcode( 'one_third_last', 'one_third_last_func' );
		add_shortcode( 'one_third_bg', 'one_third_bg_func' );
		add_shortcode( 'two_third', 'two_third_func' );
		add_shortcode( 'two_third_bg', 'two_third_bg_func' );
		add_shortcode( 'two_third_last', 'two_third_last_func' );
		add_shortcode( 'one_fourth', 'one_fourth_func' );
		add_shortcode( 'one_fourth_bg', 'one_fourth_bg_func' );
		add_shortcode( 'one_fourth_last', 'one_fourth_last_func' );
		add_shortcode( 'one_fifth', 'one_fifth_func' );
		add_shortcode( 'one_fifth_last', 'one_fifth_last_func' );
		add_shortcode( 'one_sixth', 'one_sixth_func' );
		add_shortcode( 'one_sixth_last', 'one_sixth_last_func' );
		add_shortcode( 'tg_gallery', 'tg_gallery_func' );
		add_shortcode( 'tg_image', 'tg_image_func' );
		add_shortcode( 'tg_tab', 'tg_tab_func' );
		add_shortcode( 'tg_ver_tab', 'tg_ver_tab_func' );
		add_shortcode( 'tab', 'tab_func' );
		add_shortcode( 'tg_accordion', 'tg_accordion_func' );
		add_shortcode( 'pp_pre', 'pp_pre_func' );

	}

}
add_action( 'after_setup_theme', 'anva_shortcodes_init' );

/**
 * Register text domain for localization.
 *
 * @since 1.0.0
 */
function anva_shortcodes_textdomain() {
	load_plugin_textdomain( 'anva-shortcodes' );
}
add_action( 'init', 'anva_shortcodes_textdomain' );
