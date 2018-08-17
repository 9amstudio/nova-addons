<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

$base_param = array(
	array(
		'type' => 'colorpicker',
		'param_name' => 'main_bg_color',
		'heading' => __('Main background color', 'lastudio')
	),
	array(
		'type' => 'colorpicker',
		'param_name' => 'main_text_color',
		'heading' => __('Main text color', 'lastudio')
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Package Name / Title', 'lastudio' ),
		'param_name' => 'package_title',
		'description' => __( 'Enter the package name or table heading', 'lastudio' )
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Package Price', 'lastudio' ),
		'param_name' => 'package_price',
		'description' => __( 'Enter the price for this package. e.g. $157', 'lastudio' )
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Price Unit', 'lastudio' ),
		'param_name' => 'price_unit',
		'description' => __( 'Enter the price unit for this package. e.g. per month', 'lastudio' )
	),
	array(
		'type' => 'param_group',
		'heading' => __( 'Features', 'lastudio' ),
		'param_name' => 'features',
		'description' => __( 'Create the features list', 'lastudio' ),
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
				'heading' => __( 'Highlight Text', 'lastudio' ),
				'param_name' => 'highlight',
				'admin_label' => true,
			),
			array(
				'type' => 'textfield',
				'heading' => __( 'Text', 'lastudio' ),
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
		'heading' => __( 'Description before features', 'lastudio' ),
		'param_name' => 'desc_before',
	),
	array(
		'type' => 'textarea',
		'heading' => __( 'Description after features', 'lastudio' ),
		'param_name' => 'desc_after',
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Button text', 'lastudio' ),
		'param_name' => 'button_text',
		'description' => __( 'Enter call to action button text', 'lastudio' ),
		'value' => 'View More'
	),
	array(
		'type'       => 'vc_link',
		'heading'    => __( 'Button Link', 'lastudio' ),
		'param_name' => 'button_link',
		'description' => __('Select / enter the link for call to action button', 'lastudio')
	),
	array(
		'type'       => 'checkbox',
		'param_name' => 'package_featured',
		'value'      => array( __( 'Make this pricing box as featured', 'lastudio' ) => 'yes' ),
	),
	array(
		'type' => 'textfield',
		'heading' => __( 'Custom badge', 'lastudio' ),
		'param_name' => 'custom_badge',
		'value'		=> 'Recommended',
		'dependency' => array(
			'element' => 'package_featured',
			'value' => 'yes'
		)
	),

	LaStudio_Shortcodes_Helper::fieldExtraClass()
);

$title_google_font_param = LaStudio_Shortcodes_Helper::fieldTitleGFont('package_title', __('Package Name/Title', 'lastudio'));
$price_google_font_param = LaStudio_Shortcodes_Helper::fieldTitleGFont('package_price', __('Price', 'lastudio'));
$price_unit_google_font_param = LaStudio_Shortcodes_Helper::fieldTitleGFont('package_price_unit', __('Price Unit', 'lastudio'));
$desc_google_font_param = LaStudio_Shortcodes_Helper::fieldTitleGFont('package_desc', __('After Features/ Before Features', 'lastudio'));
$features_google_font_param = LaStudio_Shortcodes_Helper::fieldTitleGFont('package_features', __('Features', 'lastudio'));

$features_google_font_param[] = array(
	'type' 			=> 'colorpicker',
	'param_name' 	=> 'package_features_highlight_color',
	'heading' 		=> __('Highlight Text Color', 'lastudio'),
	'group' 		=> __('Typography', 'lastudio')
);


