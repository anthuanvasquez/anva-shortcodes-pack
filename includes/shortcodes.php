<?php

/**
 * Toggle.
 *
 * @param  array  $atts Shortcode attributes.
 * @param  string $content The enclosed content.
 * @return string Content to output for shortcode.
 */
function anva_shortcode_toggle( $atts, $content = null ) {
	
	extract( shortcode_atts( array(
		'title' => esc_html__( 'Your Toggle Title', 'anva-shortcodes' ),
		'style' => '',
	), $atts ));

	$classes   = array();
	$classes[] = 'toggle';
	$content   = trim( $content );

	switch ( $style ) {
		case 'bg':
			$classes[] = 'toggle-bg';
			break;

		case 'border':
			$classes[] = 'toggle-border';
			break;
	}

	$classes = implode( ' ', $classes );
	
	$output  = '<div class="' . esc_attr( $classes ) . '">';
	$output .= '<div class="togglet">';
	$output .= '<i class="toggle-closed icon-ok-circle"></i>';
	$output .= '<i class="toggle-open icon-remove-circle"></i>';
	$output .= $title;
	$output .= '</div>';
	$output .= '<div class="togglec">';
	$output .= $content;
	$output .= '</div>';
	$output .= '</div>';

	return $output;
}

/**
 * Accordion Wrapper.
 *
 * @since  1.0.0
 * @param  array  $atts Shortcode attributes.
 * @param  string $content The enclosed content.
 * @return string Content to output for shortcode.
 */
function anva_shortcode_accordion_wrap( $atts, $content = null ) {

	extract( shortcode_atts( array(
		'style' => '',
	), $atts ));

	$accordion_id = uniqid( 'accordion-' . rand() );

	$classes   = array();
	$classes[] = 'accordion';

	switch ( $style ) {
		case 'bg':
			$classes[] = 'accordion-bg';
			break;

		case 'border':
			$classes[] = 'accordion-border';
			break;
	}

	$classes[] = 'clearfix';
	$classes   = implode( ' ', $classes );
	
    return sprintf(
		'<div id="%s" class="' . $classes . '">%s</div>',
		$accordion_id,
		do_shortcode( $content )
	);
}

/**
 * Accordion.
 * 
 * @param  array  $atts Shortcode attributes.
 * @param  string $content The enclosed content.
 * @return string Content to output for shortcode.
 */
function anva_shortcode_accordion( $atts, $content = null ) {
	
	extract( shortcode_atts( array(
		'title' => esc_html__( 'Your Accordion Title', 'anva-shortcodes' ),
		'style' => '',
	), $atts ));

	$content = trim( $content );
	
	$output  = '<div class="acctitle acctitlec">';
	$output .= '<i class="acc-closed icon-ok-circle"></i>';
	$output .= '<i class="acc-open icon-remove-circle"></i>';
	$output .= $title;
	$output .= '</div>';
	$output .= '<div class="acc_content clearfix">';
	$output .= $content;
	$output .= '</div>';

	return $output;
}

/**
 * Dropcap.
 *
 * @param  array  $atts Shortcode attributes.
 * @param  string $content The enclosed content.
 * @return string Content to output for shortcode.
 */
function anva_shortcode_dropcap( $atts, $content = null ) {
	
	extract( shortcode_atts( array(
		'style' => ''
	), $atts ) );

	// Get the content.
	$first_char = substr( $content, 0, 1 );
	$text_len   = strlen( $content );
	$rest_text  = substr( $content, 1, $text_len );
		
	$classes    = array();
	$classes[] = 'dropcap';
	
	switch ( $style ) {

		case 'bg':
			$classes[] = 'dropcap-bg';
			break;
		
		case 'border':
			$classes[] = 'dropcap-border';
			break;
	}

	$classes = implode( ' ', $classes );

	$ouput  = '<span class="' . esc_attr( $classes ) . '">' . esc_html( $first_char ) . '</span>';
	$ouput .= wpautop( $rest_text );

	return $output;
}

function anva_shortcode_divider( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'icon'     => '',
		'align'    => '',
		'length'   => '',
		'style'    => '',
		'scrollto' => '',
	), $atts ) );
}

/**
 * Buttons.
 *
 * @since 1.0.0
 */
