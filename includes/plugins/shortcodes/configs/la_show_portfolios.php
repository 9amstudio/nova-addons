<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(
    array(
        'type' => 'dropdown',
        'heading' => __('Layout','nova'),
        'param_name' => 'layout',
        'value' => array(
            __('Grid','nova') => 'grid'
        ),
        'default' => 'grid'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'grid_style',
        'value' => array(
            __('Design 01','nova') => '1',
            __('Design 02','nova') => '2',
            __('Design 03','nova') => '3',
            __('Design 04','nova') => '4',
            __('Design 05','nova') => '5',
            __('Design 06','nova') => '6',
            __('Design 07','nova') => '7',
            __('Design 08','nova') => '8'
        ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'grid'
        ),
        'default' => '1'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'list_style',
        'value' => array(
            __('Classic 01','nova') => '1',
            __('Classic 02','nova') => '2',
            __('Classic 03','nova') => '3'
        ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'list'
        ),
        'default' => '1'
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
        'type' => 'nova_number',
        'heading' => __('Total items', 'nova'),
        'description' => __('Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'nova'),
        'param_name' => 'per_page',
        'value' => -1,
        'min' => -1,
        'max' => 1000,
        'group' => __('Query Settings', 'nova')
    ),
    array(
        'type' => 'hidden',
        'heading' => __('Paged', 'nova'),
        'param_name' => 'paged',
        'value' => '1',
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
        'default' => 'h3',
        'description' => __('Default is H3', 'nova'),
        'group' => __('Item Settings', 'nova')
    ),
    Novaworks_Shortcodes_Helper::fieldImageSize(array(
        'group' => __('Item Settings', 'nova')
    )),

    Novaworks_Shortcodes_Helper::fieldColumn(array(
        'heading' 		=> __('Items to show', 'nova'),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => array('grid', 'masonry')
        ),
    )),

    Novaworks_Shortcodes_Helper::getParamItemSpace(),

    array(
        'type'       => 'checkbox',
        'heading'    => __('Enable slider', 'nova' ),
        'param_name' => 'enable_carousel',
        'value'      => array( __( 'Yes', 'nova' ) => 'yes' ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => array('grid')
        ),
    ),
    array(
        'type'       => 'checkbox',
        'heading'    => __( 'Enable Load More', 'nova' ),
        'param_name' => 'enable_loadmore',
        'value'      => array( __( 'Yes', 'nova' ) => 'yes' )
    ),
    array(
        'type' => 'textfield',
        'heading' => __('Load More Text', 'nova'),
        'param_name' => 'load_more_text',
        'value' => __('Load more', 'nova'),
        'dependency' => array( 'element' => 'enable_loadmore', 'value' => 'yes' ),
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
        'name'			=> __('Show Portfolios', 'nova'),
        'base'			=> 'la_show_portfolios',
        'icon'          => 'nova-wpb-icon la_show_portfolios',
        'category'  	=> __('La Studio', 'nova'),
        'description' 	=> __('Display portfolio with la-studio themes style.','nova'),
        'params' 		=> $shortcode_params
    ),
    'la_show_portfolios'
);