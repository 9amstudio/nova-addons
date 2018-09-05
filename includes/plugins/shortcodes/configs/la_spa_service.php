<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'attach_image',
        'heading' => __('Upload the banner image', 'nova'),
        'param_name' => 'banner_id'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Design','nova'),
        'param_name' => 'style',
        'value' => array(
            __('Default','nova') => '1',
            __('Style 02','nova') => '2',
        ),
        'std' => '1'
    ),

    array(
        'type' => 'vc_link',
        'heading' => __('Banner Link', 'nova'),
        'param_name' => 'banner_link',
        'description' => __('Add link / select existing page to link to this banner', 'nova')
    ),


    array(
        'type' => 'textfield',
        'heading' => __( 'Title', 'nova' ),
        'param_name' => 'title',
        'admin_label' => true,
        'dependency'    => array(
            'element' => 'style',
            'value'   => array('1')
        )
    ),

    array(
        'type' => 'textarea',
        'heading' => __( 'Content', 'nova' ),
        'param_name' => 'content',
        'admin_label' => true
    ),

    Novaworks_Shortcodes_Helper::fieldElementID(array(
        'param_name' 	=> 'el_id'
    )),
    Novaworks_Shortcodes_Helper::fieldExtraClass(),
    Novaworks_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra class name for title', 'nova'),
        'param_name' 	=> 'el_class1',
        'dependency'    => array(
            'element' => 'style',
            'value'   => array('1')
        )
    )),
    Novaworks_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra class name for content', 'nova'),
        'param_name' 	=> 'el_class2'
    )),

    array(
        'type' 			=> 'colorpicker',
        'param_name' 	=> 'bg_color',
        'heading' 		=> __('Background Color', 'nova'),
        'group' 		=> __('Design', 'nova'),
        'dependency'    => array (
            'element' => 'style',
            'value'   => array('1')
        )
    )
);

$param_fonts_title1 = Novaworks_Shortcodes_Helper::fieldTitleGFont('title', __('Title', 'nova'), array(
    'element' => 'style',
    'value'   => array('1')
));
$param_fonts_title2 = Novaworks_Shortcodes_Helper::fieldTitleGFont('content', __('Content', 'nova'));


$shortcode_params = array_merge( $shortcode_params, $param_fonts_title1, $param_fonts_title2);

return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Spa Service', 'nova'),
        'base'			=> 'la_spa_service',
        'icon'          => 'nova-wpb-icon la_spa_service',
        'category'  	=> __('La Studio', 'nova'),
        'description'   => __('Displays the banner image with information', 'nova'),
        'params' 		=> $shortcode_params
    ),
    'la_spa_service'
);