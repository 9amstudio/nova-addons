<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        "type" => "textfield",
        "heading" => __("Width (in %)", 'lastudio'),
        "param_name" => "width",
        "admin_label" => true,
        "value" => "100%",
        "group" => __("General Settings", 'lastudio')
    ),
    array(
        "type" => "textfield",
        "heading" => __("Height (in px)", 'lastudio'),
        "param_name" => "height",
        "admin_label" => true,
        "value" => "300px",
        "group" => __("General Settings", 'lastudio')
    ),
    array(
        "type" => "dropdown",
        "heading" => __("Map type", 'lastudio'),
        "param_name" => "map_type",
        "admin_label" => true,
        "value" => array(__("Roadmap", 'lastudio') => "ROADMAP", __("Satellite", 'lastudio') => "SATELLITE", __("Hybrid", 'lastudio') => "HYBRID", __("Terrain", 'lastudio') => "TERRAIN"),
        "group" => __("General Settings", 'lastudio')
    ),
    array(
        "type" => "textfield",
        "heading" => __("Latitude", 'lastudio'),
        "param_name" => "lat",
        "admin_label" => true,
        "value" => "21.027764",
        "description" => '<a href="http://www.latlong.net/" target="_blank">' . __('Here is a tool', 'lastudio') . '</a> ' . __('where you can find Latitude & Longitude of your location', 'lastudio'),
        "group" => __("General Settings", 'lastudio')
    ),
    array(
        "type" => "textfield",
        "heading" => __("Longitude", 'lastudio'),
        "param_name" => "lng",
        "admin_label" => true,
        "value" => "105.834160",
        "description" => '<a href="http://www.latlong.net/" target="_blank">' . __('Here is a tool', 'lastudio') . '</a> ' . __('where you can find Latitude & Longitude of your location', 'lastudio'),
        "group" => __("General Settings", 'lastudio')
    ),
    array(
        "type" => "dropdown",
        "heading" => __("Map Zoom", 'lastudio'),
        "param_name" => "zoom",
        "value" => array(
            __("12 - Default", 'lastudio') => 12, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 16, 17, 18, 19, 20
        ),
        "group" => __("General Settings", 'lastudio')
    ),
    array(
        "type" => "checkbox",
        "param_name" => "scrollwheel",
        "value" => array(
            __("Disable map zoom on mouse wheel scroll", 'lastudio') => "disable",
        ),
        "group" => __("General Settings", 'lastudio')
    ),
    array(
        "type" => "textarea_html",
        "heading" => __("Info Window Text", 'lastudio'),
        "param_name" => "content",
        "group" => __("Info Window", 'lastudio')
    ),

    array(
        'type' => 'checkbox',
        'heading' => __('Open on Marker Click', 'lastudio'),
        'param_name' => 'infowindow_open',
        'value' => array(__('Yes', 'lastudio') => 'yes'),
        'description' => __('Use font family from the theme.', 'lastudio'),
        "group" => __("Info Window", 'lastudio')
    ),

    array(
        "type" => "dropdown",
        "heading" => __("Marker/Point icon", 'lastudio'),
        "param_name" => "marker_icon",
        "value" => array(__("Use Google Default", 'lastudio') => "default", __("Use Plugin's Default", 'lastudio') => "default_self", __("Upload Custom", 'lastudio') => "custom"),
        "group" => __("Marker", 'lastudio')
    ),

    array(
        "type" => "attach_image",
        "heading" => __("Upload Image Icon", 'lastudio'),
        "param_name" => "icon_img",
        "description" => __("Upload the custom image icon.", 'lastudio'),
        "dependency" => array("element" => "marker_icon", "value" => array("custom")),
        "group" => __("Marker", 'lastudio')
    ),
    array(
        "type" => "textfield",
        "heading" => __("Icon Image Url", 'lastudio'),
        "param_name" => "icon_img_url",
        "dependency" => array("element" => "marker_icon", "value" => array("custom")),
        "group" => __("Marker", 'lastudio')
    ),
    array(
        "type" => "dropdown",
        "heading" => __("Street view control", 'lastudio'),
        "param_name" => "streetviewcontrol",
        "value" => array(__("Disable", 'lastudio') => "false", __("Enable", 'lastudio') => "true"),
        "group" => __("Advanced", 'lastudio')
    ),
    array(
        "type" => "dropdown",
        "heading" => __("Map type control", 'lastudio'),
        "param_name" => "maptypecontrol",
        "value" => array(__("Disable", 'lastudio') => "false", __("Enable", 'lastudio') => "true"),
        "group" => __("Advanced", 'lastudio')
    ),
    array(
        "type" => "dropdown",
        "heading" => __("Zoom control", 'lastudio'),
        "param_name" => "zoomcontrol",
        "value" => array(__("Disable", 'lastudio') => "false", __("Enable", 'lastudio') => "true"),
        "group" => __("Advanced", 'lastudio')
    ),
    array(
        "type" => "dropdown",
        "heading" => __("Zoom control size", 'lastudio'),
        "param_name" => "zoomcontrolsize",
        "value" => array(__("Small", 'lastudio') => "SMALL", __("Large", 'lastudio') => "LARGE"),
        "dependency" => array("element" => "zoomControl", "value" => array("true")),
        "group" => __("Advanced", 'lastudio')
    ),

    array(
        "type" => "dropdown",
        "heading" => __("Disable dragging on Mobile", 'lastudio'),
        "param_name" => "dragging",
        "value" => array(__("Enable", 'lastudio') => "true", __("Disable", 'lastudio') => "false"),
        "group" => __("Advanced", 'lastudio')
    ),
    array(
        "type" => "textarea_raw_html",
        "heading" => __("Google Styled Map JSON", 'lastudio'),
        "param_name" => "map_style",
        "description" => "<a target='_blank' href='https://snazzymaps.com/'>" . __("Click here", 'lastudio') . "</a> " . __("to get the style JSON code for styling your map.", 'lastudio'),
        "group" => __("Styling", 'lastudio'),
    ),
    LaStudio_Shortcodes_Helper::fieldExtraClass(array("group" => __("General Settings", 'lastudio'))),
    LaStudio_Shortcodes_Helper::fieldCssClass()
);

$shortcode_params = array_merge($shortcode_params);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name' => __('La Google Maps', 'lastudio'),
        'base' => 'la_maps',
        'category' => __('La Studio', 'lastudio'),
        'icon'  => 'la_maps',
        'description' => __('Display Google Maps to indicate your location.', 'lastudio'),
        'params' => $shortcode_params
    ),
    'la_maps'
);