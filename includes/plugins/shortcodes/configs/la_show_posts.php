<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
//    array(
//        'type' => 'hidden',
//        'heading' => __('Layout', 'nova'),
//        'param_name' => 'layout',
//        'value' => 'blog'
//    ),
//    array(
//        'type' => 'dropdown',
//        'heading' => __('Layout','nova'),
//        'param_name' => 'layout',
//        'value' => array(
//            __('List','nova') => 'list',
//            __('Grid','nova') => 'grid',
//            __('Masonry', 'nova') => 'masonry',
//            __('Special', 'nova') => 'special'
//        ),
//        'default' => 'grid'
//    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Layout','nova'),
        'param_name' => 'layout',
        'value' => array(
            __('Default','nova') => 'grid',
            __('List','nova') => 'list',
            __('Special', 'nova') => 'special'
        ),
        'std' => 'grid'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'blog_style',
        'value' => array(
            __('Style 01','nova') => '1',
            __('Style 02','nova') => '2',
            __('Style 03','nova') => '3',
            __('Style 04','nova') => '4',
        ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'blog'
        ),
        'std' => '1'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'list_style',
        'value' => array(
            __('Style 01','nova') => '1',
            __('Style 02','nova') => '2',
        ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'list'
        ),
        'std' => '1'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'grid_style',
        'value' => array(
            __('Style 01','nova') => '1',
            __('Style 02','nova') => '2',
            __('Style 03','nova') => '3',
            __('Style 04','nova') => '4'
        ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'grid'
        ),
        'std' => '1'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'masonry_style',
        'value' => array(
            __('Style 01','nova') => '1',
            __('Style 02','nova') => '2',
            __('Style 03','nova') => '3',
            __('Style 04','nova') => '4'
        ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'masonry'
        ),
        'std' => '1'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'special_style',
        'value' => array(
            __('Default', 'nova') => '1'
        ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'special'
        ),
        'std' => '1'
    ),
    array(
        'type'       => 'autocomplete',
        'heading'    => __( 'Category In:', 'nova' ),
        'param_name' => 'category__in',
        'settings'   => array(
            'unique_values'  => true,
            'multiple'       => true,
            'sortable'       => true,
            'groups'         => false,
            'min_length'     => 0,
            'auto_focus'     => true,
            'display_inline' => true,
        ),
        'group' => __('Query Settings', 'nova')
    ),
    array(
        'type'       => 'autocomplete',
        'heading'    => __( 'Category Not In:', 'nova' ),
        'param_name' => 'category__not_in',
        'settings'   => array(
            'unique_values'  => true,
            'multiple'       => true,
            'sortable'       => true,
            'groups'         => false,
            'min_length'     => 0,
            'auto_focus'     => true,
            'display_inline' => true,
        ),
        'group' => __('Query Settings', 'nova')
    ),
    array(
        'type'       => 'autocomplete',
        'heading'    => __( 'Post In:', 'nova' ),
        'param_name' => 'post__in',
        'settings'   => array(
            'unique_values'  => true,
            'multiple'       => true,
            'sortable'       => true,
            'groups'         => false,
            'min_length'     => 0,
            'auto_focus'     => true,
            'display_inline' => true,
        ),
        'group' => __('Query Settings', 'nova')
    ),
    array(
        'type'       => 'autocomplete',
        'heading'    => __( 'Post Not In:', 'nova' ),
        'param_name' => 'post__not_in',
        'settings'   => array(
            'unique_values'  => true,
            'multiple'       => true,
            'sortable'       => true,
            'groups'         => false,
            'min_length'     => 0,
            'auto_focus'     => true,
            'display_inline' => true,
        ),
        'group' => __('Query Settings', 'nova')
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Order by', 'nova' ),
        'param_name' => 'orderby',
        'value' => array(
            '',
            __( 'Date', 'nova' ) => 'date',
            __( 'ID', 'nova' ) => 'ID',
            __( 'Author', 'nova' ) => 'author',
            __( 'Title', 'nova' ) => 'title',
            __( 'Modified', 'nova' ) => 'modified',
            __( 'Random', 'nova' ) => 'rand',
            __( 'Comment count', 'nova' ) => 'comment_count',
            __( 'Menu order', 'nova' ) => 'menu_order',
        ),
        'save_always' => true,
        'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'nova' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        'group' => __('Query Settings', 'nova')
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Sort order', 'nova' ),
        'param_name' => 'order',
        'value' => array(
            '',
            __( 'Descending', 'nova' ) => 'DESC',
            __( 'Ascending', 'nova' ) => 'ASC',
        ),
        'save_always' => true,
        'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'nova' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        'group' => __('Query Settings', 'nova')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Item title tag','nova'),
        'param_name' => 'title_tag',
        'value' => array(
            __('Default','nova') => 'h3',
            __('H1','nova') => 'h1',
            __('H2','nova') => 'h2',
            __('H4','nova') => 'h4',
            __('H5','nova') => 'h5',
            __('H6','nova') => 'h6',
            __('DIV','nova') => 'div',
        ),
        'std' => 'h3',
        'description' => __('Default is H3', 'nova'),
        'group' => __('Item Settings', 'nova')
    ),
    array(
        'type' => 'nova_number',
        'heading' => __('Excerpt Length', 'nova'),
        'param_name' => 'excerpt_length',
        'value' => 20,
        'min' => 1,
        'max' => 100,
        'suffix' => '',
        'group' => __('Item Settings', 'nova')
    ),
    Novaworks_Shortcodes_Helper::fieldImageSize(array(
        'group' => __('Item Settings', 'nova')
    )),
    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Image size 2', 'nova'),
        'param_name' 	=> 'img_size2',
        'value'			=> 'thumbnail',
        'description' 	=> __('Enter image size ( For Special Style ).', 'nova'),
        'group'         => __('Item Settings', 'nova'),
        'dependency' => array(
            'element'   => 'special_style',
            'value'     => array('1')
        )
    ),
    Novaworks_Shortcodes_Helper::fieldColumn(array(
        'heading' 		=> __('Items to show', 'nova'),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => array('grid','masonry','blog')
        ),
    )),
    Novaworks_Shortcodes_Helper::getParamItemSpace(array(
        'std'        => 'default',
        'dependency' => array(
            'element'=> 'layout',
            'value'  => 'blog'
        )
    )),
    array(
        'type'       => 'checkbox',
        'heading'    => __('Enable slider', 'nova' ),
        'param_name' => 'enable_carousel',
        'value'      => array( __( 'Yes', 'nova' ) => 'yes' ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => array('grid','blog')
        )
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Display Style','nova'),
        'description' => __('Select display style for grid.', 'nova'),
        'param_name' => 'style',
        'value' => array(
            __('Show all','nova') => 'all',
            __('Load more button','nova') => 'load-more',
            __('Pagination','nova') => 'pagination',
        ),
        'std' => 'all',
        'dependency' => array(
            'element'=> 'layout',
            'value'  => array('blog', 'list')
        )
    ),

    array(
        'type' => 'nova_number',
        'heading' => __('Number items showing', 'nova'),
        'param_name' => 'items_per_page',
        'description' => __('Number of items to show per page.', 'nova'),
        'value' => 4,
        'min' => 1,
        'max' => 1000,
        'dependency' => array(
            'element'   => 'style',
            'value'     => array('load-more','pagination')
        )
    ),

    array(
        'type' => 'nova_number',
        'heading' => __('Total items', 'nova'),
        'description' => __('Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'nova'),
        'param_name' => 'per_page',
        'value' => 4,
        'min' => -1,
        'max' => 1000
    ),
    array(
        'type' => 'hidden',
        'heading' => __('Paged', 'nova'),
        'param_name' => 'paged',
        'value' => '1'
    ),

    array(
        'type' => 'textfield',
        'heading' => __('Load more text', 'nova'),
        'param_name' => 'load_more_text',
        'value' => 'Read more',
        'dependency' => array(
            'element'   => 'style',
            'value'     => 'load-more'
        )
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
        'name'			=> __('Blog Posts', 'nova'),
        'base'			=> 'la_show_posts',
        'icon'          => 'icon-wpb-application-icon-large',
        'category'  	=> __('La Studio', 'nova'),
        'description' 	=> __('Display posts with la-studio themes style.','nova'),
        'params' 		=> $shortcode_params
    ),
    'la_show_posts'
);
