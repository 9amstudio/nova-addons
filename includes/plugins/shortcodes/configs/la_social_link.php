<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'style',
        'value' => array(
            __('Default','nova') => 'default',
            __('Circle','nova') => 'circle',
            __('Square','nova') => 'square',
            __('Round','nova') => 'round',
        ),
        'default' => 'default'
    ),
    Novaworks_Shortcodes_Helper::fieldExtraClass(),
);

return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Social Media Link', 'nova'),
        'base'			=> 'la_social_link',
        'icon'          => 'la_social_link fa fa-share-alt',
        'category'  	=> __('La Studio', 'nova'),
        'description' 	=> __('Display Social Media Link.','nova'),
        'params' 		=> $shortcode_params
    ),
    'la_social_link'
);