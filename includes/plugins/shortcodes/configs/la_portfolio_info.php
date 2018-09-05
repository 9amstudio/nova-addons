<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'checkbox',
        'heading' => __('Select element to appear', 'nova'),
        'param_name' => 'advanced_opts',
        'value' => array(
            __('Tags','nova') => 'tag',
            __('Client','nova') => 'client',
            __('Category','nova') => 'category',
            __('Date','nova') => 'date',
            __('Share','nova') => 'share'
        )
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Label', 'nova'),
        'param_name' 	=> 'tag_label',
        'value' 		=> 'Tags',
        'group' 		=> 'Tags'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Label', 'nova'),
        'param_name' 	=> 'client_label',
        'group' 		=> 'Client',
        'value' 		=> 'Client'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Value', 'nova'),
        'param_name' 	=> 'client_value',
        'group' 		=> 'Client'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Label', 'nova'),
        'param_name' 	=> 'category_label',
        'group' 		=> 'Category',
        'value' 		=> 'Category'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Label', 'nova'),
        'param_name' 	=> 'date_label',
        'group' 		=> 'Date',
        'value' 		=> 'Date'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Value', 'nova'),
        'param_name' 	=> 'date_value',
        'group' 		=> 'Date'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Label', 'nova'),
        'param_name' 	=> 'share_label',
        'group' 		=> 'Share',
        'value' 		=> 'SHARE'
    ),
    Novaworks_Shortcodes_Helper::fieldExtraClass()
);

return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Portfolio Information', 'nova'),
        'base'			=> 'la_portfolio_info',
        'icon'          => 'nova-wpb-icon la_portfolio_info',
        'category'  	=> __('La Studio', 'nova'),
        'description' 	=> __('Display portfolio information with la-studio themes style.','nova'),
        'params' 		=> $shortcode_params
    ),
    'la_portfolio_info'
);