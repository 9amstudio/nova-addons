<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

$base_param = array(
	array(
		'type' => 'colorpicker',
		'param_name' => 'main_bg_color',
		'heading' => __('Main background color', 'nova')
	),
	array(
		'type' => 'colorpicker',
		'param_name' => 'main_text_color',
		'heading' => __('Main text color', 'nova')
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Package Name / Title', 'nova' ),
		'param_name' => 'package_title',
		'description' => __( 'Enter the package name or table heading', 'nova' )
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Package Price', 'nova' ),
		'param_name' => 'package_price',
		'description' => __( 'Enter the price for this package. e.g. $157', 'nova' )
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Price Unit', 'nova' ),
		'param_name' => 'price_unit',
		'description' => __( 'Enter the price unit for this package. e.g. per month', 'nova' )
	),
	array(
		'type' => 'param_group',
		'heading' => __( 'Features', 'nova' ),
		'param_name' => 'features',
		'description' => __( 'Create the features list', 'nova' ),
		'value' => urlencode( json_encode( array(
			array(
				'highlight' => 'Sample',
				'text' => 'Text'
			),
			array(
				'highlight' => 'Sample',
				'text' => 'Text',
			),
			array(
				'highlight' => 'Sample',
				'text' => 'Text',
			),
		) ) ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => __( 'Highlight Text', 'nova' ),
				'param_name' => 'highlight',
				'admin_label' => true,
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Text', 'nova' ),
				'param_name' => 'text',
				'admin_label' => true,
			),
			array(
				'type' => 'iconpicker',
				'param_name' => 'icon',
				'settings' => array(
					'emptyIcon' => true,
					'iconsPerPage' => 50,
				),
			),
		),
	),
	array(
		'type' => 'textarea',
		'heading' => __( 'Description before features', 'nova' ),
		'param_name' => 'desc_before',
	),
	array(
		'type' => 'textarea',
		'heading' => __( 'Description after features', 'nova' ),
		'param_name' => 'desc_after',
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Button text', 'nova' ),
		'param_name' => 'button_text',
		'description' => __( 'Enter call to action button text', 'nova' ),
		'value' => 'View More'
	),
	array(
		'type'       => 'vc_link',
		'heading'    => __( 'Button Link', 'nova' ),
		'param_name' => 'button_link',
		'description' => __('Select / enter the link for call to action button', 'nova')
	),
	array(
		'type'       => 'checkbox',
		'param_name' => 'package_featured',
		'value'      => array( __( 'Make this pricing box as featured', 'nova' ) => 'yes' ),
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Custom badge', 'nova' ),
		'param_name' => 'custom_badge',
		'value'		=> 'Recommended',
		'dependency' => array(
			'element' => 'package_featured',
			'value' => 'yes'
		)
	),

	Novaworks_Shortcodes_Helper::fieldExtraClass()
);

$title_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont('package_title', __('Package Name/Title', 'nova'));
$price_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont('package_price', __('Price', 'nova'));
$price_unit_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont('package_price_unit', __('Price Unit', 'nova'));
$desc_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont('package_desc', __('After Features/ Before Features', 'nova'));
$features_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont('package_features', __('Features', 'nova'));

$features_google_font_param[] = array(
	'type' 			=> 'colorpicker',
	'param_name' 	=> 'package_features_highlight_color',
	'heading' 		=> __('Highlight Text Color', 'nova'),
	'group' 		=> __('Typography', 'nova')
);


