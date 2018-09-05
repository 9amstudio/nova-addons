<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        "type" => "textfield",
        "heading" => __("Width (in %)", 'nova'),
        "param_name" => "width",
        "admin_label" => true,
        "value" => "100%",
        "group" => __("General Settings", 'nova')
    ),
    array(
        "type" => "textfield",
        "heading" => __("Height (in px)", 'nova'),
        "param_name" => "height",
        "admin_label" => true,
        "value" => "300px",
        "group" => __("General Settings", 'nova')
    ),
    array(
        "type" => "dropdown",
        "heading" => __("Map type", 'nova'),
        "param_name" => "map_type",
        "admin_label" => true,
        "value" => array(__("Roadmap", 'nova') => "ROADMAP", __("Satellite", 'nova') => "SATELLITE", __("Hybrid", 'nova') => "HYBRID", __("Terrain", 'nova') => "TERRAIN"),
        "group" => __("General Settings", 'nova')
    ),
    array(
        "type" => "textfield",
        "heading" => __("Latitude", 'nova'),
        "param_name" => "lat",
        "admin_label" => true,
        "value" => "21.027764",
        "description" => '<a href="http://www.latlong.net/" target="_blank">' . __('Here is a tool', 'nova') . '</a> ' . __('where you can find Latitude & Longitude of your location', 'nova'),
        "group" => __("General Settings", 'nova')
    ),
    array(
        "type" => "textfield",
        "heading" => __("Longitude", 'nova'),
        "param_name" => "lng",
        "admin_label" => true,
        "value" => "105.834160",
        "description" => '<a href="http://www.latlong.net/" target="_blank">' . __('Here is a tool', 'nova') . '</a> ' . __('where you can find Latitude & Longitude of your location', 'nova'),
        "group" => __("General Settings", 'nova')
    ),
    array(
        "type" => "dropdown",
        "heading" => __("Map Zoom", 'nova'),
        "param_name" => "zoom",
        "value" => array(
            __("12 - Default", 'nova') => 12, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18, 19, 20
        ),
        "group" => __("General Settings", 'nova')
    ),
    array(
        "type" => "checkbox",
        "param_name" => "scrollwheel",
        "value" => array(
            __("Disable map zoom on mouse wheel scroll", 'nova') => "disable",
        ),
        "group" => __("General Settings", 'nova')
    ),
    array(
        "type" => "textarea_html",
        "heading" => __("Info Window Text", 'nova'),
        "param_name" => "content",
        "group" => __("Info Window", 'nova')
    ),

    array(
        'type' => 'checkbox',
        'heading' => __('Open on Marker Click', 'nova'),
        'param_name' => 'infowindow_open',
        'value' => array(__('Yes', 'nova') => 'yes'),
        'description' => __('Use font family from the theme.', 'nova'),
        "group" => __("Info Window", 'nova')
    ),

    array(
        "type" => "dropdown",
        "heading" => __("Marker/Point icon", 'nova'),
        "param_name" => "marker_icon",
        "value" => array(__("Use Google Default", 'nova') => "default", __("Use Plugin's Default", 'nova') => "default_self", __("Upload Custom", 'nova') => "custom"),
        "group" => __("Marker", 'nova')
    ),

    array(
        "type" => "attach_image",
        "heading" => __("Upload Image Icon", 'nova'),
        "param_name" => "icon_img",
        "description" => __("Upload the custom image icon.", 'nova'),
        "dependency" => array("element" => "marker_icon", "value" => array("custom")),
        "group" => __("Marker", 'nova')
    ),
    array(
        "type" => "textfield",
        "heading" => __("Icon Image Url", 'nova'),
        "param_name" => "icon_img_url",
        "dependency" => array("element" => "marker_icon", "value" => array("custom")),
        "group" => __("Marker", 'nova')
    ),
    array(
        "type" => "dropdown",
        "heading" => __("Street view control", 'nova'),
        "param_name" => "streetviewcontrol",
        "value" => array(__("Disable", 'nova') => "false", __("Enable", 'nova') => "true"),
        "group" => __("Advanced", 'nova')
    ),
    array(
        "type" => "dropdown",
        "heading" => __("Map type control", 'nova'),
        "param_name" => "maptypecontrol",
        "value" => array(__("Disable", 'nova') => "false", __("Enable", 'nova') => "true"),
        "group" => __("Advanced", 'nova')
    ),
    array(
        "type" => "dropdown",
        "heading" => __("Zoom control", 'nova'),
        "param_name" => "zoomcontrol",
        "value" => array(__("Disable", 'nova') => "false", __("Enable", 'nova') => "true"),
        "group" => __("Advanced", 'nova')
    ),
    array(
        "type" => "dropdown",
        "heading" => __("Zoom control size", 'nova'),
        "param_name" => "zoomcontrolsize",
        "value" => array(__("Small", 'nova') => "SMALL", __("Large", 'nova') => "LARGE"),
        "dependency" => array("element" => "zoomControl", "value" => array("true")),
        "group" => __("Advanced", 'nova')
    ),

    array(
        "type" => "dropdown",
        "heading" => __("Disable dragging on Mobile", 'nova'),
        "param_name" => "dragging",
        "value" => array(__("Enable", 'nova') => "true", __("Disable", 'nova') => "false"),
        "group" => __("Advanced", 'nova')
    ),
    array(
        "type" => "textarea_raw_html",
        "heading" => __("Google Styled Map JSON", 'nova'),
        "param_name" => "map_style",
        "description" => "<a target='_blank' href='https://snazzymaps.com/'>" . __("Click here", 'nova') . "</a> " . __("to get the style JSON code for styling your map.", 'nova'),
        "group" => __("Styling", 'nova'),
    ),
    Novaworks_Shortcodes_Helper::fieldExtraClass(array("group" => __("General Settings", 'nova'))),
    Novaworks_Shortcodes_Helper::fieldCssClass()
);

$shortcode_params = array_merge($shortcode_params);

return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name' => __('La Google Maps', 'nova'),
        'base' => 'la_maps',
        'category' => __('La Studio', 'nova'),
        'icon'  => 'la_maps',
        'description' => __('Display Google Maps to indicate your location.', 'nova'),
        'params' => $shortcode_params
    ),
    'la_maps'
);