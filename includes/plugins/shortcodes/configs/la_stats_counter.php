<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


$icon_type = Novaworks_Shortcodes_Helper::fieldIconType(array(
	'element' => 'icon_pos',
	'value'	=> array('top','left','right')
));

$field_icon_settings = array(
	array(
		'type'	=> 'dropdown',
		'heading'	=> __('Icon Position', 'nova'),
		'param_name' => 'icon_pos',
		'value'	=> array(
			__('No display', 'nova')	=> 'none',
			__('Icon at Top', 'nova') => 'top',
			__('Icon at Left', 'nova') => 'left',
			__('Icon at Right', 'nova') => 'right'
		),
		'std' => 'top',
		'description' => __('Select icon position. Icon box style will be changed according to the icon position.', 'nova')
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
		'group' => __('Icon Settings', 'nova'),
		'dependency' => array(
			'element' 	=> 'icon_pos',
			'value_not_equal_to' => array( 'none' )
		)
	),

	array(
		'type' => 'nova_number',
		'heading' => __('Icon Size', 'nova'),
		'param_name' => 'icon_size',
		'value' => 30,
		'min' => 10,
		'suffix' => 'px',
		'group' => __('Icon Settings', 'nova'),
		'dependency' => array(
			'element' 	=> 'icon_pos',
			'value_not_equal_to' => array( 'none' )
		)
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
		'group' 	=> __('Icon Settings', 'nova'),
		'dependency' => array(
			'element' 	=> 'icon_pos',
			'value_not_equal_to' => array( 'none' )
		)
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> __('Icon Color', 'nova'),
		'param_name'=> 'icon_color',
		'group' 	=> __('Icon Settings', 'nova'),
		'dependency' => array(
			'element' 	=> 'icon_pos',
			'value_not_equal_to' => array( 'none' )
		)
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
		'heading' 	=> __('Icon Background Color #2', 'nova'),
		'param_name'=> 'icon_bg2',
		'dependency'=> array(
			'element' 	=> 'icon_bg_type',
			'value' 	=> array('gradient')
		),
		'group' 	=> __('Icon Settings', 'nova')
	),
);

$field_icon_settings = array_merge($field_icon_settings, $icon_type);

$shortcode_params = array(
	array(
		'type' => 'textfield',
		'heading' => __('Title', 'nova'),
		'param_name' => 'title',
		'admin_label' => true,
	),
	array(
		'type' => 'nova_number',
		'heading' => __('Value', 'nova'),
		'param_name' => 'value',
		'value' => 1250,
		'min' => 0,
		'suffix' => '',
		'description' => __('Enter number for counter without any special character. You may enter a decimal number. Eg 12.76', 'nova')
	),
	array(
		'type' => 'textfield',
		'heading' => __('Value Prefix', 'nova'),
		'param_name' => 'prefix'
	),
	array(
		'type' => 'textfield',
		'heading' => __('Value Suffix', 'nova'),
		'param_name' => 'suffix'
	),
	array(
		'type'  => 'dropdown',
		'heading' => __('Separator','nova'),
		'param_name'    => 'spacer',
		'value' => array(
			__('No Separator','nova')	=>	'none',
			__('Line','nova')	        =>	'line',
		),
		'default' => 'none',
	),
	array(
		'type'  => 'dropdown',
		'heading' => __('Separator Position','nova'),
		'param_name'    => 'spacer_position',
		'value' => array(
			__('Top','nova')		 	=>	'top',
			__('Bottom','nova')		=>	'bottom',
			__('Between Value & Title','nova')	=>	'middle'
		),
		'default' => 'top',
		'dependency' => array(
			'element'   => 'spacer',
			'value'     => 'line'
		)
	),
	array(
		'type'      => 'dropdown',
		'heading'   => __('Line Style', 'nova'),
		'param_name'    => 'line_style',
		'value'         => array(
			__('Solid', 'nova') => 'solid',
			__('Dashed', 'nova') => 'dashed',
			__('Dotted', 'nova') => 'dotted',
			__('Double', 'nova') => 'double'
		),
		'default' => 'solid',
		'dependency' => array(
			'element'   => 'spacer',
			'value'     => 'line'
		)
	),
	array(
		'type' 			=> 'nova_column',
		'heading' 		=> __('Line Width', 'nova'),
		'param_name' 	=> 'line_width',
		'unit'			=> 'px',
		'media'			=> array(
			'xlg'	=> '',
			'lg'	=> '',
			'md'	=> '',
			'sm'	=> '',
			'xs'	=> '',
			'mb'	=> ''
		),
		'dependency' => array(
			'element'   => 'spacer',
			'value'     => 'line'
		)
	),
	array(
		'type' => 'nova_number',
		'heading' => __('Line Height', 'nova'),
		'param_name' => 'line_height',
		'value' => 1,
		'min' => 1,
		'suffix' => 'px',
		'dependency' => array(
			'element'   => 'spacer',
			'value'     => 'line'
		)
	),
	array(
		'type' => 'colorpicker',
		'heading' => __('Line Color', 'nova'),
		'param_name' => 'line_color',
		'dependency' => array(
			'element'   => 'spacer',
			'value'     => 'line'
		)
	),
	Novaworks_Shortcodes_Helper::fieldElementID(array(
		'param_name' 	=> 'el_id'
	)),
	Novaworks_Shortcodes_Helper::fieldExtraClass(),
	Novaworks_Shortcodes_Helper::fieldExtraClass(array(
		'heading' 		=> __('Extra class name for value', 'nova'),
		'param_name' 	=> 'el_class_value'
	)),
	Novaworks_Shortcodes_Helper::fieldExtraClass(array(
		'heading' 		=> __('Extra Class name for heading', 'nova'),
		'param_name' 	=> 'el_class_heading'
	)),
);

$title_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont();
$value_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont('value', __('Value', 'nova'));
$shortcode_params = array_merge( $field_icon_settings, $shortcode_params, $value_google_font_param, $title_google_font_param, array(Novaworks_Shortcodes_Helper::fieldCssClass()) );

return apply_filters(
	'Novaworks/shortcodes/configs',
	array(
		'name'			=> __('Stats Counter', 'nova'),
		'base'			=> 'la_stats_counter',
		'icon'          => 'nova-wpb-icon la_stats_counter',
		'category'  	=> __('La Studio', 'nova'),
		'description' 	=> __('Your milestones, achievements, etc.','nova'),
		'params' 		=> $shortcode_params
	),
    'la_stats_counter'
);