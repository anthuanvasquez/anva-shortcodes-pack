<?php
/**
 * This file contains all general functions.
 *
 * @package AnvaShortcodes
 */

/**
 * Display warning telling the user they must have a
 * theme with Theme Blvd framework v2.2+ installed in
 * order to run this plugin.
 *
 * @since 1.0.0
 */
function anva_shortcodes_warning() {

	global $current_user;

	if ( ! get_user_meta( $current_user->ID, 'anva-nag-shortcodes-no-framework' ) ) {
		printf(
			'<div class="updated">%s %s</div>',
			sprintf(
				'<p><strong>Anva Shortcodes: </strong>%s</p>',
				esc_html__( 'You are not using a theme with the Anva Framework, and so this plugin will not do anything.', 'anva-shortcodes' )
			),
			sprintf(
				'<p><a href="%s">%s</a> | <a href="https://anthuanvasquez.net" target="_blank">%s</a></p>',
				esc_url( anva_post_types_disable_url( 'shortcodes-no-framework' ) ),
				esc_html__( 'Dismiss this notice', 'anva-shortcodes' ),
				esc_html__( 'Visit Anthuanvasquez.net', 'anva-shortcodes' )
			)
		);
	}
}

/**
 * Dismiss an admin notice.
 *
 * @since 1.0.0
 */
function anva_shortcodes_disable_nag() {

	global $current_user;

	// WPCS: input var okay.
	if ( ! isset( $_GET['nag-ignore'] ) ) {
		return;
	}

	// WPCS: input var okay. sanitization ok.
	if ( strpos( wp_unslash( $_GET['nag-ignore'] ), 'anva-nag-' ) !== 0 ) {
		return;
	}

	// WPCS: input var okay. sanitization ok.
	if ( isset( $_GET['security'] ) && wp_verify_nonce( wp_unslash( $_GET['security'] ), 'anva-shortcodes-nag' ) ) {
		// WPCS: input var okay. sanitization ok.
		add_user_meta( $current_user->ID, wp_unslash( $_GET['nag-ignore'] ), 'true', true );
	}
}

/**
 * Disable admin notice URL.
 *
 * @since 1.0.0
 * @param string $id ID of nag to disable.
 */
function anva_shortcodes_disable_url( $id ) {

	global $pagenow;

	$url = admin_url( $pagenow );

	// WPCS: input var okay.
	if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
		// WPCS: input var okay. sanitization ok.
		$url .= sprintf( '?%s&nag-ignore=%s', wp_unslash( $_SERVER['QUERY_STRING'] ), 'anva-nag-' . $id ); 
	} else {
		$url .= sprintf( '?nag-ignore=%s', 'anva-nag-' . $id );
	}

	$url .= sprintf( '&security=%s', wp_create_nonce( 'anva-shortcodes-nag' ) );

	return $url;
}

/**
 * Remove tags from content shortcodes.
 * 
 * @param  string $content The enclosed content.
 * @return string $content Content ouput formatted.
 */
function anva_shortcode_remove_tags( $content ) {
	
	// Shortcodes requiring the fix.
	$shortcodes = join( '|', array(
		'toggle',
		'accordion_wrap',
		'accordion',
		'columns',
	) );

	// Opening tag.
	$content = preg_replace( "/(<p>)?\[($shortcodes)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content );
		
	// Closing tag.
	$content = preg_replace( "/(<p>)?\[\/($shortcodes)](<\/p>|<br \/>)?/", "[/$2]", $content );
	
	return $content;
}
add_filter( 'the_content', 'anva_shortcode_remove_tags' );


/**
 * Sort shortcode by array key.
 * 
 * @since  1.0.0
 * @param  array  &$array Array yo be sorter.
 * @param  string $key    Key of array to orderby.
 * @return array          Sorter array by key.
 */
function anva_shortcodes_asort( &$array, $key ) {
	$sorter = array();
	$ret    = array();

	reset( $array );

	foreach ( $array as $array_key => $value ) {
		$sorter[ $array_key ] = $value[ $key ];
	}

	asort( $sorter );

	foreach ( $sorter as $sorter_key => $value ) {
		$ret[ $sorter_key ] = $array[ $sorter_key ];
	}

	$array = $ret;
}

/**
 * Register text domain for localization.
 *
 * @since 1.0.0
 */
function anva_shortcodes_textdomain() {
	load_plugin_textdomain(
		'anva-shortcodes',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'init', 'anva_shortcodes_textdomain' );
