<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(

    array(
        'type'          => 'hidden',
        'param_name'    => 'scenario',
        'value'         => 'featured_products',
        'group' 		=> __('Data Setting', 'nova')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Layout','nova'),
        'param_name' => 'layout',
        'value' => array(
            __('List','nova')      => 'list',
            __('Grid','nova')      => 'grid',
            __('Masonry','nova')   => 'masonry',
        ),
        'std'   => 'grid',
        'group' 		=> __('Layout Setting', 'nova')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'list_style',
        'value' => la_get_product_list_style(),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'list'
        ),
        'std' => 'default',
        'group' 		=> __('Layout Setting', 'nova')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'grid_style',
        'value' => la_get_product_grid_style(),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'grid'
        ),
        'std' => '1',
        'group' 		=> __('Layout Setting', 'nova')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Style','nova'),
        'param_name' => 'masonry_style',
        'value' => la_get_product_grid_style(),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'masonry'
        ),
        'std' => '1',
        'group' 		=> __('Layout Setting', 'nova')
    ),

    array(
        'type' => 'autocomplete',
        'heading' => __( 'Categories', 'nova' ),
        'param_name' => 'category',
        'settings' => array(
            'multiple' => true,
            'sortable' => true,
        ),
        'save_always' => true,
        'group' 		=> __('Data Setting', 'nova')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Operator','nova'),
        'param_name' => 'operator',
        'value' => array(
            __('IN','nova') => 'IN',
            __('NOT IN','nova') => 'NOT IN',
            __('AND','nova') => 'AND',
        ),
        'std' => 'IN',
        'group' 		=> __('Data Setting', 'nova')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __( 'Order by', 'nova' ),
        'param_name' => 'orderby',
        'value' => array(
            '',
            __( 'Date', 'nova' ) => 'date',
            __( 'Menu order', 'nova' ) => 'menu_order',
            __( 'Random', 'nova' ) => 'rand',
            __( 'Popularity', 'nova' ) => 'popularity',
            __( 'Rating', 'nova' ) => 'rating',
            __( 'Title', 'nova' ) => 'title'
        ),
        'save_always' => true,
        'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'nova' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        'group' 		=> __('Data Setting', 'nova')
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
        'group' 		=> __('Data Setting', 'nova')
    ),


    array(
        'type' => 'nova_number',
        'heading' => __('Total items', 'nova'),
        'description' => __('Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'nova'),
        'param_name' => 'per_page',
        'value' => 12,
        'min' => -1,
        'max' => 1000,
        'group' 		=> __('Data Setting', 'nova')
    ),

    Novaworks_Shortcodes_Helper::getParamItemSpace(array(
        'std' => 'default',
        'dependency' => array(
            'element'   => 'layout',
            'value'     => array('grid','masonry')
        ),
        'group' 		=> __('Layout Setting', 'nova')
    )),

    array(
        'type' 			=> 'checkbox',
        'heading' 		=> __( 'Enable Custom Image Size', 'nova' ),
        'param_name' 	=> 'enable_custom_image_size',
        'value' 		=> array( __( 'Yes', 'nova' ) => 'yes' ),
        'group' 		=> __('Layout Setting', 'nova')
    ),

    Novaworks_Shortcodes_Helper::fieldImageSize(array(
        'value'			=> 'shop_catalog',
        'dependency' => array(
            'element'   => 'enable_custom_image_size',
            'value'     => 'yes'
        ),
        'group' 		=> __('Layout Setting', 'nova')
    )),

    array(
        'type' 			=> 'checkbox',
        'heading' 		=> __( 'Disable alternative image ', 'nova' ),
        'param_name' 	=> 'disable_alt_image',
        'value' 		=> array( __( 'Yes', 'nova' ) => 'yes' ),
        'group' 		=> __('Layout Setting', 'nova')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __( 'Column Type', 'nova' ),
        'param_name' => 'column_type',
        'value' => array(
            __( 'Default', 'nova' ) => 'default',
            __( 'Custom', 'nova' ) => 'custom'
        ),
        'save_always' => true,
        'dependency' => array(
            'element'   => 'layout',
            'value'     => array('masonry')
        ),
        'group' 		=> __('Layout Setting', 'nova')
    ),

    array(
        'type' => 'nova_number',
        'heading' => __('Item Width', 'nova'),
        'param_name' => 'base_item_w',
        'description' => __('Set your item default width', 'nova'),
        'value' => 300,
        'min' => 100,
        'max' => 1920,
        'suffix' => 'px',
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Layout Setting', 'nova')
    ),

    array(
        'type' => 'nova_number',
        'heading' => __('Item Height', 'nova'),
        'description' => __('Set your item default height', 'nova'),
        'param_name' => 'base_item_h',
        'value' => 300,
        'min' => 100,
        'max' => 1920,
        'suffix' => 'px',
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Layout Setting', 'nova')
    ),

    array(
        'type' 			=> 'nova_column',
        'heading' 		=> __('[Mobile] Items to show', 'nova'),
        'param_name' 	=> 'mb_columns',
        'unit'			=> '',
        'media'			=> array(
            'md'	=> 2,
            'sm'	=> 2,
            'xs'	=> 1,
            'mb'	=> 1
        ),
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Layout Setting', 'nova')
    ),

    array(
        'type'       => 'checkbox',
        'heading'    => __( 'Enable Custom Item Setting', 'nova' ),
        'param_name' => 'custom_item_size',
        'value'      => array( __( 'Yes', 'nova' ) => 'yes' ),
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Layout Setting', 'nova')
    ),

    array(
        'type' => 'param_group',
        'param_name' => 'item_sizes',
        'heading' => __( 'Item Sizes', 'nova' ),
        'params' => array(
            array(
                'type' => 'dropdown',
                'heading' => __('Width','nova'),
                'description' 	=> __('it will occupy x width of base item width ( example: this item will be occupy 2x width of base width you need entered "2")', 'nova'),
                'param_name' => 'w',
                'admin_label' => true,
                'value' => array(
                    __('1/2x width','nova')    => '0.5',
                    __('1x width','nova')      => '1',
                    __('1.5x width','nova')    => '1.5',
                    __('2x width','nova')      => '2',
                    __('2.5x width','nova')    => '2.5',
                    __('3x width','nova')      => '3',
                    __('3.5x width','nova')    => '3.5',
                    __('4x width','nova')      => '4',
                ),
                'std' => '1'
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Height','nova'),
                'description' 	=> __('it will occupy x height of base item height ( example: this item will be occupy 2x height of base height you need entered "2")', 'nova'),
                'param_name' => 'h',
                'admin_label' => true,
                'value' => array(
                    __('1/2x height','nova')    => '0.5',
                    __('1x height','nova')      => '1',
                    __('1.5x height','nova')    => '1.5',
                    __('2x height','nova')      => '2',
                    __('2.5x height','nova')    => '2.5',
                    __('3x height','nova')      => '3',
                    __('3.5x height','nova')    => '3.5',
                    __('4x height','nova')      => '4',
                ),
                'std' => '1'
            )
        ),
        'dependency' => array(
            'element'   => 'custom_item_size',
            'value'     => 'yes'
        ),
        'group' => __('Layout Setting', 'nova')
    ),

    Novaworks_Shortcodes_Helper::fieldColumn(array(
        'heading' 		=> __('Items to show', 'nova'),
        'param_name' 	=> 'columns',
        'dependency' => array(
            'callback' => 'laWoocommerceProductColumnsDependencyCallback',
        ),
        'group' 		=> __('Layout Setting', 'nova')
    )),

    array(
        'type' => 'dropdown',
        'heading' => __( 'Display Style', 'nova' ),
        'param_name' => 'display_style',
        'value' => array(
            __( 'Show All', 'nova' ) => 'all',
            __( 'Load more button', 'nova' ) => 'load-more',
            __( 'Pagination', 'nova' ) => 'pagination',
        ),
        'std' => 'all',
        'save_always' => true,
        'description' => __('Select display style', 'nova'),
        'group' 		=> __('Layout Setting', 'nova')
    ),

    array(
        'type' => 'textfield',
        'heading' => __( 'Load more text', 'nova' ),
        'param_name' => 'load_more_text',
        'dependency' => array(
            'element'   => 'display_style',
            'value'     => 'load-more'
        ),
        'group' 		=> __('Layout Setting', 'nova')
    ),

    array(
        'type'       => 'checkbox',
        'heading'    => __('Enable slider', 'nova' ),
        'param_name' => 'enable_carousel',
        'value'      => array( __( 'Yes', 'nova' ) => 'yes' ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'grid'
        ),
        'group' 		=> __('Layout Setting', 'nova')
    ),

    array(
        'type' => 'checkbox',
        'heading' => __( 'Enable Ajax Loading', 'nova' ),
        'param_name' => 'enable_ajax_loader',
        'value' => array( __( 'Yes', 'nova' ) => 'yes' ),
        'group' 		=> __('Layout Setting', 'nova')
    ),

    Novaworks_Shortcodes_Helper::fieldElementID(array(
        'group' 		=> __('Layout Setting', 'nova')
    )),

    Novaworks_Shortcodes_Helper::fieldExtraClass(array(
        'group' 		=> __('Layout Setting', 'nova')
    )),

    array(
        'type' => 'hidden',
        'heading' => __('Paged', 'nova'),
        'param_name' => 'paged',
        'value' => '1',
        'group' 		=> __('Data Setting', 'nova')
    ),
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
        'name'			=> __('Featured products', 'nova'),
        'base'			=> 'featured_products',
        'icon'          => 'icon-wpb-woocommerce',
        'category'  	=> __('La Studio', 'nova'),
        'description' 	=> __('Featured products Display products set as "featured"','nova'),
        'params' 		=> $shortcode_params
    ),
    'featured_products'
);