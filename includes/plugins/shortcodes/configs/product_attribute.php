<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$attributes_tax = wc_get_attribute_taxonomies();
$attributes = array();
foreach ( $attributes_tax as $attribute ) {
    $attributes[ $attribute->attribute_label ] = $attribute->attribute_name;
}

$shortcode_params = array(

    array(
        'type'          => 'hidden',
        'param_name'    => 'scenario',
        'value'         => 'product_attribute',
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Layout','lastudio'),
        'param_name' => 'layout',
        'value' => array(
            __('List','lastudio')      => 'list',
            __('Grid','lastudio')      => 'grid',
            __('Masonry','lastudio')   => 'masonry',
        ),
        'std'   => 'grid',
        'group' 		=> __('Layout Setting', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Style','lastudio'),
        'param_name' => 'list_style',
        'value' => la_get_product_list_style(),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'list'
        ),
        'std' => 'default',
        'group' 		=> __('Layout Setting', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Style','lastudio'),
        'param_name' => 'grid_style',
        'value' => la_get_product_grid_style(),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'grid'
        ),
        'std' => '1',
        'group' 		=> __('Layout Setting', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Style','lastudio'),
        'param_name' => 'masonry_style',
        'value' => la_get_product_grid_style(),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'masonry'
        ),
        'std' => '1',
        'group' 		=> __('Layout Setting', 'lastudio')
    ),

    array(
        'type' => 'autocomplete',
        'heading' => __( 'Categories', 'lastudio' ),
        'param_name' => 'category',
        'settings' => array(
            'multiple' => true,
            'sortable' => true,
        ),
        'save_always' => true,
        'dependency' => array(
            'element'   => 'scenario',
            'value_not_equal_to'     => array('products')
        ),
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Operator','lastudio'),
        'param_name' => 'operator',
        'value' => array(
            __('IN','lastudio') => 'IN',
            __('NOT IN','lastudio') => 'NOT IN',
            __('AND','lastudio') => 'AND',
        ),
        'std' => 'IN',
        'dependency' => array(
            'element'   => 'scenario',
            'value_not_equal_to'     => array('products')
        ),
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __( 'Attribute', 'lastudio' ),
        'param_name' => 'attribute',
        'value' => $attributes,
        'save_always' => true,
        'description' => __( 'List of product taxonomy attribute', 'lastudio' ),
        'dependency' => array(
            'element'   => 'scenario',
            'value'     => array('product_attribute')
        ),
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'checkbox',
        'heading' => __( 'Filter', 'lastudio' ),
        'param_name' => 'filter',
        'value' => array( 'empty' => 'empty' ),
        'save_always' => true,
        'description' => __( 'Taxonomy values', 'lastudio' ),
        'dependency' => array(
            'callback' => 'laWoocommerceProductAttributeFilterDependencyCallback',
        ),
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __( 'Order by', 'lastudio' ),
        'param_name' => 'orderby',
        'value' => array(
            '',
            __( 'Date', 'lastudio' ) => 'date',
            __( 'Menu order', 'lastudio' ) => 'menu_order',
            __( 'Random', 'lastudio' ) => 'rand',
            __( 'Popularity', 'lastudio' ) => 'popularity',
            __( 'Rating', 'lastudio' ) => 'rating',
            __( 'Title', 'lastudio' ) => 'title'
        ),
        'save_always' => true,
        'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'lastudio' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        'dependency' => array(
            'element'   => 'scenario',
            'value_not_equal_to'     => array('products')
        ),
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __( 'Sort order', 'lastudio' ),
        'param_name' => 'order',
        'value' => array(
            '',
            __( 'Descending', 'lastudio' ) => 'DESC',
            __( 'Ascending', 'lastudio' ) => 'ASC',
        ),
        'save_always' => true,
        'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'lastudio' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        'dependency' => array(
            'element'   => 'scenario',
            'value_not_equal_to'     => array('products')
        ),
        'group' 		=> __('Data Setting', 'lastudio')
    ),


    array(
        'type' => 'la_number',
        'heading' => __('Total items', 'lastudio'),
        'description' => __('Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'lastudio'),
        'param_name' => 'per_page',
        'value' => 12,
        'min' => -1,
        'max' => 1000,
        'group' 		=> __('Data Setting', 'lastudio')
    ),

    LaStudio_Shortcodes_Helper::getParamItemSpace(array(
        'std' => 'default',
        'dependency' => array(
            'element'   => 'layout',
            'value'     => array('grid','masonry')
        ),
        'group' 		=> __('Layout Setting', 'lastudio')
    )),

    array(
        'type' 			=> 'checkbox',
        'heading' 		=> __( 'Enable Custom Image Size', 'lastudio' ),
        'param_name' 	=> 'enable_custom_image_size',
        'value' 		=> array( __( 'Yes', 'lastudio' ) => 'yes' ),
        'group' 		=> __('Layout Setting', 'lastudio')
    ),

    LaStudio_Shortcodes_Helper::fieldImageSize(array(
        'value'			=> 'shop_catalog',
        'dependency' => array(
            'element'   => 'enable_custom_image_size',
            'value'     => 'yes'
        ),
        'group' 		=> __('Layout Setting', 'lastudio')
    )),

    array(
        'type' 			=> 'checkbox',
        'heading' 		=> __( 'Disable alternative image ', 'lastudio' ),
        'param_name' 	=> 'disable_alt_image',
        'value' 		=> array( __( 'Yes', 'lastudio' ) => 'yes' ),
        'group' 		=> __('Layout Setting', 'lastudio')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __( 'Column Type', 'lastudio' ),
        'param_name' => 'column_type',
        'value' => array(
            __( 'Default', 'lastudio' ) => 'default',
            __( 'Custom', 'lastudio' ) => 'custom'
        ),
        'save_always' => true,
        'dependency' => array(
            'element'   => 'layout',
            'value'     => array('masonry')
        ),
        'group' 		=> __('Layout Setting', 'lastudio')
    ),

    array(
        'type' => 'la_number',
        'heading' => __('Item Width', 'lastudio'),
        'param_name' => 'base_item_w',
        'description' => __('Set your item default width', 'lastudio'),
        'value' => 300,
        'min' => 100,
        'max' => 1920,
        'suffix' => 'px',
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Layout Setting', 'lastudio')
    ),

    array(
        'type' => 'la_number',
        'heading' => __('Item Height', 'lastudio'),
        'description' => __('Set your item default height', 'lastudio'),
        'param_name' => 'base_item_h',
        'value' => 300,
        'min' => 100,
        'max' => 1920,
        'suffix' => 'px',
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Layout Setting', 'lastudio')
    ),

    array(
        'type' 			=> 'la_column',
        'heading' 		=> __('[Mobile] Items to show', 'lastudio'),
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
        'group' => __('Layout Setting', 'lastudio')
    ),

    array(
        'type'       => 'checkbox',
        'heading'    => __( 'Enable Custom Item Setting', 'lastudio' ),
        'param_name' => 'custom_item_size',
        'value'      => array( __( 'Yes', 'lastudio' ) => 'yes' ),
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Layout Setting', 'lastudio')
    ),

    array(
        'type' => 'param_group',
        'param_name' => 'item_sizes',
        'heading' => __( 'Item Sizes', 'lastudio' ),
        'params' => array(
            array(
                'type' => 'dropdown',
                'heading' => __('Width','lastudio'),
                'description' 	=> __('it will occupy x width of base item width ( example: this item will be occupy 2x width of base width you need entered "2")', 'lastudio'),
                'param_name' => 'w',
                'admin_label' => true,
                'value' => array(
                    __('1/2x width','lastudio')    => '0.5',
                    __('1x width','lastudio')      => '1',
                    __('1.5x width','lastudio')    => '1.5',
                    __('2x width','lastudio')      => '2',
                    __('2.5x width','lastudio')    => '2.5',
                    __('3x width','lastudio')      => '3',
                    __('3.5x width','lastudio')    => '3.5',
                    __('4x width','lastudio')      => '4',
                ),
                'std' => '1'
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Height','lastudio'),
                'description' 	=> __('it will occupy x height of base item height ( example: this item will be occupy 2x height of base height you need entered "2")', 'lastudio'),
                'param_name' => 'h',
                'admin_label' => true,
                'value' => array(
                    __('1/2x height','lastudio')    => '0.5',
                    __('1x height','lastudio')      => '1',
                    __('1.5x height','lastudio')    => '1.5',
                    __('2x height','lastudio')      => '2',
                    __('2.5x height','lastudio')    => '2.5',
                    __('3x height','lastudio')      => '3',
                    __('3.5x height','lastudio')    => '3.5',
                    __('4x height','lastudio')      => '4',
                ),
                'std' => '1'
            )
        ),
        'dependency' => array(
            'element'   => 'custom_item_size',
            'value'     => 'yes'
        ),
        'group' => __('Layout Setting', 'lastudio')
    ),

    LaStudio_Shortcodes_Helper::fieldColumn(array(
        'heading' 		=> __('Items to show', 'lastudio'),
        'param_name' 	=> 'columns',
        'dependency' => array(
            'callback' => 'laWoocommerceProductColumnsDependencyCallback',
        ),
        'group' 		=> __('Layout Setting', 'lastudio')
    )),

    array(
        'type' => 'dropdown',
        'heading' => __( 'Display Style', 'lastudio' ),
        'param_name' => 'display_style',
        'value' => array(
            __( 'Show All', 'lastudio' ) => 'all',
            __( 'Load more button', 'lastudio' ) => 'load-more',
            __( 'Pagination', 'lastudio' ) => 'pagination',
        ),
        'std' => 'all',
        'save_always' => true,
        'description' => __('Select display style', 'lastudio'),
        'group' 		=> __('Layout Setting', 'lastudio')
    ),

    array(
        'type' => 'textfield',
        'heading' => __( 'Load more text', 'lastudio' ),
        'param_name' => 'load_more_text',
        'dependency' => array(
            'element'   => 'display_style',
            'value'     => 'load-more'
        ),
        'group' 		=> __('Layout Setting', 'lastudio')
    ),

    array(
        'type'       => 'checkbox',
        'heading'    => __('Enable slider', 'lastudio' ),
        'param_name' => 'enable_carousel',
        'value'      => array( __( 'Yes', 'lastudio' ) => 'yes' ),
        'dependency' => array(
            'element'   => 'layout',
            'value'     => 'grid'
        ),
        'group' 		=> __('Layout Setting', 'lastudio')
    ),

    array(
        'type' => 'checkbox',
        'heading' => __( 'Enable Ajax Loading', 'lastudio' ),
        'param_name' => 'enable_ajax_loader',
        'value' => array( __( 'Yes', 'lastudio' ) => 'yes' ),
        'group' 		=> __('Layout Setting', 'lastudio')
    ),

    LaStudio_Shortcodes_Helper::fieldElementID(array(
        'group' 		=> __('Layout Setting', 'lastudio')
    )),

    LaStudio_Shortcodes_Helper::fieldExtraClass(array(
        'group' 		=> __('Layout Setting', 'lastudio')
    )),

    array(
        'type' => 'hidden',
        'heading' => __('Paged', 'lastudio'),
        'param_name' => 'paged',
        'value' => '1',
        'group' 		=> __('Data Setting', 'lastudio')
    ),
);


$carousel = LaStudio_Shortcodes_Helper::paramCarouselShortCode(false);

$slides_column_idx = LaStudio_Shortcodes_Helper::getParamIndex( $carousel, 'slides_column');

if($slides_column_idx){
    unset($carousel[$slides_column_idx]);
}

$shortcode_params = array_merge( $shortcode_params, $carousel);

return apply_filters(
    'LaStudio/shortcodes/configs',
    array(
        'name'			=> __('Product Attribute', 'lastudio'),
        'base'			=> 'product_attribute',
        'icon'          => 'icon-wpb-woocommerce',
        'category'  	=> __('La Studio', 'lastudio'),
        'description' 	=> __('List Products with an attribute shortcodes','lastudio'),
        'params' 		=> $shortcode_params
    ),
    'product_attribute'
);