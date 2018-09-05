<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type'          => 'dropdown',
        'save_always'   => true,
        'heading'       => __('Position', 'nova'),
        'param_name'    => 'position',
        'value'         => array(
            __('Top', 'nova')      => 'top',
            __('Right', 'nova')    => 'right',
            __('Bottom', 'nova')   => 'bottom',
            __('Left', 'nova')     => 'left'
        )
    ),
    array(
        'type'          => 'textfield',
        'heading'       => __('Left', 'nova'),
        'param_name'    => 'left'
    ),
    array(
        'type'          => 'textfield',
        'heading'       => __('Top', 'nova'),
        'param_name'    => 'top'
    ),
    array(
        'type'          => 'textarea_html',
        'heading'       => __('Content', 'nova'),
        'param_name'    => 'content'
    )
);


return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'                      => __('LA Hotspot', 'nova'),
        'base'                      => 'la_hotspot',
        'allowed_container_element' => 'vc_row',
        'content_element'           => false,
        'params' 		            => $shortcode_params
    ),
    'la_hotspot'
);