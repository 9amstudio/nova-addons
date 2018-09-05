<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}


$shortcode_params = array(

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
            __( 'Date', 'nova' ) => 'date',
            __( 'ID', 'nova' ) => 'ID',
            __( 'Author', 'nova' ) => 'author',
            __( 'Title', 'nova' ) => 'title',
            __( 'Modified', 'nova' ) => 'modified',
            __( 'Random', 'nova' ) => 'rand',
            __( 'Comment count', 'nova' ) => 'comment_count',
            __( 'Menu order', 'nova' ) => 'menu_order',
        ),
        'default' => 'date',
        'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'nova' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        'group' => __('Query Settings', 'nova')
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Sort order', 'nova' ),
        'param_name' => 'order',
        'default' => 'desc',
        'value' => array(
            __( 'Descending', 'nova' ) => 'desc',
            __( 'Ascending', 'nova' ) => 'asc',
        ),
        'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'nova' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        'group' => __('Query Settings', 'nova')
    ),
    array(
        'type' => 'nova_number',
        'heading' => __('Items per page', 'nova'),
        'description' => __('Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'nova'),
        'param_name' => 'per_page',
        'value' => 10,
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
        'type'       => 'checkbox',
        'heading'    => __( 'Enable Skill Filter', 'nova' ),
        'param_name' => 'enable_skill_filter',
        'value'      => array( __( 'Yes', 'nova' ) => 'yes' ),
        'group' => __('Query Settings', 'nova')
    ),
    array(
        'type'       => 'checkbox',
        'heading'    => __( 'Enable Load More', 'nova' ),
        'param_name' => 'enable_loadmore',
        'value'      => array( __( 'Yes', 'nova' ) => 'yes' ),
        'group' => __('Query Settings', 'nova')
    ),
    array(
        'type' => 'textfield',
        'heading' => __('Load More Text', 'nova'),
        'param_name' => 'load_more_text',
        'value' => __('Load more', 'nova'),
        'dependency' => array( 'element' => 'enable_loadmore', 'value' => 'yes' ),
        'group' => __('Query Settings', 'nova')
    ),

    array(
        'type'       => 'autocomplete',
        'heading'    => __( 'Skill Filter', 'nova' ),
        'param_name' => 'filters',
        'settings'   => array(
            'unique_values'  => true,
            'multiple'       => true,
            'sortable'       => true,
            'groups'         => false,
            'min_length'     => 0,
            'auto_focus'     => true,
            'display_inline' => true,
        ),
        'dependency' => array(
            'element'   => 'enable_skill_filter',
            'value'     => 'yes'
        ),
        'group' => __('Skill Filters', 'nova'),
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Filter style','nova'),
        'param_name' => 'filter_style',
        'value' => array(
            __('Style 01','nova') => '1',
            __('Style 02','nova') => '2',
            __('Style 03','nova') => '3'
        ),
        'std' => '1',
        'dependency' => array(
            'element'   => 'enable_skill_filter',
            'value'     => 'yes'
        ),
        'group' => __('Skill Filters', 'nova')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Design','nova'),
        'param_name' => 'masonry_style',
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
        'std' => '1',
        'group' => __('Item Settings', 'nova')
    ),

    array(
        'type' => 'dropdown',
        'heading' => __('Item Title HTML Tag','nova'),
        'param_name' => 'title_tag',
        'value' => array(
            __('Default','nova') => 'h5',
            __('H1','nova') => 'h1',
            __('H2','nova') => 'h2',
            __('H3','nova') => 'h3',
            __('H4','nova') => 'h4',
            __('H6','nova') => 'h6',
            __('DIV','nova') => 'div',
        ),
        'default' => 'h5',
        'description' => __('Default is H5', 'nova'),
        'group' => __('Item Settings', 'nova')
    ),

    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Image Size', 'nova'),
        'param_name' 	=> 'img_size',
        'value'			=> 'full',
        'description' 	=> __('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'nova'),
        'group' => __('Item Settings', 'nova')
    ),

    Novaworks_Shortcodes_Helper::getParamItemSpace(array(
        'group' => __('Item Settings', 'nova')
    )),

    array(
        'type' => 'dropdown',
        'heading' => __('Column Type','nova'),
        'param_name' => 'column_type',
        'value' => array(
            __('Default','nova') => 'default',
            __('Custom','nova') => 'custom',
        ),
        'default' => 'default',
        'group' => __('Item Settings', 'nova')
    ),
    array(
        'type' 			=> 'nova_column',
        'heading' 		=> __('Responsive Column', 'nova'),
        'param_name' 	=> 'column',
        'unit'			=> '',
        'media'			=> array(
            'xlg'	=> 1,
            'lg'	=> 1,
            'md'	=> 1,
            'sm'	=> 1,
            'xs'	=> 1,
            'mb'	=> 1
        ),
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'default'
        ),
        'group' => __('Item Settings', 'nova')
    ),

    array(
        'type' => 'nova_number',
        'heading' => __('Portfolio Item Width', 'nova'),
        'param_name' => 'base_item_w',
        'description' => __('Set your portfolio item default width', 'nova'),
        'value' => 400,
        'min' => 100,
        'max' => 1920,
        'suffix' => 'px',
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Item Settings', 'nova')
    ),

    array(
        'type' => 'nova_number',
        'heading' => __('Portfolio Item Height', 'nova'),
        'description' => __('Set your portfolio item default height', 'nova'),
        'param_name' => 'base_item_h',
        'value' => 400,
        'min' => 100,
        'max' => 1920,
        'suffix' => 'px',
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Item Settings', 'nova')
    ),

    array(
        'type' 			=> 'nova_column',
        'heading' 		=> __('Mobile Column', 'nova'),
        'param_name' 	=> 'mb_column',
        'unit'			=> '',
        'media'			=> array(
            'md'	=> 1,
            'sm'	=> 1,
            'xs'	=> 1,
            'mb'	=> 1
        ),
        'dependency'        => array(
            'element'   => 'column_type',
            'value'     => 'custom'
        ),
        'group' => __('Item Settings', 'nova')
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
        'group' => __('Item Settings', 'nova')
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
                'default' => '1'
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
                'default' => '1'
            ),
            array(
                'type' 			=> 'textfield',
                'heading' 		=> __('Custom Image Size', 'nova'),
                'param_name' 	=> 's',
                'description' 	=> __('leave blank to inherit from parent settings', 'nova')
            ),
        ),
        'dependency' => array(
            'element'   => 'custom_item_size',
            'value'     => 'yes'
        ),
        'group' => __('Item Settings', 'nova')
    ),

    array(
        'type' 			=> 'textfield',
        'heading' 		=> __('Extra Class name', 'nova'),
        'param_name' 	=> 'el_class',
        'description' 	=> __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nova'),
        'group' => __('Item Settings', 'nova')
    )
);

return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Portfolio Masonry', 'nova'),
        'base'			=> 'la_portfolio_masonry',
        'icon'          => 'nova-wpb-icon la_portfolio_masonry',
        'category'  	=> __('La Studio', 'nova'),
        'description' 	=> __('Display portfolio with la-studio themes style.','nova'),
        'params' 		=> $shortcode_params
    ),
    'la_portfolio_masonry'
);