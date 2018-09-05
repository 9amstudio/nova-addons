<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type'          => 'attach_image',
        'heading'       => __('Image', 'nova'),
        'param_name'    => 'image',
        'description'   => __('Choose your image that will show the hotspots. <br/> You can then click on the image in the preview area to add your hotspots in the desired locations.', 'nova')
    ),
    array(
        'type'          => 'la_hotspot_image_preview',
        'heading'       => __('Preview', 'nova'),
        'param_name'    => 'preview',
        'description'   => __("Click to add - Drag to move - Edit content below<br/> Note: this preview will not reflect hotspot style choices or show tooltips. <br/>This is only used as a visual guide for positioning.", 'nova')
    ),
    array(
        'type'          => 'textarea_html',
        'heading'       => __('Hotspots', 'nova'),
        'param_name'    => 'content'
    ),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Extra Class name', 'nova'),
        'group'         => __('Style', 'nova'),
        'param_name' 	=> 'el_class',
        'description' 	=> __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nova')
    ),
    array(
        'type'          => 'dropdown',
        'save_always'   => true,
        'group'         => __('Style', 'nova'),
        'heading'       => __('Color', 'nova'),
        'admin_label'   => true,
        'param_name'    => 'color',
        'default'       => 'primary',
        'description'   => __('Choose the color which the hotspot will use', 'nova'),
        'value'         => array(
            'Primary' => 'primary',
            'Secondary' => 'secondary',
            'Blue' => 'blue',
            'Turquoise' => 'turquoise',
            'Pink' => 'pink',
            'Violet' => 'violet',
            'Peacoc' => 'peacoc',
            'Chino' => 'chino',
            'Mulled Wine' => 'mulled_wine',
            'Vista Blue' => 'vista_blue',
            'Black' => 'black',
            'Grey' => 'grey',
            'Orange' => 'orange',
            'Sky' => 'sky',
            'Green' => 'green',
            'Juicy pink' => 'juicy_pink',
            'Sandy brown' => 'sandy_brown',
            'Purple' => 'purple',
            'White' => 'white'
        )
    ),
    array(
        'type'          => 'dropdown',
        'save_always'   => true,
        'group'         => __('Style', 'nova'),
        'heading'       => __('Hotspot Icon', 'nova'),
        'description'   => __('The icon that will be shown on the hotspots', 'nova'),
        'param_name'    => 'hotspot_icon',
        'admin_label'   => true,
        'value'         => array(
            __('Plus Sign', 'nova') => 'plus_sign',
            __('Numerical', 'nova') => 'numerical'
        )
    ),
    array(
        'type'          => 'dropdown',
        'save_always'   => true,
        'group'         => __('Style', 'nova'),
        'heading'       => __('Tooltip Functionality', 'nova'),
        'param_name'    => 'tooltip',
        'description'   => __('Select how you want your tooltips to display to the user', 'nova'),
        'value'         => array(
            __('Show On Hover', 'nova')    => 'hover',
            __('Show On Click', 'nova')    => 'click',
            __('Always Show', 'nova')      => 'always_show'
        )
    ),
    array(
        'type'          => 'dropdown',
        'save_always'   => true,
        'group'         => __('Style', 'nova'),
        'heading'       => __('Tooltip Shadow', 'nova'),
        'param_name'    => 'tooltip_shadow',
        'description'   => __('Select the shadow size for your tooltip', 'nova'),
        'value'         => array(
            __('None', 'nova')         => 'none',
            __('Small Depth', 'nova')  => 'small_depth',
            __('Medium Depth', 'nova') => 'medium_depth',
            __('Large Depth', 'nova')  => 'large_depth'
        )
    ),
    array(
        'type'          => 'checkbox',
        'heading'       => __('Enable Animation', 'nova'),
        'param_name'    => 'animation',
        'group'         => __('Style', 'nova'),
        'description'   => __('Turning this on will make your hotspots animate in when the user scrolls to the element', 'nova'),
        'value'         => array(
            __('Yes, please', 'nova') => 'true'
        )
    )
);


return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Image With Hotspots', 'nova'),
        'base'			=> 'la_image_with_hotspots',
        'icon'          => 'icon-wpb-single-image',
        'category'  	=> __('La Studio', 'nova'),
        'description'   => __('Add Hotspots On Your Image', 'nova'),
        'params' 		=> $shortcode_params
    ),
    'la_image_with_hotspots'
);