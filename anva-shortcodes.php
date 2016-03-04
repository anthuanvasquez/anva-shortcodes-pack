<?php
/*
Plugin Name: Anva Shortcodes
Description: This plugin works in conjuction with the Anva Framework to create Shortcodes for use with the framework to generate content.
Version: 1.0.0
Author: Anthuan Vásquez
Author URI: http://anthuanvasquez.net
License: GPL2

	Copyright 2015  Anva Framework

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License version 2,
	as published by the Free Software Foundation.

	You may NOT assume that you can use any other version of the GPL.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	The license for this software can likely be found here:
	http://www.gnu.org/licenses/gpl-2.0.html

*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ANVA_SHORTCODES_PLUGIN_VERSION', '1.0.0' );
define( 'ANVA_SHORTCODES_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'ANVA_SHORTCODES_PLUGIN_URI', plugins_url( '' , __FILE__ ) );

// Include shortcodes
include_once( ANVA_SHORTCODES_PLUGIN_DIR . '/includes/shortcodes.php' );

/**
 * Shortcodes init
 *
 * @version 1.0.0
 */
function anva_shortcodes_init() {
	
	add_shortcode( 'dropcap', 'anva_dropcap' );
	add_shortcode( 'button', 	'anva_button' );
	add_shortcode( 'toggle', 	'anva_toggle' );
	add_shortcode( 'counter', 'anva_counter' );	
	add_shortcode( 'column', 	'anva_column' );
	add_shortcode( 'slides', 	'anva_slides' );
	
}
add_action( 'after_setup_theme', 'anva_shortcodes_init' );