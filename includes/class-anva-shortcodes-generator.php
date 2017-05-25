<?php

/**
 * Shortcode generator options.
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
	 * @since 1.0.0
	 * @var   array
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
	 */
	public function __construct() {
		// Shortcode options.
		$this->options = anva_shortcodes_options();

		// Arguments settings.
		$this->args = array(
			'id'       => 'anva_shortcode_options',
			'title'    => __( 'Shortcode Options', 'anva' ),
			'page'     => array( 'post', 'page', 'portfolio' ),
			'context'  => 'normal',
			'priority' => 'high',
			'desc'     => __( 'Please select short code from list below then enter short code attributes and click "Generate Shortcode".', 'anva' ),
		);

		add_action( 'add_meta_boxes', array( $this, 'add' ) );
	}

	/**
	 * Metaboxes.
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
	 * Display UI.
	 *
	 * @param object $post
	 */
	public function display( $post ) {
	?>
	<div class="anva-shcg-wrap">
		<?php if ( ! empty( $this->options ) ) : ?>
			<div class="anva-shcg-description">
				<?php esc_html_e( $this->args['desc'] ); ?>
			</div>
			<div class="select-wrapper">
				<select id="shortcode-select">
					<?php
					foreach ( $this->options as $shortcode_id => $shortcode ) :
						$shortcode_name = $shortcode['name'];
					?>
					<option value="<?php echo esc_attr( $shortcode_id ); ?>">
						<?php echo esc_html( $shortcode_name ); ?>
					</option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $this->options ) ) : ?>
			<?php foreach ( $this->options as $shortcode_id => $shortcode ) : ?>

				<div id="anva-shcg-<?php echo esc_attr( $shortcode_id ); ?>" class="anva-shcg-section">
					<div class="anva-shcg-col">
						<div class="anva-shcg-section-title">
							<h3><?php echo esc_html( $shortcode['name'] ); ?></h3>
						</div>
						<div class="anva-textarea-wrap">
							<div id="<?php echo esc_attr( $shortcode_id ); ?>-code" class="anva-shcg-codearea"></div>
						</div>
						<div class="anva-shcg-footer">
							<button type="button" data-id="<?php echo esc_attr( $shortcode_id ); ?>" class="button button-primary button-shortcode">
								<?php esc_attr_e( 'Generate Shortcode' ); ?>
							</button>
						</div>
					</div>

					<div class="anva-shcg-col">
						<div class="anva-shcg-section-title">
							<h3><?php esc_html_e( 'Shortcode Attributes', 'anva-shortcodes' ); ?></h3>
						</div>
						<div class="anva-shcg-section-content">
							<div class="anva-shcg-section-content-wrap" id="<?php echo esc_attr( $shortcode_id ); ?>-attr-option">
								<?php if ( isset( $shortcode['attr'] ) && ! empty( $shortcode['attr'] ) ) : ?>
									<?php foreach ( $shortcode['attr'] as $attr_id => $type ) : ?>
										<div class="anva-shcg-section-option">
											<div class="anva-shcg-title">
												<h4><?php echo $shortcode['title'][ $attr_id ]; ?>:</h4>
											</div>
											<div class="anva-shcg-option">
												<?php $id = sprintf( '%s-%s', $shortcode_id, $attr_id ); ?>
												<div class="anva-shcg-controls">
													<?php switch ( $type ) :
														case 'text': ?>
															<input type="text" id="<?php echo esc_attr( $id ); ?>" class="anva-shcg-attr anva-input" data-attr="<?php echo esc_attr( $attr_id ); ?>" />

														<?php break;
														case 'colorpicker':
														?>
															<input type="text" id="<?php echo esc_attr( $id ); ?>" class="anva-shcg-attr anva-input anva-color" data-attr="<?php echo esc_attr( $attr_id ); ?>" readonly />

														<?php break;
														case 'select':
														?>
															<div class="select-wrapper">
																<select id="<?php echo esc_attr( $id ); ?>" class="anva-shcg-attr anva-input anva-select" data-attr="<?php echo esc_attr( $attr_id ); ?>">
																	<?php if ( isset( $shortcode['options'][ $attr_id ] ) && ! empty( $shortcode['options'][ $attr_id ] ) ) :
																		foreach ( $shortcode['options'][ $attr_id ] as $option_id => $option ) :
																		?>
																		<option value="<?php echo esc_attr( $option_id ); ?>">
																			<?php echo esc_html( $option ); ?>
																		</option>
																		<?php endforeach; ?>
																	<?php endif; ?>
																</select>
															</div>

														<?php break;
														case 'textarea':
														?>
															<textarea id="<?php echo esc_attr( $id ); ?>" class="anva-shcg-attr anva-input anva-textarea" data-attr="<?php echo esc_attr( $attr_id ); ?>"></textarea>

													<?php
													endswitch;
													?>
												</div>
												<div class="anva-shcg-explain">
													<?php echo $shortcode['desc'][ $attr_id ]; ?>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>

								<?php
								if ( isset( $shortcode['content'] ) && $shortcode['content'] ) :
									if ( isset( $shortcode['content_text'] ) ) {
										$content_text = $shortcode['content_text'];
									} else {
										$content_text = __( 'Your Content', 'anva' );
									}
									?>
									
									<div class="anva-shcg-section-option">
										<div class="anva-shcg-title">
											<h4><?php echo esc_html( $content_text ); ?>:</h4>
										</div>
										<div class="anva-shcg-option">
											<div class="anva-shcg-controls">
												<?php if ( isset( $shortcode['repeat'] ) ) : ?>
													<input type="hidden" id="<?php echo esc_attr( $shortcode_id ); ?>-content-repeat" value="<?php echo esc_attr( $shortcode['repeat'] ); ?>" />
												<?php endif; ?>

												<div class="anva-textarea-wrap">
													<textarea id="<?php echo esc_attr( $shortcode_id ); ?>-content" class="anva-input anva-textarea" rows="3"><?php echo esc_textarea( 'Enter your content here.', 'anva' ) ?></textarea>
												</div>
											</div>
											<div class="anva-shcg-explain">
												<?php esc_html_e( 'Enter the content you want to display in the shortcode.', 'anva' ) ?>
											</div>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<?php
	}
}
