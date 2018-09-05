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
        'description' => __( 'Input block ID or block title to see suggestions', 'nova' ),
    ),
    array(
        'type' => 'hidden',
        'param_name' => 'name',
    ),
    Novaworks_Shortcodes_Helper::fieldExtraClass(),
);


return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('La Custom Block', 'nova'),
        'base'			=> 'la_block',
        'icon'          => 'nova-wpb-icon la_block',
        'category'  	=> __('La Studio', 'nova'),
        'description'   => __('Displays the custom block', 'nova'),
        'params' 		=> $shortcode_params
    ),
    'la_block'
);