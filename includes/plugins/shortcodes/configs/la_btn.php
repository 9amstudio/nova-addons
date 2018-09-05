<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'textfield',
        'heading' => __( 'Text', 'nova' ),
        'param_name' => 'title',
        'value' => __( 'Text on the button', 'nova' ),
        'admin_label' => true
    ),
    array(
        'type' => 'vc_link',
        'heading' => __( 'URL (Link)', 'nova' ),
        'param_name' => 'link',
        'description' => __( 'Add link to button.', 'nova' )
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Style', 'nova' ),
        'description' => __( 'Select button display style.', 'nova' ),
        'param_name' => 'style',
        'value' => array(
            __( 'Flat', 'nova' ) => 'flat',
            __( 'Outline', 'nova' ) => 'outline'
        )
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Border width', 'nova' ),
        'description' => __( 'Select border width.', 'nova' ),
        'param_name' => 'border_width',
        'value' => array(
            __( '1px', 'nova' ) => '1',
            __( '2px', 'nova' ) => '2',
            __( '3px', 'nova' ) => '3'
        ),
        'dependency' => array(
            'element' => 'style',
            'value' => 'outline'
        ),
        'std' => '1'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Shape', 'nova' ),
        'description' => __( 'Select button shape.', 'nova' ),
        'param_name' => 'shape',
        'value' => array(
            __( 'Rounded', 'nova' ) => 'rounded',
            __( 'Square', 'nova' ) => 'square',
            __( 'Round', 'nova' ) => 'round'
        ),
        'std' => 'square'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Color', 'nova' ),
        'param_name' => 'color',
        'description' => __( 'Select button color.', 'nova' ),
        'value' => array(
                __( 'Black', 'nova' ) => 'black',
                __( 'Primary', 'nova' ) => 'primary',
                __( 'Three', 'nova' ) => 'three',
                __( 'White', 'nova' ) => 'white',
                __( 'White 2', 'nova' ) => 'white2',
                __( 'Gray', 'nova' ) => 'gray'
        ),
        'std' => 'black'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Size', 'nova' ),
        'param_name' => 'size',
        'description' => __( 'Select button display size.', 'nova' ),
        'std' => 'md',
        'value' => array(
            'Mini' => 'xs',
            'Small' => 'sm',
            'Normal' => 'md',
            'Large' => 'lg',
        )
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Alignment', 'nova' ),
        'param_name' => 'align',
        'description' => __( 'Select button alignment.', 'nova' ),
        'value' => array(
            __( 'Inline', 'nova' ) => 'inline',
            __( 'Left', 'nova' ) => 'left',
            __( 'Right', 'nova' ) => 'right',
            __( 'Center', 'nova' ) => 'center',
        ),
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Extra class name', 'nova' ),
        'param_name' => 'el_class',
        'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'nova' ),
    ),
);


return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('La Button', 'nova'),
        'base'			=> 'la_btn',
        'icon'          => 'icon-wpb-ui-button',
        'category'  	=> __('La Studio', 'nova'),
        'description'   => __('Eye catching button', 'nova'),
        'params' 		=> $shortcode_params
    ),
    'la_btn'
);