<?php

class Anva_Shortcodes_Dropcap {
    
    public function __construct()
    {
    	add_shortcode( 'dropcap', array( $this, 'shortcode') );
    }

    public function shortcode( $atts, $content = NULL )
    {
    	// Extract the attributes into variables
    	extract( shortcode_atts( array(
			'style' => ''
		), $atts ) );

    	// Get the content
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

		$html  = '<span class="' . esc_attr( $classes ) . '">' . esc_html( $first_char ) . '</span>';
		$html .= wpautop( $rest_text );

		return $html;

    }

}

$dropcap = new Anva_Shortcodes_Dropcap();
