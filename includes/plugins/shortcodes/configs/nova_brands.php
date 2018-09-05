<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
      'heading'     => esc_html__( 'Image source', 'nova' ),
      'description' => esc_html__( 'Select images source', 'nova' ),
      'type'        => 'dropdown',
      'param_name'  => 'source',
      'value'       => array(
        esc_html__( 'Media library', 'nova' )  => 'media_library',
        esc_html__( 'External Links', 'nova' ) => 'external_link',
      ),
    ),
    array(
      'heading'     => esc_html__( 'Images', 'nova' ),
      'description' => esc_html__( 'Select images from media library', 'nova' ),
      'type'        => 'attach_images',
      'param_name'  => 'images',
      'dependency'  => array(
        'element' => 'source',
        'value'   => 'media_library',
      ),
    ),
    array(
      'heading'     => esc_html__( 'Image size', 'nova' ),
      'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)). Leave empty to use "thumbnail" size.', 'nova' ),
      'type'        => 'textfield',
      'param_name'  => 'img_size',
      'dependency'  => array(
        'element' => 'source',
        'value'   => 'media_library',
      ),
    ),
    array(
      'heading'     => esc_html__( 'External links', 'nova' ),
      'description' => esc_html__( 'Enter external links for partner logos (Note: divide links with linebreaks (Enter)).', 'nova' ),
      'type'        => 'exploded_textarea_safe',
      'param_name'  => 'custom_srcs',
      'dependency'  => array(
        'element' => 'source',
        'value'   => 'external_link',
      ),
    ),
    array(
      'heading'     => esc_html__( 'Image size', 'nova' ),
      'description' => esc_html__( 'Enter image size in pixels. Example: 200x100 (Width x Height).', 'nova' ),
      'type'        => 'textfield',
      'param_name'  => 'external_img_size',
      'dependency'  => array(
        'element' => 'source',
        'value'   => 'external_link',
      ),
    ),
    array(
      'heading'     => esc_html__( 'Custom links', 'nova' ),
      'description' => esc_html__( 'Enter links for each image here. Divide links with linebreaks (Enter).', 'nova' ),
      'type'        => 'exploded_textarea_safe',
      'param_name'  => 'custom_links',
    ),
    array(
      'heading'     => esc_html__( 'Custom link target', 'nova' ),
      'description' => esc_html__( 'Select where to open custom links.', 'nova' ),
      'type'        => 'dropdown',
      'param_name'  => 'custom_links_target',
      'value'       => array(
        esc_html__( 'Same window', 'nova' ) => '_self',
        esc_html__( 'New window', 'nova' )  => '_blank',
      ),
    ),
    array(
      'heading'     => esc_html__( 'Layout', 'nova' ),
      'description' => esc_html__( 'Select the layout images source', 'nova' ),
      'type'        => 'dropdown',
      'param_name'  => 'layout',
      'value'       => array(
        esc_html__( 'Bordered', 'nova' ) => 'bordered',
        esc_html__( 'Plain', 'nova' )    => 'plain',
      ),
    ),
    Novaworks_Shortcodes_Helper::fieldColumnGrid(array(
        'heading' 		=> __('Items per row', 'nova'),
        'media'			=> array(
            'lg'	=> 3,
            'md'	=> 3,
            'mb'  => 2
        )
    )),
    Novaworks_Shortcodes_Helper::fieldExtraClass()
);

return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Brands List', 'nova'),
        'base'			=> 'nova_brands',
        'icon'          => 'nova-wpb-icon nova_brands',
        'category'  	=> __('9AM Studio', 'nova'),
        'description' 	=> __('Show list of partner logo.','nova'),
        'params' 		=> $shortcode_params
    ),
    'nova_divider'
);