$button_google_font_param = array(
	array(
		'type' 			=> 'nova_heading',
		'param_name' 	=> 'icon__package_button',
		'text' 			=> __('Button settings', 'nova'),
		'group' 		=> __('Typography', 'nova')
	),
	array(
		'type' => 'checkbox',
		'heading' => __( 'Use google fonts family?', 'nova' ),
		'param_name' => 'use_gfont_package_button',
		'value' => array( __( 'Yes', 'nova' ) => 'yes' ),
		'description' => __( 'Use font family from the theme.', 'nova' ),
		'group' 		=> __('Typography', 'nova')
	),
	array(
		'type' 			=> 'google_fonts',
		'param_name' 	=> 'package_button_font',
		'dependency' 	=> array(
			'element' => 'use_gfont_package_button',
			'value' => 'yes'
		),
		'group' 		=> __('Typography', 'nova')
	),
	array(
		'type' 			=> 'nova_column',
		'heading' 		=> __('Font size', 'nova'),
		'param_name' 	=> 'package_button_fz',
		'unit' 			=> 'px',
		'media' => array(
			'xlg'	=> '',
			'lg'    => '',
			'md'    => '',
			'sm'    => '',
			'xs'	=> '',
			'mb'	=> ''
		),
		'group' 		=> __('Typography', 'nova')
	),
	array(
		'type' 			=> 'nova_column',
		'heading' 		=> __('Line Height', 'nova'),
		'param_name' 	=> 'package_button_lh',
		'unit' 			=> 'px',
		'media' => array(
			'xlg'	=> '',
			'lg'    => '',
			'md'    => '',
			'sm'    => '',
			'xs'	=> '',
			'mb'	=> ''
		),
		'group' 		=> __('Typography', 'nova')
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'package_button_color',
		'heading' 		=> __('Color', 'nova'),
		'group' 		=> __('Typography', 'nova')
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'package_button_bg_color',
		'heading' 		=> __('Background Color', 'nova'),
		'group' 		=> __('Typography', 'nova')
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'package_button_hover_color',
		'heading' 		=> __('Hover Color', 'nova'),
		'group' 		=> __('Typography', 'nova')
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'package_button_hover_bg_color',
		'heading' 		=> __('Hover Background Color', 'nova'),
		'group' 		=> __('Typography', 'nova')
	)
);

$icon_google_font_param = array(
	array(
		'type' 			=> 'nova_heading',
		'param_name' 	=> 'icon__typography',
		'text' 			=> __('Icon settings', 'nova'),
		'group' 		=> __('Typography', 'nova'),
		'dependency'	=> array(
			'element'	=> 'style',
			'value'		=> '1'
		)
	),
	array(
		'type' 			=> 'nova_column',
		'heading' 		=> __('Icon Width', 'nova'),
		'param_name' 	=> 'icon_lh',
		'unit' 			=> 'px',
		'media' => array(
			'xlg'	=> '',
			'lg'    => '',
			'md'    => '',
			'sm'    => '',
			'xs'	=> '',
			'mb'	=> ''
		),
		'group' 		=> __('Typography', 'nova'),
		'dependency'	=> array(
			'element'	=> 'style',
			'value'		=> '1'
		)
	),
	array(
		'type' 			=> 'nova_column',
		'heading' 		=> __('Font size', 'nova'),
		'param_name' 	=> 'icon_fz',
		'unit' 			=> 'px',
		'media' => array(
			'xlg'	=> '',
			'lg'    => '',
			'md'    => '',
			'sm'    => '',
			'xs'	=> '',
			'mb'	=> ''
		),
		'group' 		=> __('Typography', 'nova'),
		'dependency'	=> array(
			'element'	=> 'style',
			'value'		=> '1'
		)
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'icon_color',
		'heading' 		=> __('Color', 'nova'),
		'group' 		=> __('Typography', 'nova'),
		'dependency'	=> array(
			'element'	=> 'style',
			'value'		=> '1'
		)
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'icon_bg_color',
		'heading' 		=> __('Background Color', 'nova'),
		'group' 		=> __('Typography', 'nova'),
		'dependency'	=> array(
			'element'	=> 'style',
			'value'		=> '1'
		)
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'icon_bg_color2',
		'heading' 		=> __('Background Color 2', 'nova'),
		'group' 		=> __('Typography', 'nova'),
		'dependency'	=> array(
			'element'	=> 'style',
			'value'		=> '1'
		)
	)
);

$icon_type = Novaworks_Shortcodes_Helper::fieldIconType(array(
	'element' => 'style',
	'value'	  => '1'
), true);

$shortcode_params = array_merge(
	array(
		array(
			'type' => 'dropdown',
			'heading' => __('Select Design','nova'),
			'description' => __('Select Pricing box design you would like to use','nova'),
			'param_name' => 'style',
			'value' => array(
				__('Design 01','nova') => '1',
				__('Design 02','nova') => '2',
				__('Design 03','nova') => '3'
			)
		)
	),
	$icon_type,
	$base_param,
	$icon_google_font_param,
	$title_google_font_param,
	$price_google_font_param,
	$price_unit_google_font_param,
	$desc_google_font_param,
	$features_google_font_param,
	$button_google_font_param
);

return apply_filters(
	'Novaworks/shortcodes/configs',
	array(
		'name'			=> __('Pricing Box', 'nova'),
		'base'			=> 'la_pricing_table',
		'icon'          => 'nova-wpb-icon la_pricing_table',
		'category'  	=> __('La Studio', 'nova'),
		'description' 	=> __('Create nice looking pricing tables','nova'),
		'params' 		=> $shortcode_params
	),
    'la_pricing_table'
);