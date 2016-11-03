<?php

function anva_clearfix() {
	echo '<div class="clearfix"></div>';
}

/*
 * Shortcodes
 */
function anva_shortcodes_setup() {
	
	add_shortcode( 'dropcap', 'dropcap_func' );
	add_shortcode( 'button', 'button_func' );
	add_shortcode( 'toggle', 'toggle_func' );
	add_shortcode( 'column_six', 'column_six_func' );
	add_shortcode( 'column_six_last', 'column_six_last_func' );	
	add_shortcode( 'column_four', 'column_four_func' );
	add_shortcode( 'column_four_last', 'column_four_last_func' );	
	add_shortcode( 'column_three', 'column_three_func' );
	add_shortcode( 'column_three_last', 'column_three_last_func' );	
	add_shortcode( 'column_two', 'column_two_func' );
	add_shortcode( 'column_two_last', 'column_two_last_func' );
	add_shortcode( 'column_one', 'column_one_func' );
	add_shortcode( 'column_one_last', 'column_one_last_func' );
	
}
add_filter( 'after_setup_theme', 'anva_shortcodes_setup'  );

/*
 * Dropcap
 */
function dropcap_func( $atts, $content ) {
	extract( shortcode_atts( array(
		'style' => 1
	), $atts ));
	$first_char = substr( $content, 0, 1 );
	$text_len 	= strlen( $content );
	$rest_text 	= substr( $content, 1, $text_len );
	$html  			= '<span class="dropcap">' . $first_char . '</span>';
	$html 		 .= wpautop( $rest_text );
	return $html;
}

/*
 * Buttons
 */
function button_func( $atts, $content )  {
	extract( shortcode_atts( array(
		'href' => '#',
		'align' => 'none',
		'color' => '',
		'size' => 'btn-sm',
		'target' => '_self',
	), $atts ));

	$bg = '';
	$text = '';

	if ( ! empty( $color ) ) {
		switch( strtolower( $color ) ) {
			case 'black':
				$bg 	= '#000000';
				$text = '#ffffff';
				break;
			case 'grey':
				$bg 	= '#666666';
				$text = '#ffffff';
				break;
			case 'white':
				$bg	= '#f5f5f5';
				$text = '#444444';
				break;
			case 'blue':
				$bg 	= '#3498DB';
				$text = '#ffffff';
				break;
			case 'yellow':
				$bg 	= '#F1C40F';
				$text = '#ffffff';
				break;
			case 'red':
				$bg 	= '#ff0000';
				$text = '#ffffff';
				break;
			case 'orange':
				$bg 	= '#ff9900';
				$text = '#ffffff';
				break;
			case 'green':
				$bg 	= '#2ECC71';
				$text = '#ffffff';
				break;
			case 'pink':
				$bg 	= '#ed6280';
				$text = '#ffffff';
				break;
			case 'purple':
				$bg 	= '#9B59B6';
				$text = '#ffffff';
				break;
		}
	}
	
	if ( ! empty( $bg ) ) {
		$border = $bg;
	} else {
		$border = 'transparent';
	}
	
	if ( ! empty( $bg ) ) { 
		$html = '<a class="btn ' . $size . ' align' . $align . '" style="background-color:' . $bg . ';border:1px solid ' . $border . ';color:' . $text . ';"';
	} else {
		$html = '<a class="btn ' . $size . ' align' . $align . '"';
	}
	
	if ( ! empty( $href ) ) {
		$html .= ' onclick="window.open(\'' . $href . '\', \'' . $target . '\')"';
	}

	$html .= '>' . $content . '</a>';

	return $html;
}

/*
 * Six columns
 */
