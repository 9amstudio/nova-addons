<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Novaworks
 * @subpackage Novaworks/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Novaworks
 * @subpackage Novaworks/public
 * @author     Your Name <email@example.com>
 */
class Novaworks_Public {

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		wp_register_style( 'nova-icons', plugin_dir_url( __FILE__ ) . 'css/nova-icons.css', array(), null );
		wp_register_style( 'font-nucleo-glyph', plugin_dir_url( __FILE__ ) . 'css/font-nucleo-glyph.min.css', array(), null );

		if( wp_style_is( 'font-awesome', 'registered' ) ) {
			wp_deregister_style( 'font-awesome' );
		}
		if( wp_style_is( 'animate-css', 'registered' ) ) {
			wp_deregister_style( 'animate-css' );
		}

	}
	public function add_fonts_to_visual_composer( $font_name ) {

		global $nova_external_icon_font;

		if( ! is_array( $nova_external_icon_font ) ) {
			$nova_external_icon_font = array();
		}

		$asset_font_without_domain = apply_filters( 'Novaworks/filter/assets_font_url', untrailingslashit( plugin_dir_url( dirname(__FILE__) ) ) );

		$font_face_html = '';

		if( 'nova_icon_outline' == $font_name ) {
			wp_enqueue_style( 'nova-icons' );
			if( ! isset( $nova_external_icon_font[$font_name] ) ) {
				$font_face_html .= "@font-face {
					font-family: 'Nova-Icons';
					src:  url('{$asset_font_without_domain}/public/fonts/Nova-Icons.eot?sxsdz3');
					src:  url('{$asset_font_without_domain}/public/fonts/Nova-Icons.eot?sxsdz3#iefix') format('embedded-opentype'),
						url('{$asset_font_without_domain}/public/fonts/Nova-Icons.ttf?sxsdz3') format('truetype'),
						url('{$asset_font_without_domain}/public/fonts/Nova-Icons.woff?sxsdz3') format('woff'),
						url('{$asset_font_without_domain}/public/fonts/Nova-Icons.svg?sxsdz3#Nova-Icons') format('svg');
					font-weight: normal;
					font-style: normal;
				}";
				$nova_external_icon_font[$font_name] = $font_name;
			}
		}
		if( 'nucleo_glyph' == $font_name ) {
			wp_enqueue_style( 'font-nucleo-glyph' );
			if( ! isset( $nova_external_icon_font[$font_name] ) ) {
				$font_face_html .= "@font-face {
					font-family: 'Nucleo Glyph';
					src: url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.eot');
					src: url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.eot') format('embedded-opentype'),
					url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.woff2') format('woff2'),
					url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.woff') format('woff'),
					url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.ttf') format('truetype'),
					url('{$asset_font_without_domain}/public/fonts/nucleo-glyph.svg') format('svg');
					font-weight: 400;
					font-style: normal
				}";
				$nova_external_icon_font[$font_name] = $font_name;
			}
		}
		if( ! empty( $font_face_html ) ) {
			printf(
				'<span data-nova_component="InsertCustomCSS" class="js-el hidden">%s</span>',
				$font_face_html
			);
		}
	}
}
