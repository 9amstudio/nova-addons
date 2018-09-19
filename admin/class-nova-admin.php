<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Novaworks
 * @subpackage Novaworks/admin
 * @author     Duy Pham <dpv.0990@gmail.com>
 */
class Novaworks_Admin {

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Novaworks_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Novaworks_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_deregister_style( 'jquery-chosen' );

		if( wp_style_is( 'font-awesome', 'registered' ) ) {
			wp_deregister_style( 'font-awesome' );
		}

		// wp core styles
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );


		wp_enqueue_style( 'nova-admin', plugin_dir_url( __FILE__ ) . 'css/nova-admin.css', array(), NOVA_ADDONS_VER, 'all' );

		wp_enqueue_style( 'font-awesome', plugin_dir_url( dirname(__FILE__) ) . 'public/css/font-awesome.min.css', array(), null );
		wp_enqueue_style( 'nova-icons', plugin_dir_url( dirname(__FILE__) ) . 'public/css/nova-icons.css', array(), null);
		wp_enqueue_style( 'font-nucleo-glyph', plugin_dir_url( dirname(__FILE__) ) . 'public/css/font-nucleo-glyph.min.css', array(), null );

		if ( is_rtl() ) {
			wp_enqueue_style( 'nova-admin-rtl', plugin_dir_url(__FILE__) . 'css/nova-admin-rtl.css', array(), NOVA_ADDONS_VER, 'all' );
		}

		$asset_font_without_domain = apply_filters( 'Novaworks/filter/assets_font_url', untrailingslashit(plugin_dir_url( dirname(__FILE__) ) ) );

		wp_add_inline_style(
			'nova-addons',
			"@font-face {
				font-family: 'icomoon';
				src:url('{$asset_font_without_domain}/public/fonts/icomoon.ttf');
				font-weight: normal;
				font-style: normal;
			}"
		);

		wp_add_inline_style(
			'font-awesome',
			"@font-face{
				font-family: 'FontAwesome';
				src: url('{$asset_font_without_domain}/public/fonts/fontawesome-webfont.eot');
				src: url('{$asset_font_without_domain}/public/fonts/fontawesome-webfont.eot') format('embedded-opentype'),
					 url('{$asset_font_without_domain}/public/fonts/fontawesome-webfont.woff2') format('woff2'),
					 url('{$asset_font_without_domain}/public/fonts/fontawesome-webfont.woff') format('woff'),
					 url('{$asset_font_without_domain}/public/fonts/fontawesome-webfont.ttf') format('truetype'),
					 url('{$asset_font_without_domain}/public/fonts/fontawesome-webfont.svg') format('svg');
				font-weight:normal;
				font-style:normal
			}"
		);
		wp_add_inline_style(
			'nova-icons',
			"@font-face {
				font-family: 'Nova-Icons';
				src: url('{$asset_font_without_domain}/public/fonts/Nova-Icons.eot');
				src: url('{$asset_font_without_domain}/public/fonts/Nova-Icons.eot') format('embedded-opentype'),
					 url('{$asset_font_without_domain}/public/fonts/Nova-Icons.woff') format('woff'),
					 url('{$asset_font_without_domain}/public/fonts/Nova-Icons.ttf') format('truetype'),
					 url('{$asset_font_without_domain}/public/fonts/Nova-Icons.svg') format('svg');
				font-weight: 400;
				font-style: normal
			}"
		);
		wp_add_inline_style(
			'font-nucleo-glyph',
			"@font-face {
				font-family: 'Nucleo Glyph';
				src: url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.eot');
				src: url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.eot') format('embedded-opentype'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.woff2') format('woff2'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.woff') format('woff'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.ttf') format('truetype'),
					 url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.svg') format('svg');
				font-weight: 400;
				font-style: normal
			}"
		);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Novaworks_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Novaworks_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_deregister_script( 'jquery-chosen' );
		// admin utilities
		wp_enqueue_media();

		$script_dependencies = array(
			'jquery',
			'wp-color-picker',
			'jquery-ui-dialog',
			'jquery-ui-sortable',
			'jquery-ui-accordion'
		);

		wp_register_script( 'nova-plugins', plugin_dir_url( __FILE__ ) . 'js/nova-admin-plugin.js', $script_dependencies, NOVA_ADDONS_VER, true );

		wp_enqueue_script( 'nova-admin', plugin_dir_url( __FILE__ ) . 'js/nova-admin.js', array( 'nova-plugins' ), NOVA_ADDONS_VER, true );

		$vars = array(
			'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
			'swatches_nonce' => wp_create_nonce( 'swatches_nonce' )
		);
		wp_localize_script( 'nova-addons' , 'la_swatches_vars', $vars );

	}

	/**
	 * Register Text Sanitize
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_text( $value ) {
		return wp_filter_nohtml_kses( $value );
	}

	/**
	 * Register Textarea Sanitize
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_textarea( $value ) {
		global $allowedposttags;
		return wp_kses( $value, $allowedposttags );
	}

	/**
	 * Register Checkbox Sanitize
	 * Do not touch, or think twice
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_checkbox( $value ) {
		if( ! empty( $value ) && $value == 1 ) {
			$value = true;
		}
		if( empty( $value ) ) {
			$value = false;
		}
		return $value;
	}

	/**
	 * Register Image Select Sanitize
	 * Do not touch, or think twice
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_image_select( $value ) {
		if( isset( $value ) && is_array( $value ) ) {
			if( count( $value ) ) {
				$value = $value;
			}
			else {
				$value = $value[0];
			}
		}
		else if ( empty( $value ) ) {
			$value = '';
		}

		return $value;
	}

	/**
	 * Register Group Sanitize
	 * Do not touch, or think twice
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_group( $value ) {
		return ( empty( $value ) ) ? '' : $value;
	}

	/**
	 * Register Title Sanitize
	 * Do not touch, or think twice
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_title( $value ) {
		return sanitize_title( $value );
	}

	/**
	 * Register Text Sanitize
	 *
	 * @since 1.0.0
	 */
	public static function sanitize_clean( $value ) {
		return $value;
	}

	/**
	 * Register Email Validate
	 *
	 * @since 1.0.0
	 */
	public static function validate_email( $value ) {
		if ( ! sanitize_email( $value ) ) {
			return esc_html__( 'Please write a valid email address!', 'nova' );
		}
	}

	/**
	 * Register Numeric Validate
	 *
	 * @since 1.0.0
	 */
	public static function validate_numeric( $value ) {
		if ( ! is_numeric( $value ) ) {
			return esc_html__( 'Please write a numeric data!', 'nova' );
		}
	}

	/**
	 * Register Required Validate
	 *
	 * @since 1.0.0
	 */
	public static function validate_required( $value ) {
		if ( empty( $value ) ) {
			return esc_html__( 'Fatal Error! This field is required!', 'nova' );
		}
	}


	private function get_icon_library(){

		$cache = wp_cache_get('icon_fonts', 'la_studio');
		if ( empty( $cache ) ) {
			$jsons = apply_filters('nova/filter/framework/field/icon/json', array(
				plugin_dir_path( dirname(__FILE__) ) . 'public/fonts/font-awesome.json'
			) );
			if ( ! empty( $jsons ) ) {
				$cache_tmp = array();
				foreach ( $jsons as $path ) {
					$file_data = @file_get_contents( $path );
					if ( ! is_wp_error( $file_data ) ) {
						$cache_tmp[] = json_decode( $file_data, false );
					}
				}
				wp_cache_set('icon_fonts', maybe_serialize($cache_tmp), 'la_studio' );
				return $cache_tmp;
			}
		}
		if ( empty( $cache ) ) {
			return array();
		}
		return maybe_unserialize( $cache );
	}

	/**
	 * Get icons from admin ajax
	 *
	 * @since 1.0.0
	 */
	public function ajax_get_icons(){
		$icons = $this->get_icon_library();
		if( ! empty( $icons ) ) {
			foreach ( $icons as $icon_object ) {
				if( is_object( $icon_object ) ) {
					echo ( count( $icons ) >= 2 ) ? '<h4 class="nova-icon-title">'. $icon_object->name .'</h4>' : '';
					foreach ( $icon_object->icons as $icon ) {
						echo '<a class="nova-icon-tooltip" data-nova-icon="'. $icon .'" data-title="'. $icon .'"><span class="nova-icon--selector la-selector"><i class="'. $icon .'"></i></span></a>';
					}
				} else {
					echo '<h4 class="nova-icon-title">'. esc_html__( 'Error! Can not load json file.', 'nova' ) .'</h4>';
				}
			}
		}
		die();
	}
	/**
	 * Get value form admin field autocomplete
	 *
	 * @since 1.0.0
	 */
	public function ajax_autocomplete(){
		if ( empty( $_GET['query_args'] ) || empty( $_GET['s'] ) ) {
			echo '<b>' . esc_html__( 'Query is empty ...', 'nova' ) . '</b>';
			die();
		}
		ob_start();

		$query = new WP_Query( wp_parse_args( $_GET['query_args'], array( 's' => $_GET['s'] ) ) );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				echo '<div data-id="' . get_the_ID() . '">' . get_the_title() . '</div>';
			}
		} else {
			echo '<b>' . esc_html__('Not found', 'nova' ) . '</b>';
		}

		wp_reset_postdata();
		echo ob_get_clean();
		die();
	}

	/**
	 * Get theme options form export field
	 *
	 * @since 1.0.0
	 */
	public function ajax_export_options(){
		$unique = isset( $_REQUEST['unique'] ) ? $_REQUEST['unique'] : 'la_options';
		header('Content-Type: plain/text');
		header('Content-disposition: attachment; filename=backup-' . esc_attr( $unique ) . '-' . gmdate( 'd-m-Y' ) . '.txt' );
		header('Content-Transfer-Encoding: binary');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo wp_json_encode( get_option( $unique ) );
		die();
	}

}
