<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

return apply_filters(
	'Novaworks/shortcodes/configs',
	array(
		'name'			=> __('Timeline', 'nova'),
		'base'			=> 'la_timeline_item',
		'icon'          => 'nova-wpb-icon la_timeline_item',
		'category'  	=> __('La Studio', 'nova'),
		'description' 	=> __('Displays the timeline block','nova'),
		'as_child'         => array('only' => 'la_timeline'),
		'content_element'   => true,
		'params' 		=> array(
			array(
				"type" => "textfield",
				"heading" => __("Title", 'nova'),
				"param_name" => "title",
				"admin_label" => true
			),
			array(
				"type" => "textfield",
				"heading" => __("Sub-Title", 'nova'),
				"param_name" => "sub_title",
				"admin_label" => true
			),
			array(
				"type" => "textarea_html",
				"heading" => __("Content", 'nova'),
				"param_name" => "content",
			),
			array(
				"type" => "dropdown",
				"heading" => __("Apply link to:", 'nova'),
				"param_name" => "time_link_apply",
				"value" => array(
					__("None",'nova') => "",
					__("Complete box",'nova') => "box",
					__("Box Title",'nova') => "title",
					__("Display Read More",'nova') => "more",
				),
				"description" => __("Select the element for link.", 'nova')
			),
			array(
				"type" => "vc_link",
				"heading" => __("Add Link", 'nova'),
				"param_name" => "time_link",
				"dependency" => Array("element" => "time_link_apply","value" => array("more","title","box")),
				"description" => __("Provide the link that will be applied to this timeline.", 'nova')
			),
			array(
				"type" => "textfield",
				"heading" => __("Read More Text", 'nova'),
				"param_name" => "time_read_text",
				"value" => "Read More",
				"description" => __("Customize the read more text.", 'nova'),
				"dependency" => Array("element" => "time_link_apply","value" => array("more")),
			),
			array(
				'type' => 'colorpicker',
				'heading' => __('Dot Color', 'nova'),
				'param_name' => 'dot_color'
			),
			Novaworks_Shortcodes_Helper::fieldCssAnimation(),
			Novaworks_Shortcodes_Helper::fieldExtraClass()
		),
	),
    'la_timeline_item'
);