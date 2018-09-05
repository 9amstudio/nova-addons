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
            __('Style 01','nova') => '1'
        ),
        'std' => '1'
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
        'type' => 'nova_number',
        'heading' => __('Total items', 'nova'),
        'description' => __('Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'nova'),
        'param_name' => 'per_page',
        'value' => 4,
        'min' => -1,
        'max' => 1000
    ),
    Novaworks_Shortcodes_Helper::fieldColumnGrid(array(
        'heading' 		=> __('Items per row', 'nova'),
        'media'			=> array(
            'lg'	=> 2,
            'md'	=> 2,
            'mb'  => 1
        )
    )),
    Novaworks_Shortcodes_Helper::fieldElementID(array(
        'param_name' 	=> 'el_id'
    )),
    Novaworks_Shortcodes_Helper::fieldExtraClass()
);

return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Team Member', 'nova'),
        'base'			=> 'nova_team_member',
        'icon'          => 'nova-wpb-icon nova_team_member',
        'category'  	=> __('9AM Studio', 'nova'),
        'description' 	=> __('Display the team member','nova'),
        'params' 		=> $shortcode_params
    ),
    'nova_team_member'
);
