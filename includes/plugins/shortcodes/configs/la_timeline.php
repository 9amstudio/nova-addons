<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

if ( !class_exists( 'WPBakeryShortCode_la_timeline' ) ) {
	class WPBakeryShortCode_la_timeline extends WPBakeryShortCodesContainer{

	}
}

return apply_filters(
	'Novaworks/shortcodes/configs',
	array(
		'name'			=> __('Timeline', 'nova'),
		'base'			=> 'la_timeline',
		'icon'          => 'nova-wpb-icon la_timeline',
		'category'  	=> __('La Studio', 'nova'),
		'description' 	=> __('Displays the timeline block','nova'),
		'as_parent'         => array('only' => 'la_timeline_item'),
		'content_element'   => true,
		'is_container'      => false,
		'show_settings_on_create' => false,
		'params' 		=> array(
			array(
				'type' => 'dropdown',
				'heading' => __( 'Design', 'nova'),
				'param_name' => 'style',
				'value' => array(
					__('Style 01', 'nova') => '1',
					__('Style 02', 'nova') => '2',
				),
			),
			array(
				'type'       => 'checkbox',
				'heading'    => __('Enable load more', 'nova' ),
				'param_name' => 'enable_load_more',
				'value'      => array( __( 'Yes', 'nova' ) => 'yes' )
			),
			array(
				"type" => "textfield",
				"heading" => __("Load More Text", 'nova'),
				"param_name" => "load_more_text",
				"value" => "Load More",
				"description" => __("Customize the load more text.", 'nova'),
				"dependency" => Array("element" => "enable_load_more","value" => array("yes")),
			),
			Novaworks_Shortcodes_Helper::fieldExtraClass()
		),
		'js_view' => 'VcColumnView',
		'html_template' => plugin_dir_path( dirname(__FILE__) ) . 'templates/la_timeline.php'
	),
    'la_timeline'
);