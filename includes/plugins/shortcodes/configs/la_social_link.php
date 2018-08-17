<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'dropdown',
        'heading' => __('Style','lastudio'),
        'param_name' => 'style',
        'value' => array(
            __('Default','lastudio') => 'default',
            __('Circle','lastudio') => 'circle',
            __('Square','lastudio') => 'square',
            __('Round','lastudio') => 'round',
        ),
        'default' => 'default'
    ),
    LaStudio_Shortcodes_Helper::fieldExtraClass(),
);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Social Media Link', 'lastudio'),
        'base'			=> 'la_social_link',
        'icon'          => 'la_social_link fa fa-share-alt',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('Display Social Media Link.','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'la_social_link'
);