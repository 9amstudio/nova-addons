<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'dropdown',
        'heading' => __('Feed Type','nova'),
        'param_name' => 'feed_type',
        'value' => array(
            __('Images with a specific tag','nova') => 'tagged',
            __('Images from a location.','nova') => 'location',
            __('Images from a user','nova') => 'user'
        ),
        'admin_label' => true,
        'std' => 'user'
    ),
    array(
        'type' => 'textfield',
        'heading' => __('Hashtag', 'nova'),
        'description' => __('Only Alphanumeric characters are allowed (a-z, A-Z, 0-9)', 'nova'),
        'param_name' => 'hashtag',
        'admin_label' => true
    ),
    array(
        'type' => 'textfield',
        'heading' => __('Location ID', 'nova'),
        'description' => __('Unique id of a location to get', 'nova'),
        'param_name' => 'location_id'
    ),
    array(
        'type' => 'textfield',
        'heading' => __('User ID', 'nova'),
        'description' => __('Unique id of a user to get', 'nova'),
        'param_name' => 'user_id'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Sort By','nova'),
        'param_name' => 'sort_by',
        'admin_label' => true,
        'value' => array(
            __('Default','nova') => 'none',
            __('Newest to oldest','nova') => 'most-recent',
            __('Oldest to newest','nova') => 'least-recent',
            __('Highest # of likes to lowest.','nova') => 'most-liked',
            __('Lowest # likes to highest.','nova') => 'least-liked',
            __('Highest # of comments to lowest','nova') => 'most-commented',
            __('Lowest # of comments to highest.','nova') => 'least-commented',
            __('Random order','nova') => 'random',
        ),
        'std' => 'none'
    ),

    Novaworks_Shortcodes_Helper::fieldColumn(array(
        'heading' 		=> __('Items to show', 'nova')
    )),
    Novaworks_Shortcodes_Helper::getParamItemSpace(array(
        'std' => 'default'
    )),

    array(
        'type'       => 'checkbox',
        'heading'    => __('Enable slider', 'nova' ),
        'param_name' => 'enable_carousel',
        'value'      => array( __( 'Yes', 'nova' ) => 'yes' )
    ),

    array(
        'type' => 'textfield',
        'heading' => __('Limit', 'nova'),
        'description' => __('Maximum number of Images to add. Max of 60', 'nova'),
        'param_name' => 'limit',
        'admin_label' => true,
        'value' => 5
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Image size','nova'),
        'param_name' => 'image_size',
        'value' => array(
            __('Thumbnail','nova') => 'thumbnail',
            __('Low Resolution','nova') => 'low_resolution',
            __('Standard Resolution','nova') => 'standard_resolution'
        ),
        'std' => 'thumbnail'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Image Aspect Ration','nova'),
        'param_name' => 'image_aspect_ration',
        'value' => array(
            __('1:1','nova') => '11',
            __('16:9','nova') => '169',
            __('4:3','nova') => '43',
            __('2.35:1','nova') => '2351'
        ),
        'std' => '11'
    ),
    Novaworks_Shortcodes_Helper::fieldExtraClass()
);

$carousel = Novaworks_Shortcodes_Helper::paramCarouselShortCode(false);
$slides_column_idx = Novaworks_Shortcodes_Helper::getParamIndex( $carousel, 'slides_column');
if($slides_column_idx){
    unset($carousel[$slides_column_idx]);
}

$shortcode_params = array_merge( $shortcode_params, $carousel);

return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Instagram Feed', 'nova'),
        'base'			=> 'la_instagram_feed',
        'icon'          => 'nova-wpb-icon la_instagram_feed',
        'category'  	=> __('La Studio', 'nova'),
        'description'   => __('Display Instagram photos from any non-private Instagram accounts', 'nova'),
        'params' 		=> $shortcode_params
    ),
    'la_instagram_feed'
);