$button_google_font_param = array(
	array(
		'type' 			=> 'la_heading',
		'param_name' 	=> 'icon__package_button',
		'text' 			=> __('Button settings', 'lastudio'),
		'group' 		=> __('Typography', 'lastudio')
	),
	array(
		'type' => 'checkbox',
		'heading' => __( 'Use google fonts family?', 'lastudio' ),
		'param_name' => 'use_gfont_package_button',
		'value' => array( __( 'Yes', 'lastudio' ) => 'yes' ),
		'description' => __( 'Use font family from the theme.', 'lastudio' ),
		'group' 		=> __('Typography', 'lastudio')
	),
	array(
		'type' 			=> 'google_fonts',
		'param_name' 	=> 'package_button_font',
		'dependency' 	=> array(
			'element' => 'use_gfont_package_button',
			'value' => 'yes'
		),
		'group' 		=> __('Typography', 'lastudio')
	),
	array(
		'type' 			=> 'la_column',
		'heading' 		=> __('Font size', 'lastudio'),
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
		'group' 		=> __('Typography', 'lastudio')
	),
	array(
		'type' 			=> 'la_column',
		'heading' 		=> __('Line Height', 'lastudio'),
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
		'group' 		=> __('Typography', 'lastudio')
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'package_button_color',
		'heading' 		=> __('Color', 'lastudio'),
		'group' 		=> __('Typography', 'lastudio')
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'package_button_bg_color',
		'heading' 		=> __('Background Color', 'lastudio'),
		'group' 		=> __('Typography', 'lastudio')
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'package_button_hover_color',
		'heading' 		=> __('Hover Color', 'lastudio'),
		'group' 		=> __('Typography', 'lastudio')
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'package_button_hover_bg_color',
		'heading' 		=> __('Hover Background Color', 'lastudio'),
		'group' 		=> __('Typography', 'lastudio')
	)
);

$icon_google_font_param = array(
	array(
		'type' 			=> 'la_heading',
		'param_name' 	=> 'icon__typography',
		'text' 			=> __('Icon settings', 'lastudio'),
		'group' 		=> __('Typography', 'lastudio'),
		'dependency'	=> array(
			'element'	=> 'style',
			'value'		=> '1'
		)
	),
	array(
		'type' 			=> 'la_column',
		'heading' 		=> __('Icon Width', 'lastudio'),
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
		'group' 		=> __('Typography', 'lastudio'),
		'dependency'	=> array(
			'element'	=> 'style',
			'value'		=> '1'
		)
	),
	array(
		'type' 			=> 'la_column',
		'heading' 		=> __('Font size', 'lastudio'),
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
		'group' 		=> __('Typography', 'lastudio'),
		'dependency'	=> array(
			'element'	=> 'style',
			'value'		=> '1'
		)
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'icon_color',
		'heading' 		=> __('Color', 'lastudio'),
		'group' 		=> __('Typography', 'lastudio'),
		'dependency'	=> array(
			'element'	=> 'style',
			'value'		=> '1'
		)
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'icon_bg_color',
		'heading' 		=> __('Background Color', 'lastudio'),
		'group' 		=> __('Typography', 'lastudio'),
		'dependency'	=> array(
			'element'	=> 'style',
			'value'		=> '1'
		)
	),
	array(
		'type' 			=> 'colorpicker',
		'param_name' 	=> 'icon_bg_color2',
		'heading' 		=> __('Background Color 2', 'lastudio'),
		'group' 		=> __('Typography', 'lastudio'),
		'dependency'	=> array(
			'element'	=> 'style',
			'value'		=> '1'
		)
	)
);

$icon_type = LaStudio_Shortcodes_Helper::fieldIconType(array(
	'element' => 'style',
	'value'	  => '1'
), true);

$shortcode_params = array_merge(
	array(
		array(
			'type' => 'dropdown',
			'heading' => __('Select Design','lastudio'),
			'description' => __('Select Pricing box design you would like to use','lastudio'),
			'param_name' => 'style',
			'value' => array(
				__('Design 01','lastudio') => '1',
				__('Design 02','lastudio') => '2',
				__('Design 03','lastudio') => '3'
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
	'LaStudio/shortcodes/configs',
	array(
		'name'			=> __('Pricing Box', 'lastudio'),
		'base'			=> 'la_pricing_table',
		'icon'          => 'la-wpb-icon la_pricing_table',
		'category'  	=> __('La Studio', 'lastudio'),
		'description' 	=> __('Create nice looking pricing tables','lastudio'),
		'params' 		=> $shortcode_params
	),
    'la_pricing_table'
);