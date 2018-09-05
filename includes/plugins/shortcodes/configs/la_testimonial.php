<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'dropdown',
        'heading' => __('Design','nova'),
        'param_name' => 'style',
        'value' => array(
            __('Style 01','nova') => '1',
            __('Style 02','nova') => '2',
            __('Style 03','nova') => '3',
            __('Style 04','nova') => '4'
        ),
        'std' => '1',
        'admin_label' => true
    ),
    array(
        'type'       => 'autocomplete',
        'heading'    => __( 'Choose member', 'nova' ),
        'param_name' => 'ids',
        'settings'   => array(
            'unique_values'  => true,
            'multiple'       => true,
            'sortable'       => true,
            'groups'         => false,
            'min_length'     => 1,
            'auto_focus'     => true,
            'display_inline' => true
        ),
    ),
    array(
        'type'       => 'checkbox',
        'heading'    => __('Enable slider', 'nova' ),
        'param_name' => 'enable_carousel',
        'value'      => array( __( 'Yes', 'nova' ) => 'yes' )
    ),
    Novaworks_Shortcodes_Helper::fieldColumn(array(
        'heading' 		=> __('Items to show', 'nova')
    )),
    Novaworks_Shortcodes_Helper::getParamItemSpace(array(
        'std' => 'default'
    )),
    Novaworks_Shortcodes_Helper::fieldElementID(array(
        'param_name' 	=> 'el_id'
    )),
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
        'name'			=> __('Testimonials', 'nova'),
        'base'			=> 'la_testimonial',
        'icon'          => 'nova-wpb-icon la_testimonial',
        'category'  	=> __('La Studio', 'nova'),
        'description' 	=> __('Display the testimonial','nova'),
        'params' 		=> $shortcode_params
    ),
    'la_testimonial'
);