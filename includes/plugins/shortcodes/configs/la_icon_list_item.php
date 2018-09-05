<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

return apply_filters(
	'Novaworks/shortcodes/configs',
	array(
		'name'			=> __('Icon', 'nova'),
		'base'			=> 'la_icon_list_item',
		'icon'          => 'nova-wpb-icon la_icon_list_item',
		'category'  	=> __('La Studio', 'nova'),
		'description' 	=> __('Displays the list icon','nova'),
		'as_child'         => array('only' => 'la_icon_list'),
		'content_element'   => true,
		'params' 		=> array(
			array(
				'type' => 'dropdown',
				'heading' => __( 'Icon library', 'nova' ),
				'value' => array(
					__( 'Font Awesome', 'nova' ) => 'fontawesome',
					__( 'Novaworks Icon Outline', 'nova' ) => 'nova_icon_outline',
				),
				'param_name' => 'icon_type',
				'description' => __( 'Select icon library.', 'nova' )
			),
			array(
				'type' => 'iconpicker',
				'heading' => __( 'Icon', 'nova' ),
				'param_name' => 'icon_fontawesome',
				'settings' => array(
					'emptyIcon' => true,
					'iconsPerPage' => 20,
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'fontawesome',
				)
			),
			array(
				'type' => 'iconpicker',
				'heading' => __( 'Icon', 'nova' ),
				'param_name' => 'icon_nova_icon_outline',
				'settings' => array(
					'emptyIcon' => true,
					'type' => 'nova_icon_outline',
					'iconsPerPage' => 20,
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'nova_icon_outline',
				)
			),
			array(
				'type' => 'hidden',
				'heading' => __('Icon', 'nova'),
				'param_name' => 'icon'
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Icon Color', 'nova'),
				'param_name' => 'icon_color'
			),
			array(
				"type" => "textfield",
				"heading" => __("Content", 'nova'),
				"param_name" => "content",
				"admin_label" => true
			),
			Novaworks_Shortcodes_Helper::fieldExtraClass()
		),
	),
    'la_icon_list_item'
);