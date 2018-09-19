<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


$icon_type = Novaworks_Shortcodes_Helper::fieldIconType();
$icon_type[0]['value'][ esc_html__( 'Custom Number', 'nova') ] = 'number';
$icon_type[] = array(
	'type' => 'textfield',
	'heading' => esc_html__('Enter the number', 'nova'),
	'param_name' => 'custom_number',
	'dependency' => array(
		'element' => 'icon_type',
		'value' => 'number'
	)
);

$shortcode_params = array(
	array(
		'type' => 'textfield',
		'heading' => esc_html__( 'Heading', 'nova' ),
		'param_name' => 'title',
		'admin_label' => true,
		'description' => esc_html__( 'Provide the title for this icon boxes.', 'nova' ),
	),
	array(
		'type' => 'textarea_html',
		'heading' => esc_html__( 'Description', 'nova' ),
		'param_name' => 'content',
		'description' => esc_html__( 'Provide the description for this icon box.', 'nova' ),
	),
	// Select link option - to box or with read more text
	array(
		'type' 			=> 'dropdown',
		'heading' 		=> esc_html__( 'Apply link to:', 'nova' ),
		'param_name' 	=> 'read_more',
		'value' 		=> array(
			esc_html__( 'No Link','nova' ) => 'none',
			esc_html__( 'Complete Box','nova' ) => 'box',
			esc_html__( 'Box Title','nova' ) => 'title',
			esc_html__( 'Icon','nova' ) => 'icon'
		)
	),
	// Add link to existing content or to another resource
	array(
		'type' 			=> 'vc_link',
		'heading' 		=> esc_html__( 'Add Link', 'nova' ),
		'param_name' 	=> 'link',
		'description' 	=> esc_html__( 'Add a custom link or select existing page. You can remove existing link as well.', 'nova' ),
		'dependency' 	=> array(
			'element' 	=> 'read_more',
			'value' 	=> array( 'box', 'title', 'icon' )
		)
	),
	array(
		'type'	=> 'dropdown',
		'heading'	=> esc_html__( 'Icon Position', 'nova' ),
		'param_name' => 'icon_pos',
		'value'	=> array(
			esc_html__( 'Icon at left with heading', 'nova' ) => 'default',
			esc_html__( 'Icon at Right with heading', 'nova' ) => 'heading-right',
			esc_html__( 'Icon at Left', 'nova' ) => 'left',
			esc_html__( 'Icon at Right', 'nova' ) => 'right',
			esc_html__( 'Icon at Top', 'nova' ) => 'top',
		),
		'std' => 'default',
		'description' => esc_html__( 'Select icon position. Icon box style will be changed according to the icon position.', 'nova' ),
		'group' => esc_html__( 'Icon Settings', 'nova' )
	),

	array(
		'type' => 'dropdown',
		'heading' => esc_html__( 'Icon Styles', 'nova' ),
		'param_name' => 'icon_style',
		'description' => esc_html__( 'We have given four quick preset if you are in a hurry. Otherwise, create your own with various options.', 'nova' ),
		'std'	=> 'simple',
		'value' => array(
			esc_html__( 'Simple', 'nova' ) => 'simple',
			esc_html__( 'Circle Background', 'nova' ) => 'circle',
			esc_html__( 'Square Background', 'nova' ) => 'square',
			esc_html__( 'Round Background', 'nova' ) => 'round',
			esc_html__( 'Advanced', 'nova' ) => 'advanced',
		),
		'group' => esc_html__( 'Icon Settings', 'nova' )
	),

	array(
		'type' => 'nova_number',
		'heading' => esc_html__( 'Icon Size', 'nova' ),
		'param_name' => 'icon_size',
		'value' => 30,
		'min' => 10,
		'suffix' => 'px',
		'group' => esc_html__( 'Icon Settings', 'nova' )
	),
	array(
		'type' => 'nova_number',
		'heading' => esc_html__( 'Icon Box Width', 'nova' ),
		'param_name' => 'icon_width',
		'value' => 30,
		'min' => 10,
		'suffix' => 'px',
		'group' => esc_html__( 'Icon Settings', 'nova' ),
		'dependency' => array(
			'element' 	=> 'icon_style',
			'value' 	=> array( 'circle', 'square', 'round', 'advanced' )
		),
	),
	array(
		'type' => 'nova_number',
		'heading' => esc_html__( 'Icon Padding', 'nova' ),
		'param_name' => 'icon_padding',
		'value' => 0,
		'min' => 0,
		'suffix' => 'px',
		'group' => esc_html__( 'Icon Settings', 'nova' ),
		'dependency' => array(
			'element' 	=> 'icon_style',
			'value' 	=> array('advanced')
		)
	),
	array(
		'type' 		=> 'dropdown',
		'heading' 	=> esc_html__( 'Icon Color Type', 'nova' ),
		'param_name'=> 'icon_color_type',
		'std'		=> 'simple',
		'value' 	=> array(
			esc_html__( 'Simple', 'nova' ) => 'simple',
			esc_html__( 'Gradient', 'nova' ) => 'gradient',
		),
		'group' 	=> esc_html__( 'Icon Settings', 'nova' )
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> esc_html__( 'Icon Color', 'nova' ),
		'param_name'=> 'icon_color',
		'group' 	=> esc_html__( 'Icon Settings', 'nova' )
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> esc_html__( 'Icon Hover Color', 'nova' ),
		'param_name'=> 'icon_h_color',
		'group' 	=> esc_html__( 'Hover Style', 'nova' )
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> esc_html__( 'Icon Color #2', 'nova' ),
		'param_name'=> 'icon_color2',
		'group' 	=> esc_html__( 'Icon Settings', 'nova' ),
		'dependency' => array(
			'element' 	=> 'icon_color_type',
			'value' 	=> array( 'gradient' )
		)
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> esc_html__( 'Icon Hover Color #2', 'nova' ),
		'param_name'=> 'icon_h_color2',
		'dependency' => array(
			'element' 	=> 'icon_color_type',
			'value' 	=> array( 'gradient' )
		),
		'group' 	=> esc_html__( 'Hover Style', 'nova' )
	),

	array(
		'type' 		=> 'dropdown',
		'heading' 	=> esc_html__( 'Icon Background Type', 'nova' ),
		'param_name'=> 'icon_bg_type',
		'std'		=> 'simple',
		'value' 	=> array(
			esc_html__( 'Simple', 'nova' ) => 'simple',
			esc_html__( 'Gradient', 'nova' ) => 'gradient',
		),
		'dependency' => array(
			'element' 	=> 'icon_style',
			'value' 	=> array( 'circle', 'square', 'round', 'advanced' )
		),
		'group' 	=> esc_html__( 'Icon Settings', 'nova' )
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> esc_html__( 'Icon Background Color', 'nova' ),
		'param_name'=> 'icon_bg',
		'dependency'=> array(
			'element' 	=> 'icon_style',
			'value' 	=> array( 'circle', 'square', 'round', 'advanced' )
		),
		'group' 	=> esc_html__( 'Icon Settings', 'nova' )
	),
	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> esc_html__( 'Icon Hover Background Color', 'nova' ),
		'param_name'=> 'icon_h_bg',
		'dependency'=> array(
			'element' 	=> 'icon_style',
			'value' 	=> array( 'circle', 'square', 'round', 'advanced' )
		),
		'group' 	=> esc_html__( 'Hover Style', 'nova' )
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> esc_html__( 'Icon Background Color #2', 'nova' ),
		'param_name'=> 'icon_bg2',
		'dependency'=> array(
			'element' 	=> 'icon_bg_type',
			'value' 	=> array('gradient')
		),
		'group' 	=> esc_html__( 'Icon Settings', 'nova' )
	),

	array(
		'type' 		=> 'colorpicker',
		'heading' 	=> esc_html__( 'Icon Hover Background Color #2', 'nova' ),
		'param_name'=> 'icon_h_bg2',
		'dependency'=> array(
			'element' 	=> 'icon_bg_type',
			'value' 	=> array('gradient')
		),
		'group' 	=> esc_html__( 'Hover Style', 'nova' )
	),

	array(
		'type' => 'dropdown',
		'heading' => esc_html__( 'Icon Border Style', 'nova' ),
		'param_name' => 'icon_border_style',
		'value' => array(
			esc_html__( 'None', 'nova' ) => '',
			esc_html__( 'Solid', 'nova' ) => 'solid',
			esc_html__( 'Dashed', 'nova' ) => 'dashed',
			esc_html__( 'Dotted', 'nova' ) => 'dotted',
			esc_html__( 'Double', 'nova' ) => 'double',
		),
		'group' => esc_html__( 'Icon Settings', 'nova' )
	),
	array(
		'type' => 'nova_number',
		'heading' => esc_html__( 'Icon Border Width', 'nova' ),
		'param_name' => 'icon_border_width',
		'value' => 1,
		'min' => 1,
		'max' => 10,
		'suffix' => 'px',
		'dependency' => array(
			'element' 	=> 'icon_border_style',
			'not_empty' 	=> true
		),
		'group' => esc_html__( 'Icon Settings', 'nova' )
	),
	array(
		'type' => 'colorpicker',
		'heading' => esc_html__( 'Icon Border Color', 'nova' ),
		'param_name' => 'icon_border_color',
		'dependency' => array(
			'element' 	=> 'icon_border_style',
			'not_empty' 	=> true
		),
		'group' => esc_html__( 'Icon Settings', 'nova' )
	),

	array(
		'type' => 'colorpicker',
		'heading' => esc_html__( 'Icon Hover Border Color', 'nova' ),
		'param_name' => 'icon_h_border_color',
		'dependency' => array(
			'element' 	=> 'icon_border_style',
			'not_empty' 	=> true
		),
		'group' => esc_html__( 'Hover Style', 'nova' )
	),

	array(
		'type' => 'nova_number',
		'heading' => esc_html__( 'Icon Border Radius', 'nova' ),
		'param_name' => 'icon_border_radius',
		'value' => 500,
		'min' => 1,
		'suffix' => 'px',
		'description' => esc_html__( '0 pixel value will create a square border. As you increase the value, the shape convert in circle slowly. (e.g 500 pixels).', 'nova' ),
		'dependency' => array(
			'element' 	=> 'icon_style',
			'value' 	=> array('advanced')
		),
		'group' => esc_html__( 'Icon Settings', 'nova' )
	),



	Novaworks_Shortcodes_Helper::fieldExtraClass(),
	Novaworks_Shortcodes_Helper::fieldExtraClass(array(
		'heading' 		=> esc_html__( 'Extra Class for heading', 'nova' ),
		'param_name' 	=> 'title_class',
	)),
	Novaworks_Shortcodes_Helper::fieldExtraClass(array(
		'heading' 		=> esc_html__( 'Extra Class for description', 'nova' ),
		'param_name' 	=> 'desc_class',
	))
);

$title_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont();
$desc_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont( 'desc', esc_html__( 'Description', 'nova' ) );

$shortcode_params = array_merge( $icon_type, $shortcode_params, $title_google_font_param, $desc_google_font_param, array( Novaworks_Shortcodes_Helper::fieldCssClass() ) );

return apply_filters(
	'Novaworks/shortcodes/configs',
	array(
		'name'			=> esc_html__( 'Icon boxes', 'nova' ),
		'base'			=> 'nova_icon_boxes',
		'icon'          => 'nova-wpb-icon nova_icon_boxes',
		'category'  	=> esc_html__( '9AM Studio', 'nova' ),
		'description' 	=> esc_html__( 'Adds icon box with custom font icon', 'nova' ),
		'params' 		=> $shortcode_params
	),
    'nova_icon_boxes'
);
