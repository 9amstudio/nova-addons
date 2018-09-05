<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' 			=> 'nova_column',
        'heading' 		=> __('Space Height', 'nova'),
        'admin_label'   => true,
        'param_name' 	=> 'height',
        'unit'			=> 'px',
        'media'			=> array(
            'xlg'	=> '',
            'lg'	=> '',
            'md'	=> '',
            'sm'	=> '',
            'xs'	=> '',
            'mb'	=> ''
        )
    ),
    Novaworks_Shortcodes_Helper::fieldExtraClass()
);

return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Content Space', 'nova'),
        'base'			=> 'nova_divider',
        'icon'          => 'nova-wpb-icon nova_divider',
        'category'  	=> __('9AM Studio', 'nova'),
        'description' 	=> __('Blank space with custom height.','nova'),
        'params' 		=> $shortcode_params
    ),
    'nova_divider'
);
