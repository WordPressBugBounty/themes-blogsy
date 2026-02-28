<?php
/**
 * Blogsy Customizer custom select control class.
 *
 * @package     Blogsy
 * @author      Peregrine Themes
 * @since       1.0.0
 */

/**
 * Do not allow direct script access.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Blogsy_Customizer_Control_Select' ) ) :
	/**
	 * Blogsy Customizer custom select control class.
	 */
	class Blogsy_Customizer_Control_Select extends Blogsy_Customizer_Control {

		/**
		 * The control type.
		 *
		 * @var string
		 */
		public $type = 'blogsy-select';

		/**
		 * Subtitle
		 *
		 * @var string|html
		 */
		public $subtitle = '';

		/**
		 * Choices.
		 *
		 * @since 1.0.0
		 * @var Array
		 */
		public $choices = [];

		/**
		 * Placeholder text.
		 *
		 * @since 1.0.0
		 * @var string|false
		 */
		public $placeholder = false;

		/**
		 * Select2 flag.
		 *
		 * @since 1.0.0
		 * @var boolean
		 */
		public $is_select2 = false;

		/**
		 * Data source.
		 *
		 * @since 1.0.0
		 * @var string|false
		 */
		public $data_source = false;

		/**
		 * Source from where we will show data like custom taxonomy.
		 *
		 * @var boolean
		 */
		public $data_source_name = null;

		/**
		 * Multiple items.
		 *
		 * @since 1.0.0
		 * @var boolean
		 */
		public $multiple = false;

		/**
		 * Set the default typography options.
		 *
		 * @since 1.0.0
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string               $id      Control ID.
		 * @param array                $args    Default parent's arguments.
		 */
		public function __construct( $manager, $id, $args = [] ) {
			parent::__construct( $manager, $id, $args );

			if ( ! $this->is_select2 ) {
				return;
			}

			if ( is_callable( $this->data_source ) ) {
				$this->choices = call_user_func( $this->data_source );
				return;
			}

			$choices = [];

			switch ( $this->data_source ) {
				case 'category':
				case 'tags':
					$taxonomy = 'category' === $this->data_source ? ( $this->data_source_name ?? 'category' ) : ( $this->data_source_name ?? 'post_tag' );
					$args     = [
						'hide_empty' => ( 'category' === $this->data_source ),
						'taxonomy'   => $taxonomy,
					];
					$terms    = get_terms( $args );

					if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
						foreach ( $terms as $term ) {
							if ( is_object( $term ) ) {
								$choices[ $term->slug ] = $term->name;
							}
						}
					}
					break;

				case 'page':
					$pages = get_pages();

					if ( ! empty( $pages ) ) {
						foreach ( $pages as $page ) {
							$choices[ $page->ID ] = $page->post_title;
						}
					}
					break;
				case 'post_types':
				case 'post_type':
					global $wp_post_types;
					$post_types = get_post_types(
						[
							'public'              => true,
							'exclude_from_search' => false,
						]
					);
					foreach ( $post_types as $name ) {
						if ( isset( $wp_post_types[ $name ]->labels->menu_name ) ) {
							$choices[ $name ] = $wp_post_types[ $name ]->labels->menu_name;
						} else {
							$choices[ $name ] = ucfirst( $name );
						}
					}
					break;

				default:
					// Handle other data sources if needed.
					break;
			}

			$this->choices = $choices;
		}


		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @see WP_Customize_Control::to_json()
		 */
		public function to_json() {
			parent::to_json();

			$this->json['subtitle']    = $this->subtitle;
			$this->json['choices']     = $this->choices;
			$this->json['placeholder'] = $this->placeholder;
			$this->json['is_select2']  = $this->is_select2;
			$this->json['multiple']    = $this->multiple ? ' multiple="multiple"' : '';

			if ( $this->multiple ) {
				$this->json['value'] = implode( ',', (array) $this->json['value'] );
			}
		}

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @access public
		 */
		public function enqueue() {

			parent::enqueue();

			if ( $this->is_select2 ) {

				// Script debug.
				$blogsy_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				/**
				 * Enqueue select2 stylesheet.
				 */
				wp_enqueue_style(
					'blogsy-select2-style',
					BLOGSY_THEME_URI . '/admin/dashboard/assets/css/select2' . $blogsy_suffix . '.css',
					false,
					BLOGSY_THEME_VERSION,
					'all'
				);

				/**
				 * Enqueue select2 script.
				 */
				wp_enqueue_script(
					'blogsy-select2-js',
					BLOGSY_THEME_URI . '/admin/dashboard/assets/js/libs/select2' . $blogsy_suffix . '.js',
					[ 'jquery' ],
					BLOGSY_THEME_VERSION,
					true
				);
			}
		}

		/**
		 * An Underscore (JS) template for this control's content (but not its container).
		 *
		 * Class variables for this control class are available in the `data` JS object;
		 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
		 *
		 * @see WP_Customize_Control::print_template()
		 */
		protected function content_template() {
			?>
			<div class="blogsy-control-wrapper blogsy-select-wrapper">

			<label>
				<# if ( data.label ) { #>
					<div class="customize-control-title">
						<span>{{{ data.label }}}</span>
						<# if ( data.description ) { #>
							<i class="blogsy-info-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-help-circle">
									<circle cx="12" cy="12" r="10"></circle>
									<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
									<line x1="12" y1="17" x2="12" y2="17"></line>
								</svg>
								<span class="blogsy-tooltip">{{{ data.description }}}</span>
							</i>
						<# } #>
					</div>
				<# } #>

				<select class="blogsy-select-control" {{{ data.link }}}{{{ data.multiple }}}>

					<# if ( data.is_select2 ) { #>

						<# _.each( data.choices, function( label, choice ) {
							if(data.value) { #>

							<option value="{{ choice }}" <# if ( -1 !== data.value.indexOf( choice ) ) { #> selected="selected" <# } #>>{{ label }}</option>

						<# } } ) #>

					<#  } else { #>
						<# for ( key in data.choices ) { #>
							<option value="{{ key }}" <# if ( key === data.value ) { #> checked="checked" <# } #>>{{ data.choices[ key ] }}</option>
						<# } #>
					<# } #>
				</select>
				<# if ( data.subtitle ) { #>
					<div class="customize-control-subtilte">{{{ data.subtitle }}}</div>
				<# } #>
			</label>

			</div><!-- END .blogsy-control-wrapper -->
																							<?php
		}
	}
endif;
