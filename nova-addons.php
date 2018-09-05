<?php
/**
 * Plugin Name: Nova Addons
 * Plugin URI: http://nineamstudio.com/nova-addons
 * Description: A collection of extra elements for Visual Composer. It was made for Nova premium theme and requires Nova theme installed in order to work properly.
 * Author: 9AM Studio
 * Author URI: http://nineamstudio.com
 * Version: 0.1.0
 * Text Domain: nova
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Nova_Addons
 */
class Nova_Addons {

	protected $loader;
	/**
	 * Constructor function.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->init();
	}

	/**
	 * Defines constants
	 */
	public function define_constants() {
		define( 'NOVA_ADDONS_VER', '0.1.0' );
		define( 'NOVA_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
		define( 'NOVA_ADDONS_URL', plugin_dir_url( __FILE__ ) );
	}
	/**
	 * Defines admin hook
	 */
	public function define_admin_hooks() {
		$plugin_admin = new Novaworks_Admin();
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ), 999 );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ), 999 );
	}
	/**
	 * Defines pubic hook
	 */
	private function define_public_hooks() {
		$plugin_public = new Novaworks_Public();
		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_styles' ));
		add_filter( 'vc_enqueue_font_icon_element', array( $plugin_public, 'add_fonts_to_visual_composer' ) );

	}
	/**
	 * Load files
	 */
	public function includes() {
		include_once( NOVA_ADDONS_DIR . 'admin/class-nova-admin.php' );
		include_once( NOVA_ADDONS_DIR . 'public/nova-functions.php' );
		include_once( NOVA_ADDONS_DIR . 'public/class-nova-public.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/import.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/user.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/portfolio.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/team.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/class-nova-vc.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/plugins/shortcodes/class-nova-shortcodes.php');
		include_once( NOVA_ADDONS_DIR . 'includes/shortcodes/class-nova-shortcodes-row.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/shortcodes/class-nova-shortcodes.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/shortcodes/class-nova-banner.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/shortcodes/class-nova-banner-grid.php' );
		WPBakeryShortCode_Nova_Row::get_instance();
	}
	/**
	 * Initialize
	 */
	public function init() {
		add_action( 'admin_notices', array( $this, 'check_dependencies' ) );

		load_plugin_textdomain( 'nova', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

		add_action( 'vc_after_init', array( 'Nova_Addons_VC', 'init' ), 50 );

		$shortcode_extension = new Novaworks_Shortcodes();

		add_action( 'init', array( 'Nova_Shortcodes', 'init' ), 50 );

		add_action( 'init', array( $shortcode_extension, 'create_shortcode' ), 8 );
		add_action( 'init', array( $shortcode_extension, 'load_dependencies' ), 8 );
		add_action( 'init', array( $shortcode_extension, 'remove_old_woocommerce_shortcode' ), 20 );

		add_action( 'wp_ajax_la_get_shortcode_loader_by_ajax', array( $shortcode_extension, 'ajax_render_shortcode' ) );
		add_action( 'wp_ajax_nopriv_la_get_shortcode_loader_by_ajax', array( $shortcode_extension, 'ajax_render_shortcode' ) );

		add_action( 'vc_after_init', array( $shortcode_extension, 'vc_after_init' ) );
		add_action( 'vc_param_animation_style_list', array( $shortcode_extension, 'vc_param_animation_style_list' ) );
		add_filter( 'vc_iconpicker-type-nova_icon_outline', array( $shortcode_extension, 'get_nova_icon_outline_font_icon' ) );
		add_filter( 'vc_iconpicker-type-nucleo_glyph', array( $shortcode_extension, 'get_nucleo_glyph_font_icon' ) );

		add_action( 'init', array( 'Nova_Addons_Portfolio', 'init' ) );
		add_action( 'init', array( 'Nova_Team_Member', 'init' ) );
	}
	/**
	 * Check plugin dependencies
	 * Check if Visual Composer plugin is installed
	 */
	public function check_dependencies() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			$plugin_data = get_plugin_data( __FILE__ );

			printf(
				'<div class="updated"><p>%s</p></div>',
				sprintf(
					__( '<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'nova' ),
					$plugin_data['Name']
				)
			);
		}
	}
}
new Nova_Addons();