function anva_shortcode_button( $atts, $content = null ) {
	
	extract( shortcode_atts( array(
		'href'   => '',
		'align'  => '',
		'icon'   => '',
		'size'   => '',
		'color'  => '',
		'style'  => '',
		'text'   => '',
		'target' => '',
	), $atts ) );

	$classes = array();

	if ( ! empty( $icon ) ) {
		$icon = '<i class="icon-' . $icon . '"></i>';
	}

	if ( ! empty( $align ) ) {
		$classes[] = 'align' . $align;
	}

	if ( ! empty( $href ) ) {
		$href = 'href="' . esc_url( $href ) . '"';
	}

	if ( ! empty( $text ) ) {
		$classes[] = 'button-desc';
		$text = '<span>' . esc_html( $text ) . '</span>';
	}

	if ( ! empty( $target ) ) {
		$target = 'target="' . esc_attr( $target ) . '"';
	}

	// Sizes.
	switch ( $size ) {
		case 'mini':
			$classes[] = 'button-mini';
			break;
		
		case 'small':
			$classes[] = 'button-small';
			break;

		case 'large':
			$classes[] = 'button-large';
			break;

		case 'xlarge':
			$classes[] = 'button-xlarge';
			break;
	}

	// Styles.
	switch ( $style ) {
		case 'round':
			$classes[] = 'button-rounded';
			break;

		case '3d':
			$classes[] = 'button-3d';
			break;
	}

	// Colors.
	switch ( $color ) {
		case 'dark':
			$classes[] = 'button-dark';
			break;
		case 'light':
			$classes[] = 'button-light';
			break;
	}

	$classes = implode( ' ', $classes );
	
	$html  = '<a class="button ' . esc_attr( $classes ) . '" ' . $href . ' ' . $target . '>';
	$html .= $icon;
	$html .= $content;
	$html .= $text;
	$html .= '</a>';

	return $html;
}


/*
 * Six columns
 */
function anva_shortcode_column_six( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract(shortcode_atts(array(
		'class' => '',
	), $atts));
	$html = '<div class="grid_6 '. $class .'">'. $content . '</div>';
	return $html;
}

/*
 * Six columns last
 */
function anva_shortcode_column_six_last( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract(shortcode_atts(array(
		'class' => '',
	), $atts));
	$html = '<div class="grid_6 grid_last '. $class .'">'. $content . '</div><div class="clearfixfix"></div>';
	return $html;
}

/*
 * Four columns
 */
function anva_shortcode_column_four( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract(shortcode_atts(array(
		'class' => '',
	), $atts));
	$html = '<div class="grid_4 '. $class .'">'. $content . '</div>';
	return $html;
}

/*
 * Four columns last
 */
function anva_shortcode_column_four_last( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract(shortcode_atts(array(
		'class' => '',
	), $atts));
	$html = '<div class="grid_4 grid_last '. $class .'">'. $content . '</div><div class="clearfix"></div>';
	return $html;
}

/*
 * Three columns
 */
function anva_shortcode_column_three( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract(shortcode_atts(array(
		'class' => '',
	), $atts));
	$html = '<div class="grid_3 '. $class .'">'. $content . '</div>';
	return $html;
}

/*
 * Three columns last
 */
function anva_shortcode_column_three_last( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract(shortcode_atts(array(
		'class' => '',
	), $atts));
	$html = '<div class="grid_3 grid_last '. $class .'">'. $content . '</div><div class="clearfix"></div>';
	return $html;
}

/*
 * Two columns
 */
function anva_shortcode_column_two( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract(shortcode_atts(array(
		'class' => '',
	), $atts));
	$html = '<div class="grid_2 '. $class .'">'. $content . '</div>';
	return $html;
}

/*
 * Two columns last
 */
function anva_shortcode_column_two_last( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract(shortcode_atts(array(
		'class' => '',
	), $atts));
	$html = '<div class="grid_2 grid_last '. $class .'">'. $content . '</div><div class="clearfix"></div>';
	return $html;
}

/*
 * One column
 */
function anva_shortcode_column_one( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract( shortcode_atts( array(
		'class' => '',
	), $atts));
	$html = '<div class="grid_1 '. $class .'">' . $content . '</div>';
	return $html;
}

/*
 * One column last
 */
function anva_shortcode_column_one_last( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract( shortcode_atts( array(
		'class' => '',
	), $atts ));
	$html  = '<div class="grid_1 grid_last '. $class .'">' . $content . '</div>';
	$html .= '<div class="clearfixfix"></div>';
	return $html;
}
