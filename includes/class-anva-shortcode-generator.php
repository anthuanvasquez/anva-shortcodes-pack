<?php

/**
 * Shortcodes generator.
 *
 * @version      1.0.0
 * @author       Anthuan Vásquez
 * @copyright    Copyright (c) Anthuan Vásquez
 * @link         https://anthuanvasquez.net
 * @package      AnvaShortcodes
 */
class Anva_Shortcode_Generator {

	/**
	 * A single instance of this class.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    object
	 */
	private static $instance = null;

	/**
	 * Arguments to pass to add_meta_box().
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private $args = array();

	/**
	 * Options array.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    array
	 */
	private $options = array();

	/**
	 * Option id.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $option_id = '';

	/**
	 * Enable or disable the cache for options.
	 *
	 * @since 1.0.0
	 * @var boolean
	 */
	private $cache = true;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since 1.0.0
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Set option name.
		$this->option_id = 'anva_shortcodes_options';

		// if DEBUG is active disable cache.
		if ( ANVA_SHORTCODES_PLUGIN_DEBUG ) {
			$this->cache = false;
		}

		// Shortcodes options.
		$this->options = $this->get_options();

		// Arguments settings.
		$this->args = apply_filters( 'anva_shortcodes_arguments', array(
			'id'       => 'anva_shortcode_options',
			'title'    => esc_html__( 'Shortcode Options', 'anva' ),
			'page'     => array( 'post', 'page', 'portfolio' ),
			'context'  => 'advanced',
			'priority' => 'default',
			'desc'     => esc_html__( 'Please select short code from list below then enter short code attributes and click "Generate Shortcode".', 'anva' ),
		) );

		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'add_meta_boxes', array( $this, 'add' ) );
	}

	/**
	 * Generator assets.
	 *
	 * @since 1.0.0
	 * @param string $hook
	 */
	public function assets( $hook ) {
		if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
	
			wp_enqueue_script( 'anva_shortcodes', ANVA_SHORTCODES_PLUGIN_URI . 'assets/js/page-shortcodes.js', array( 'jquery' ), ANVA_SHORTCODES_PLUGIN_VERSION, false );
			wp_enqueue_style( 'anva_shortcodes', ANVA_SHORTCODES_PLUGIN_URI . 'assets/css/page-shortcodes.css', array(), ANVA_SHORTCODES_PLUGIN_VERSION );

		}
	}

	/**
	 * Metaboxes.
	 *
	 * @since 1.0.0
	 */
	public function add() {
		foreach ( $this->args['page'] as $page ) {
			add_meta_box(
				$this->args['id'],
				$this->args['title'],
				array( $this, 'display' ),
				$page,
				$this->args['context'],
				$this->args['priority']
			);
		}
	}

	/**
	 * Generate options list.
	 * 
	 * @return string $output
	 */
	public function get_select() {
		$output = '';
		if ( ! empty( $this->options ) ) {
			$output .= '<div class="anva-shcg-fields">';
			$output .= '<div class="anva-shcg-items">';
			$output .= '<ul id="shortcodes-items">';

			foreach ( $this->options as $option_id => $option ) {
				$output .= '<li data-value="' . esc_attr( $option_id ) . '">';
				$output .= esc_html( $option['name'] );
				$output .= '</li>';
			}
		
			$output .= '</ul>';
			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}

	/**
	 * Display Generator UI.
	 *
	 * @param object $post
	 */
	public function display( $post ) {
		$output = '';
		$output .= '<div class="anva-shcg-wrap">';
		$output .= '<div class="anva-shcg-description">';
		$output .= esc_html( $this->args['desc'] );
		$output .= '</div>';
	
		$output .= $this->get_select();

		$output .= '<div class="anva-shcg-section-wrap">';

		foreach ( $this->options as $shortcode_id => $shortcode ) :

			$output .= '<div id="anva-shcg-section-' . esc_attr( $shortcode_id ) . '" class="anva-shcg-section">';
		
			$output .= '<div class="anva-shcg-group">';
			$output .= '<div class="anva-shcg-title">';
			$output .= '<h3>' . esc_html( $shortcode['name'] ) . '</h3>';
			$output .= '</div><!-- .anva-shcg-title (end) -->';
			$output .= '<div class="anva-textarea-wrap">';
			$output .= '<div id="anva-shcg-code-' . esc_attr( $shortcode_id ) . '" class="anva-shcg-codearea"></div>';
			$output .= '</div><!-- .anva-textarea-wrap (end) -->';
			$output .= '<div class="anva-shcg-bottom">';
			$output .= '<button type="button" data-id="' . esc_attr( $shortcode_id ) . '" class="button button-primary button-shortcode">';
			$output .= esc_html__( 'Generate Shortcode', 'anva-shortcodes' );
			$output .= '</button>';
			$output .= '</div><!-- .anva-shcg-bottom (end) -->';
			$output .= '</div><!-- .anva-shcg-group (end) -->';

			$output .= '<div class="anva-shcg-group">';
			$output .= '<div class="anva-shcg-title">';
			$output .= '<h3>' . esc_html__( 'Shortcode Attributes', 'anva-shortcodes' ) . '</h3>';
			$output .= '</div><!-- .anva-shcg-title (end) -->';
	
			$output .= '<div id="anva-shcg-attribute-group-' . esc_attr( $shortcode_id ) . '" class="anva-shcg-attribute-group">';
	
			if ( isset( $shortcode['attr'] ) && ! empty( $shortcode['attr'] ) ) :
		
				$attributes = $shortcode['attr'];

				foreach ( $attributes as $attr_id => $attr_type ) :
			
					$output .= '<div class="anva-shcg-section-option">';
					$output .= '<div class="anva-shcg-title">';
					$output .= '<h4>'. $shortcode['title'][ $attr_id ] . ':</h4>';
					$output .= '</div><!-- .anva-shcg-title (end) -->';
					$output .= '<div class="anva-shcg-option">';
			
					$id = sprintf( '%s-%s', $shortcode_id, $attr_id );

					$output .= '<div class="anva-shcg-controls">';
					switch ( $attr_type ) :
				
						case 'text':
							$output .= sprintf(
								'<input type="text" id="%1$s" class="anva-shcg-attribute anva-input anva-text" data-attr="%1$s">',
								esc_attr( $id )
							);
							break;

						case 'colorpicker':
							$output .= sprintf(
								'<input type="text" id="%1$s" class="anva-shcg-attribute anva-input anva-color" data-attr="%1$s" readonly>',
								esc_attr( $id )
							);
							break;

						case 'select':
							$output .= '<div class="select-wrapper">';
							$output .= '<select id="' . esc_attr( $id ) . '" class="anva-shcg-attr anva-input anva-select" data-attr="' . esc_attr( $attr_id ) . '">';
				
							if ( isset( $shortcode['options'][ $attr_id ] ) && ! empty( $shortcode['options'][ $attr_id ] ) ) :
								foreach ( $shortcode['options'][ $attr_id ] as $option_id => $option ) :
									$output .= '<option value="' . esc_attr( $option_id ) . '">';
									$output .= esc_html( $option );
									$output .= '</option>';
								endforeach;
							endif;
				
							$output .= '</select>';
							$output .= '</div>';
							break;
			
						case 'textarea':
							$output .= sprintf(
								'<textarea id="%1$s" class="anva-shcg-attr anva-input anva-textarea" data-attr="%1$s"></textarea>',
								esc_attr( $id )
							);
							break;

					endswitch;
		
					$output .= '</div><!-- .anva-shcg-controls (end) -->';
		
					$output .= '<div class="anva-shcg-explain">';
					$output .= $shortcode['desc'][ $attr_id ];
					$output .= '</div><!-- .anva-shcg-explain (end) -->';

					$output .= '</div><!-- .anva-shcg-option (end) -->';
					$output .= '</div><!-- .anva-shcg-section-option (end) -->';
				endforeach;
			endif;

			if ( isset( $shortcode['content'] ) && $shortcode['content'] ) :
	
				$content_text = esc_html__( 'Your Content', 'anva' );

				if ( isset( $shortcode['content_text'] ) ) {
					$content_text = $shortcode['content_text'];
				}
						
				$output .= '<div class="anva-shcg-section-option">';
				$output .= '<div class="anva-shcg-title">';
				$output .= '<h4>' . esc_html( $content_text ) . ':</h4>';
				$output .= '</div>';
				$output .= '<div class="anva-shcg-option">';
				$output .= '<div class="anva-shcg-controls">';
		
				if ( isset( $shortcode['repeat'] ) ) :
					$output .= sprintf(
						'<input type="hidden" id="anva-shcg-content-repeat-%1$s" value="%2$s">',
						esc_attr( $shortcode_id ),
						esc_attr( $shortcode['repeat'] )
					);
				endif;

				$output .= '<div class="anva-textarea-wrap">';
				$output .= sprintf(
					'<textarea id="anva-shcg-content-%1$s" class="anva-input anva-textarea" rows="3">%2$s</textarea>',
					esc_attr( $shortcode_id ),
					esc_textarea( esc_html__( 'Enter your content here.', 'anva' ) )
				);

				$output .= '</div><!-- .anva-textarea-wrap (end) -->';
				$output .= '</div><!-- .anva-shcg-controls (end) -->';
		
				$output .= sprintf(
					'<div class="anva-shcg-explain">%s</div><!-- .anva-shcg-explain (end) -->',
					esc_html__( 'Enter the content you want to display in the shortcode.', 'anva' )
				);

				$output .= '</div><!-- .anva-shcg-option (end) -->';
				$output .= '</div><!-- .anva-shcg-section-group(end) -->';

			endif;
	
			$output .= '</div><!-- .anva-shcg-attribute-group (end) -->';
			$output .= '</div><!-- .anva-shcg-group (end) -->';
			$output .= '</div><!-- .anva-shcg-section (end) -->';

		endforeach;

		$output .= '</div><!-- .anva-shcg-section-wrap (end) -->';
		$output .= '</div><!-- .anva-shcg-wrap (end) -->';

		echo $output;
	}

	/**
	 * Set defaults options values.
	 *
	 * @since 1.0.0
	 * @return array $defualt_cache
	 */
	public function set_options() {
		$cache = get_transient( $this->option_id );
		if ( ! $cache && $this->cache ) {
			set_transient( $this->option_id, $this->get_options(), 60 * 60 );
		}
	}

	/**
	 * Shortcode options generator.
	 *
	 * @return array $shortcodes
	 */
	public function get_options() {
		// Get options from cache.
		$cache = get_transient( $this->option_id );

		if ( $cache && $this->cache ) {
			return $cache;
		}

		$options = array();

		// Helpers.
		$colors           = anva_get_button_colors();
		$galleries        = anva_pull_galleries();
		$testimonial_cats = anva_pull_testimonial_cats();
		$price_cats       = anva_pull_price_cats();

		// Begin shortcode array.
		$options = array(
			'dropcap' => array(
				'name'    => esc_html__( 'Dropcap', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
			),
			'quote' => array(
				'name'    => esc_html__( 'Quote Text', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
			),
			'button' => array(
				'name' => esc_html__( 'Button', 'anva-shortcodes' ),
				'attr' => array(
					'href'       => 'text',
					'color'      => 'select',
					'bg_color'   => 'colorpicker',
					'text_color' => 'colorpicker',
				),
				'title' => array(
					'href'       => esc_html__( 'URL', 'anva-shortcodes' ),
					'color'      => esc_html__( 'Skin', 'anva-shortcodes' ),
					'bg_color'   => esc_html__( 'Custom Background Color (Optional)', 'anva-shortcodes' ),
					'text_color' => esc_html__( 'Custom Font Color (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'href'       => esc_html__( 'Enter URL for button', 'anva-shortcodes' ),
					'color'      => esc_html__( 'Select predefined button colors', 'anva-shortcodes' ),
					'bg_color'   => esc_html__( 'Enter custom button background color code ex. #4ec380', 'anva-shortcodes' ),
					'text_color' => esc_html__( 'Enter custom button text color code ex. #ffffff', 'anva-shortcodes' ),
				),
				'options' => array(
					'color' => $colors,
				),
				'content'      => true,
				'content_text' => esc_html__( 'Enter text on button', 'anva-shortcodes' ),
			),
			'alert_box' => array(
				'name' => esc_html__( 'Alert Box', 'anva-shortcodes' ),
				'attr' => array(
					'style' => 'select',
				),
				'title' => array(
					'style' => esc_html__( 'Type', 'anva' ),
				),
				'desc' => array(
					'style' => esc_html__( 'Select alert box type', 'anva' ),
				),
				'options' => array(
					'style' => array(
						'error'   => esc_html__( 'Error', 'anva-shortcodes' ),
						'success' => esc_html__( 'Success', 'anva-shortcodes' ),
						'notice'  => esc_html__( 'Notice', 'anva-shortcodes' ),
					),
				),
				'content' => true,
			),
			'one_half' => array(
				'name'    => esc_html__( 'One Half Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
				'repeat'  => 1,
			),
			'one_half_last' => array(
				'name'    => esc_html__( 'One Half Last Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
				'repeat'  => 1,
			),
			'one_third' => array(
				'name'    => esc_html__( 'One Third Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
				'repeat'  => 2,
			),
			'one_third_last' => array(
				'name'    => esc_html__( 'One Third Last Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
			),
			'two_third' => array(
				'name'    => esc_html__( 'Two Third Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
			),
			'two_third_last' => array(
				'name'    => esc_html__( 'Two Third Last Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
			),
			'one_fourth' => array(
				'name'    => esc_html__( 'One Fourth Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
				'repeat'  => 3,
			),
			'one_fifth' => array(
				'name'    => esc_html__( 'One Fifth Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
				'repeat'  => 4,
			),
			'one_sixth' => array(
				'name'    => esc_html__( 'One Sixth Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
				'repeat'  => 5,
			),
			'one_half_bg' => array(
				'name' => esc_html__( 'One Half Column With Background', 'anva-shortcodes' ),
				'attr' => array(
					'bgcolor'   => 'colorpicker',
					'fontcolor' => 'colorpicker',
					'padding'   => 'text',
				),
				'title' => array(
					'bgcolor'   => esc_html__( 'Background Color', 'anva-shortcodes' ),
					'fontcolor' => esc_html__( 'Font Color', 'anva-shortcodes' ),
					'padding'   => esc_html__( 'Padding (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'bgcolor'   => esc_html__( 'Select background color', 'anva-shortcodes' ),
					'fontcolor' => esc_html__( 'Select font color', 'anva-shortcodes' ),
					'padding'   => esc_html__( 'Enter padding for this content (in px)', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'one_third_bg' => array(
				'name' => esc_html__( 'One Third Column With Background', 'anva-shortcodes' ),
				'attr' => array(
					'bgcolor'   => 'colorpicker',
					'fontcolor' => 'colorpicker',
					'padding'   => 'text',
				),
				'title' => array(
					'bgcolor'   => esc_html__( 'Background Color', 'anva-shortcodes' ),
					'fontcolor' => esc_html__( 'Font Color', 'anva-shortcodes' ),
					'padding'   => esc_html__( 'Padding (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'bgcolor'   => esc_html__( 'Select background color', 'anva-shortcodes' ),
					'fontcolor' => esc_html__( 'Select font color', 'anva-shortcodes' ),
					'padding'   => esc_html__( 'Enter padding for this content (in px)', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'two_third_bg' => array(
				'name' => esc_html__( 'Two Third Column With Background', 'anva-shortcodes' ),
				'attr' => array(
					'bgcolor'   => 'colorpicker',
					'fontcolor' => 'colorpicker',
					'padding'   => 'text',
				),
				'title' => array(
					'bgcolor'   => esc_html__( 'Background Color', 'anva-shortcodes' ),
					'fontcolor' => esc_html__( 'Font Color', 'anva-shortcodes' ),
					'padding'   => esc_html__( 'Padding (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'bgcolor'   => esc_html__( 'Select background color', 'anva-shortcodes' ),
					'fontcolor' => esc_html__( 'Select font color', 'anva-shortcodes' ),
					'padding'   => esc_html__( 'Enter padding for this content (in px)', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'one_fourth_bg' => array(
				'name' => esc_html__( 'One Fourth Column With Background', 'anva-shortcodes' ),
				'attr' => array(
					'bgcolor'   => 'colorpicker',
					'fontcolor' => 'colorpicker',
					'padding'   => 'text',
				),
				'title' => array(
					'bgcolor'   => esc_html__( 'Background Color', 'anva-shortcodes' ),
					'fontcolor' => esc_html__( 'Font Color', 'anva-shortcodes' ),
					'padding'   => esc_html__( 'Padding (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'bgcolor'   => esc_html__( 'Select background color', 'anva-shortcodes' ),
					'fontcolor' => esc_html__( 'Select font color', 'anva-shortcodes' ),
					'padding'   => esc_html__( 'Enter padding for this content (in px)', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'map' => array(
				'name' => esc_html__( 'Google Map', 'anva-shortcodes' ),
				'attr' => array(
					'type'   => 'select',
					'width'  => 'text',
					'height' => 'text',
					'lat'    => 'text',
					'long'   => 'text',
					'zoom'   => 'text',
					'popup'  => 'text',
					'marker' => 'text',
				),
				'title' => array(
					'type'   => esc_html__( 'Map Type', 'anva-shortcodes' ),
					'width'  => esc_html__( 'Width', 'anva-shortcodes' ),
					'height' => esc_html__( 'Height', 'anva-shortcodes' ),
					'lat'    => esc_html__( 'Latitude', 'anva-shortcodes' ),
					'long'   => esc_html__( 'Longtitude', 'anva-shortcodes' ),
					'zoom'   => esc_html__( 'Zoom Level', 'anva-shortcodes' ),
					'popup'  => esc_html__( 'Popup Text (Optional)', 'anva-shortcodes' ),
					'marker' => esc_html__( 'Custom Marker Icon (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'type'   => esc_html__( 'Select map display type', 'anva-shortcodes' ),
					'width'  => esc_html__( 'Map width in pixels', 'anva-shortcodes' ),
					'height' => esc_html__( 'Map height in pixels', 'anva-shortcodes' ),
					'lat'    => esc_html__( 'Map latitude', 'anva-shortcodes' ),
					'long'   => esc_html__( 'Map longitude', 'anva-shortcodes' ),
					'zoom'   => esc_html__( 'Enter zoom number (1-16)', 'anva-shortcodes' ),
					'popup'  => esc_html__( 'Enter text to display as popup above location on map for ex. your company name', 'anva-shortcodes' ),
					'marker' => esc_html__( 'Enter custom marker image URL', 'anva-shortcodes' ),
				),
				'content' => false,
				'options' => array(
					'type' => array(
						'ROADMAP'   => esc_html__( 'Roadmap', 'anva-shortcodes' ),
						'SATELLITE' => esc_html__( 'Satellite', 'anva-shortcodes' ),
						'HYBRID'    => esc_html__( 'Hybrid', 'anva-shortcodes' ),
						'TERRAIN'   => esc_html__( 'Terrain', 'anva-shortcodes' ),
					)
				),
			),
			'googlefont' => array(
				'name' => esc_html__( 'Google Font', 'anva-shortcodes' ),
				'attr' => array(
					'font'     => 'text',
					'fontsize' => 'text',
					'style'    => 'text',
				),
				'title' => array(
					'font'     => esc_html__( 'Font Name', 'anva-shortcodes' ),
					'fontsize' => esc_html__( 'Font Size', 'anva-shortcodes' ),
					'style'    => esc_html__( 'Custom CSS style ex. font-style:italic; (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'font'     => esc_html__( 'Enter Google Web Font Name you want to use', 'anva-shortcodes' ),
					'fontsize' => esc_html__( 'Enter font size in pixels', 'anva-shortcodes' ),
					'style'    => esc_html__( 'Enter custom CSS styling code', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'thumb_gallery' => array(
				'name' => esc_html__( 'Gallery Thumbnails', 'anva-shortcodes' ),
				'attr' => array(
					'gallery_id' => 'select',
					'width'      => 'text',
					'height'     => 'text',
				),
				'title' => array(
					'gallery_id' => esc_html__( 'Gallery', 'anva-shortcodes' ),
					'width'      => esc_html__( 'Width', 'anva-shortcodes' ),
					'height'     => esc_html__( 'Height', 'anva-shortcodes' ),
				),
				'desc' => array(
					'gallery_id' => esc_html__( 'Select gallery you want to display its images', 'anva-shortcodes' ),
					'width'      => esc_html__( 'Gallery image width in pixels', 'anva-shortcodes' ),
					'height'     => esc_html__( 'Gallery image height in pixels', 'anva-shortcodes' ),
				),
				'options' => array(
					'gallery_id' => $galleries,
				),
				'content' => false,
			),
			'grid_gallery' => array(
				'name' => esc_html__( 'Gallery Grid', 'anva-shortcodes' ),
				'attr' => array(
					'gallery_id' => 'select',
					'layout'     => 'select',
					'columns'    => 'select',
				),
				'title' => array(
					'gallery_id' => esc_html__( 'Gallery', 'anva-shortcodes' ),
					'layout'     => esc_html__( 'Layout', 'anva-shortcodes' ),
					'columns'    => esc_html__( 'Columns', 'anva-shortcodes' ),
				),
				'desc' => array(
					'gallery_id' => esc_html__( 'Select gallery you want to display its images', 'anva-shortcodes' ),
					'layout'     => esc_html__( 'Select gallery layout you to display its images', 'anva-shortcodes' ),
					'columns'    => esc_html__( 'Select gallery columns you to display its images', 'anva-shortcodes' ),
				),
				'options' => array(
					'gallery_id' => $galleries,
					'layout' => array(
						'contain' => esc_html__( 'Contain', 'anva-shortcodes' ),
						'wide'    => esc_html__( 'Wide', 'anva-shortcodes' ),
					),
					'columns' => array(
						2 => esc_html__( '2 Colmuns', 'anva-shortcodes' ),
						3 => esc_html__( '3 Colmuns', 'anva-shortcodes' ),
						4 => esc_html__( '4 Colmuns', 'anva-shortcodes' ),
					),
				),
				'content' => false,
			),
			'masonry_gallery' => array(
				'name' => esc_html__( 'Gallery Masonry', 'anva-shortcodes' ),
				'attr' => array(
					'gallery_id' => 'select',
					'layout'     => 'select',
					'columns'    => 'select',
				),
				'title' => array(
					'gallery_id' => esc_html__( 'Gallery', 'anva-shortcodes' ),
					'layout'     => esc_html__( 'Layout', 'anva-shortcodes' ),
					'columns'    => esc_html__( 'Columns', 'anva-shortcodes' ),
				),
				'desc' => array(
					'gallery_id' => esc_html__( 'Select gallery you want to display its images', 'anva-shortcodes' ),
					'layout'     => esc_html__( 'Select gallery layout you to display its images', 'anva-shortcodes' ),
					'columns'    => esc_html__( 'Select gallery columns you to display its images', 'anva-shortcodes' ),
				),
				'options' => array(
					'gallery_id' => $galleries,
					'layout' => array(
						'contain' => esc_html__( 'Contain', 'anva-shortcodes' ),
						'wide'    => esc_html__( 'Wide', 'anva-shortcodes' ),
					),
					'columns' => array(
						2 => esc_html__( '2 Colmuns', 'anva-shortcodes' ),
						3 => esc_html__( '3 Colmuns', 'anva-shortcodes' ),
						4 => esc_html__( '4 Colmuns', 'anva-shortcodes' ),
					),
				),
				'content' => false,
			),
			'gallery_slider' => array(
				'name' => esc_html__( 'Gallery Slider', 'anva-shortcodes' ),
				'attr' => array(
					'gallery_id' => 'select',
				),
				'title' => array(
					'gallery_id' => esc_html__( 'Gallery', 'anva-shortcodes' ),
				),
				'desc' => array(
					'gallery_id' => esc_html__( 'Select gallery you want to display its images', 'anva-shortcodes' ),
				),
				'options' => array(
					'gallery_id' => $galleries,
				),
				'content' => false,
			),
			'social_icons' => array(
				'name' => esc_html__( 'Social Icons', 'anva-shortcodes' ),
				'attr' => array(
					'style' => 'select',
					'size'  => 'text',
				),
				'title' => array(
					'style' => esc_html__( 'Color Style', 'anva-shortcodes' ),
					'size'  => esc_html__( 'Icon Size', 'anva-shortcodes' ),
				),
				'desc' => array(
					'style' => esc_html__( 'Select color style for social icons', 'anva-shortcodes' ),
					'size'  => esc_html__( 'Enter icon size between small and large', 'anva-shortcodes' ),
				),
				'options' => array(
					'style' => array(
						'dark'  => esc_html__( 'Dark', 'anva-shortcodes' ),
						'light' => esc_html__( 'Light', 'anva-shortcodes' ),
					),
				),
				'content' => false,
			),
			'social_share' => array(
				'name' => esc_html__( 'Social Share', 'anva-shortcodes' ),
				'attr' => array(),
				'content' => false,
			),
			'promo_box' => array(
				'name' => esc_html__( 'Promo Box', 'anva-shortcodes' ),
				'attr' => array(
					'title'       => 'text',
					'bgcolor'     => 'colorpicker',
					'fontcolor'   => 'colorpicker',
					'bordercolor' => 'colorpicker',
					'button_text' => 'text',
					'button_url'  => 'text',
					'buttoncolor' => 'colorpicker',
				),
				'title' => array(
					'title'       => esc_html__( 'Title (Optional)', 'anva-shortcodes' ),
					'bgcolor'     => esc_html__( 'Background Color', 'anva-shortcodes' ),
					'fontcolor'   => esc_html__( 'Font Color', 'anva-shortcodes' ),
					'bordercolor' => esc_html__( 'Border Color', 'anva-shortcodes' ),
					'button_text' => esc_html__( 'Button Text', 'anva-shortcodes' ),
					'button_url'  => esc_html__( 'Button Link URL', 'anva-shortcodes' ),
					'buttoncolor' => esc_html__( 'Button Color', 'anva-shortcodes' ),
				),
				'desc' => array(
					'title'       => esc_html__( 'Enter promo box title', 'anva-shortcodes' ),
					'bgcolor'     => esc_html__( 'Select background color for this content', 'anva-shortcodes' ),
					'fontcolor'   => esc_html__( 'Select font color for this content', 'anva-shortcodes' ),
					'bordercolor' => esc_html__( 'Select border color for this content', 'anva-shortcodes' ),
					'button_text' => esc_html__( 'Enter promo box button text. For example More Info', 'anva-shortcodes' ),
					'button_url'  => esc_html__( 'Enter promo box button link URL', 'anva-shortcodes' ),
					'bordercolor' => esc_html__( 'Select button color', 'anva-shortcodes' ),
					'buttoncolor' => esc_html__( 'Enter the button Color', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'accordion' => array(
				'name' => esc_html__( 'Accordion & Toggle', 'anva-shortcodes' ),
				'attr' => array(
					'title' => 'text',
					'icon'  => 'text',
					'close' => 'select',
				),
				'title' => array(
					'title' => esc_html__( 'Title', 'anva-shortcodes' ),
					'icon'  => esc_html__( 'Icon (optional)', 'anva-shortcodes' ),
					'close' => esc_html__( 'Open State', 'anva-shortcodes' ),
				),
				'desc' => array(
					'title' => esc_html__( 'Enter Accordion\'s title', 'anva-shortcodes' ),
					'icon'  => esc_html__( 'Enter icon class name ex. fa-star. <a href="http://fontawesome.io/cheatsheet/">See all possible here</a>', 'anva-shortcodes' ),
					'close' => esc_html__( 'Select default status (close or open)', 'anva-shortcodes' ),
				),
				'content' => true,
				'options' => array(
					'close' => array(
						0 => esc_html__( 'Open', 'anva-shortcodes' ),
						1 => esc_html__( 'Close', 'anva-shortcodes' ),
					)
				),
			),
			'image' => array(
				'name' => esc_html__( 'Image Animation', 'anva-shortcodes' ),
				'attr' => array(
					'src'       => 'text',
					'animation' => 'select',
					'frame'     => 'select',
				),
				'title' => array(
					'src'       => esc_html__( 'Image URL', 'anva-shortcodes' ),
					'animation' => esc_html__( 'Animation Type', 'anva-shortcodes' ),
					'frame'     => esc_html__( 'Frame Style', 'anva-shortcodes' ),
				),
				'desc' => array(
					'src'       => esc_html__( 'Enter image URL', 'anva-shortcodes' ),
					'animation' => esc_html__( 'Select animation type', 'anva-shortcodes' ),
					'frame'     => esc_html__( 'Select image frame style', 'anva-shortcodes' ),
				),
				'content' => true,
				'options' => array(
					'animation' => array(
						'slideRight' => esc_html__( 'Slide Right', 'anva-shortcodes' ),
						'slideLeft'  => esc_html__( 'Slide Left', 'anva-shortcodes' ),
						'slideUp'    => esc_html__( 'Slide Up', 'anva-shortcodes' ),
						'fadeIn'     => esc_html__( 'Fade In', 'anva-shortcodes' ),
					),
					'frame' => array(
						''             => esc_html__( 'None', 'anva-shortcodes' ),
						'border'       => esc_html__( 'Border', 'anva-shortcodes' ),
						'glow'         => esc_html__( 'Glow', 'anva-shortcodes' ),
						'dropshadow'   => esc_html__( 'Drop Shadow', 'anva-shortcodes' ),
						'bottomshadow' => esc_html__( 'Bottom Shadow', 'anva-shortcodes' ),
					),
				),
				'content' => false,
			),
			'divider' => array(
				'name' => esc_html__( 'Divider', 'anva-shortcodes' ),
				'attr' => array(
					'style' => 'select',
				),
				'title' => array(
					'style' => esc_html__( 'Style', 'anva-shortcodes' ),
				),
				'desc' => array(
					'style' => esc_html__( 'Select HR divider style', 'anva-shortcodes' ),
				),
				'options' => array(
					'style' => array(
						'normal' => esc_html__( 'Normal', 'anva-shortcodes' ),
						'thick'  => esc_html__( 'Thick', 'anva-shortcodes' ),
						'dotted' => esc_html__( 'Dotted', 'anva-shortcodes' ),
						'dashed' => esc_html__( 'Dashed', 'anva-shortcodes' ),
						'faded'  => esc_html__( 'Faded', 'anva-shortcodes' ),
						'totop'  => esc_html__( 'Go To Top', 'anva-shortcodes' ),
					)
				),
				'content' => false,
			),
			'teaser' => array(
				'name' => esc_html__( 'Image Teaser', 'anva-shortcodes' ),
				'attr' => array(
					'columns'   => 'select',
					'image'     => 'text',
					'title'     => 'text',
					'align'     => 'select',
					'bgcolor'   => 'colorpicker',
					'fontcolor' => 'colorpicker',
				),
				'title' => array(
					'columns'   => esc_html__( 'Columns Type', 'anva-shortcodes' ),
					'image'     => esc_html__( 'image URL', 'anva-shortcodes' ),
					'title'     => esc_html__( 'Title', 'anva-shortcodes' ),
					'align'     => esc_html__( 'Content Align', 'anva-shortcodes' ),
					'bgcolor'   => esc_html__( 'Background Color', 'anva-shortcodes' ),
					'fontcolor' => esc_html__( 'Font Color', 'anva-shortcodes' ),
				),
				'desc' => array(
					'columns'   => esc_html__( 'Select columns for image teaser', 'anva-shortcodes' ),
					'image'     => esc_html__( 'Enter full image URL', 'anva-shortcodes' ),
					'title'     => esc_html__( 'Enter teaser title', 'anva-shortcodes' ),
					'align'     => esc_html__( 'Enter teaser content text align from left, center and right', 'anva-shortcodes' ),
					'bgcolor'   => esc_html__( 'Select background color for this content', 'anva-shortcodes' ),
					'fontcolor' => esc_html__( 'Select font color for this content', 'anva-shortcodes' ),
				),
				'options' => array(
					'columns' => array(
						'one'             => esc_html__( 'Fullwidth', 'anva-shortcodes' ),
						'one_half'        => esc_html__( 'One Half', 'anva-shortcodes' ),
						'one_half last'   => esc_html__( 'One Half Last', 'anva-shortcodes' ),
						'one_third'       => esc_html__( 'One Third', 'anva-shortcodes' ),
						'one_half last'   => esc_html__( 'One Third Last', 'anva-shortcodes' ),
						'one_fourth'      => esc_html__( 'One Fourth', 'anva-shortcodes' ),
						'one_fourth last' => esc_html__( 'One Fourth Last', 'anva-shortcodes' ),
					),
					'align' => array(
						'left'   => esc_html__( 'Left', 'anva-shortcodes' ),
						'center' => esc_html__( 'Center', 'anva-shortcodes' ),
						'right'  => esc_html__( 'Right', 'anva-shortcodes' ),
					),
				),
				'content' => true,
			),
			'lightbox' => array(
				'name' => esc_html__( 'Media Lightbox', 'anva-shortcodes' ),
				'attr' => array(
					'type'       => 'select',
					'src'        => 'text',
					'href'       => 'text',
					'vimeo_id'   => 'text',
					'youtube_id' => 'text',
				),
				'title' => array(
					'type'       => esc_html__( 'Content Type', 'anva-shortcodes' ),
					'src'        => esc_html__( 'Image URL', 'anva-shortcodes' ),
					'href'       => esc_html__( 'Link URL', 'anva-shortcodes' ),
					'vimeo_id'   => esc_html__( 'Vimeo Video ID', 'anva-shortcodes' ),
					'youtube_id' => esc_html__( 'Youtube Video ID', 'anva-shortcodes' ),
				),
				'desc' => array(
					'type'       => esc_html__( 'Select ligthbox content type', 'anva-shortcodes' ),
					'src'        => esc_html__( 'Enter lightbox preview image URL', 'anva-shortcodes' ),
					'href'       => esc_html__( 'If you selected "Image". Enter full image URL here', 'anva-shortcodes' ),
					'vimeo_id'   => esc_html__( 'If you selected "Vimeo". Enter Vimeo video ID here ex. 82095744', 'anva-shortcodes' ),
					'youtube_id' => esc_html__( 'If you selected "Youtube". Enter Youtube video ID here ex. hT_nvWreIhg', 'anva-shortcodes' ),
				),
				'content' => true,
				'options' => array(
					'type' => array(
						'image'   => esc_html__( 'Image', 'anva-shortcodes' ),
						'vimeo'   => esc_html__( 'Vimeo', 'anva-shortcodes' ),
						'youtube' => esc_html__( 'Youtube', 'anva-shortcodes' ),
					)
				),
				'content' => false,
			),
			'youtube' => array(
				'name' => esc_html__( 'Youtube Video', 'anva-shortcodes' ),
				'attr' => array(
					'width'    => 'text',
					'height'   => 'text',
					'video_id' => 'text',
				),
				'title' => array(
					'width'    => esc_html__( 'Width', 'anva-shortcodes' ),
					'height'   => esc_html__( 'Height', 'anva-shortcodes' ),
					'video_id' => esc_html__( 'Youtube Video ID', 'anva-shortcodes' ),
				),
				'desc' => array(
					'width'    => esc_html__( 'Enter video width in pixels', 'anva-shortcodes' ),
					'height'   => esc_html__( 'Enter video height in pixels', 'anva-shortcodes' ),
					'video_id' => esc_html__( 'Enter Youtube video ID here ex. hT_nvWreIhg', 'anva-shortcodes' ),
				),
				'content' => false,
			),
			'vimeo' => array(
				'name' => esc_html__( 'Vimeo Video', 'anva-shortcodes' ),
				'attr' => array(
					'width'    => 'text',
					'height'   => 'text',
					'video_id' => 'text',
				),
				'title' => array(
					'width'    => esc_html__( 'Width', 'anva-shortcodes' ),
					'height'   => esc_html__( 'Height', 'anva-shortcodes' ),
					'video_id' => esc_html__( 'Vimeo Video ID', 'anva-shortcodes' ),
				),
				'desc' => array(
					'width'    => esc_html__( 'Enter video width in pixels', 'anva-shortcodes' ),
					'height'   => esc_html__( 'Enter video height in pixels', 'anva-shortcodes' ),
					'video_id' => esc_html__( 'Enter Vimeo video ID here ex. 82095744', 'anva-shortcodes' ),
				),
				'content' => false,
			),
			'animate_counter' => array(
				'name' => esc_html__( 'Animated Counter', 'anva-shortcodes' ),
				'attr' => array(
					'start'         => 'text',
					'end'           => 'text',
					'fontsize'      => 'text',
					'fontcolor'     => 'colorpicker',
					'count_subject' => 'text',
				),
				'title' => array(
					'start'         => esc_html__( 'Start', 'anva-shortcodes' ),
					'end'           => esc_html__( 'End', 'anva-shortcodes' ),
					'fontsize'      => esc_html__( 'Font Size', 'anva-shortcodes' ),
					'fontcolor'     => esc_html__( 'Font Color', 'anva-shortcodes' ),
					'count_subject' => esc_html__( 'Subject', 'anva-shortcodes' ),
				),
				'desc' => array(
					'start'         => esc_html__( 'Enter start number ex. 0', 'anva-shortcodes' ),
					'end'           => esc_html__( 'Enter end number ex. 100', 'anva-shortcodes' ),
					'fontsize'      => esc_html__( 'Enter counter number font size in pixel ex. 38', 'anva-shortcodes' ),
					'fontcolor'     => esc_html__( 'Enter counter number font color code ex. #000000', 'anva-shortcodes' ),
					'count_subject' => esc_html__( 'Enter count subject ex. followers', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'animate_bar' => array(
				'name' => esc_html__( 'Animated Progress Bar', 'anva-shortcodes' ),
				'attr' => array(
					'percent' => 'text',
					'color'   => 'colorpicker',
					'height'  => 'text',
				),
				'title' => array(
					'percent' => esc_html__( 'Percentage (Maximum 100)', 'anva-shortcodes' ),
					'color'   => esc_html__( 'Bar Color', 'anva-shortcodes' ),
					'height'  => esc_html__( 'Bar Height (In px)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'percent' => esc_html__( 'Enter number of percent value (maximum 100)', 'anva-shortcodes' ),
					'color'   => esc_html__( 'Enter progress bar background color code ex. #000000', 'anva-shortcodes' ),
					'height'  => esc_html__( 'Enter progress bar height', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'animate_circle' => array(
				'name' => esc_html__( 'Animated Circle', 'anva-shortcodes' ),
				'attr' => array(
					'percent'   => 'text',
					'dimension' => 'text',
					'width'     => 'text',
					'color'     => 'colorpicker',
					'fontsize'  => 'text',
					'subject'   => 'text',
				),
				'title' => array(
					'percent'   => esc_html__( 'Percentage (Maximum 100)', 'anva-shortcodes' ),
					'dimension' => esc_html__( 'Circle Dimension (In px)', 'anva-shortcodes' ),
					'width'     => esc_html__( 'Circle Border Width (In px)', 'anva-shortcodes' ),
					'color'     => esc_html__( 'Circle Border Color', 'anva-shortcodes' ),
					'fontsize'  => esc_html__( 'Font Size', 'anva-shortcodes' ),
					'subject'   => esc_html__( 'Sbuject', 'anva-shortcodes' ),
				),
				'desc' => array(
					'percent'   => esc_html__( 'Enter percent completion number ex. 90', 'anva-shortcodes' ),
					'dimension' => esc_html__( 'Enter circle dimension ex. 200', 'anva-shortcodes' ),
					'width'     => esc_html__( 'Enter circle border width ex. 10', 'anva-shortcodes' ),
					'color'     => esc_html__( 'Enter circle border color code ex. #000000', 'anva-shortcodes' ),
					'fontsize'  => esc_html__( 'Enter title font size in pixel ex. 38', 'anva-shortcodes' ),
					'subject'   => esc_html__( 'Enter circle subject info ex. completion', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'pricing' => array(
				'name' => esc_html__( 'Pricing Table', 'anva-shortcodes' ),
				'attr' => array(
					'skin'           => 'select',
					'category'       => 'select',
					'columns'        => 'select',
					'items'          => 'text',
					'highlightcolor' => 'colorpicker',
				),
				'title' => array(
					'skin'           => esc_html__( 'Skin', 'anva-shortcodes' ),
					'category'       => esc_html__( 'Pricing Category (Optional)', 'anva-shortcodes' ),
					'columns'        => esc_html__( 'Columns', 'anva-shortcodes' ),
					'items'          => esc_html__( 'Items', 'anva-shortcodes' ),
					'highlightcolor' => esc_html__( 'Highlight Color', 'anva-shortcodes' ),
				),
				'desc' => array(
					'skin'           => esc_html__( 'Select skin for this content', 'anva-shortcodes' ),
					'category'       => esc_html__( 'Select Pricing Category to filter content', 'anva-shortcodes' ),
					'columns'        => esc_html__( 'Select Number of Pricing Columns', 'anva-shortcodes' ),
					'items'          => esc_html__( 'Enter number of items you want to display', 'anva-shortcodes' ),
					'highlightcolor' => esc_html__( 'Select hightlight color for this content', 'anva-shortcodes' ),
				),
				'content' => true,
				'options' => array(
					'skin' => array(
						'light'  => esc_html__( 'Light', 'anva-shortcodes' ),
						'normal' => esc_html__( 'Normal', 'anva-shortcodes' ),
					),
					'category' => $price_cats,
					'columns' => array(
						2 => esc_html__( '2 Columns', 'anva-shortcodes' ),
						3 => esc_html__( '3 Columns', 'anva-shortcodes' ),
						4 => esc_html__( '4 Columns', 'anva-shortcodes' ),
					),
				),
				'content' => false,
			),
			'testimonial_slider' => array(
				'name' => esc_html__( 'Testimonials Slider', 'anva-shortcodes' ),
				'attr' => array(
					'cat'       => 'select',
					'items'     => 'text',
					'fontcolor' => 'colorpicker',
				),
				'title' => array(
					'cat'       => esc_html__( 'Testimonial Category (Optinal)', 'anva-shortcodes' ),
					'items'     => esc_html__( 'Items', 'anva-shortcodes' ),
					'fontcolor' => esc_html__( 'Font Color', 'anva-shortcodes' ),
				),
				'desc' => array(
					'cat'       => esc_html__( 'Select testimonials category you want to display its contents', 'anva-shortcodes' ),
					'items'     => esc_html__( 'Enter number of items you want to display', 'anva-shortcodes' ),
					'fontcolor' => esc_html__( 'Select font color for this content', 'anva-shortcodes' ),
				),
				'options' => array(
					'cat' => $testimonial_cats,
				),
				'content' => false,
			),
		);

		anva_shortcodes_asort( $options, 'name' );

		return apply_filters( 'anva_shortcodes_options', $options );
	}
}
