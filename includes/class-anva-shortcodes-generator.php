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
class Anva_Shortcodes_Generator {

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
			'title'    => __( 'Shortcode Options', 'anva' ),
			'page'     => array( 'post', 'page', 'portfolio' ),
			'context'  => 'normal',
			'priority' => 'high',
			'desc'     => __( 'Please select short code from list below then enter short code attributes and click "Generate Shortcode".', 'anva' ),
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
					esc_textarea( __( 'Enter your content here.', 'anva' ) )
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
				'name'    => __( 'Dropcap', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
			),
			'quote' => array(
				'name'    => __( 'Quote Text', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
			),
			'button' => array(
				'name' => __( 'Button', 'anva-shortcodes' ),
				'attr' => array(
					'href'       => 'text',
					'color'      => 'select',
					'bg_color'   => 'colorpicker',
					'text_color' => 'colorpicker',
				),
				'title' => array(
					'href'       => __( 'URL', 'anva-shortcodes' ),
					'color'      => __( 'Skin', 'anva-shortcodes' ),
					'bg_color'   => __( 'Custom Background Color (Optional)', 'anva-shortcodes' ),
					'text_color' => __( 'Custom Font Color (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'href'       => __( 'Enter URL for button', 'anva-shortcodes' ),
					'color'      => __( 'Select predefined button colors', 'anva-shortcodes' ),
					'bg_color'   => __( 'Enter custom button background color code ex. #4ec380', 'anva-shortcodes' ),
					'text_color' => __( 'Enter custom button text color code ex. #ffffff', 'anva-shortcodes' ),
				),
				'options' => array(
					'color' => $colors,
				),
				'content'      => true,
				'content_text' => __( 'Enter text on button', 'anva-shortcodes' ),
			),
			'alert_box' => array(
				'name' => __( 'Alert Box', 'anva-shortcodes' ),
				'attr' => array(
					'style' => 'select',
				),
				'title' => array(
					'style' => __( 'Type', 'anva' ),
				),
				'desc' => array(
					'style' => __( 'Select alert box type', 'anva' ),
				),
				'options' => array(
					'style' => array(
						'error'   => __( 'Error', 'anva-shortcodes' ),
						'success' => __( 'Success', 'anva-shortcodes' ),
						'notice'  => __( 'Notice', 'anva-shortcodes' ),
					),
				),
				'content' => true,
			),
			'one_half' => array(
				'name'    => __( 'One Half Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
				'repeat'  => 1,
			),
			'one_half_last' => array(
				'name'    => __( 'One Half Last Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
				'repeat'  => 1,
			),
			'one_third' => array(
				'name'    => __( 'One Third Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
				'repeat'  => 2,
			),
			'one_third_last' => array(
				'name'    => __( 'One Third Last Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
			),
			'two_third' => array(
				'name'    => __( 'Two Third Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
			),
			'two_third_last' => array(
				'name'    => __( 'Two Third Last Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
			),
			'one_fourth' => array(
				'name'    => __( 'One Fourth Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
				'repeat'  => 3,
			),
			'one_fifth' => array(
				'name'    => __( 'One Fifth Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
				'repeat'  => 4,
			),
			'one_sixth' => array(
				'name'    => __( 'One Sixth Column', 'anva-shortcodes' ),
				'attr'    => array(),
				'desc'    => array(),
				'content' => true,
				'repeat'  => 5,
			),
			'one_half_bg' => array(
				'name' => __( 'One Half Column With Background', 'anva-shortcodes' ),
				'attr' => array(
					'bgcolor'   => 'colorpicker',
					'fontcolor' => 'colorpicker',
					'padding'   => 'text',
				),
				'title' => array(
					'bgcolor'   => __( 'Background Color', 'anva-shortcodes' ),
					'fontcolor' => __( 'Font Color', 'anva-shortcodes' ),
					'padding'   => __( 'Padding (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'bgcolor'   => __( 'Select background color', 'anva-shortcodes' ),
					'fontcolor' => __( 'Select font color', 'anva-shortcodes' ),
					'padding'   => __( 'Enter padding for this content (in px)', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'one_third_bg' => array(
				'name' => __( 'One Third Column With Background', 'anva-shortcodes' ),
				'attr' => array(
					'bgcolor'   => 'colorpicker',
					'fontcolor' => 'colorpicker',
					'padding'   => 'text',
				),
				'title' => array(
					'bgcolor'   => __( 'Background Color', 'anva-shortcodes' ),
					'fontcolor' => __( 'Font Color', 'anva-shortcodes' ),
					'padding'   => __( 'Padding (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'bgcolor'   => __( 'Select background color', 'anva-shortcodes' ),
					'fontcolor' => __( 'Select font color', 'anva-shortcodes' ),
					'padding'   => __( 'Enter padding for this content (in px)', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'two_third_bg' => array(
				'name' => __( 'Two Third Column With Background', 'anva-shortcodes' ),
				'attr' => array(
					'bgcolor'   => 'colorpicker',
					'fontcolor' => 'colorpicker',
					'padding'   => 'text',
				),
				'title' => array(
					'bgcolor'   => __( 'Background Color', 'anva-shortcodes' ),
					'fontcolor' => __( 'Font Color', 'anva-shortcodes' ),
					'padding'   => __( 'Padding (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'bgcolor'   => __( 'Select background color', 'anva-shortcodes' ),
					'fontcolor' => __( 'Select font color', 'anva-shortcodes' ),
					'padding'   => __( 'Enter padding for this content (in px)', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'one_fourth_bg' => array(
				'name' => __( 'One Fourth Column With Background', 'anva-shortcodes' ),
				'attr' => array(
					'bgcolor'   => 'colorpicker',
					'fontcolor' => 'colorpicker',
					'padding'   => 'text',
				),
				'title' => array(
					'bgcolor'   => __( 'Background Color', 'anva-shortcodes' ),
					'fontcolor' => __( 'Font Color', 'anva-shortcodes' ),
					'padding'   => __( 'Padding (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'bgcolor'   => __( 'Select background color', 'anva-shortcodes' ),
					'fontcolor' => __( 'Select font color', 'anva-shortcodes' ),
					'padding'   => __( 'Enter padding for this content (in px)', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'map' => array(
				'name' => __( 'Google Map', 'anva-shortcodes' ),
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
					'type'   => __( 'Map Type', 'anva-shortcodes' ),
					'width'  => __( 'Width', 'anva-shortcodes' ),
					'height' => __( 'Height', 'anva-shortcodes' ),
					'lat'    => __( 'Latitude', 'anva-shortcodes' ),
					'long'   => __( 'Longtitude', 'anva-shortcodes' ),
					'zoom'   => __( 'Zoom Level', 'anva-shortcodes' ),
					'popup'  => __( 'Popup Text (Optional)', 'anva-shortcodes' ),
					'marker' => __( 'Custom Marker Icon (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'type'   => __( 'Select map display type', 'anva-shortcodes' ),
					'width'  => __( 'Map width in pixels', 'anva-shortcodes' ),
					'height' => __( 'Map height in pixels', 'anva-shortcodes' ),
					'lat'    => __( 'Map latitude', 'anva-shortcodes' ),
					'long'   => __( 'Map longitude', 'anva-shortcodes' ),
					'zoom'   => __( 'Enter zoom number (1-16)', 'anva-shortcodes' ),
					'popup'  => __( 'Enter text to display as popup above location on map for ex. your company name', 'anva-shortcodes' ),
					'marker' => __( 'Enter custom marker image URL', 'anva-shortcodes' ),
				),
				'content' => false,
				'options' => array(
					'type' => array(
						'ROADMAP'   => __( 'Roadmap', 'anva-shortcodes' ),
						'SATELLITE' => __( 'Satellite', 'anva-shortcodes' ),
						'HYBRID'    => __( 'Hybrid', 'anva-shortcodes' ),
						'TERRAIN'   => __( 'Terrain', 'anva-shortcodes' ),
					)
				),
			),
			'googlefont' => array(
				'name' => __( 'Google Font', 'anva-shortcodes' ),
				'attr' => array(
					'font'     => 'text',
					'fontsize' => 'text',
					'style'    => 'text',
				),
				'title' => array(
					'font'     => __( 'Font Name', 'anva-shortcodes' ),
					'fontsize' => __( 'Font Size', 'anva-shortcodes' ),
					'style'    => __( 'Custom CSS style ex. font-style:italic; (Optional)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'font'     => __( 'Enter Google Web Font Name you want to use', 'anva-shortcodes' ),
					'fontsize' => __( 'Enter font size in pixels', 'anva-shortcodes' ),
					'style'    => __( 'Enter custom CSS styling code', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'thumb_gallery' => array(
				'name' => __( 'Gallery Thumbnails', 'anva-shortcodes' ),
				'attr' => array(
					'gallery_id' => 'select',
					'width'      => 'text',
					'height'     => 'text',
				),
				'title' => array(
					'gallery_id' => __( 'Gallery', 'anva-shortcodes' ),
					'width'      => __( 'Width', 'anva-shortcodes' ),
					'height'     => __( 'Height', 'anva-shortcodes' ),
				),
				'desc' => array(
					'gallery_id' => __( 'Select gallery you want to display its images', 'anva-shortcodes' ),
					'width'      => __( 'Gallery image width in pixels', 'anva-shortcodes' ),
					'height'     => __( 'Gallery image height in pixels', 'anva-shortcodes' ),
				),
				'options' => array(
					'gallery_id' => $galleries,
				),
				'content' => false,
			),
			'grid_gallery' => array(
				'name' => __( 'Gallery Grid', 'anva-shortcodes' ),
				'attr' => array(
					'gallery_id' => 'select',
					'layout'     => 'select',
					'columns'    => 'select',
				),
				'title' => array(
					'gallery_id' => __( 'Gallery', 'anva-shortcodes' ),
					'layout'     => __( 'Layout', 'anva-shortcodes' ),
					'columns'    => __( 'Columns', 'anva-shortcodes' ),
				),
				'desc' => array(
					'gallery_id' => __( 'Select gallery you want to display its images', 'anva-shortcodes' ),
					'layout'     => __( 'Select gallery layout you to display its images', 'anva-shortcodes' ),
					'columns'    => __( 'Select gallery columns you to display its images', 'anva-shortcodes' ),
				),
				'options' => array(
					'gallery_id' => $galleries,
					'layout' => array(
						'contain' => __( 'Contain', 'anva-shortcodes' ),
						'wide'    => __( 'Wide', 'anva-shortcodes' ),
					),
					'columns' => array(
						2 => __( '2 Colmuns', 'anva-shortcodes' ),
						3 => __( '3 Colmuns', 'anva-shortcodes' ),
						4 => __( '4 Colmuns', 'anva-shortcodes' ),
					),
				),
				'content' => false,
			),
			'masonry_gallery' => array(
				'name' => __( 'Gallery Masonry', 'anva-shortcodes' ),
				'attr' => array(
					'gallery_id' => 'select',
					'layout'     => 'select',
					'columns'    => 'select',
				),
				'title' => array(
					'gallery_id' => __( 'Gallery', 'anva-shortcodes' ),
					'layout'     => __( 'Layout', 'anva-shortcodes' ),
					'columns'    => __( 'Columns', 'anva-shortcodes' ),
				),
				'desc' => array(
					'gallery_id' => __( 'Select gallery you want to display its images', 'anva-shortcodes' ),
					'layout'     => __( 'Select gallery layout you to display its images', 'anva-shortcodes' ),
					'columns'    => __( 'Select gallery columns you to display its images', 'anva-shortcodes' ),
				),
				'options' => array(
					'gallery_id' => $galleries,
					'layout' => array(
						'contain' => __( 'Contain', 'anva-shortcodes' ),
						'wide'    => __( 'Wide', 'anva-shortcodes' ),
					),
					'columns' => array(
						2 => __( '2 Colmuns', 'anva-shortcodes' ),
						3 => __( '3 Colmuns', 'anva-shortcodes' ),
						4 => __( '4 Colmuns', 'anva-shortcodes' ),
					),
				),
				'content' => false,
			),
			'gallery_slider' => array(
				'name' => __( 'Gallery Slider', 'anva-shortcodes' ),
				'attr' => array(
					'gallery_id' => 'select',
				),
				'title' => array(
					'gallery_id' => __( 'Gallery', 'anva-shortcodes' ),
				),
				'desc' => array(
					'gallery_id' => __( 'Select gallery you want to display its images', 'anva-shortcodes' ),
				),
				'options' => array(
					'gallery_id' => $galleries,
				),
				'content' => false,
			),
			'social_icons' => array(
				'name' => __( 'Social Icons', 'anva-shortcodes' ),
				'attr' => array(
					'style' => 'select',
					'size'  => 'text',
				),
				'title' => array(
					'style' => __( 'Color Style', 'anva-shortcodes' ),
					'size'  => __( 'Icon Size', 'anva-shortcodes' ),
				),
				'desc' => array(
					'style' => __( 'Select color style for social icons', 'anva-shortcodes' ),
					'size'  => __( 'Enter icon size between small and large', 'anva-shortcodes' ),
				),
				'options' => array(
					'style' => array(
						'dark'  => __( 'Dark', 'anva-shortcodes' ),
						'light' => __( 'Light', 'anva-shortcodes' ),
					),
				),
				'content' => false,
			),
			'social_share' => array(
				'name' => __( 'Social Share', 'anva-shortcodes' ),
				'attr' => array(),
				'content' => false,
			),
			'promo_box' => array(
				'name' => __( 'Promo Box', 'anva-shortcodes' ),
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
					'title'       => __( 'Title (Optional)', 'anva-shortcodes' ),
					'bgcolor'     => __( 'Background Color', 'anva-shortcodes' ),
					'fontcolor'   => __( 'Font Color', 'anva-shortcodes' ),
					'bordercolor' => __( 'Border Color', 'anva-shortcodes' ),
					'button_text' => __( 'Button Text', 'anva-shortcodes' ),
					'button_url'  => __( 'Button Link URL', 'anva-shortcodes' ),
					'buttoncolor' => __( 'Button Color', 'anva-shortcodes' ),
				),
				'desc' => array(
					'title'       => __( 'Enter promo box title', 'anva-shortcodes' ),
					'bgcolor'     => __( 'Select background color for this content', 'anva-shortcodes' ),
					'fontcolor'   => __( 'Select font color for this content', 'anva-shortcodes' ),
					'bordercolor' => __( 'Select border color for this content', 'anva-shortcodes' ),
					'button_text' => __( 'Enter promo box button text. For example More Info', 'anva-shortcodes' ),
					'button_url'  => __( 'Enter promo box button link URL', 'anva-shortcodes' ),
					'bordercolor' => __( 'Select button color', 'anva-shortcodes' ),
					'buttoncolor' => __( 'Enter the button Color', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'accordion' => array(
				'name' => __( 'Accordion & Toggle', 'anva-shortcodes' ),
				'attr' => array(
					'title' => 'text',
					'icon'  => 'text',
					'close' => 'select',
				),
				'title' => array(
					'title' => __( 'Title', 'anva-shortcodes' ),
					'icon'  => __( 'Icon (optional)', 'anva-shortcodes' ),
					'close' => __( 'Open State', 'anva-shortcodes' ),
				),
				'desc' => array(
					'title' => __( 'Enter Accordion\'s title', 'anva-shortcodes' ),
					'icon'  => __( 'Enter icon class name ex. fa-star. <a href="http://fontawesome.io/cheatsheet/">See all possible here</a>', 'anva-shortcodes' ),
					'close' => __( 'Select default status (close or open)', 'anva-shortcodes' ),
				),
				'content' => true,
				'options' => array(
					'close' => array(
						0 => __( 'Open', 'anva-shortcodes' ),
						1 => __( 'Close', 'anva-shortcodes' ),
					)
				),
			),
			'image' => array(
				'name' => __( 'Image Animation', 'anva-shortcodes' ),
				'attr' => array(
					'src'       => 'text',
					'animation' => 'select',
					'frame'     => 'select',
				),
				'title' => array(
					'src'       => __( 'Image URL', 'anva-shortcodes' ),
					'animation' => __( 'Animation Type', 'anva-shortcodes' ),
					'frame'     => __( 'Frame Style', 'anva-shortcodes' ),
				),
				'desc' => array(
					'src'       => __( 'Enter image URL', 'anva-shortcodes' ),
					'animation' => __( 'Select animation type', 'anva-shortcodes' ),
					'frame'     => __( 'Select image frame style', 'anva-shortcodes' ),
				),
				'content' => true,
				'options' => array(
					'animation' => array(
						'slideRight' => __( 'Slide Right', 'anva-shortcodes' ),
						'slideLeft'  => __( 'Slide Left', 'anva-shortcodes' ),
						'slideUp'    => __( 'Slide Up', 'anva-shortcodes' ),
						'fadeIn'     => __( 'Fade In', 'anva-shortcodes' ),
					),
					'frame' => array(
						''             => __( 'None', 'anva-shortcodes' ),
						'border'       => __( 'Border', 'anva-shortcodes' ),
						'glow'         => __( 'Glow', 'anva-shortcodes' ),
						'dropshadow'   => __( 'Drop Shadow', 'anva-shortcodes' ),
						'bottomshadow' => __( 'Bottom Shadow', 'anva-shortcodes' ),
					),
				),
				'content' => false,
			),
			'divider' => array(
				'name' => __( 'Divider', 'anva-shortcodes' ),
				'attr' => array(
					'style' => 'select',
				),
				'title' => array(
					'style' => __( 'Style', 'anva-shortcodes' ),
				),
				'desc' => array(
					'style' => __( 'Select HR divider style', 'anva-shortcodes' ),
				),
				'options' => array(
					'style' => array(
						'normal' => __( 'Normal', 'anva-shortcodes' ),
						'thick'  => __( 'Thick', 'anva-shortcodes' ),
						'dotted' => __( 'Dotted', 'anva-shortcodes' ),
						'dashed' => __( 'Dashed', 'anva-shortcodes' ),
						'faded'  => __( 'Faded', 'anva-shortcodes' ),
						'totop'  => __( 'Go To Top', 'anva-shortcodes' ),
					)
				),
				'content' => false,
			),
			'teaser' => array(
				'name' => __( 'Image Teaser', 'anva-shortcodes' ),
				'attr' => array(
					'columns'   => 'select',
					'image'     => 'text',
					'title'     => 'text',
					'align'     => 'select',
					'bgcolor'   => 'colorpicker',
					'fontcolor' => 'colorpicker',
				),
				'title' => array(
					'columns'   => __( 'Columns Type', 'anva-shortcodes' ),
					'image'     => __( 'image URL', 'anva-shortcodes' ),
					'title'     => __( 'Title', 'anva-shortcodes' ),
					'align'     => __( 'Content Align', 'anva-shortcodes' ),
					'bgcolor'   => __( 'Background Color', 'anva-shortcodes' ),
					'fontcolor' => __( 'Font Color', 'anva-shortcodes' ),
				),
				'desc' => array(
					'columns'   => __( 'Select columns for image teaser', 'anva-shortcodes' ),
					'image'     => __( 'Enter full image URL', 'anva-shortcodes' ),
					'title'     => __( 'Enter teaser title', 'anva-shortcodes' ),
					'align'     => __( 'Enter teaser content text align from left, center and right', 'anva-shortcodes' ),
					'bgcolor'   => __( 'Select background color for this content', 'anva-shortcodes' ),
					'fontcolor' => __( 'Select font color for this content', 'anva-shortcodes' ),
				),
				'options' => array(
					'columns' => array(
						'one'             => __( 'Fullwidth', 'anva-shortcodes' ),
						'one_half'        => __( 'One Half', 'anva-shortcodes' ),
						'one_half last'   => __( 'One Half Last', 'anva-shortcodes' ),
						'one_third'       => __( 'One Third', 'anva-shortcodes' ),
						'one_half last'   => __( 'One Third Last', 'anva-shortcodes' ),
						'one_fourth'      => __( 'One Fourth', 'anva-shortcodes' ),
						'one_fourth last' => __( 'One Fourth Last', 'anva-shortcodes' ),
					),
					'align' => array(
						'left'   => __( 'Left', 'anva-shortcodes' ),
						'center' => __( 'Center', 'anva-shortcodes' ),
						'right'  => __( 'Right', 'anva-shortcodes' ),
					),
				),
				'content' => true,
			),
			'lightbox' => array(
				'name' => __( 'Media Lightbox', 'anva-shortcodes' ),
				'attr' => array(
					'type'       => 'select',
					'src'        => 'text',
					'href'       => 'text',
					'vimeo_id'   => 'text',
					'youtube_id' => 'text',
				),
				'title' => array(
					'type'       => __( 'Content Type', 'anva-shortcodes' ),
					'src'        => __( 'Image URL', 'anva-shortcodes' ),
					'href'       => __( 'Link URL', 'anva-shortcodes' ),
					'vimeo_id'   => __( 'Vimeo Video ID', 'anva-shortcodes' ),
					'youtube_id' => __( 'Youtube Video ID', 'anva-shortcodes' ),
				),
				'desc' => array(
					'type'       => __( 'Select ligthbox content type', 'anva-shortcodes' ),
					'src'        => __( 'Enter lightbox preview image URL', 'anva-shortcodes' ),
					'href'       => __( 'If you selected "Image". Enter full image URL here', 'anva-shortcodes' ),
					'vimeo_id'   => __( 'If you selected "Vimeo". Enter Vimeo video ID here ex. 82095744', 'anva-shortcodes' ),
					'youtube_id' => __( 'If you selected "Youtube". Enter Youtube video ID here ex. hT_nvWreIhg', 'anva-shortcodes' ),
				),
				'content' => true,
				'options' => array(
					'type' => array(
						'image'   => __( 'Image', 'anva-shortcodes' ),
						'vimeo'   => __( 'Vimeo', 'anva-shortcodes' ),
						'youtube' => __( 'Youtube', 'anva-shortcodes' ),
					)
				),
				'content' => false,
			),
			'youtube' => array(
				'name' => __( 'Youtube Video', 'anva-shortcodes' ),
				'attr' => array(
					'width'    => 'text',
					'height'   => 'text',
					'video_id' => 'text',
				),
				'title' => array(
					'width'    => __( 'Width', 'anva-shortcodes' ),
					'height'   => __( 'Height', 'anva-shortcodes' ),
					'video_id' => __( 'Youtube Video ID', 'anva-shortcodes' ),
				),
				'desc' => array(
					'width'    => __( 'Enter video width in pixels', 'anva-shortcodes' ),
					'height'   => __( 'Enter video height in pixels', 'anva-shortcodes' ),
					'video_id' => __( 'Enter Youtube video ID here ex. hT_nvWreIhg', 'anva-shortcodes' ),
				),
				'content' => false,
			),
			'vimeo' => array(
				'name' => __( 'Vimeo Video', 'anva-shortcodes' ),
				'attr' => array(
					'width'    => 'text',
					'height'   => 'text',
					'video_id' => 'text',
				),
				'title' => array(
					'width'    => __( 'Width', 'anva-shortcodes' ),
					'height'   => __( 'Height', 'anva-shortcodes' ),
					'video_id' => __( 'Vimeo Video ID', 'anva-shortcodes' ),
				),
				'desc' => array(
					'width'    => __( 'Enter video width in pixels', 'anva-shortcodes' ),
					'height'   => __( 'Enter video height in pixels', 'anva-shortcodes' ),
					'video_id' => __( 'Enter Vimeo video ID here ex. 82095744', 'anva-shortcodes' ),
				),
				'content' => false,
			),
			'animate_counter' => array(
				'name' => __( 'Animated Counter', 'anva-shortcodes' ),
				'attr' => array(
					'start'         => 'text',
					'end'           => 'text',
					'fontsize'      => 'text',
					'fontcolor'     => 'colorpicker',
					'count_subject' => 'text',
				),
				'title' => array(
					'start'         => __( 'Start', 'anva-shortcodes' ),
					'end'           => __( 'End', 'anva-shortcodes' ),
					'fontsize'      => __( 'Font Size', 'anva-shortcodes' ),
					'fontcolor'     => __( 'Font Color', 'anva-shortcodes' ),
					'count_subject' => __( 'Subject', 'anva-shortcodes' ),
				),
				'desc' => array(
					'start'         => __( 'Enter start number ex. 0', 'anva-shortcodes' ),
					'end'           => __( 'Enter end number ex. 100', 'anva-shortcodes' ),
					'fontsize'      => __( 'Enter counter number font size in pixel ex. 38', 'anva-shortcodes' ),
					'fontcolor'     => __( 'Enter counter number font color code ex. #000000', 'anva-shortcodes' ),
					'count_subject' => __( 'Enter count subject ex. followers', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'animate_bar' => array(
				'name' => __( 'Animated Progress Bar', 'anva-shortcodes' ),
				'attr' => array(
					'percent' => 'text',
					'color'   => 'colorpicker',
					'height'  => 'text',
				),
				'title' => array(
					'percent' => __( 'Percentage (Maximum 100)', 'anva-shortcodes' ),
					'color'   => __( 'Bar Color', 'anva-shortcodes' ),
					'height'  => __( 'Bar Height (In px)', 'anva-shortcodes' ),
				),
				'desc' => array(
					'percent' => __( 'Enter number of percent value (maximum 100)', 'anva-shortcodes' ),
					'color'   => __( 'Enter progress bar background color code ex. #000000', 'anva-shortcodes' ),
					'height'  => __( 'Enter progress bar height', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'animate_circle' => array(
				'name' => __( 'Animated Circle', 'anva-shortcodes' ),
				'attr' => array(
					'percent'   => 'text',
					'dimension' => 'text',
					'width'     => 'text',
					'color'     => 'colorpicker',
					'fontsize'  => 'text',
					'subject'   => 'text',
				),
				'title' => array(
					'percent'   => __( 'Percentage (Maximum 100)', 'anva-shortcodes' ),
					'dimension' => __( 'Circle Dimension (In px)', 'anva-shortcodes' ),
					'width'     => __( 'Circle Border Width (In px)', 'anva-shortcodes' ),
					'color'     => __( 'Circle Border Color', 'anva-shortcodes' ),
					'fontsize'  => __( 'Font Size', 'anva-shortcodes' ),
					'subject'   => __( 'Sbuject', 'anva-shortcodes' ),
				),
				'desc' => array(
					'percent'   => __( 'Enter percent completion number ex. 90', 'anva-shortcodes' ),
					'dimension' => __( 'Enter circle dimension ex. 200', 'anva-shortcodes' ),
					'width'     => __( 'Enter circle border width ex. 10', 'anva-shortcodes' ),
					'color'     => __( 'Enter circle border color code ex. #000000', 'anva-shortcodes' ),
					'fontsize'  => __( 'Enter title font size in pixel ex. 38', 'anva-shortcodes' ),
					'subject'   => __( 'Enter circle subject info ex. completion', 'anva-shortcodes' ),
				),
				'content' => true,
			),
			'pricing' => array(
				'name' => __( 'Pricing Table', 'anva-shortcodes' ),
				'attr' => array(
					'skin'           => 'select',
					'category'       => 'select',
					'columns'        => 'select',
					'items'          => 'text',
					'highlightcolor' => 'colorpicker',
				),
				'title' => array(
					'skin'           => __( 'Skin', 'anva-shortcodes' ),
					'category'       => __( 'Pricing Category (Optional)', 'anva-shortcodes' ),
					'columns'        => __( 'Columns', 'anva-shortcodes' ),
					'items'          => __( 'Items', 'anva-shortcodes' ),
					'highlightcolor' => __( 'Highlight Color', 'anva-shortcodes' ),
				),
				'desc' => array(
					'skin'           => __( 'Select skin for this content', 'anva-shortcodes' ),
					'category'       => __( 'Select Pricing Category to filter content', 'anva-shortcodes' ),
					'columns'        => __( 'Select Number of Pricing Columns', 'anva-shortcodes' ),
					'items'          => __( 'Enter number of items you want to display', 'anva-shortcodes' ),
					'highlightcolor' => __( 'Select hightlight color for this content', 'anva-shortcodes' ),
				),
				'content' => true,
				'options' => array(
					'skin' => array(
						'light'  => __( 'Light', 'anva-shortcodes' ),
						'normal' => __( 'Normal', 'anva-shortcodes' ),
					),
					'category' => $price_cats,
					'columns' => array(
						2 => __( '2 Columns', 'anva-shortcodes' ),
						3 => __( '3 Columns', 'anva-shortcodes' ),
						4 => __( '4 Columns', 'anva-shortcodes' ),
					),
				),
				'content' => false,
			),
			'testimonial_slider' => array(
				'name' => __( 'Testimonials Slider', 'anva-shortcodes' ),
				'attr' => array(
					'cat'       => 'select',
					'items'     => 'text',
					'fontcolor' => 'colorpicker',
				),
				'title' => array(
					'cat'       => __( 'Testimonial Category (Optinal)', 'anva-shortcodes' ),
					'items'     => __( 'Items', 'anva-shortcodes' ),
					'fontcolor' => __( 'Font Color', 'anva-shortcodes' ),
				),
				'desc' => array(
					'cat'       => __( 'Select testimonials category you want to display its contents', 'anva-shortcodes' ),
					'items'     => __( 'Enter number of items you want to display', 'anva-shortcodes' ),
					'fontcolor' => __( 'Select font color for this content', 'anva-shortcodes' ),
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
