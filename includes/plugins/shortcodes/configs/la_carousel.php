<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

if ( class_exists('WPBakeryShortCodesContainer') && !class_exists( 'WPBakeryShortCode_la_carousel' ) ) {
	class WPBakeryShortCode_la_carousel extends WPBakeryShortCodesContainer{

	}
}

return apply_filters(
	'Novaworks/shortcodes/configs',
	array(
		'name'			=> __('LA Advanced Carousel', 'nova'),
		'base'			=> 'la_carousel',
		'icon'          => 'nova-wpb-icon la_carousel',
		'category'  	=> __('La Studio', 'nova'),
		'description' 	=> __('Carousel anything.','nova'),
		'as_parent'     => array( 'except' => array(
			'la_carousel'
		) ),
		'content_element'=> true,
		'controls'       => 'full',
		'show_settings_on_create' => true,
		'params' 		=> Novaworks_Shortcodes_Helper::paramCarouselShortCode(),
		'js_view' 		=> 'VcColumnView',
		'html_template' => plugin_dir_path( dirname(__FILE__) ) . 'templates/la_carousel.php'
	),
    'la_carousel'
);