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
            __('Style 1','nova') => '1',
            __('Style 2','nova') => '2',
            __('Style 3','nova') => '3',
            __('Style 4','nova') => '4',
            __('Style 5','nova') => '5',
            __('Style 6','nova') => '6',
            __('Style 7','nova') => '7',
            __('Style 8','nova') => '8',
            __('Style 9','nova') => '9'
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
        'heading' => __( 'Banner Title 1', 'nova' ),
        'param_name' => 'title_1',
        'admin_label' => true
    ),

    array(
        'type' => 'textfield',
        'heading' => __( 'Banner Title 2', 'nova' ),
        'param_name' => 'title_2',
        'admin_label' => true,
        'dependency' => array(
            'element' => 'style',
            'value' => array('1','2','5', '6', '7', '8', '9')
        ),
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Banner Title 3', 'nova' ),
        'param_name' => 'title_3',
        'admin_label' => true,
        'dependency' => array(
            'element' => 'style',
            'value' => array('2','9')
        ),
    ),

    Novaworks_Shortcodes_Helper::fieldElementID(array(
        'param_name' 	=> 'el_id'
    )),

    Novaworks_Shortcodes_Helper::fieldExtraClass(),
    Novaworks_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra class name for title 1', 'nova'),
        'param_name' 	=> 'el_class1',
    )),
    Novaworks_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra class name for title 2', 'nova'),
        'param_name' 	=> 'el_class2',
        'dependency' => array(
            'element' => 'style',
            'value' => array('1','2','5', '6', '7', '8', '9')
        )
    )),
    Novaworks_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra class name for title 3', 'nova'),
        'param_name' 	=> 'el_class3',
        'dependency' => array(
            'element' => 'style',
            'value' => array('2','9')
        )
    )),
    array(
        'type' 			=> 'colorpicker',
        'param_name' 	=> 'overlay_bg_color',
        'heading' 		=> __('Overlay background color', 'nova'),
        'group' 		=> 'Design'
    )
);

$param_fonts_title1 = Novaworks_Shortcodes_Helper::fieldTitleGFont('title_1', __('Title 1', 'nova'));
$param_fonts_title2 = Novaworks_Shortcodes_Helper::fieldTitleGFont('title_2', __('Title 2', 'nova'), array(
    'element' => 'style',
    'value' => array('1','2','5', '6', '7', '8', '9')
));
$param_fonts_title3 = Novaworks_Shortcodes_Helper::fieldTitleGFont('title_3', __('Title 3', 'nova'), array(
    'element' => 'style',
    'value' => array('2','9')
));


$shortcode_params = array_merge( $shortcode_params, $param_fonts_title1, $param_fonts_title2, $param_fonts_title3);

return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Banner Box', 'nova'),
        'base'			=> 'la_banner',
        'icon'          => 'nova-wpb-icon la_banner',
        'category'  	=> __('La Studio', 'nova'),
        'description'   => __('Displays the banner image with Information', 'nova'),
        'params' 		=> $shortcode_params
    ),
    'la_banner'
);