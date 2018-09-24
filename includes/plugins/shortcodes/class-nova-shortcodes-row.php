<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class Novaworks_Shortcodes_Row {

    public static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'vc_after_init', array( $this, 'vc_after_init' ) );
        add_filter( 'vc_shortcode_output',array( $this, 'modify_vc_row_output' ), 10, 3 );
    }
    public function vc_after_init() {
        $group_name = esc_html__( 'Background Gradient', 'nova' );

        vc_add_param( 'vc_row', array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Use Background Gradient',  'nova' ),
            'param_name' => 'use_grad',
            'value' => array( esc_html__( 'Yes', 'nova' ) => 'yes' ),
            'group' => $group_name,
        ) );

        vc_add_param( 'vc_row', array(
            'type' => 'gradient',
            'heading' => esc_html__('Gradient Type',  'nova'),
            'param_name' => 'bg_grad',
            'description' => esc_html__('At least two color points should be selected.', 'nova'),
            'dependency' => array( 'element' => 'use_grad', 'value' => array( 'yes' ) ),
            'group' => $group_name,
        ) );
    }

    public function modify_vc_row_output( $output, $obj, $attr ) {
        if( $obj->settings( 'base' ) == 'vc_row' ) {
            $output = $this->output( $attr, '' ) . $output;
        }
        return $output;
    }

    public static function output( $atts, $content ) {
        $output = $use_grad = $bg_grad = '';
        extract( shortcode_atts( array(
            'use_grad'  => '',
            'bg_grad'   => ''
        ), $atts ) );

        if( empty( $use_grad ) ) {
            return $output;
        }
        if( empty( $bg_grad ) ){
            return $output;
        }

        $output = '<div class="js-el la_row_grad" data-la_component="GradientBackground" data-grad="' . $bg_grad . '" ></div>';
        return $output;
    }

}