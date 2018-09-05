<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'autocomplete',
        'heading' => __( 'Select identificator', 'nova' ),
        'param_name' => 'id',
        'description' => __( 'Input product ID or product SKU or product title to see suggestions', 'nova' ),
    ),
    array(
        'type' => 'hidden',
        'param_name' => 'sku',
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Layout','nova'),
        'param_name' => 'layout',
        'value' => array(
            __('List','nova') => 'list',
            __('Grid','nova') => 'grid'
        ),
        'std' => 'grid'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'list_style',
        'value' => la_get_product_list_style(),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'list'
        ),
        'std' => 'default'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'grid_style',
        'value' => la_get_product_grid_style(),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'grid'
        ),
        'std' => '1'
    ),
    array(
        'type' 			=> 'checkbox',
        'heading' 		=> __( 'Enable Custom Image Size', 'nova' ),
        'param_name' 	=> 'enable_custom_image_size',
        'value' 		=> array( __( 'Yes', 'nova' ) => 'yes' ),
    ),
    array(
        'type' 			=> 'checkbox',
        'heading' 		=> __( 'Disable alternative image ', 'nova' ),
        'param_name' 	=> 'disable_alt_image',
        'value' 		=> array( __( 'Yes', 'nova' ) => 'yes' ),
    ),
    Novaworks_Shortcodes_Helper::fieldImageSize(array(
        'value'			=> 'shop_catalog',
        'dependency' => array(
            'element'   => 'enable_custom_image_size',
            'value'     => 'yes'
        )
    )),
    array(
        'type' => 'checkbox',
        'heading' => __( 'Enable Ajax Loading', 'nova' ),
        'param_name' => 'enable_ajax_loader',
        'value' => array( __( 'Yes', 'nova' ) => 'yes' ),
    ),
    Novaworks_Shortcodes_Helper::fieldExtraClass()
);


return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Product', 'nova'),
        'base'			=> 'product',
        'icon'          => 'icon-wpb-woocommerce',
        'category'  	=> __('La Studio', 'nova'),
        'description' 	=> __('Show a single product by ID or SKU.','nova'),
        'params' 		=> $shortcode_params
    ),
    'product'
);