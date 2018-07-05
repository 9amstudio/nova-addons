<?php
/**
 * Plugin Name: Nova Addons
 * Plugin URI: http://uix.store/nova
 * Description: A collection of extra elements for Visual Composer. It was made for Nova premium theme and requires Nova theme installed in order to work properly.
 * Author: UIX Themes
 * Author URI: http://uix.store
 * Version: 1.3.9
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
	/**
	 * Constructor function.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
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
	 * Load files
	 */
	public function includes() {
		include_once( NOVA_ADDONS_DIR . 'includes/update.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/import.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/user.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/portfolio.php' );
		include_once( NOVA_ADDONS_DIR . 'includes/class-nova-vc.php' );
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
		add_action( 'init', array( 'Nova_Shortcodes', 'init' ), 50 );
		add_action( 'init', array( $this, 'update' ) );

		add_action( 'init', array( 'Nova_Addons_Portfolio', 'init' ) );
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

	/**
	 * Check for update
	 */
	public function update() {
		// set auto-update params
		$plugin_current_version = NOVA_ADDONS_VER;
		$plugin_remote_path     = 'http://update.uix.store';
        $plugin_slug            = plugin_basename( __FILE__ );
        $license_user           = '';
        $license_key            = '';

		new Nova_Addons_AutoUpdate( $plugin_current_version, $plugin_remote_path, $plugin_slug );
	}
}

new Nova_Addons();
