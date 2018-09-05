<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'autocomplete',
        'heading' => __( 'Categories', 'nova' ),
        'param_name' => 'ids',
        'settings' => array(
            'multiple' => true,
            'sortable' => true,
        ),
        'save_always' => true,
        'description' => __( 'List of product categories', 'nova' ),
    ),
    array(
        'type' => 'nova_number',
        'heading' => __( 'Maximum Category will be displayed', 'nova' ),
        'param_name' => 'number',
        'description' => __( 'The `number` field is used to display the number of products.', 'nova' ),
        'min' => 0,
        'max' => 50,
        'default' => 0
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Order by', 'nova' ),
        'param_name' => 'orderby',
        'value' => array(
            '',
            __( 'Date', 'nova' ) => 'date',
            __( 'ID', 'nova' ) => 'ID',
            __( 'Menu order', 'nova' ) => 'menu_order',
            __( 'Random', 'nova' ) => 'rand',
            __( 'Popularity', 'nova' ) => 'popularity',
            __( 'Rating', 'nova' ) => 'rating',
            __( 'Title', 'nova' ) => 'title'
        ),
        'save_always' => true,
        'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'nova' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Sort order', 'nova' ),
        'param_name' => 'order',
        'value' => array(
            '',
            __( 'Descending', 'nova' ) => 'DESC',
            __( 'Ascending', 'nova' ) => 'ASC',
        ),
        'save_always' => true,
        'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'nova' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
    ),
    array(
        'type' => 'checkbox',
        'heading' => __( 'Hide Empty', 'nova' ),
        'param_name' => 'hide_empty',
        'value' => array( __( 'Yes', 'nova') => '1' ),
    ),
    Novaworks_Shortcodes_Helper::fieldColumn(array(
        'heading' 		=> __('Items to show', 'nova'),
        'param_name' 	=> 'columns'
    )),

    Novaworks_Shortcodes_Helper::getParamItemSpace(array(
        'std' => 'default'
    )),

    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'style',
        'value' => array(
            __('Design 01','nova') => '1'
        ),
        'default' => '1'
    ),
    array(
        'type'       => 'checkbox',
        'heading'    => __('Enable slider', 'nova' ),
        'param_name' => 'enable_carousel',
        'value'      => array( __( 'Yes', 'nova' ) => 'yes' )
    ),
    Novaworks_Shortcodes_Helper::fieldExtraClass()
);

$carousel = Novaworks_Shortcodes_Helper::paramCarouselShortCode(false);
$slides_column_idx = Novaworks_Shortcodes_Helper::getParamIndex( $carousel, 'slides_column');
if($slides_column_idx){
    unset($carousel[$slides_column_idx]);
}
$shortcode_params = array_merge( $shortcode_params, $carousel);

return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Product Categories', 'nova'),
        'base'			=> 'product_categories',
        'icon'          => 'icon-wpb-woocommerce',
        'category'  	=> __('La Studio', 'nova'),
        'description' 	=> __('Display categories','nova'),
        'params' 		=> $shortcode_params
    ),
    'product_categories'
);