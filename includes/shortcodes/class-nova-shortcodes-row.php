<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class WPBakeryShortCode_Nova_Row{

    public static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        add_action('vc_after_init', array( $this, 'vc_after_init' ) );
    }
    public function vc_after_init(){
        $group_name = __('Burger Header', 'nova');

        vc_add_param('vc_row',array(
            'type' => 'dropdown',
            'heading' => __('Header Preset',  'nova'),
            'param_name' => 'burder_header_preset',
            'value'       => array(
  						esc_html__( 'Default', 'nova' ) => '',
  						esc_html__( 'Dark', 'nova' ) => 'dark',
  						esc_html__( 'Light', 'nova' ) => 'light',
  					),
            'group' => $group_name,
        ));
    }
}
