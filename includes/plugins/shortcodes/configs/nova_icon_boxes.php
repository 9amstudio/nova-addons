<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


$icon_type = Novaworks_Shortcodes_Helper::fieldIconType();
$icon_type[0]['value'][__( 'Custom Number', 'nova') ] = 'number';
$icon_type[] = array(
	'type' => 'textfield',
	'heading' => __('Enter the number', 'nova'),
	'param_name' => 'custom_number',
	'dependency' => array(
		'element' => 'icon_type',
		'value' => 'number'
	)
);

$shortcode_params = array(
	array(
		'type' => 'textfield',
		'heading' => __('Heading', 'nova'),
		'param_name' => 'title',
		'admin_label' => true,
		'description' => __('Provide the title for this icon boxes.', 'nova'),
	),
	array(
		'type' => 'textarea_html',
		'heading' => __('Description', 'nova'),
		'param_name' => 'content',
		'description' => __('Provide the description for this icon box.', 'nova'),
	),
	// Select link option - to box or with read more text
	array(
		'type' 			=> 'dropdown',
		'heading' 		=> __('Apply link to:', 'nova'),
		'param_name' 	=> 'read_more',
		'value' 		=> array(
			__('No Link','nova') => 'none',
			__('Complete Box','nova') => 'box',
			__('Box Title','nova') => 'title',
			__('Icon','nova') => 'icon'
		)
	),
	// Add link to existing content or to another resource
	array(
		'type' 			=> 'vc_link',
		'heading' 		=> __('Add Link', 'nova'),
		'param_name' 	=> 'link',
		'description' 	=> __('Add a custom link or select existing page. You can remove existing link as well.', 'nova'),
		'dependency' 	=> array(
			'element' 	=> 'read_more',
			'value' 	=> array('box','title','icon')
		)
	),
	array(
		'type'	=> 'dropdown',
		'heading'	=> __('Icon Position', 'nova'),
		'param_name' => 'icon_pos',
		'value'	=> array(
			__('Icon at left with heading', 'nova') => 'default',
			__('Icon at Right with heading', 'nova') => 'heading-right',
			__('Icon at Left', 'nova') => 'left',
			__('Icon at Right', 'nova') => 'right',
			__('Icon at Top', 'nova') => 'top',
		),
		'std' => 'default',
		'description' => __('Select icon position. Icon box style will be changed according to the icon position.', 'nova'),
		'group' => __('Icon Settings', 'nova')
	),

	array(
		'type' => 'dropdown',
		'heading' => __('Icon Styles', 'nova'),
		'param_name' => 'icon_style',
		'description' => __('We have given four quick preset if you are in a hurry. Otherwise, create your own with various options.', 'nova'),
		'std'	=> 'simple',
		'value' => array(
			__('Simple', 'nova') => 'simple',
			__('Circle Background', 'nova') => 'circle',
			__('Square Background', 'nova') => 'square',
			__('Round Background', 'nova') => 'round',
			__('Advanced', 'nova') => 'advanced',
		),
		'group' => __('Icon Settings', 'nova')
	),

	array(
		'type' => 'nova_number',
		'heading' => __('Icon Size', 'nova'),
		'param_name' => 'icon_size',
		'value' => 30,
		'min' => 10,
		'suffix' => 'px',
		'group' => __('Icon Settings', 'nova')
	),
	array(
		'type' => 'nova_number',
		'heading' => __('Icon Box Width', 'nova'),
		'param_name' => 'icon_width',
		'value' => 30,
		'min' => 10,
		'suffix' => 'px',
		'group' => __('Icon Settings', 'nova'),
		'dependency' => array(
			'element' 	=> 'icon_style',
			'value' 	=> array('circle','square','round','advanced')
		),
	),
	array(
		'type' => 'nova_number',
		'heading' => __('Icon Padding', 'nova'),
		'param_name' => 'icon_padding',
		'value' => 0,
		'min' => 0,
		'suffix' => 'px',
		'group' => __('Icon Settings', 'nova'),
		'dependency' => array(
			'element' 	=> 'icon_style',
			'value' 	=> array('advanced')
		)
	),
	array(
		'type' 		=> 'dropdown',
		'heading' 	=> __('Icon Color Type', 'nova'),
		'param_name'=> 'icon_color_type',
		'std'		=> 'simple',
		'value' 	=> array(
			__('Simple', 'nova') => 'simple',
			__('Gradient', 'nova') => 'gradient',
		),
		'group' 	=> __('Icon Settings', 'nova')
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Color', 'nova'),
		'param_name'=> 'icon_color',
		'group' 	=> __('Icon Settings', 'nova')
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Hover Color', 'nova'),
		'param_name'=> 'icon_h_color',
		'group' 	=> __('Hover Style', 'nova')
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Color #2', 'nova'),
		'param_name'=> 'icon_color2',
		'group' 	=> __('Icon Settings', 'nova'),
		'dependency' => array(
			'element' 	=> 'icon_color_type',
			'value' 	=> array('gradient')
		)
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Hover Color #2', 'nova'),
		'param_name'=> 'icon_h_color2',
		'dependency' => array(
			'element' 	=> 'icon_color_type',
			'value' 	=> array('gradient')
		),
		'group' 	=> __('Hover Style', 'nova')
	),

	array(
		'type' 		=> 'dropdown',
		'heading' 	=> __('Icon Background Type', 'nova'),
		'param_name'=> 'icon_bg_type',
		'std'		=> 'simple',
		'value' 	=> array(
			__('Simple', 'nova') => 'simple',
			__('Gradient', 'nova') => 'gradient',
		),
		'dependency' => array(
			'element' 	=> 'icon_style',
			'value' 	=> array('circle','square','round','advanced')
		),
		'group' 	=> __('Icon Settings', 'nova')
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Background Color', 'nova'),
		'param_name'=> 'icon_bg',
		'dependency'=> array(
			'element' 	=> 'icon_style',
			'value' 	=> array('circle','square','round','advanced')
		),
		'group' 	=> __('Icon Settings', 'nova')
	),
	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Hover Background Color', 'nova'),
		'param_name'=> 'icon_h_bg',
		'dependency'=> array(
			'element' 	=> 'icon_style',
			'value' 	=> array('circle','square','round','advanced')
		),
		'group' 	=> __('Hover Style', 'nova')
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Background Color #2', 'nova'),
		'param_name'=> 'icon_bg2',
		'dependency'=> array(
			'element' 	=> 'icon_bg_type',
			'value' 	=> array('gradient')
		),
		'group' 	=> __('Icon Settings', 'nova')
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Hover Background Color #2', 'nova'),
		'param_name'=> 'icon_h_bg2',
		'dependency'=> array(
			'element' 	=> 'icon_bg_type',
			'value' 	=> array('gradient')
		),
		'group' 	=> __('Hover Style', 'nova')
	),

	array(
		'type' => 'dropdown',
		'heading' => __('Icon Border Style', 'nova'),
		'param_name' => 'icon_border_style',
		'value' => array(
			__('None', 'nova') => '',
			__('Solid', 'nova') => 'solid',
			__('Dashed', 'nova') => 'dashed',
			__('Dotted', 'nova') => 'dotted',
			__('Double', 'nova') => 'double',
		),
		'group' => __('Icon Settings', 'nova')
	),
	array(
		'type' => 'nova_number',
		'heading' => __('Icon Border Width', 'nova'),
		'param_name' => 'icon_border_width',
		'value' => 1,
		'min' => 1,
		'max' => 10,
		'suffix' => 'px',
		'dependency' => array(
			'element' 	=> 'icon_border_style',
			'not_empty' 	=> true
		),
		'group' => __('Icon Settings', 'nova')
	),
	array(
		'type' => 'colorpicker',
		'heading' => __('Icon Border Color', 'nova'),
		'param_name' => 'icon_border_color',
		'dependency' => array(
			'element' 	=> 'icon_border_style',
			'not_empty' 	=> true
		),
		'group' => __('Icon Settings', 'nova')
	),

	array(
		'type' => 'colorpicker',
		'heading' => __('Icon Hover Border Color', 'nova'),
		'param_name' => 'icon_h_border_color',
		'dependency' => array(
			'element' 	=> 'icon_border_style',
			'not_empty' 	=> true
		),
		'group' => __('Hover Style', 'nova')
	),

	array(
		'type' => 'nova_number',
		'heading' => __('Icon Border Radius', 'nova'),
		'param_name' => 'icon_border_radius',
		'value' => 500,
		'min' => 1,
		'suffix' => 'px',
		'description' => __('0 pixel value will create a square border. As you increase the value, the shape convert in circle slowly. (e.g 500 pixels).', 'nova'),
		'dependency' => array(
			'element' 	=> 'icon_style',
			'value' 	=> array('advanced')
		),
		'group' => __('Icon Settings', 'nova')
	),



	Novaworks_Shortcodes_Helper::fieldExtraClass(),
	Novaworks_Shortcodes_Helper::fieldExtraClass(array(
		'heading' 		=> __('Extra Class for heading', 'nova'),
		'param_name' 	=> 'title_class',
	)),
	Novaworks_Shortcodes_Helper::fieldExtraClass(array(
		'heading' 		=> __('Extra Class for description', 'nova'),
		'param_name' 	=> 'desc_class',
	))
);

$title_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont();
$desc_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont('desc', __('Description', 'nova'));

$shortcode_params = array_merge( $icon_type, $shortcode_params, $title_google_font_param, $desc_google_font_param, array(Novaworks_Shortcodes_Helper::fieldCssClass()) );

return apply_filters(
	'Novaworks/shortcodes/configs',
	array(
		'name'			=> __('Icon boxes', 'nova'),
		'base'			=> 'nova_icon_boxes',
		'icon'          => 'nova-wpb-icon nova_icon_boxes',
		'category'  	=> __('9AM Studio', 'nova'),
		'description' 	=> __('Adds icon box with custom font icon','nova'),
		'params' 		=> $shortcode_params
	),
    'nova_icon_boxes'
);