function column_six_func( $atts, $content ) {
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
function column_six_last_func( $atts, $content ) {
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
function column_four_func( $atts, $content ) {
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
function column_four_last_func( $atts, $content ) {
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
function column_three_func( $atts, $content ) {
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
function column_three_last_func( $atts, $content ) {
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
function column_two_func( $atts, $content ) {
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
function column_two_last_func( $atts, $content ) {
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
function column_one_func( $atts, $content ) {
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
function column_one_last_func( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract( shortcode_atts( array(
		'class' => '',
	), $atts ));
	$html  = '<div class="grid_1 grid_last '. $class .'">' . $content . '</div>';
	$html .= '<div class="clearfixfix"></div>';
	return $html;
}

/*
 * Toggle
 */
function toggle_func( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract( shortcode_atts( array(
		'title' => __( 'Click para Abrir', 'anva-start' ),
		'id' => 'default',
		'collapse' => '',
		''
	), $atts ));
	$html  = '<div class="panel-group">';
	$html .= '<div class="panel panel-default">';
	$html .= '<div class="panel-heading">';
	$html .= '<h4 class="panel-title">';
	$html .= '<a href="#'. $id .'" data-toggle="collapse">'. $title .'</a>';
	$html .= '</h4>';
	$html .= '</div>';
	$html .= '<div id="'. $id .'" class="panel-collapse collapse">';
	$html .= '<div class="panel-body">'. $content . '</div>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>';	
	return $html;
}

/*
 * Remove br and p tags from shortcodes.
 */
// function anva_fix_shortcodes( $content ) {
// 	$array = array (
// 		'<p>[' 		=> '[', 
// 		']</p>' 	=> ']', 
// 		']<br />' => ']'
// 	);
// 	$content = strtr($content, $array);
// 	return $content;
// }

/*
 * Buttons
 */
function anva_button( $atts, $content = null ) {
	
	extract( shortcode_atts( array(
		'href' 		=> '',
		'align' 	=> '',
		'icon'		=> '',
		'size' 		=> '',
		'color'		=> '',
		'style'		=> '',
		'text'		=> '',
		'target' 	=> '',
	), $atts ));

	$classes = array();

	if ( ! empty( $icon ) ) {
		$icon = '<i class="fa fa-'. $icon .'"></i>';
	}

	if ( ! empty( $align ) ) {
		$classes[] = 'align'. $align;
	}

	if ( ! empty( $href ) ) {
		$href	= 'href="'. esc_url( $href ) .'"';
	}

	if ( ! empty( $text ) ) {
		$classes[] = 'button-desc';
		$text = '<span>'. esc_html( $text ) .'</span>';
	}

	if ( ! empty( $target ) ) {
		$target = 'target="'. esc_attr( $target ) .'"';
	}

	// Sizes
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

	switch ( $style ) {
		case 'round':
			$classes[] = 'button-rounded';
			break;

		case '3d':
			$classes[] = 'button-3d';
			break;
	}

	// Colors
	switch ( $color ) {
		case 'dark':
			$classes[] = 'button-dark';
			break;
		case 'light':
			$classes[] = 'button-light';
			break;
	}

	$classes = implode( ' ', $classes );
	
	$html  = '<a class="button '. esc_attr( $classes ) .'" '. $href .' '. $target .'>';
	$html .= $icon;
	$html .= $content;
	$html .= $text;
	$html .= '</a>';

	return $html;
}

/*
 * Columns
 */
function anva_column( $atts, $content = null ) {
	
	extract(shortcode_atts(array(
		'id'		=> '',
		'class' => '',
		'col'		=> '',
		'last'	=> false,
	), $atts));

	$classes = array();

	if ( ! empty( $id ) ) {
		$id = 'id="'. esc_attr( $id ) .'"';
	}

	if ( ! empty( $col ) ) {
		$classes[] = 'grid_' . $col;
	}

	if ( ! empty( $class ) ) {
		$classes[] = $class;
	}

	if ( true == $last ) {
		$classes[] = 'grid_last';
	}

	$classes = implode( ' ', $classes );

	$html = '<div '. $id .' class="'. esc_attr( $classes ) .'">'. $content .'</div>';
	return $html;
}

/*
 * Toggle
 */
function anva_toggle( $atts, $content ) {
	$content = wpautop( trim( $content ) );
	extract( shortcode_atts( array(
		'title' 	=> __( 'Click para ver el Contenido', ANVA_DOMAIN ),
		'id' 			=> '',
		'style'		=> ''
	), $atts ));

	$classes = '';

	if ( ! empty( $id ) ) {
		$id = 'id="'. esc_attr( $id ) . '"';
	}

	switch ( $style ) {
		case 'bg':
			$classes .= 'toggle-bg';
			break;
		case 'border':
			$classes .= 'toggle-border';
			break;
	}

	$html  = '<div class="toggle '. esc_attr( $classes ) .'" '. $id .'>';
	$html .= '<div class="toggle-title" "><i class="toggle-closed fa fa-minus-circle"></i><i class="toggle-open fa fa-plus-circle"></i>'. esc_html( $title ) .'</div>';
	$html .= '<div class="toggle-content">'. $content . '</div>';
	$html .= '</div>';	
	return $html;
}

/*
 * Counter
 */
function anva_counter( $atts, $content = null ) {
	
	$content = wpautop( trim( $content ) );
	
	extract( shortcode_atts( array(
		'from' 			=> 0,
		'to' 				=> 100,
		'interval'	=> 100,  // Refresh interval
		'speed' 		=> 2000, // Speed animation
		'size'			=> ''
	), $atts ));

	$classes = '';

	switch ( $size ) {
		case 'small':
			$classes = 'counter-small';
			break;
		case 'large':
			$classes = 'counter-large';
			break;
	}

	$html  = '<div class="counter text-center '. $classes .'">';
	$html .= '<span data-from="'. $from .'" data-to="'. $to .'" data-refresh-interval="'. $interval .'" data-speed="'. $speed .'"></span>';
	$html .= '</div>';

	return $html;
}

/*
 * Create slideshows shortcode
 */
function anva_slides( $atts, $content = null ) {
	
	extract(shortcode_atts( array(
		'id' => '', // Default ID
	), $atts ));

	$html = '<div class="alert alert-warning">Please install the plugin <strong>Anva Sideshows</strong> to use the slides shortcode.</div>';
	
	if ( function_exists( 'anva_put_slideshows' ) ) {
		if ( ! empty( $id ) ) {
			return anva_put_slideshows( $id );
		}
	} else {
		return $html;
	}
}
