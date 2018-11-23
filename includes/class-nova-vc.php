<?php

/**
 * Class Nova_Addons_VC
 */
class Nova_Addons_VC {
	/**
	 * The single instance of the class.
	 *
	 * @var object
	 */
	protected static $_instance = null;

	/**
	 * Temporary cached terms variable
	 *
	 * @var array
	 */
	protected $terms = array();

	/**
	 * Main Instance.
	 * Ensures only one instance of WooCommerce is loaded or can be loaded.
	 *
	 * @return Nova_Addons_VC - Main instance.
	 */
	public static function init() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->modify_elements();
		$this->map_shortcodes();

		vc_set_as_theme();
		remove_action( 'admin_bar_menu', array( vc_frontend_editor(), 'adminBarEditLink' ), 1000 );

		add_filter( 'vc_google_fonts_get_fonts_filter', array( $this, 'add_google_fonts' ) );
	}

	/**
	 * Modify VC element params
	 */
	public function modify_elements() {
		// Add new option to Custom Header element
		vc_add_param( 'vc_custom_heading', array(
			'heading'     => esc_html__( 'Separate URL', 'nova' ),
			'description' => esc_html__( 'Do not wrap heading text with link tag. Display URL separately', 'nova' ),
			'type'        => 'checkbox',
			'param_name'  => 'separate_link',
			'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' ),
			'weight'      => 0
		) );
		vc_add_param( 'vc_custom_heading', array(
			'heading'     => esc_html__( 'Link Arrow', 'nova' ),
			'description' => esc_html__( 'Add an arrow to the separated link when hover', 'nova' ),
			'type'        => 'checkbox',
			'param_name'  => 'link_arrow',
			'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' ),
			'weight'      => 0,
			'dependency'  => array(
				'element' => 'separate_link',
				'value'   => 'yes'
			)
		) );
	}

	/**
	 * Register custom shortcodes within Visual Composer interface
	 *
	 * @see http://kb.wpbakery.com/index.php?title=Vc_map
	 */
	public function map_shortcodes() {
		// Product Grid
		vc_map( array(
			'name'        => esc_html__( 'Product Grid', 'nova' ),
			'description' => esc_html__( 'Display products in grid', 'nova' ),
			'base'        => 'nova_product_grid',
			'icon'        => $this->get_icon( 'product-grid.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Number Of Products', 'nova' ),
					'description' => esc_html__( 'Total number of products you want to show', 'nova' ),
					'param_name'  => 'per_page',
					'type'        => 'textfield',
					'value'       => 15
				),
				array(
					'heading'     => esc_html__( 'Columns', 'nova' ),
					'description' => esc_html__( 'Display products in how many columns', 'nova' ),
					'param_name'  => 'columns',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( '2 Columns', 'nova' ) => 2,
						esc_html__( '3 Columns', 'nova' ) => 3,
						esc_html__( '4 Columns', 'nova' ) => 4,
						esc_html__( '5 Columns', 'nova' ) => 5,
						esc_html__( '6 Columns', 'nova' ) => 6
					)
				),
				array(
					'heading'     => esc_html__( 'Category', 'nova' ),
					'description' => esc_html__( 'Select what categories you want to use. Leave it empty to use all categories.', 'nova' ),
					'param_name'  => 'category',
					'type'        => 'autocomplete',
					'value'       => '',
					'settings'    => array(
						'multiple'  => true,
						'sortable'  => true,
						'values'    => $this->get_terms()
					)
				),
				array(
					'heading'     => esc_html__( 'Product Type', 'nova' ),
					'description' => esc_html__( 'Select product type you want to show', 'nova' ),
					'param_name'  => 'type',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Recent Products', 'nova' ) => 'recent',
						esc_html__( 'Featured Products', 'nova' ) => 'featured',
						esc_html__( 'Sale Products', 'nova' ) => 'sale',
						esc_html__( 'Best Selling Products', 'nova' ) => 'best_sellers',
						esc_html__( 'Top Rated Products', 'nova' ) => 'top_rated'
					)
				),
				array(
					'heading'     => esc_html__( 'Load More Button', 'nova' ),
					'description' => esc_html__( 'Show load more button with ajax loading', 'nova' ),
					'param_name'  => 'load_more',
					'type'        => 'checkbox',
					'value'       => array(
						esc_html__( 'Yes', 'nova' ) => 'yes'
					)
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => ''
				)
			)
		) );

		// Product Tabs
		vc_map( array(
			'name'        => esc_html__( 'Product Tabs', 'nova' ),
			'description' => esc_html__( 'Product grid grouped by tabs', 'nova' ),
			'base'        => 'nova_product_tabs',
			'icon'        => $this->get_icon( 'product-tabs.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Layout', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'layout',
					'value'       => array(
						esc_html__( 'Grid', 'nova' )    => 'grid',
						esc_html__( 'Special', 'nova' ) => 'special',
					),
					'std'         => 'grid'
				),
				array(
					'heading'     => esc_html__( 'Number Of Products', 'nova' ),
					'param_name'  => 'per_page',
					'type'        => 'textfield',
					'value'       => 15,
					'description' => esc_html__( 'Total number of products will be display in single tab', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Columns', 'nova' ),
					'param_name'  => 'columns',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( '2 Columns', 'nova' ) => 2,
						esc_html__( '3 Columns', 'nova' ) => 3,
						esc_html__( '4 Columns', 'nova' ) => 4,
						esc_html__( '5 Columns', 'nova' ) => 5,
						esc_html__( '6 Columns', 'nova' ) => 6
					),
					'std'         => 4,
					'description' => esc_html__( 'Display products in how many columns', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Tabs', 'nova' ),
					'description' => esc_html__( 'Select how to group products in tabs', 'nova' ),
					'param_name'  => 'filter',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Group by category', 'nova' ) => 'category',
						esc_html__( 'Group by feature', 'nova' ) => 'group'
					)
				),
				array(
					'heading'     => esc_html__( 'Categories', 'nova' ),
					'description' => esc_html__( 'Select what categories you want to use. Leave it empty to use all categories.', 'nova' ),
					'param_name'  => 'category',
					'type'        => 'autocomplete',
					'value'       => '',
					'settings'    => array(
						'multiple'  => true,
						'sortable'  => true,
						'values'    => $this->get_terms()
					),
					'dependency'  => array(
						'element'   => 'filter',
						'value'     => 'category'
					)
				),
				array(
					'heading'     => esc_html__( 'Tabs Styles', 'nova' ),
					'param_name'  => 'filter_style',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Style 01', 'nova' ) => '1',
						esc_html__( 'Style 02', 'nova' ) => '2',
						esc_html__( 'Style 03', 'nova' ) => '3',
					)
				),
				array(
					'heading'     => esc_html__( 'Tabs Effect', 'nova' ),
					'description' => esc_html__( 'Select the way tabs load products', 'nova' ),
					'param_name'  => 'filter_type',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Isotope Toggle', 'nova' ) => 'isotope',
						esc_html__( 'Ajax Load', 'nova' ) => 'ajax'
					)
				),
				array(
					'heading'     => esc_html__( 'Load More Button', 'nova' ),
					'param_name'  => 'load_more',
					'type'        => 'checkbox',
					'value'       => array(
						esc_html__( 'Yes', 'nova' ) => 'yes'
					),
					'description' => esc_html__( 'Show load more button with ajax loading', 'nova' )
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' )
				)
			)
		) );

		// Product Carousel
		vc_map( array(
			'name'        => esc_html__( 'Product Carousel', 'nova' ),
			'description' => esc_html__( 'Product carousel slider', 'nova' ),
			'base'        => 'nova_product_carousel',
			'icon'        => $this->get_icon( 'product-carousel.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Number Of Products', 'nova' ),
					'description' => esc_html__( 'Total number of products you want to show', 'nova' ),
					'param_name'  => 'number',
					'type'        => 'textfield',
					'value'       => 15
				),
				array(
					'heading'     => esc_html__( 'Columns', 'nova' ),
					'description' => esc_html__( 'Display products in how many columns', 'nova' ),
					'param_name'  => 'columns',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( '2 Columns', 'nova' ) => 2,
						esc_html__( '3 Columns', 'nova' ) => 3,
						esc_html__( '4 Columns', 'nova' ) => 4,
						esc_html__( '5 Columns', 'nova' ) => 5,
						esc_html__( '6 Columns', 'nova' ) => 6
					)
				),
				array(
					'heading'     => esc_html__( 'Product Type', 'nova' ),
					'description' => esc_html__( 'Select product type you want to show', 'nova' ),
					'param_name'  => 'type',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Recent Products', 'nova' ) => 'recent',
						esc_html__( 'Featured Products', 'nova' ) => 'featured',
						esc_html__( 'Sale Products', 'nova' ) => 'sale',
						esc_html__( 'Best Selling Products', 'nova' ) => 'best_sellers',
						esc_html__( 'Top Rated Products', 'nova' ) => 'top_rated'
					)
				),
				array(
					'heading'     => esc_html__( 'Categories', 'nova' ),
					'description' => esc_html__( 'Select what categories you want to use. Leave it empty to use all categories.', 'nova' ),
					'param_name'  => 'category',
					'type'        => 'autocomplete',
					'value'       => '',
					'settings'    => array(
						'multiple'  => true,
						'sortable'  => true,
						'values'    => $this->get_terms()
					)
				),
				array(
					'heading'     => esc_html__( 'Auto Play', 'nova' ),
					'description' => esc_html__( 'Auto play speed in miliseconds. Enter "0" to disable auto play.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'autoplay',
					'value'       => 5000
				),
				array(
					'heading'     => esc_html__( 'Loop', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'loop',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' )
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => ''
				)
			)
		) );

		// Post Grid
		vc_map( array(
			'name'        => esc_html__( 'Nova Post Grid', 'nova' ),
			'description' => esc_html__( 'Display posts in grid', 'nova' ),
			'base'        => 'nova_post_grid',
			'icon'        => $this->get_icon( 'post-grid.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Styles', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'style',
					'value'       => array(
						esc_html__( 'Style 01', 'nova' ) => '1',
						esc_html__( 'Style 02', 'nova' ) => '2',
						esc_html__( 'Style 03', 'nova' ) => '3',
					),
				),
				array(
					'description' => esc_html__( 'Number of posts you want to show', 'nova' ),
					'heading'     => esc_html__( 'Number of posts', 'nova' ),
					'param_name'  => 'per_page',
					'type'        => 'textfield',
					'value'       => 3
				),
				array(
					'heading'     => esc_html__( 'Columns', 'nova' ),
					'description' => esc_html__( 'Display posts in how many columns', 'nova' ),
					'param_name'  => 'columns',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( '2 Columns', 'nova' ) => 2,
						esc_html__( '3 Columns', 'nova' ) => 3,
						esc_html__( '4 Columns', 'nova' ) => 4
					),
					'std'         => '3'
				),
				array(
					'heading'     => esc_html__( 'Category', 'nova' ),
					'description' => esc_html__( 'Enter categories name', 'nova' ),
					'param_name'  => 'category',
					'type'        => 'autocomplete',
					'settings'    => array(
						'multiple'  => true,
						'sortable'  => true,
						'values'    => $this->get_terms( 'category' )
					)
				),
				array(
					'heading'     => esc_html__( 'Hide Post Meta', 'nova' ),
					'description' => esc_html__( 'Hide information about date, category', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'hide_meta',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' )
				),
				array(
					'heading'     => esc_html__( 'Hide Post Excerpt', 'nova' ),
					'description' => esc_html__( 'Hide short description', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'hide_excerpt',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' )
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => ''
				)
			)
		) );

		// Countdown
		vc_map( array(
			'name'        => esc_html__( 'Countdown', 'nova' ),
			'description' => esc_html__( 'Countdown digital clock', 'nova' ),
			'base'        => 'nova_countdown',
			'icon'        => $this->get_icon( 'countdown.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Style', 'nova' ),
					'param_name'  => 'style',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Style 01', 'nova' ) => '1',
						esc_html__( 'Style 02', 'nova' ) => '2',
						esc_html__( 'Style 03', 'nova' ) => '3',
						esc_html__( 'Style 04', 'nova' ) => '4'
					)
				),
				array(
					'heading'     => esc_html__( 'Date', 'nova' ),
					'description' => esc_html__( 'Date and time format (yyyy/mm/dd hh:mm:ss)', 'nova' ),
					'admin_label' => true,
					'type'        => 'datetimepicker',
					'param_name'  => 'date'
				),
				array(
					'heading'     => esc_html__( 'Text Align', 'nova' ),
					'description' => esc_html__( 'Select text alignment', 'nova' ),
					'param_name'  => 'text_align',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Left', 'nova' ) => 'left',
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Right', 'nova' ) => 'right'
					)
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => ''
				)
			)
		) );
		
		// Stats Counter
		vc_map( array(
			'name'        => esc_html__( 'Stats Counter', 'nova' ),
			'base'        => 'nova_stats_counter',
			'icon'        => 'nova-wpb-icon nova_stats_counter',
			'category'    => esc_html__( 'Nova', 'nova' ),
			'description' => esc_html__( 'Your milestones, achievements, etc.','nova' ),
			'params'      => array_merge( array(
				array(
					'heading'     => esc_html__('Icon Position', 'nova'),
					'description' => esc_html__( 'Select icon position. Icon box style will be changed according to the icon position.', 'nova' ),
					'param_name'  => 'icon_pos',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'No display', 'nova' ) => 'none',
						esc_html__( 'Icon at Top', 'nova' ) => 'top',
						esc_html__( 'Icon at Left', 'nova' ) => 'left',
						esc_html__( 'Icon at Right', 'nova' ) => 'right'
					),
					'std'         => 'top'					
				),
				array(
					'heading'     => esc_html__( 'Icon Styles', 'nova' ),
					'description' => esc_html__( 'We have given four quick preset if you are in a hurry. Otherwise, create your own with various options.', 'nova' ),
					'param_name'  => 'icon_style',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Normal', 'nova' ) => 'normal',
						esc_html__( 'Circle', 'nova' ) => 'circle',
						esc_html__( 'Square', 'nova' ) => 'square',
						esc_html__( 'Round', 'nova' ) => 'round',
						esc_html__( 'Advanced', 'nova' ) => 'advanced',
					),
					'dependency'  => array(
						'element'            => 'icon_pos',
						'value_not_equal_to' => array( 'none' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Size', 'nova' ),
					'param_name'  => 'icon_size',
					'type'        => 'nova_number',
					'value'       => 30,
					'min'         => 10,
					'suffix'      => 'px',
					'dependency'  => array(
						'element'            => 'icon_pos',
						'value_not_equal_to' => array( 'none' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Box Width', 'nova' ),
					'param_name'  => 'icon_width',
					'type'        => 'nova_number',
					'value'       => 30,
					'min'         => 10,
					'suffix'      => 'px',
					'dependency'  => array(
						'element'   => 'icon_style',
						'value'     => array( 'circle', 'square', 'round', 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Padding', 'nova' ),
					'param_name'  => 'icon_padding',
					'type'        => 'nova_number',
					'value'       => 0,
					'min'         => 0,
					'suffix'      => 'px',
					'dependency'  => array(
						'element'   => 'icon_style',
						'value'     => array( 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Color Type', 'nova' ),
					'param_name'  => 'icon_color_type',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Simple', 'nova' ) => 'simple',
						esc_html__( 'Gradient', 'nova' ) => 'gradient',
					),
					'std'         => 'simple',
					'dependency'  => array(
						'element'            => 'icon_pos',
						'value_not_equal_to' => array( 'none' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Color', 'nova' ),
					'param_name'  => 'icon_color',
					'type'        => 'colorpicker',
					'dependency'  => array(
						'element'            => 'icon_pos',
						'value_not_equal_to' => array( 'none' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Color #2', 'nova' ),
					'param_name'  => 'icon_color2',
					'type'        => 'colorpicker',
					'dependency'  => array(
						'element'   => 'icon_color_type',
						'value'     => array( 'gradient' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Background Type', 'nova' ),
					'param_name'  => 'icon_bg_type',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Simple', 'nova' ) => 'simple',
						esc_html__( 'Gradient', 'nova' ) => 'gradient',
					),
					'std'         => 'simple',
					'dependency'  => array(
						'element'   => 'icon_style',
						'value'     => array( 'circle', 'square', 'round', 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Background Color', 'nova' ),
					'param_name'  => 'icon_bg',
					'type'        => 'colorpicker',
					'dependency'  => array(
						'element'   => 'icon_style',
						'value'     => array( 'circle', 'square', 'round', 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__('Icon Background Color #2', 'nova'),
					'param_name'  => 'icon_bg2',
					'type'        => 'colorpicker',
					'dependency'  => array(
						'element'   => 'icon_bg_type',
						'value'     => array( 'gradient' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				)
			),
			Novaworks_Shortcodes_Helper::fieldIconType( array(
					'element'     => 'icon_pos',
					'value'       => array( 'top', 'left', 'right' )
			) ),
			array(
				array(
					'heading'     => esc_html__( 'Title', 'nova' ),
					'param_name'  => 'title',
					'type'        => 'textfield',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Value', 'nova' ),
					'description' => esc_html__( 'Enter number for counter without any special character. You may enter a decimal number. Eg 12.76', 'nova' ),
					'param_name'  => 'value',
					'type'        => 'nova_number',
					'value'       => 1250,
					'min'         => 0,
					'suffix'      => ''
				),
				array(
					'heading'     => esc_html__( 'Value Prefix', 'nova' ),
					'param_name'  => 'prefix',
					'type'        => 'textfield'
				),
				array(
					'heading'     => esc_html__( 'Value Suffix', 'nova' ),
					'param_name'  => 'suffix',
					'type'        => 'textfield'
				),
				array(
					'heading'     => esc_html__( 'Separator', 'nova' ),
					'param_name'  => 'spacer',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'No Separator', 'nova' ) => 'none',
						esc_html__( 'Line', 'nova' ) => 'line'
					),
					'std'         => 'none'
				),
				array(
					'heading'     => esc_html__( 'Separator Position', 'nova' ),
					'param_name'  => 'spacer_position',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Top', 'nova' ) => 'top',
						esc_html__( 'Bottom', 'nova' ) => 'bottom',
						esc_html__( 'Between Value - Title', 'nova' ) => 'middle'
					),
					'std'         => 'top',
					'dependency'  => array(
						'element'   => 'spacer',
						'value'     => 'line'
					)
				),
				array(
					'heading'     => esc_html__( 'Line Style', 'nova' ),
					'param_name'  => 'line_style',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Solid', 'nova' ) => 'solid',
						esc_html__( 'Dashed', 'nova' ) => 'dashed',
						esc_html__( 'Dotted', 'nova' ) => 'dotted',
						esc_html__( 'Double', 'nova' ) => 'double'
					),
					'std'         => 'solid',
					'dependency'  => array(
						'element'   => 'spacer',
						'value'     => 'line'
					)
				),
				array(
					'heading'     => esc_html__( 'Line Width', 'nova' ),
					'param_name'  => 'line_width',
					'type'        => 'nova_column',
					'unit'        => 'px',
					'media'       => array(
						'xlg'	=> '',
						'lg'	=> '',
						'md'	=> '',
						'sm'	=> '',
						'xs'	=> '',
						'mb'	=> ''
					),
					'dependency'  => array(
						'element'   => 'spacer',
						'value'     => 'line'
					)
				),
				array(
					'heading'     => esc_html__( 'Line Height', 'nova' ),
					'param_name'  => 'line_height',
					'type'        => 'nova_number',
					'value'       => 1,
					'min'         => 1,
					'suffix'      => 'px',
					'dependency'  => array(
						'element'   => 'spacer',
						'value'     => 'line'
					)
				),
				array(
					'heading'     => esc_html__( 'Line Color', 'nova' ),
					'param_name'  => 'line_color',
					'type'        => 'colorpicker',
					'dependency'  => array(
						'element'   => 'spacer',
						'value'     => 'line'
					)
				),
				Novaworks_Shortcodes_Helper::fieldElementID( array(
					'param_name'  => 'el_id'
				) ),
				Novaworks_Shortcodes_Helper::fieldExtraClass(),
				Novaworks_Shortcodes_Helper::fieldExtraClass( array(
					'heading'     => esc_html__( 'Extra class name for value', 'nova' ),
					'param_name'  => 'el_class_value'
				) ),
				Novaworks_Shortcodes_Helper::fieldExtraClass(array(
					'heading'     => esc_html__( 'Extra Class name for heading', 'nova' ),
					'param_name'  => 'el_class_heading'
				) )
			),
			Novaworks_Shortcodes_Helper::fieldTitleGFont(),
			Novaworks_Shortcodes_Helper::fieldTitleGFont( 'value', esc_html__( 'Value', 'nova' ) ),
			array( Novaworks_Shortcodes_Helper::fieldCssClass() ) )
		) );

		// Button
		vc_map( array(
			'name'        => esc_html__( 'Nova Button', 'nova' ),
			'description' => esc_html__( 'Button in style', 'nova' ),
			'base'        => 'nova_button',
			'icon'        => $this->get_icon( 'button.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Text', 'nova' ),
					'description' => esc_html__( 'Enter button text', 'nova' ),
					'admin_label' => true,
					'type'        => 'textfield',
					'param_name'  => 'label'
				),
				array(
					'heading'     => esc_html__( 'URL (Link)', 'nova' ),
					'type'        => 'vc_link',
					'param_name'  => 'link'
				),
				array(
					'heading'     => esc_html__( 'Style', 'nova' ),
					'description' => esc_html__( 'Select button style', 'nova' ),
					'param_name'  => 'style',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Normal', 'nova' ) => 'normal',
						esc_html__( 'Outline', 'nova' ) => 'outline',
						esc_html__( 'Light', 'nova' ) => 'light'
					)
				),
				array(
					'heading'     => esc_html__( 'Size', 'nova' ),
					'description' => esc_html__( 'Select button size', 'nova' ),
					'param_name'  => 'size',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Normal', 'nova' ) => 'normal',
						esc_html__( 'Large', 'nova' ) => 'large',
						esc_html__( 'Small', 'nova' ) => 'small'
					),
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'normal', 'outline' )
					)
				),
				array(
					'heading'     => esc_html__( 'Color', 'nova' ),
					'description' => esc_html__( 'Select button color', 'nova' ),
					'param_name'  => 'color',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Dark', 'nova' ) => 'dark',
						esc_html__( 'White', 'nova' ) => 'white'
					),
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'outline' )
					)
				),
				array(
					'heading'     => esc_html__( 'Alignment', 'nova' ),
					'description' => esc_html__( 'Select button alignment', 'nova' ),
					'param_name'  => 'align',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Inline', 'nova' ) => 'inline',
						esc_html__( 'Left', 'nova' ) => 'left',
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Right', 'nova' ) => 'right'
					)
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => ''
				)
			)
		) );

		// Banner
		vc_map( array(
			'name'        => esc_html__( 'Banner Image', 'nova' ),
			'description' => esc_html__( 'Banner image for promotion', 'nova' ),
			'base'        => 'nova_banner',
			'icon'        => $this->get_icon( 'banner.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Image', 'nova' ),
					'description' => esc_html__( 'Banner Image', 'nova' ),
					'param_name'  => 'image',
					'type'        => 'attach_image'
				),
				array(
					'heading'     => esc_html__( 'Image size', 'nova' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'value'       => ''
				),
				array(
					'heading'     => esc_html__( 'Banner description', 'nova' ),
					'description' => esc_html__( 'A short text display before the banner text', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'desc'
				),
				array(
					'heading'     => esc_html__( 'Banner Text', 'nova' ),
					'description' => esc_html__( 'Enter the banner text', 'nova' ),
					'type'        => 'textarea_html',
					'param_name'  => 'content',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Banner Text Position', 'nova' ),
					'description' => esc_html__( 'Select text position', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'text_position',
					'value'       => array(
						esc_html__( 'Left', 'nova' ) => 'left',
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Right', 'nova' ) => 'right'
					)
				),
				array(
					'type'        => 'font_container',
					'param_name'  => 'font_container',
					'value'       => '',
					'settings'    => array(
						'fields'  => array(
							'font_size',
							'line_height',
							'color',
							'font_size_description'   => esc_html__( 'Enter text font size.', 'nova' ),
							'line_height_description' => esc_html__( 'Enter text line height.', 'nova' ),
							'color_description'       => esc_html__( 'Select text color.', 'nova' )
						)
					)
				),
				array(
					'heading'     => esc_html__( 'Use theme default font family?', 'nova' ),
					'description' => esc_html__( 'Use font family from the theme.', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'use_theme_fonts',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' ),
					'std'         => 'yes'
				),
				array(
					'type'        => 'google_fonts',
					'param_name'  => 'google_fonts',
					'value'       => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
					'settings'    => array(
						'fields'  => array(
							'font_family_description' => esc_html__( 'Select font family.', 'nova' ),
							'font_style_description'  => esc_html__( 'Select font styling.', 'nova' ),
						)
					),
					'dependency'  => array(
						'element'            => 'use_theme_fonts',
						'value_not_equal_to' => 'yes'
					)
				),
				array(
					'heading'     => esc_html__( 'Link (URL)', 'nova' ),
					'type'        => 'vc_link',
					'param_name'  => 'link'
				),
				array(
					'heading'     => esc_html__( 'Button Type', 'nova' ),
					'description' => esc_html__( 'Select button type', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'button_type',
					'value'       => array(
						esc_html__( 'Light Button', 'nova' ) => 'light',
						esc_html__( 'Normal Button', 'nova' ) => 'normal',
						esc_html__( 'Arrow Icon', 'nova' ) => 'arrow_icon'
					)
				),
				array(
					'heading'     => esc_html__( 'Button Text', 'nova' ),
					'description' => esc_html__( 'Enter the text for banner button', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'button_text',
					'dependency'  => array(
						'element' => 'button_type',
						'value'   => array( 'light', 'normal' )
					)
				),
				array(
					'heading'     => esc_html__( 'Button Visibility', 'nova' ),
					'description' => esc_html__( 'Select button visibility', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'button_visibility',
					'value'       => array(
						esc_html__( 'Always visible', 'nova' ) => 'always',
						esc_html__( 'When hover', 'nova' ) => 'hover',
						esc_html__( 'Hidden', 'nova' ) => 'hidden'
					)
				),
				array(
					'heading'     => esc_html__( 'Banner Color Scheme', 'nova' ),
					'description' => esc_html__( 'Select color scheme for description, button color', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'scheme',
					'value'       => array(
						esc_html__( 'Dark', 'nova' ) => 'dark',
						esc_html__( 'Light', 'nova' ) => 'light'
					)
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => ''
				),
				array(
					'heading'     => esc_html__( 'CSS box', 'nova' ),
					'type'        => 'css_editor',
					'param_name'  => 'css',
					'group'       => esc_html__( 'Design Options', 'nova' )
				)
			)
		) );

		// Banner 2
		vc_map( array(
			'name'        => esc_html__( 'Banner Image 2', 'nova' ),
			'description' => esc_html__( 'Simple banner that supports multiple buttons', 'nova' ),
			'base'        => 'nova_banner2',
			'icon'        => $this->get_icon( 'banner2.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Image', 'nova' ),
					'description' => esc_html__( 'Banner Image', 'nova' ),
					'param_name'  => 'image',
					'type'        => 'attach_image',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Image size', 'nova' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'value'       => ''
				),
				array(
					'heading'     => esc_html__( 'Buttons', 'nova' ),
					'description' => esc_html__( 'Enter link and label for buttons.', 'nova' ),
					'type'        => 'param_group',
					'param_name'  => 'buttons',
					'params'      => array(
						array(
							'heading'    => esc_html__( 'Button Text', 'nova' ),
							'type'       => 'textfield',
							'param_name' => 'text'
						),
						array(
							'heading'    => esc_html__( 'Button Link', 'nova' ),
							'type'       => 'vc_link',
							'param_name' => 'link'
						)
					)
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => ''
				),
			),
		) );

		// Banner 3
		vc_map( array(
			'name'        => esc_html__( 'Banner Image 3', 'nova' ),
			'description' => esc_html__( 'Simple banner with text at bottom', 'nova' ),
			'base'        => 'nova_banner3',
			'icon'        => $this->get_icon( 'banner3.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Image', 'nova' ),
					'description' => esc_html__( 'Banner Image', 'nova' ),
					'param_name'  => 'image',
					'type'        => 'attach_image'
				),
				array(
					'heading'     => esc_html__( 'Image size', 'nova' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'value'       => ''
				),
				array(
					'heading'     => esc_html__( 'Banner Text', 'nova' ),
					'description' => esc_html__( 'Enter banner text', 'nova' ),
					'type'        => 'textarea_html',
					'param_name'  => 'text',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Banner Text Position', 'nova' ),
					'description' => esc_html__( 'Select text position', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'text_align',
					'value'       => array(
						esc_html__( 'Left', 'nova' ) => 'left',
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Right', 'nova' ) => 'right'
					)
				),
				array(
					'heading'     => esc_html__( 'Link (URL)', 'nova' ),
					'type'        => 'vc_link',
					'param_name'  => 'link'
				),
				array(
					'heading'     => esc_html__( 'Button Text', 'nova' ),
					'description' => esc_html__( 'Enter the text for banner button', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'button_text'
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => ''
				)
			)
		) );

		// Banner 4
		vc_map( array(
			'name'        => esc_html__( 'Banner Image 4', 'nova' ),
			'description' => esc_html__( 'Simple banner image with text', 'nova' ),
			'base'        => 'nova_banner4',
			'icon'        => $this->get_icon( 'banner4.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Image', 'nova' ),
					'description' => esc_html__( 'Banner Image', 'nova' ),
					'param_name'  => 'image',
					'type'        => 'attach_image'
				),
				array(
					'heading'     => esc_html__( 'Image size', 'nova' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'value'       => 'full'
				),
				array(
					'heading'     => esc_html__( 'Link (URL)', 'nova' ),
					'type'        => 'vc_link',
					'param_name'  => 'link'
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => ''
				),
				array(
					'heading'     => esc_html__( 'Banner Content', 'nova' ),
					'type'        => 'textarea_html',
					'param_name'  => 'content',
					'group'       => esc_html__( 'Text', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Button Text', 'nova' ),
					'description' => esc_html__( 'Enter the text for banner button', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'button_text',
					'group'       => esc_html__( 'Text', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Text Color Scheme', 'nova' ),
					'description' => esc_html__( 'Select color scheme for banner content', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'scheme',
					'group'       => esc_html__( 'Text', 'nova' ),
					'value'       => array(
						esc_html__( 'Dark', 'nova' ) => 'dark',
						esc_html__( 'Light', 'nova' ) => 'light'
					)
				),
				array(
					'heading'     => esc_html__( 'Content Horizontal Alignment', 'nova' ),
					'description' => esc_html__( 'Horizontal alignment of banner text', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'align_horizontal',
					'group'       => esc_html__( 'Text', 'nova' ),
					'value'       => array(
						esc_html__( 'Left', 'nova' ) => 'left',
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Right', 'nova' ) => 'right'
					)
				),
				array(
					'heading'     => esc_html__( 'Content Vertical Alignment', 'nova' ),
					'description' => esc_html__( 'Vertical alignment of banner text', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'align_vertical',
					'group'       => esc_html__( 'Text', 'nova' ),
					'value'       => array(
						esc_html__( 'Top', 'nova' ) => 'top',
						esc_html__( 'Middle', 'nova' ) => 'middle',
						esc_html__( 'Bottom', 'nova' ) => 'bottom'
					)
				)
			)
		) );
		
		// Banner 5
		vc_map( array(
			'name'        => esc_html__( 'Banner Image 5', 'nova' ),
			'description' => esc_html__( 'Simple banner image with text', 'nova' ),
			'base'        => 'nova_banner5',
			'icon'        => $this->get_icon( 'banner.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Image', 'nova' ),
					'description' => esc_html__( 'Banner Image', 'nova' ),
					'param_name'  => 'image',
					'type'        => 'attach_image'
				),
				array(
					'heading'     => esc_html__( 'Image size', 'nova' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'value'       => ''
				),
				array(
					'heading'     => esc_html__( 'Banner description', 'nova' ),
					'description' => esc_html__( 'A short text display before the banner text', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'desc'
				),
				array(
					'heading'     => esc_html__( 'Desc Color', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'desc_color'
				),
				array(
					'heading'     => esc_html__( 'Banner Text', 'nova' ),
					'description' => esc_html__( 'Enter the banner text', 'nova' ),
					'type'        => 'textarea_html',
					'param_name'  => 'content',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Banner Text Position', 'nova' ),
					'description' => esc_html__( 'Select text position', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'text_position',
					'value'       => array(
						esc_html__( 'Left', 'nova' ) => 'left',
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Right', 'nova' ) => 'right'
					)
				),
				array(
					'type'        => 'font_container',
					'param_name'  => 'font_container',
					'value'       => '',
					'settings'    => array(
						'fields'  => array(
							'font_size',
							'line_height',
							'color',
							'font_size_description'   => esc_html__( 'Enter text font size.', 'nova' ),
							'line_height_description' => esc_html__( 'Enter text line height.', 'nova' ),
							'color_description'       => esc_html__( 'Select text color.', 'nova' )
						)
					)
				),
				array(
					'heading'     => esc_html__( 'Use theme default font family?', 'nova' ),
					'description' => esc_html__( 'Use font family from the theme.', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'use_theme_fonts',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' ),
					'std'         => 'yes'
				),
				array(
					'type'        => 'google_fonts',
					'param_name'  => 'google_fonts',
					'value'       => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
					'settings'    => array(
						'fields'  => array(
							'font_family_description' => esc_html__( 'Select font family.', 'nova' ),
							'font_style_description'  => esc_html__( 'Select font styling.', 'nova' ),
						)
					),
					'dependency'  => array(
						'element'            => 'use_theme_fonts',
						'value_not_equal_to' => 'yes'
					)
				),
				array(
					'heading'     => esc_html__( 'Link (URL)', 'nova' ),
					'type'        => 'vc_link',
					'param_name'  => 'link'
				),
				array(
					'heading'     => esc_html__( 'Button Type', 'nova' ),
					'description' => esc_html__( 'Select button type', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'button_type',
					'value'       => array(
						esc_html__( 'Light Button', 'nova' ) => 'light',
						esc_html__( 'Normal Button', 'nova' ) => 'normal',
						esc_html__( 'Arrow Icon', 'nova' ) => 'arrow_icon'
					)
				),
				array(
					'heading'     => esc_html__( 'Button Text', 'nova' ),
					'description' => esc_html__( 'Enter the text for banner button', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'button_text',
					'dependency'  => array(
						'element' => 'button_type',
						'value'   => array( 'light', 'normal' )
					)
				),
				array(
					'heading'     => esc_html__( 'Button Visibility', 'nova' ),
					'description' => esc_html__( 'Select button visibility', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'button_visibility',
					'value'       => array(
						esc_html__( 'Always visible', 'nova' ) => 'always',
						esc_html__( 'When hover', 'nova' ) => 'hover',
						esc_html__( 'Hidden', 'nova' ) => 'hidden'
					)
				),
				array(
					'heading'     => esc_html__( 'Banner Color Scheme', 'nova' ),
					'description' => esc_html__( 'Select color scheme for description, button color', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'scheme',
					'value'       => array(
						esc_html__( 'Dark', 'nova' ) => 'dark',
						esc_html__( 'Light', 'nova' ) => 'light'
					)
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => ''
				),
				array(
					'heading'     => esc_html__( 'CSS box', 'nova' ),
					'type'        => 'css_editor',
					'param_name'  => 'css',
					'group'       => esc_html__( 'Design Options', 'nova' )
				)
			)
		) );

		// Category Banner
		vc_map( array(
			'name'        => esc_html__( 'Category Banner', 'nova' ),
			'description' => esc_html__( 'Banner image with special style', 'nova' ),
			'base'        => 'nova_category_banner',
			'icon'        => $this->get_icon( 'category-banner.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Image', 'nova' ),
					'description' => esc_html__( 'Banner Image', 'nova' ),
					'param_name'  => 'image',
					'type'        => 'attach_image'
				),
				array(
					'heading'     => esc_html__( 'Image Position', 'nova' ),
					'description' => esc_html__( 'Select image position', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'image_position',
					'value'       => array(
						esc_html__( 'Left', 'nova' ) => 'left',
						esc_html__( 'Right', 'nova' ) => 'right',
						esc_html__( 'Top', 'nova' ) => 'top',
						esc_html__( 'Bottom', 'nova' ) => 'bottom',
						esc_html__( 'Top Left', 'nova' ) => 'top-left',
						esc_html__( 'Top Right', 'nova' ) => 'top-right',
						esc_html__( 'Bottom Left', 'nova' ) => 'bottom-left',
						esc_html__( 'Bottom Right', 'nova' ) => 'bottom-right'
					)
				),
				array(
					'heading'     => esc_html__( 'Title', 'nova' ),
					'description' => esc_html__( 'The banner title', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'title',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Description', 'nova' ),
					'description' => esc_html__( 'The banner description', 'nova' ),
					'type'        => 'textarea',
					'param_name'  => 'content'
				),
				array(
					'heading'     => esc_html__( 'Text Position', 'nova' ),
					'description' => esc_html__( 'Select the position for title and description', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'text_position',
					'value'       => array(
						esc_html__( 'Top Left', 'nova' ) => 'top-left',
						esc_html__( 'Top Right', 'nova' ) => 'top-right',
						esc_html__( 'Middle Left', 'nova' ) => 'middle-left',
						esc_html__( 'Middle Right', 'nova' ) => 'middle-right',
						esc_html__( 'Bottom Left', 'nova' ) => 'bottom-left',
						esc_html__( 'Bottom Right', 'nova' ) => 'bottom-right'
					)
				),
				array(
					'heading'     => esc_html__( 'Link (URL)', 'nova' ),
					'type'        => 'vc_link',
					'param_name'  => 'link'
				),
				array(
					'heading'     => esc_html__( 'Button Text', 'nova' ),
					'description' => esc_html__( 'Enter the text for banner button', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'button_text'
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => ''
				),
				array(
					'heading'    => esc_html__( 'CSS box', 'nova' ),
					'type'       => 'css_editor',
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'nova' )
				)
			)
		) );

		// Product
		vc_map( array(
			'name'        => esc_html__( 'Nova Product', 'nova' ),
			'description' => esc_html__( 'Display single product banner', 'nova' ),
			'base'        => 'nova_product',
			'icon'        => $this->get_icon( 'product.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Styles', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'style',
					'value'       => array(
						esc_html__( 'Style 01', 'nova' ) => '1',
						esc_html__( 'Style 02', 'nova' ) => '2',
					),
				),
				array(
					'heading'     => esc_html__( 'Images', 'nova' ),
					'description' => esc_html__( 'Upload a product image', 'nova' ),
					'param_name'  => 'image',
					'type'        => 'attach_image',
					'value'       => ''
				),
				array(
					'heading'     => esc_html__( 'Product name', 'nova' ),
					'description' => esc_html__( 'Enter product name', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'title',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Product price', 'nova' ),
					'description' => esc_html__( 'Enter product price. Only allow number.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'price'
				),
				array(
					'heading'     => esc_html__( 'Regular price', 'nova' ),
					'description' => esc_html__( 'Enter product price. Only allow number.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'regular_price'
				),
				array(
					'heading'     => esc_html__( 'Product description', 'nova' ),
					'description' => esc_html__( 'Enter product description', 'nova' ),
					'type'        => 'textarea',
					'param_name'  => 'content'
				),
				array(
					'heading'     => esc_html__( 'Product URL', 'nova' ),
					'type'        => 'vc_link',
					'param_name'  => 'link'
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield'
				),
				array(
					'heading'    => esc_html__( 'CSS box', 'nova' ),
					'type'       => 'css_editor',
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'nova' )
				)
			)
		) );

		// Banner Grid 4
		vc_map( array(
			'name'        => esc_html__( 'Banner Grid 4', 'nova' ),
			'description' => esc_html__( 'Arrange 4 banners per row with unusual structure.', 'nova' ),
			'base'        => 'nova_banner_grid_4',
			'icon'        => $this->get_icon( 'banner-grid-4.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'js_view'     => 'VcColumnView',
			'content_element'         => true,
			'show_settings_on_create' => false,
			'as_parent'   => array( 'only' => 'nova_banner, nova_banner2, nova_banner3' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Reverse Order', 'nova' ),
					'description' => esc_html__( 'Reverse the order of banners inside this grid', 'nova' ),
					'param_name'  => 'reverse',
					'type'        => 'checkbox',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' )
				),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield'
				)
			)
		) );

		// Banner Grid 5
		vc_map( array(
			'name'        => esc_html__( 'Banner Grid 5', 'nova' ),
			'description' => esc_html__( 'Arrange 5 banners in 3 columns.', 'nova' ),
			'base'        => 'nova_banner_grid_5',
			'icon'        => $this->get_icon( 'banner-grid-5.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'js_view'     => 'VcColumnView',
			'content_element'         => true,
			'show_settings_on_create' => false,
			'as_parent'   => array( 'only' => 'nova_banner, nova_banner2, nova_banner3' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield'
				)
			)
		) );

		// Banner Grid 6
		vc_map( array(
			'name'        => esc_html__( 'Banner Grid 6', 'nova' ),
			'description' => esc_html__( 'Arrange 6 banners in 4 columns.', 'nova' ),
			'base'        => 'nova_banner_grid_6',
			'icon'        => $this->get_icon( 'banner-grid-6.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'js_view'     => 'VcColumnView',
			'content_element'         => true,
			'show_settings_on_create' => false,
			'as_parent'   => array( 'only' => 'nova_banner, nova_banner2, nova_banner3' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Reverse Order', 'nova' ),
					'description' => esc_html__( 'Reverse the order of banners inside this grid', 'nova' ),
					'param_name'  => 'reverse',
					'type'        => 'checkbox',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' )
				),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield'
				)
			)
		) );

		// Circle Chart
		vc_map( array(
			'name'        => esc_html__( 'Circle Chart', 'nova' ),
			'description' => esc_html__( 'Circle chart with animation', 'nova' ),
			'base'        => 'nova_chart',
			'icon'        => $this->get_icon( 'chart.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Value', 'nova' ),
					'description' => esc_html__( 'Enter the chart value in percentage. Minimum 0 and maximum 100.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'value',
					'value'       => 100,
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Circle Size', 'nova' ),
					'description' => esc_html__( 'Width of the circle', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'size',
					'value'       => 200
				),
				array(
					'heading'     => esc_html__( 'Circle thickness', 'nova' ),
					'description' => esc_html__( 'Width of the arc', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'thickness',
					'value'       => 8
				),
				array(
					'heading'     => esc_html__( 'Color', 'nova' ),
					'description' => esc_html__( 'Pick color for the circle', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'color',
					'value'       => '#6dcff6'
				),
				array(
					'heading'     => esc_html__( 'Label Source', 'nova' ),
					'description' => esc_html__( 'Chart label source', 'nova' ),
					'param_name'  => 'label_source',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Auto', 'nova' )   => 'auto',
						esc_html__( 'Custom', 'nova' ) => 'custom'
					)
				),
				array(
					'heading'     => esc_html__( 'Custom label', 'nova' ),
					'description' => esc_html__( 'Text label for the chart', 'nova' ),
					'param_name'  => 'label',
					'type'        => 'textfield',
					'dependency'  => array(
						'element' => 'label_source',
						'value'   => 'custom'
					)
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class'
				)
			)
		) );

		// Message Box
		vc_map( array(
			'name'        => esc_html__( 'Nova Message Box', 'nova' ),
			'description' => esc_html__( 'Notification box with close button', 'nova' ),
			'base'        => 'nova_message_box',
			'icon'        => $this->get_icon( 'message-box.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Type', 'nova' ),
					'description' => esc_html__( 'Select message box type', 'nova' ),
					'edit_field_class' => 'vc_col-xs-12 vc_message-type',
					'type'        => 'dropdown',
					'param_name'  => 'type',
					'std'     => 'success',
					'admin_label' => true,
					'value'       => array(
						esc_html__( 'Success', 'nova' ) => 'success',
						esc_html__( 'Informational', 'nova' ) => 'info',
						esc_html__( 'Error', 'nova' ) => 'danger',
						esc_html__( 'Warning', 'nova' ) => 'warning'
					)
				),
				array(
					'heading'     => esc_html__( 'Message Text', 'nova' ),
					'type'        => 'textarea_html',
					'param_name'  => 'content',
					'holder'      => 'div'
				),
				array(
					'heading'     => esc_html__( 'Closeable', 'nova' ),
					'description' => esc_html__( 'Display close button for this box', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'closeable',
					'value'       => array(
						esc_html__( 'Yes', 'nova' ) => true
					),
				),
				vc_map_add_css_animation(),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' )
				)
			)
		) );

		// Icon Box
		vc_map( array(
			'name'        => esc_html__( 'Icon Box', 'nova' ),
			'description' => esc_html__( 'Information box with icon', 'nova' ),
			'base'        => 'nova_icon_box',
			'icon'        => $this->get_icon( 'icon-box.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array_merge( array(
				array(
					'heading'     => esc_html__( 'Icon library', 'nova' ),
					'description' => esc_html__( 'Select icon library.', 'nova' ),
					'param_name'  => 'icon_type',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Font Awesome', 'nova' ) => 'fontawesome',
						esc_html__( 'Open Iconic', 'nova' ) => 'openiconic',
						esc_html__( 'Typicons', 'nova' ) => 'typicons',
						esc_html__( 'Entypo', 'nova' ) => 'entypo',
						esc_html__( 'Linecons', 'nova' ) => 'linecons',
						esc_html__( 'Mono Social', 'nova' ) => 'monosocial',
						esc_html__( 'Novaworks Icons', 'nova' ) => 'nova_icon_outline',
						esc_html__( 'Nucleo Glyph', 'nova' ) => 'nucleo_glyph',
						esc_html__( 'Material', 'nova' ) => 'material',
						esc_html__( 'Custom Image', 'nova' ) => 'image',
						esc_html__( 'Custom Number', 'nova' ) => 'number'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_fontawesome',
					'value'       => 'fa fa-adjust',
					'settings'    => array(
						'emptyIcon'    => false,
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'fontawesome'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_openiconic',
					'value'       => 'vc-oi vc-oi-dial',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'openiconic',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'openiconic'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_typicons',
					'value'       => 'typcn typcn-adjust-brightness',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'typicons',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'typicons'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_entypo',
					'value'       => 'entypo-icon entypo-icon-note',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'entypo',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'entypo'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_linecons',
					'value'       => 'vc_li vc_li-heart',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'linecons',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'linecons'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_monosocial',
					'value'       => 'vc-mono vc-mono-fivehundredpx',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'monosocial',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'monosocial'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_nova_icon_outline',
					'value'       => 'nova-icon nature_bear',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'nova_icon_outline',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'nova_icon_outline'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_nucleo_glyph',
					'value'       => 'nc-icon-glyph nature_bear',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'nucleo_glyph',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'nucleo_glyph'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_material',
					'value'       => 'vc-material vc-material-cake',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'material',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'material'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon Image', 'nova' ),
					'description' => esc_html__( 'Upload icon image', 'nova' ),
					'type'        => 'attach_image',
					'param_name'  => 'image',
					'value'       => '',
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'image'
					)
				),
				array(
					'heading'     => esc_html__( 'Enter the number', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'number',
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'number'
					)
				),
				array(
					'heading'     => esc_html__( 'Title', 'nova' ),
					'description' => esc_html__( 'The box title', 'nova' ),
					'admin_label' => true,
					'type'        => 'textfield',
					'param_name'  => 'title',
					'value'       => esc_html__( 'I am Icon Box', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Content', 'nova' ),
					'description' => esc_html__( 'The box title', 'nova' ),
					'holder'      => 'div',
					'type'        => 'textarea_html',
					'param_name'  => 'content',
					'value'       => esc_html__( 'I am icon box. Click edit button to change this text.', 'nova' )
				),
				// Select link option - to box or with read more text
				array(
					'heading'     => esc_html__( 'Apply link to:', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'read_more',
					'value'       => array(
						esc_html__( 'No Link', 'nova' ) => 'none',
						esc_html__( 'Box Title', 'nova' ) => 'title',
						esc_html__( 'Icon', 'nova' ) => 'icon'
					)
				),
				// Add link to existing content or to another resource
				array(
					'heading'     => esc_html__( 'Add Link', 'nova' ),
					'description' => esc_html__( 'Add a custom link or select existing page. You can remove existing link as well.', 'nova' ),
					'type'        => 'vc_link',
					'param_name'  => 'link',
					'dependency'  => array(
						'element'      => 'read_more',
						'value'        => array( 'box', 'title', 'icon' )
					)
				),
				array(
					'heading'     => esc_html__( 'Icon Position', 'nova' ),
					'description' => esc_html__( 'Select icon position. Icon box style will be changed according to the icon position.', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'icon_pos',
					'value'       => array(
						esc_html__( 'Icon at Top', 'nova' ) => 'default',
						esc_html__( 'Icon at Left', 'nova' ) => 'left',
						esc_html__( 'Icon at Right', 'nova' ) => 'right',
						esc_html__( 'Icon at left with heading', 'nova' ) => 'heading-left',
						esc_html__( 'Icon at Right with heading', 'nova' ) => 'heading-right',
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Style', 'nova' ),
					'description' => esc_html__( 'Select icon style', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'style',
					'value'       => array(
						esc_html__( 'Normal', 'nova' ) => 'normal',
						esc_html__( 'Circle', 'nova' ) => 'circle',
						esc_html__( 'Square', 'nova' ) => 'square',
						esc_html__( 'Round', 'nova' ) => 'round',
						esc_html__( 'Advanced', 'nova' ) => 'advanced'
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Size', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'icon_size',
					'value'       => 50,
					'min'         => 10,
					'suffix'      => 'px',
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Box Width', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'icon_width',
					'value'       => 50,
					'min'         => 10,
					'suffix'      => 'px',
					'dependency'  => array(
						'element' 	=> 'style',
						'value' 	=> array( 'circle', 'square', 'round', 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Padding', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'icon_padding',
					'value'       => 0,
					'min'         => 0,
					'suffix'      => 'px',
					'dependency'  => array(
						'element' 	=> 'style',
						'value' 	=> array( 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading' 	  => esc_html__( 'Icon Color Type', 'nova' ),
					'type' 		  => 'dropdown',
					'param_name'  => 'icon_color_type',
					'value' 	  => array(
						esc_html__( 'Simple', 'nova' ) => 'simple',
						esc_html__( 'Gradient', 'nova' ) => 'gradient',
					),
					'std'		  => 'simple',
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading' 	  => esc_html__( 'Icon Color', 'nova' ),
					'type' 		  => 'colorpicker',
					'param_name'  => 'icon_color',
					'group' 	  => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading' 	  => esc_html__( 'Icon Hover Color', 'nova' ),
					'type' 		  => 'colorpicker',
					'param_name'  => 'icon_h_color',
					'group' 	  => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading' 	  => esc_html__( 'Icon Color #2', 'nova' ),
					'type' 		  => 'colorpicker',
					'param_name'  => 'icon_color2',
					'dependency'  => array(
						'element' 	=> 'icon_color_type',
						'value' 	=> array( 'gradient' )
					),
					'group' 	  => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading' 	  => esc_html__( 'Icon Hover Color #2', 'nova' ),
					'type' 		  => 'colorpicker',
					'param_name'  => 'icon_h_color2',
					'dependency'  => array(
						'element' 	=> 'icon_color_type',
						'value' 	=> array('gradient')
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Background Type', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'icon_bg_type',
					'value'       => array(
						esc_html__( 'Simple', 'nova' ) => 'simple',
						esc_html__( 'Gradient', 'nova' ) => 'gradient',
					),
					'std'         => 'simple',
					'dependency'  => array(
						'element' 	=> 'style',
						'value' 	=> array( 'circle', 'square', 'round', 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Background Color', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'icon_bg',
					'dependency'  => array(
						'element' 	=> 'style',
						'value' 	=> array( 'circle', 'square', 'round', 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Hover Background Color', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'icon_h_bg',
					'dependency'  => array(
						'element' 	=> 'style',
						'value' 	=> array( 'circle', 'square', 'round', 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Background Color #2', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'icon_bg2',
					'dependency'  => array(
						'element' 	=> 'icon_bg_type',
						'value' 	=> array( 'gradient' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Hover Background Color #2', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'icon_h_bg2',
					'dependency'  => array(
						'element' 	=> 'icon_bg_type',
						'value' 	=> array( 'gradient' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Border Style', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'icon_border_style',
					'value'       => array(
						esc_html__( 'None', 'nova' ) => '',
						esc_html__( 'Solid', 'nova' ) => 'solid',
						esc_html__( 'Dashed', 'nova' ) => 'dashed',
						esc_html__( 'Dotted', 'nova' ) => 'dotted',
						esc_html__( 'Double', 'nova' ) => 'double',
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Border Width', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'icon_border_width',
					'value'       => 1,
					'min'         => 1,
					'max'         => 10,
					'suffix'      => 'px',
					'dependency'  => array(
						'element'   => 'icon_border_style',
						'not_empty'	=> true
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Border Color', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'icon_border_color',
					'dependency'  => array(
						'element'   => 'icon_border_style',
						'not_empty' => true
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Hover Border Color', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'icon_h_border_color',
					'dependency'  => array(
						'element'   => 'icon_border_style',
						'not_empty' => true
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Border Radius', 'nova' ),
					'description' => esc_html__( '0 pixel value will create a square border. As you increase the value, the shape convert in circle slowly. (e.g 500 pixels).', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'icon_border_radius',
					'value'       => 500,
					'min'         => 1,
					'suffix'      => 'px',
					'dependency'  => array(
						'element' 	=> 'style',
						'value' 	=> array( 'advanced' )
					),
					'group'       => esc_html__('Icon Settings', 'nova')
				),
				vc_map_add_css_animation(),
				Novaworks_Shortcodes_Helper::fieldExtraClass(),
			), Novaworks_Shortcodes_Helper::fieldTitleGFont(), Novaworks_Shortcodes_Helper::fieldTitleGFont( 'desc', esc_html__( 'Description', 'nova' ) ), array( Novaworks_Shortcodes_Helper::fieldCssClass() ) )
		) );
		
		// Custom Heading
		vc_map( array(
			'name'        => esc_html__( 'Title Heading', 'nova' ),
			'description' => esc_html__( 'Awesome heading styles.', 'nova' ),
			'base'        => 'nova_heading',
			'icon'        => 'nova-wpb-icon fa fa-font nova_heading',
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array_merge( array(
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Heading', 'nova' ),
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'type'        => 'textarea_html',
					'heading'     => esc_html__( 'Sub Heading(Optional)', 'nova' ),
					'param_name'  => 'content'
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Heading tag', 'nova' ),
					'param_name'  => 'tag',
					'value'       => array(
						esc_html__( 'Default', 'nova' ) => 'h2',
						esc_html__( 'H1', 'nova' ) => 'h1',
						esc_html__( 'H3', 'nova' ) => 'h3',
						esc_html__( 'H4', 'nova' ) => 'h4',
						esc_html__( 'H5', 'nova' ) => 'h5',
						esc_html__( 'H6', 'nova' ) => 'h6',
						esc_html__( 'DIV', 'nova' ) => 'div',
						esc_html__( 'p', 'nova' ) => 'p'
					),
					'std'         => 'h2',
					'description' => esc_html__( 'Default is H2', 'nova' )
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Alignment', 'nova' ),
					'param_name'  => 'alignment',
					'value' => array(
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Left','nova') => 'left',
						esc_html__( 'Right','nova')	=> 'right',
						esc_html__( 'Inline','nova') => 'inline'
					),
					'std'     => 'left'
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Separator', 'nova' ),
					'param_name'  => 'spacer',
					'value'       => array(
						esc_html__( 'No Separator', 'nova' ) =>	'none',
						esc_html__( 'Line', 'nova' ) =>	'line',
					),
					'std'         => 'none',
					'dependency'  => array(
						'element'   => 'alignment',
						'value'     => array( 'center', 'left', 'right' )
					)
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Separator Position', 'nova' ),
					'param_name'  => 'spacer_position',
					'value'       => array(
						esc_html__( 'Top', 'nova' ) => 'top',
						esc_html__( 'Bottom', 'nova' ) => 'bottom',
						esc_html__( 'Left', 'nova' ) => 'left',
						esc_html__( 'Right', 'nova' ) => 'right',
						esc_html__( 'Between Heading - Subheading', 'nova' ) => 'middle',
						esc_html__( 'Title between separator', 'nova' ) => 'separator'
					),
					'std'         => 'top',
					'dependency'  => array(
						'element'   => 'spacer',
						'value'     => 'line'
					)
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Line Style', 'nova' ),
					'param_name'  => 'line_style',
					'value'       => array(
						esc_html__( 'Solid', 'nova' ) => 'solid',
						esc_html__( 'Dashed', 'nova' ) => 'dashed',
						esc_html__( 'Dotted', 'nova' ) => 'dotted',
						esc_html__( 'Double', 'nova' ) => 'double'
					),
					'std'         => 'solid',
					'dependency'  => array(
						'element'   => 'spacer',
						'value'     => 'line'
					)
				),
				array(
					'type'        => 'nova_column',
					'heading'     => esc_html__('Line Width', 'nova'),
					'param_name'  => 'line_width',
					'unit'        => 'px',
					'media'       => array(
						'xlg'       => '',
						'lg'        => '',
						'md'        => '',
						'sm'        => '',
						'xs'        => '',
						'mb'        => ''
					),
					'dependency'  => array(
						'element'     => 'spacer',
						'value'       => 'line'
					)
				),
				array(
					'type'        => 'nova_number',
					'heading'     => esc_html__( 'Line Height', 'nova' ),
					'param_name'  => 'line_height',
					'value'       => 1,
					'min'         => 1,
					'suffix'      => 'px',
					'dependency'  => array(
						'element'   => 'spacer',
						'value'     => 'line'
					)
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => esc_html__( 'Line Color', 'nova' ),
					'param_name'  => 'line_color',
					'dependency'  => array(
						'element'   => 'spacer',
						'value'     => 'line'
					)
				),
				Novaworks_Shortcodes_Helper::fieldExtraClass(),
				Novaworks_Shortcodes_Helper::fieldExtraClass( array(
					'heading'     => esc_html__( 'Extra Class for heading', 'nova' ),
					'param_name'  => 'title_class',
				) ),
				Novaworks_Shortcodes_Helper::fieldExtraClass( array(
					'heading'     => esc_html__( 'Extra Class for subheading', 'nova' ),
					'param_name'  => 'subtitle_class',
				) ),
				Novaworks_Shortcodes_Helper::fieldExtraClass(array(
					'heading'     => esc_html__( 'Extra Class for Line', 'nova' ),
					'param_name'  => 'line_class',
					'dependency'  => array(
						'element'   => 'spacer',
						'value'     => 'line'
					)
				) )
			), Novaworks_Shortcodes_Helper::fieldTitleGFont(), Novaworks_Shortcodes_Helper::fieldTitleGFont( 'subtitle', esc_html__( 'Subheading', 'nova' ) ), array( Novaworks_Shortcodes_Helper::fieldCssClass() ) )
		) );

		// Content Space
		vc_map( array(
			'name'        => esc_html__( 'Content Space', 'nova' ),
			'base'        => 'nova_divider',
			'icon'        => 'nova-wpb-icon nova_divider',
			'category'    => esc_html__( 'Nova', 'nova' ),
			'description' => esc_html__( 'Blank space with custom height.','nova' ),
			'params'      => array(
				array(
					'type'        => 'nova_column',
					'heading'     => esc_html__( 'Space Height', 'nova' ),
					'admin_label' => true,
					'param_name'  => 'height',
					'unit'        => 'px',
					'media'       => array(
						'xlg'   => '',
						'lg'    => '',
						'md'    => '',
						'sm'    => '',
						'xs'    => '',
						'mb'    => ''
					)
				),
				Novaworks_Shortcodes_Helper::fieldExtraClass()
			)
		) );
		
		// Pricing Table
		vc_map( array(
			'name'        => esc_html__( 'Pricing Table', 'nova' ),
			'description' => esc_html__( 'Eye catching pricing table', 'nova' ),
			'base'        => 'nova_pricing_table',
			'icon'        => $this->get_icon( 'pricing-table.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Plan Name', 'nova' ),
					'admin_label' => true,
					'param_name'  => 'name',
					'type'        => 'textfield'
				),
				array(
					'heading'     => esc_html__( 'Price', 'nova' ),
					'description' => esc_html__( 'Plan pricing', 'nova' ),
					'param_name'  => 'price',
					'type'        => 'textfield'
				),
				array(
					'heading'     => esc_html__( 'Currency', 'nova' ),
					'description' => esc_html__( 'Price currency', 'nova' ),
					'param_name'  => 'currency',
					'type'        => 'textfield',
					'value'       => '$'
				),
				array(
					'heading'     => esc_html__( 'Recurrence', 'nova' ),
					'description' => esc_html__( 'Recurring payment unit', 'nova' ),
					'param_name'  => 'recurrence',
					'type'        => 'textfield',
					'value'       => esc_html__( 'Per Month', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Features', 'nova' ),
					'description' => esc_html__( 'Feature list of this plan. Click to arrow button to edit.', 'nova' ),
					'param_name'  => 'features',
					'type'        => 'param_group',
					'params'      => array(
						array(
							'heading'    => esc_html__( 'Feature name', 'nova' ),
							'param_name' => 'name',
							'type'       => 'textfield'
						),
						array(
							'heading'    => esc_html__( 'Feature value', 'nova' ),
							'param_name' => 'value',
							'type'       => 'textfield'
						)
					)
				),
				array(
					'heading'     => esc_html__( 'Button Text', 'nova' ),
					'param_name'  => 'button_text',
					'type'        => 'textfield',
					'value'       => esc_html__( 'Get Started', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Button Link', 'nova' ),
					'param_name'  => 'button_link',
					'type'        => 'vc_link',
					'value'       => esc_html__( 'Get Started', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Table color', 'nova' ),
					'description' => esc_html__( 'Pick color scheme for this table. It will be applied to table header and button.', 'nova' ),
					'param_name'  => 'color',
					'type'        => 'colorpicker',
					'value'       => '#6dcff6'
				),
				array(
					'type'        => 'checkbox',
					'param_name'  => 'package_featured',
					'value'       => array( esc_html__( 'Make this pricing box as featured', 'nova' ) => 'yes' ),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class'
				)
			)
		) );

		// Google Map
		vc_map( array(
			'name'        => esc_html__( 'Nova Maps', 'nova' ),
			'description' => esc_html__( 'Google maps in style', 'nova' ),
			'base'        => 'nova_map',
			'icon'        => $this->get_icon( 'map.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'API Key', 'nova' ),
					'description' => esc_html__( 'Google requires an API key to work.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'api_key'
				),
				array(
					'heading'     => esc_html__( 'Address', 'nova' ),
					'description' => esc_html__( 'Enter address for map marker. If this option does not work correctly, use the Latitude and Longitude options bellow.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'address',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Latitude', 'nova' ),
					'type'        => 'textfield',
					'edit_field_class' => 'vc_col-xs-6',
					'param_name'  => 'lat',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Longitude', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'lng',
					'edit_field_class' => 'vc_col-xs-6',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Marker', 'nova' ),
					'description' => esc_html__( 'Upload custom marker icon or leave this to use default marker.', 'nova' ),
					'type'        => 'attach_image',
					'param_name'  => 'marker'
				),
				array(
					'heading'     => esc_html__( 'Width', 'nova' ),
					'description' => esc_html__( 'Map width in pixel or percentage.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'width',
					'value'       => '100%'
				),
				array(
					'heading'     => esc_html__( 'Height', 'nova' ),
					'description' => esc_html__( 'Map height in pixel.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'height',
					'value'       => '625px'
				),
				array(
					'heading'     => esc_html__( 'Zoom', 'nova' ),
					'description' => esc_html__( 'Enter zoom level. The value is between 1 and 20.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'zoom',
					'value'       => '15'
				),
				array(
					'heading'     => esc_html__( 'Color', 'nova' ),
					'description' => esc_html__( 'Select map color style', 'nova' ),
					'edit_field_class' => 'vc_col-xs-12 vc_btn3-colored-dropdown vc_colored-dropdown',
					'type'        => 'dropdown',
					'param_name'  => 'color',
					'value'       => array(
						esc_html__( 'Default', 'nova' ) => '',
						esc_html__( 'Grey', 'nova' ) => 'grey',
						esc_html__( 'Classic Black', 'nova' ) => 'inverse',
						esc_html__( 'Vista Blue', 'nova' ) => 'vista-blue'
					)
				),
				array(
					'heading'     => esc_html__( 'Content', 'nova' ),
					'description' => esc_html__( 'Enter content of info window.', 'nova' ),
					'type'        => 'textarea_html',
					'param_name'  => 'content',
					'holder'      => 'div'
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class'
				)
			)
		) );

		// Testimonial
		vc_map( array(
			'name'        => esc_html__( 'Testimonial', 'nova' ),
			'description' => esc_html__( 'Written review from a satisfied customer', 'nova' ),
			'base'        => 'nova_testimonial',
			'icon'        => $this->get_icon( 'testimonial.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Styles', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'style',
					'value'       => array(
						esc_html__( 'Style 01', 'nova' ) => '1',
						esc_html__( 'Style 02', 'nova' ) => '2',
					),
				),
				array(
					'heading'     => esc_html__( 'Photo', 'nova' ),
					'description' => esc_html__( 'Author photo or avatar. Recommend 160x160 in dimension.', 'nova' ),
					'type'        => 'attach_image',
					'param_name'  => 'image'
				),
				array(
					'heading'     => esc_html__( 'Name', 'nova' ),
					'description' => esc_html__( 'Enter full name of the author', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'name',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Company', 'nova' ),
					'description' => esc_html__( 'Enter company name of author', 'nova' ),
					'param_name'  => 'company',
					'type'        => 'textfield',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Alignment', 'nova' ),
					'description' => esc_html__( 'Select testimonial alignment', 'nova' ),
					'param_name'  => 'align',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Left', 'nova' )   => 'left',
						esc_html__( 'Right', 'nova' )  => 'right'
					)
				),
				array(
					'heading'     => esc_html__( 'Content', 'nova' ),
					'description' => esc_html__( 'Testimonial content', 'nova' ),
					'type'        => 'textarea_html',
					'param_name'  => 'content'
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class'
				)
			)
		) );

		// Partners
		vc_map( array(
			'name'        => esc_html__( 'Partner Logos', 'nova' ),
			'description' => esc_html__( 'Show list of partner logo', 'nova' ),
			'base'        => 'nova_partners',
			'icon'        => $this->get_icon( 'partners.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Image source', 'nova' ),
					'description' => esc_html__( 'Select images source', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'source',
					'value'       => array(
						esc_html__( 'Media library', 'nova' )  => 'media_library',
						esc_html__( 'External Links', 'nova' ) => 'external_link'
					)
				),
				array(
					'heading'     => esc_html__( 'Images', 'nova' ),
					'description' => esc_html__( 'Select images from media library', 'nova' ),
					'type'        => 'attach_images',
					'param_name'  => 'images',
					'dependency'  => array(
						'element'   => 'source',
						'value'     => 'media_library'
					)
				),
				array(
					'heading'     => esc_html__( 'Image size', 'nova' ),
					'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)). Leave empty to use "thumbnail" size.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'img_size',
					'dependency'  => array(
						'element'   => 'source',
						'value'     => 'media_library'
					)
				),
				array(
					'heading'     => esc_html__( 'External links', 'nova' ),
					'description' => esc_html__( 'Enter external links for partner logos (Note: divide links with linebreaks (Enter)).', 'nova' ),
					'type'        => 'exploded_textarea_safe',
					'param_name'  => 'custom_srcs',
					'dependency'  => array(
						'element'   => 'source',
						'value'     => 'external_link'
					)
				),
				array(
					'heading'     => esc_html__( 'Image size', 'nova' ),
					'description' => esc_html__( 'Enter image size in pixels. Example: 200x100 (Width x Height).', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'external_img_size',
					'dependency'  => array(
						'element'   => 'source',
						'value'     => 'external_link'
					)
				),
				array(
					'heading'     => esc_html__( 'Custom links', 'nova' ),
					'description' => esc_html__( 'Enter links for each image here. Divide links with linebreaks (Enter).', 'nova' ),
					'type'        => 'exploded_textarea_safe',
					'param_name'  => 'custom_links'
				),
				array(
					'heading'     => esc_html__( 'Custom link target', 'nova' ),
					'description' => esc_html__( 'Select where to open custom links.', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'custom_links_target',
					'value'       => array(
						esc_html__( 'Same window', 'nova' ) => '_self',
						esc_html__( 'New window', 'nova' ) => '_blank'
					)
				),
				array(
					'heading'     => esc_html__( 'Layout', 'nova' ),
					'description' => esc_html__( 'Select the layout images source', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'layout',
					'value'       => array(
						esc_html__( 'Bordered', 'nova' ) => 'bordered',
						esc_html__( 'Plain', 'nova' ) => 'plain'
					)
				),
				Novaworks_Shortcodes_Helper::fieldColumnGrid( array(
					'heading' 		=> esc_html__( 'Items per row', 'nova' ),
					'media'			=> array(
						'lg'  => 4,
						'md'  => 4,
						'mb'  => 2
					)
				) ),
				vc_map_add_css_animation(),
				Novaworks_Shortcodes_Helper::fieldExtraClass()
			)
		) );

		// Contact Box
		vc_map( array(
			'name'        => esc_html__( 'Contact Box', 'nova' ),
			'description' => esc_html__( 'Contact information', 'nova' ),
			'base'        => 'nova_contact_box',
			'icon'        => $this->get_icon( 'contact.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Address', 'nova' ),
					'description' => esc_html__( 'The office address', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'address',
					'holder'      => 'p'
				),
				array(
					'heading'     => esc_html__( 'Phone', 'nova' ),
					'description' => esc_html__( 'The phone number', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'phone',
					'holder'      => 'p'
				),
				array(
					'heading'     => esc_html__( 'Fax', 'nova' ),
					'description' => esc_html__( 'The fax number', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'fax',
					'holder'      => 'p'
				),
				array(
					'heading'     => esc_html__( 'Email', 'nova' ),
					'description' => esc_html__( 'The email adress', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'email',
					'holder'      => 'p'
				),
				array(
					'heading'     => esc_html__( 'Website', 'nova' ),
					'description' => esc_html__( 'The phone number', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'website',
					'holder'      => 'p'
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class'
				)
			)
		) );

		// Info List
		vc_map( array(
			'name'        => esc_html__( 'Info List', 'nova' ),
			'description' => esc_html__( 'List of information', 'nova' ),
			'base'        => 'nova_info_list',
			'icon'        => $this->get_icon( 'info-list.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Information', 'nova' ),
					'description' => esc_html__( 'Enter information', 'nova' ),
					'type'        => 'param_group',
					'param_name'  => 'info',
					'value'       => urlencode( json_encode( array(
						array(
							'icon'  => 'fa fa-home',
							'label' => esc_html__( 'Address', 'nova' ),
							'value' => '9606 North MoPac Expressway'
						),
						array(
							'icon'  => 'fa fa-phone',
							'label' => esc_html__( 'Phone', 'nova' ),
							'value' => '+1 248-785-8545'
						),
						array(
							'icon'  => 'fa fa-fax',
							'label' => esc_html__( 'Fax', 'nova' ),
							'value' => '123123123'
						),
						array(
							'icon'  => 'fa fa-envelope',
							'label' => esc_html__( 'Email', 'nova' ),
							'value' => 'nova@uix.store'
						),
						array(
							'icon'  => 'fa fa-globe',
							'label' => esc_html__( 'Website', 'nova' ),
							'value' => 'http://uix.store'
						)
					) ) ),
					'params'      => array(
						array(
							'type'       => 'iconpicker',
							'heading'    => esc_html__( 'Icon', 'nova' ),
							'param_name' => 'icon',
							'settings'   => array(
								'emptyIcon'    => false,
								'iconsPerPage' => 4000
							)
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Label', 'nova' ),
							'param_name'  => 'label',
							'admin_label' => true
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Value', 'nova' ),
							'param_name'  => 'value',
							'admin_label' => true
						),
					),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class'
				)
			)
		) );

		// FAQ
		vc_map( array(
			'name'        => esc_html__( 'FAQ', 'nova' ),
			'description' => esc_html__( 'Question and answer toggle', 'nova' ),
			'base'        => 'nova_faq',
			'icon'        => $this->get_icon( 'faq.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'js_view'     => 'VcToggleView',
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Question', 'nova' ),
					'description' => esc_html__( 'Enter title of toggle block.', 'nova' ),
					'type'        => 'textfield',
					'holder'      => 'h4',
					'class'       => 'vc_toggle_title wpb_element_title',
					'param_name'  => 'title',
					'value'       => esc_html__( 'Question content goes here', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Answer', 'nova' ),
					'description' => esc_html__( 'Toggle block content.', 'nova' ),
					'type'        => 'textarea_html',
					'holder'      => 'div',
					'class'       => 'vc_toggle_content',
					'param_name'  => 'content',
					'value'       => esc_html__( 'Answer content goes here, click edit button to change this text.', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Default state', 'nova' ),
					'description' => esc_html__( 'Select "Open" if you want toggle to be open by default.', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'open',
					'value'       => array(
						esc_html__( 'Closed', 'nova' ) => 'false',
						esc_html__( 'Open', 'nova' ) => 'true'
					)
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield'
				)
			)
		) );

		// Team Member
		vc_map( array(
			'name'        => esc_html__( 'Team Member', 'nova' ),
			'description' => esc_html__( 'Single team member information', 'nova' ),
			'base'        => 'nova_team_member',
			'icon'        => $this->get_icon( 'member.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Image', 'nova' ),
					'description' => esc_html__( 'Member photo', 'nova' ),
					'param_name'  => 'image',
					'type'        => 'attach_image'
				),
				array(
					'heading'     => esc_html__( 'Image Size', 'nova' ),
					'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)). Leave empty to use "thumbnail" size.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'value'       => 'full'
				),
				array(
					'heading'     => esc_html__( 'Full Name', 'nova' ),
					'description' => esc_html__( 'Member name', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'name',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Job', 'nova' ),
					'description' => esc_html__( 'The job/position name of member in your team', 'nova' ),
					'param_name'  => 'job',
					'type'        => 'textfield',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Facebook', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'facebook'
				),
				array(
					'heading'     => esc_html__( 'Twitter', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'twitter'
				),
				array(
					'heading'     => esc_html__( 'Google Plus', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'google'
				),
				array(
					'heading'     => esc_html__( 'Pinterest', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'pinterest'
				),
				array(
					'heading'     => esc_html__( 'Linkedin', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'linkedin'
				),
				array(
					'heading'     => esc_html__( 'Youtube', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'youtube'
				),
				array(
					'heading'     => esc_html__( 'Instagram', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'instagram'
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class'
				)
			)
		) );
		
		// Hotspot
		vc_map( array(
			'name'                      => esc_html__( 'Nova Hotspot', 'nova' ),
			'base'                      => 'nova_hotspot',
			'allowed_container_element' => 'vc_row',
			'content_element'           => false,
			'params'                    => array(

				array(
					'heading'     => esc_html__( 'Select identificator', 'nova' ),
					'description' => esc_html__( 'Input product ID or product SKU or product title to see suggestions', 'nova' ),
					'type'        => 'autocomplete',
					'param_name'  => 'product_id',
				),
				array(
					'heading'     => esc_html__( 'Position', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'position',
					'value'       => array(
						esc_html__( 'Top', 'nova' ) => 'top',
						esc_html__( 'Right', 'nova' ) => 'right',
						esc_html__( 'Bottom', 'nova' ) => 'bottom',
						esc_html__( 'Left', 'nova' ) => 'left'
					),
					'save_always' => true
				),
				array(
					'heading'     => esc_html__( 'Left', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'left'
				),
				array(
					'heading'     => esc_html__( 'Top', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'top'
				),
				array(
					'heading'     => esc_html__( 'Title', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'title'
				),
				array(
					'heading'     => esc_html__( 'Content', 'nova' ),
					'type'        => 'textarea_html',
					'param_name'  => 'content'
				),

			)
		) );
		
		// Image with hotspots
		vc_map( array(
			'name'        => esc_html__( 'Image With Hotspots', 'nova' ),
			'base'        => 'nova_image_with_hotspots',
			'icon'        => 'icon-wpb-single-image',
			'category'    => esc_html__( 'Nova', 'nova' ),
			'description' => esc_html__( 'Add Hotspots On Your Image', 'nova' ),
			'params'      => array(

				array(
					'heading'     => esc_html__( 'Enable Product Viewer', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'product_viewer',
					'value'       => array(
						esc_html__( 'Yes', 'nova' ) => 'true'
					)
				),

				array(
					'heading'     => esc_html__( 'Image', 'nova' ),
					'description' => esc_html__( 'Choose your image that will show the hotspots. <br/> You can then click on the image in the preview area to add your hotspots in the desired locations.', 'nova' ),
					'type'        => 'attach_image',
					'param_name'  => 'image'
				),
				array(
					'heading'     => esc_html__( 'Preview', 'nova' ),
					'description' => wp_kses_post( __( "Click to add - Drag to move - Edit content below<br/> Note: this preview will not reflect hotspot style choices or show tooltips. <br/>This is only used as a visual guide for positioning.", 'nova' ) ),
					'type'        => 'nova_hotspot_image_preview',
					'param_name'  => 'preview'
				),
				array(
					'heading'     => esc_html__( 'Hotspots', 'nova' ),
					'type'        => 'textarea_html',
					'param_name'  => 'content'
				),
				array(
					'heading'     => esc_html__( 'Extra Class name', 'nova' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class',
					'group'       => esc_html__( 'Style', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Color', 'nova' ),
					'description' => esc_html__( 'Choose the color which the hotspot will use', 'nova' ),
					'admin_label' => true,
					'type'        => 'dropdown',
					'param_name'  => 'color',
					'value'       => array(
						esc_html__( 'Primary', 'nova' ) => 'primary',
						esc_html__( 'Secondary', 'nova' ) => 'secondary',
						esc_html__( 'Blue', 'nova' ) => 'blue',
						esc_html__( 'Turquoise', 'nova' ) => 'turquoise',
						esc_html__( 'Pink', 'nova' ) => 'pink',
						esc_html__( 'Violet', 'nova' ) => 'violet',
						esc_html__( 'Peacoc', 'nova' ) => 'peacoc',
						esc_html__( 'Chino', 'nova' ) => 'chino',
						esc_html__( 'Mulled Wine', 'nova' ) => 'mulled_wine',
						esc_html__( 'Vista Blue', 'nova' ) => 'vista_blue',
						esc_html__( 'Black', 'nova' ) => 'black',
						esc_html__( 'Grey', 'nova' ) => 'grey',
						esc_html__( 'Orange', 'nova' ) => 'orange',
						esc_html__( 'Sky', 'nova' ) => 'sky',
						esc_html__( 'Green', 'nova' ) => 'green',
						esc_html__( 'Juicy pink', 'nova' ) => 'juicy_pink',
						esc_html__( 'Sandy brown', 'nova' ) => 'sandy_brown',
						esc_html__( 'Purple', 'nova' ) => 'purple',
						esc_html__( 'White', 'nova' ) => 'white'
					),
					'std'         => 'primary',
					'save_always' => true,
					'group'       => esc_html__( 'Style', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Hotspot Icon', 'nova' ),
					'description' => esc_html__( 'The icon that will be shown on the hotspots', 'nova' ),
					'admin_label' => true,
					'type'        => 'dropdown',
					'param_name'  => 'hotspot_icon',
					'value'       => array(
						esc_html__( 'Plus Sign', 'nova' ) => 'plus_sign',
						//esc_html__( 'Numerical', 'nova' ) => 'numerical',
						esc_html__( 'Custom Title', 'nova' ) => 'custom_title',
					),
					'save_always' => true,
					'group'       => esc_html__( 'Style', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Start number', 'nova' ),
					'description' => esc_html__( 'The number that will begin on the hotspots', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'start_number',
					'value'       => '1',
					'dependency'  => array(
						'element'   => 'hotspot_icon',
						'value'     => 'numerical'
					),
					'save_always' => true,
					'group'       => esc_html__( 'Style', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Tooltip Functionality', 'nova' ),
					'description' => esc_html__( 'Select how you want your tooltips to display to the user', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'tooltip',
					'value'       => array(
						esc_html__( 'Show On Hover', 'nova' ) => 'hover',
						esc_html__( 'Show On Click', 'nova' ) => 'click',
						esc_html__( 'Always Show', 'nova' ) => 'always_show'
					),
					'save_always' => true,
					'group'       => esc_html__( 'Style', 'nova' ),
				),
				array(
					'heading'     => esc_html__( 'Tooltip Shadow', 'nova' ),
					'description' => esc_html__( 'Select the shadow size for your tooltip', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'tooltip_shadow',
					'value'       => array(
						esc_html__( 'None', 'nova' ) => 'none',
						esc_html__( 'Small Depth', 'nova' ) => 'small_depth',
						esc_html__( 'Medium Depth', 'nova' ) => 'medium_depth',
						esc_html__( 'Large Depth', 'nova' ) => 'large_depth'
					),
					'save_always' => true,
					'group'       => esc_html__( 'Style', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Enable Animation', 'nova' ),
					'description' => esc_html__( 'Turning this on will make your hotspots animate in when the user scrolls to the element', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'animation',
					'value'       => array(
						esc_html__( 'Yes, please', 'nova' ) => 'true'
					),
					'group'       => esc_html__( 'Style', 'nova' )
				)
			)		
		) );
		
		// Advanced Carousel
		vc_map( array(
			'name'        => esc_html__( 'Nova Advanced Carousel', 'nova' ),
			'base'        => 'nova_carousel',
			'icon'        => 'nova-wpb-icon nova_carousel',
			'category'    => esc_html__( 'Nova', 'nova' ),
			'description' => esc_html__( 'Carousel anything.','nova' ),
			'as_parent'   => array( 'except' => array( 'nova_carousel' ) ),
			'content_element' => true,
			'controls'    => 'full',
			'show_settings_on_create' => true,
			'params'      => Novaworks_Shortcodes_Helper::paramCarouselShortCode(),
			'js_view'     => 'VcColumnView'
		) );
		
		// Timeline
		vc_map( array(
			'name'        => esc_html__( 'Timeline', 'nova' ),
			'base'        => 'nova_timeline',
			'icon'        => 'nova-wpb-icon nova_timeline',
			'category'    => esc_html__( 'Nova', 'nova' ),
			'description' => esc_html__( 'Displays the timeline block', 'nova' ),
			'as_parent'   => array( 'only' => 'nova_timeline_item' ),
			'content_element' => true,
			'is_container' => false,
			'show_settings_on_create' => false,
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Styles', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'style',
					'value'       => array(
						esc_html__( 'Style 01', 'nova' ) => '1',
						esc_html__( 'Style 02', 'nova' ) => '2',
					),
				),
				/*
				array(
					'heading'     => esc_html__( 'Enable load more', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'enable_load_more',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' )
				),
				*/
				array(
					'heading'     => esc_html__( 'Load More Text', 'nova' ),
					'description' => esc_html__( 'Customize the load more text.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'load_more_text',
					'value'       => 'Load More',
					'dependency'  => array( 'element' => 'enable_load_more', 'value' => array( 'yes' ) )
				),
				Novaworks_Shortcodes_Helper::fieldExtraClass()
			),
			'js_view' => 'VcColumnView'
		) );
		
		// Timeline Item
		vc_map( array(
			'name'        => esc_html__( 'Timeline Item', 'nova' ),
			'base'        => 'nova_timeline_item',
			'icon'        => 'nova-wpb-icon nova_timeline_item',
			'category'    => esc_html__( 'Nova', 'nova' ),
			'description' => esc_html__( 'Displays the timeline block', 'nova' ),
			'as_child'    => array( 'only' => 'nova_timeline' ),
			'content_element' => true,
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Title', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'title',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Subtitle', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'subtitle',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Content', 'nova' ),
					'type'        => 'textarea_html',
					'param_name'  => 'content',
				),
				array(
					'heading'     => esc_html__( 'Apply link to:', 'nova' ),
					'description' => esc_html__( 'Select the element for link.', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'time_link_apply',
					'value'       => array(
						esc_html__( 'None', 'nova' ) => '',
						esc_html__( 'Complete box', 'nova' ) => 'box',
						esc_html__( 'Box Title', 'nova' ) => 'title',
						esc_html__( 'Display Read More', 'nova' ) => 'more',
					)
				),
				array(
					'heading'     => esc_html__( 'Add Link', 'nova' ),
					'description' => esc_html__( 'Provide the link that will be applied to this timeline.', 'nova' ),
					'type'        => 'vc_link',
					'param_name'  => 'time_link',
					'dependency'  => array(
						'element'   => 'time_link_apply',
						'value'     => array( 'more', 'title', 'box' )
					)
				),
				array(
					'heading'     => esc_html__( 'Read More Text', 'nova' ),
					'description' => esc_html__( 'Customize the read more text.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'time_read_text',
					'value'       => 'Read More',
					'dependency'  => array(
						'element'   => 'time_link_apply',
						'value'     => array( 'more' )
					)
				),
				array(
					'heading'     => esc_html__( 'Dot Color', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'dot_color'
				),
				Novaworks_Shortcodes_Helper::fieldCssAnimation(),
				Novaworks_Shortcodes_Helper::fieldExtraClass()
			)
		) );
		
		// Instagram Feed
		vc_map( array(
			'name'        => esc_html__( 'Instagram Feed', 'nova' ),
			'base'        => 'nova_instagram_feed',
			'icon'        => 'nova-wpb-icon nova_instagram_feed',
			'category'    => esc_html__( 'Nova', 'nova' ),
			'description' => esc_html__( 'Display Instagram photos from any non-private Instagram accounts', 'nova' ),
			'params'      => array_merge( array(
				array(
					'heading'     => esc_html__( 'Instagram Access Token', 'nova' ),
					'description' => esc_html__( 'In order to display your photos you need an Access Token from Instagram.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'instagram_token'
				),
				array(
					'heading'     => esc_html__( 'Feed Type', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'feed_type',
					'value'       => array(
						esc_html__( 'Images with a specific tag', 'nova' ) => 'tagged',
						esc_html__( 'Images from a location.', 'nova' ) => 'location',
						esc_html__( 'Images from a user', 'nova' ) => 'user'
					),
					'admin_label' => true,
					'std'         => 'user'
				),
				array(
					'heading'     => esc_html__( 'Hashtag', 'nova' ),
					'description' => esc_html__( 'Only Alphanumeric characters are allowed (a-z, A-Z, 0-9)', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'hashtag',
					'admin_label' => true
				),
				array(
					'heading'     => esc_html__( 'Location ID', 'nova' ),
					'description' => esc_html__( 'Unique id of a location to get', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'location_id'
				),
				array(
					'heading'     => esc_html__( 'User ID', 'nova' ),
					'description' => esc_html__( 'Unique id of a user to get', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'user_id'
				),
				array(
					'heading'     => esc_html__( 'Sort By', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'sort_by',
					'admin_label' => true,
					'value'       => array(
						esc_html__( 'Default', 'nova' ) => 'none',
						esc_html__( 'Newest to oldest', 'nova' ) => 'most-recent',
						esc_html__( 'Oldest to newest', 'nova' ) => 'least-recent',
						esc_html__( 'Highest # of likes to lowest.', 'nova' ) => 'most-liked',
						esc_html__( 'Lowest # likes to highest.', 'nova' ) => 'least-liked',
						esc_html__( 'Highest # of comments to lowest', 'nova' ) => 'most-commented',
						esc_html__( 'Lowest # of comments to highest.', 'nova' ) => 'least-commented',
						esc_html__( 'Random order', 'nova' ) => 'random',
					),
					'std'         => 'none'
				),

				Novaworks_Shortcodes_Helper::fieldColumn( array(
					'heading'     => esc_html__( 'Items to show', 'nova' )
				) ),
				Novaworks_Shortcodes_Helper::getParamItemSpace( array(
					'std'         => 'default'
				) ),
/*
				array(
					'heading'     => esc_html__( 'Enable slider', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'enable_carousel',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' )
				),
*/
				array(
					'heading'     => esc_html__( 'Limit', 'nova' ),
					'description' => esc_html__( 'Maximum number of Images to add. Max of 60', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'limit',
					'admin_label' => true,
					'value'       => 5
				),
				array(
					'heading'     => esc_html__( 'Image size', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'image_size',
					'value'       => array(
						esc_html__( 'Thumbnail', 'nova' ) => 'thumbnail',
						esc_html__( 'Low Resolution', 'nova' ) => 'low_resolution',
						esc_html__( 'Standard Resolution', 'nova' ) => 'standard_resolution'
					),
					'std'         => 'thumbnail'
				),
				array(
					'heading'     => esc_html__( 'Image Aspect Ration', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'image_aspect_ration',
					'value'       => array(
						esc_html__( '1:1', 'nova' ) => '11',
						esc_html__( '16:9', 'nova' ) => '169',
						esc_html__( '4:3', 'nova' ) => '43',
						esc_html__( '2.35:1', 'nova' ) => '2351'
					),
					'std'         => '11'
				),
				Novaworks_Shortcodes_Helper::fieldExtraClass()
			) /*,Novaworks_Shortcodes_Helper::paramCarouselShortCode( false )*/ )
		) );
		
		// Portfolio Grid
		vc_map( array(
			'name'        => esc_html__( 'Portfolio Grid', 'nova' ),
			'base'        => 'nova_portfolio_grid',
			'icon'        => 'nova-wpb-icon nova_show_portfolios',
			'category'    => esc_html__( 'Nova', 'nova' ),
			'description' => esc_html__( 'Display portfolio with themes style.', 'nova' ),
			'params'      => array_merge( array(
				/*
				array(
					'heading'     => esc_html__( 'Layout', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'layout',
					'value'       => array(
						esc_html__( 'Grid', 'nova' ) => 'grid'
					),
					'std'         => 'grid'
				),
				array(
					'heading'     => esc_html__( 'Style', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'grid_style',
					'value' => array(
						esc_html__( 'Design 01', 'nova' ) => '1',
						esc_html__( 'Design 02', 'nova' ) => '2',
						esc_html__( 'Design 03', 'nova' ) => '3',
						esc_html__( 'Design 04', 'nova' ) => '4',
						esc_html__( 'Design 05', 'nova' ) => '5',
						esc_html__( 'Design 06', 'nova' ) => '6',
						esc_html__( 'Design 07', 'nova' ) => '7',
						esc_html__( 'Design 08', 'nova' ) => '8'
					),
					'dependency'  => array(
						'element'   => 'layout',
						'value'     => 'grid'
					),
					'std'         => '1'
				),
				array(
					'heading'     => esc_html__( 'Style', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'list_style',
					'value' => array(
						esc_html__( 'Classic 01', 'nova' ) => '1',
						esc_html__( 'Classic 02', 'nova' ) => '2',
						esc_html__( 'Classic 03', 'nova' ) => '3'
					),
					'dependency'  => array(
						'element'   => 'layout',
						'value'     => 'list'
					),
					'std'         => '1'
				),
				*/
				Novaworks_Shortcodes_Helper::fieldImageSize( array(
					'value'       => 'nova-portfolio',
					//'group'       => esc_html__( 'Item Settings', 'nova' )
				) ),

				array(
					'heading'     => esc_html__( 'Category In:', 'nova' ),
					'type'        => 'autocomplete',
					'param_name'  => 'category__in',
					'settings'    => array(
						'unique_values'  => true,
						'multiple'       => true,
						'sortable'       => true,
						'groups'         => false,
						'min_length'     => 0,
						'auto_focus'     => true,
						'display_inline' => true,
					),
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Category Not In:', 'nova' ),
					'type'        => 'autocomplete',
					'param_name'  => 'category__not_in',
					'settings'    => array(
						'unique_values'  => true,
						'multiple'       => true,
						'sortable'       => true,
						'groups'         => false,
						'min_length'     => 0,
						'auto_focus'     => true,
						'display_inline' => true,
					),
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Post In:', 'nova' ),
					'type'        => 'autocomplete',
					'param_name'  => 'post__in',
					'settings'    => array(
						'unique_values'  => true,
						'multiple'       => true,
						'sortable'       => true,
						'groups'         => false,
						'min_length'     => 0,
						'auto_focus'     => true,
						'display_inline' => true,
					),
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Post Not In:', 'nova' ),
					'type'        => 'autocomplete',
					'param_name'  => 'post__not_in',
					'settings'    => array(
						'unique_values'  => true,
						'multiple'       => true,
						'sortable'       => true,
						'groups'         => false,
						'min_length'     => 0,
						'auto_focus'     => true,
						'display_inline' => true,
					),
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Order by', 'nova' ),
					'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'nova' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
					'type'        => 'dropdown',
					'param_name'  => 'orderby',
					'value'       => array(
						'',
						esc_html__( 'Date', 'nova' ) => 'date',
						esc_html__( 'ID', 'nova' ) => 'ID',
						esc_html__( 'Author', 'nova' ) => 'author',
						esc_html__( 'Title', 'nova' ) => 'title',
						esc_html__( 'Modified', 'nova' ) => 'modified',
						esc_html__( 'Random', 'nova' ) => 'rand',
						esc_html__( 'Comment count', 'nova' ) => 'comment_count',
						esc_html__( 'Menu order', 'nova' ) => 'menu_order',
					),
					'save_always' => true,
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Sort order', 'nova' ),
					'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'nova' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
					'type'        => 'dropdown',
					'param_name'  => 'order',
					'value'       => array(
						'',
						esc_html__( 'Descending', 'nova' ) => 'DESC',
						esc_html__( 'Ascending', 'nova' ) => 'ASC',
					),
					'save_always' => true,
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Total items', 'nova' ),
					'description' => esc_html__( 'Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'per_page',
					'value'       => -1,
					'min'         => -1,
					'max'         => 1000,
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Paged', 'nova' ),
					'type'        => 'hidden',
					'param_name'  => 'paged',
					'value'       => '1',
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				/*
				array(
					'heading'     => esc_html__( 'Item title tag', 'nova' ),
					'description' => esc_html__( 'Default is H3', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'title_tag',
					'value'       => array(
						esc_html__( 'Default', 'nova' ) => 'h3',
						esc_html__( 'H1', 'nova' ) => 'h1',
						esc_html__( 'H2', 'nova' ) => 'h2',
						esc_html__( 'H4', 'nova' ) => 'h4',
						esc_html__( 'H5', 'nova' ) => 'h5',
						esc_html__( 'H6', 'nova' ) => 'h6',
						esc_html__( 'DIV', 'nova' ) => 'div',
					),
					'std'         => 'h3',
					'group'       => esc_html__( 'Item Settings', 'nova' )
				),
				*/

				Novaworks_Shortcodes_Helper::fieldColumn( array(
					'heading'     => esc_html__( 'Items to show', 'nova' ),
					'dependency'  => array(
						'element'   => 'layout',
						'value'     => array( 'grid', 'masonry' )
					),
				) ),

				Novaworks_Shortcodes_Helper::getParamItemSpace(),

				array(
					'heading'     => esc_html__( 'Enable slider', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'enable_carousel',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' ),
					'dependency'  => array(
						'element'   => 'layout',
						'value'     => array( 'grid' )
					),
				),
				array(
					'heading'     => esc_html__( 'Enable Load More', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'enable_loadmore',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' )
				),
				array(
					'heading'     => esc_html__( 'Load More Text', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'load_more_text',
					'value'       => esc_html__( 'Load more', 'nova' ),
					'dependency'  => array( 'element' => 'enable_loadmore', 'value' => 'yes' ),
				),
				Novaworks_Shortcodes_Helper::fieldExtraClass()
			), Novaworks_Shortcodes_Helper::paramCarouselShortCode( false ) )		
		) );
		
		// Portfolio Masonry
		vc_map( array(
			'name'        => esc_html__( 'Portfolio Masonry', 'nova' ),
			'base'        => 'nova_portfolio_masonry',
			'icon'        => 'nova-wpb-icon nova_portfolio_masonry',
			'category'    => esc_html__( 'Nova', 'nova' ),
			'description' => esc_html__( 'Display portfolio with themes style.', 'nova' ),
			'params'      => array(
				/*
				array(
					'heading'     => esc_html__( 'Style', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'masonry_style',
					'value'       => array(
						esc_html__( 'Design 01', 'nova' ) => '1',
						esc_html__( 'Design 02', 'nova' ) => '2',
						esc_html__( 'Design 03', 'nova' ) => '3',
						esc_html__( 'Design 04', 'nova' ) => '4',
						esc_html__( 'Design 05', 'nova' ) => '5',
						esc_html__( 'Design 06', 'nova' ) => '6',
						esc_html__( 'Design 07', 'nova' ) => '7',
						esc_html__( 'Design 08', 'nova' ) => '8'
					),
					'std'         => '1'
				),

				array(
					'heading'     => esc_html__( 'Item Title HTML Tag', 'nova' ),
					'description' => esc_html__( 'Default is H5', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'title_tag',
					'value'       => array(
						esc_html__( 'Default', 'nova' ) => 'h5',
						esc_html__( 'H1', 'nova' ) => 'h1',
						esc_html__( 'H2', 'nova' ) => 'h2',
						esc_html__( 'H3', 'nova' ) => 'h3',
						esc_html__( 'H4', 'nova' ) => 'h4',
						esc_html__( 'H6', 'nova' ) => 'h6',
						esc_html__( 'DIV', 'nova' ) => 'div',
					),
					'std'         => 'h5'
				),
				*/
				array(
					'heading'     => esc_html__( 'Image Size', 'nova' ),
					'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'img_size',
					'value'       => 'full'
				),

				array(
					'heading'     => esc_html__( 'Category In:', 'nova' ),
					'type'        => 'autocomplete',
					'param_name'  => 'category__in',
					'settings'    => array(
						'unique_values'  => true,
						'multiple'       => true,
						'sortable'       => true,
						'groups'         => false,
						'min_length'     => 0,
						'auto_focus'     => true,
						'display_inline' => true,
					),
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Category Not In:', 'nova' ),
					'type'        => 'autocomplete',
					'param_name'  => 'category__not_in',
					'settings'    => array(
						'unique_values'  => true,
						'multiple'       => true,
						'sortable'       => true,
						'groups'         => false,
						'min_length'     => 0,
						'auto_focus'     => true,
						'display_inline' => true,
					),
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Post In:', 'nova' ),
					'type'        => 'autocomplete',
					'param_name'  => 'post__in',
					'settings'    => array(
						'unique_values'  => true,
						'multiple'       => true,
						'sortable'       => true,
						'groups'         => false,
						'min_length'     => 0,
						'auto_focus'     => true,
						'display_inline' => true,
					),
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Post Not In:', 'nova' ),
					'type'        => 'autocomplete',
					'param_name'  => 'post__not_in',
					'settings'    => array(
						'unique_values'  => true,
						'multiple'       => true,
						'sortable'       => true,
						'groups'         => false,
						'min_length'     => 0,
						'auto_focus'     => true,
						'display_inline' => true,
					),
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Order by', 'nova' ),
					'description' => sprintf( __( 'Select how to sort retrieved products. More at %s.', 'nova' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
					'type'        => 'dropdown',
					'param_name'  => 'orderby',
					'value'       => array(
						esc_html__( 'Date', 'nova' ) => 'date',
						esc_html__( 'ID', 'nova' ) => 'ID',
						esc_html__( 'Author', 'nova' ) => 'author',
						esc_html__( 'Title', 'nova' ) => 'title',
						esc_html__( 'Modified', 'nova' ) => 'modified',
						esc_html__( 'Random', 'nova' ) => 'rand',
						esc_html__( 'Comment count', 'nova' ) => 'comment_count',
						esc_html__( 'Menu order', 'nova' ) => 'menu_order',
					),
					'std'         => 'date',
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Sort order', 'nova' ),
					'description' => sprintf( __( 'Designates the ascending or descending order. More at %s.', 'nova' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
					'type'        => 'dropdown',
					'param_name'  => 'order',
					'std'         => 'desc',
					'value'       => array(
						esc_html__( 'Descending', 'nova' ) => 'desc',
						esc_html__( 'Ascending', 'nova' ) => 'asc',
					),
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Items per page', 'nova' ),
					'description' => esc_html__( 'Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'per_page',
					'value'       => 10,
					'min'         => -1,
					'max'         => 1000,
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Paged', 'nova' ),
					'param_name'  => 'paged',
					'type'        => 'hidden',
					'value'       => '1',
					'group'       => esc_html__( 'Query Settings', 'nova' )
				),

				array(
					'heading'     => esc_html__( 'Column Type', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'column_type',
					'value'       => array(
						esc_html__( 'Default', 'nova' ) => 'default',
						//esc_html__( 'Custom', 'nova' ) => 'custom',
					),
					'std'         => 'default'
				),
				array(
					'heading'     => esc_html__( 'Responsive Column', 'nova' ),
					'type'        => 'nova_column',
					'param_name'  => 'column',
					'unit'        => '',
					'media'       => array(
						'xlg'	=> 1,
						'lg'	=> 1,
						'md'	=> 1,
						'sm'	=> 1,
						'xs'	=> 1,
						'mb'	=> 1
					),
					'dependency'  => array(
						'element'   => 'column_type',
						'value'     => 'default'
					)
				),

				array(
					'heading'     => esc_html__( 'Portfolio Item Width', 'nova' ),
					'description' => esc_html__( 'Set your portfolio item default width', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'base_item_w',
					'value'       => 400,
					'min'         => 100,
					'max'         => 1920,
					'suffix'      => 'px',
					'dependency'  => array(
						'element'   => 'column_type',
						'value'     => 'custom'
					)
				),

				array(
					'heading'     => esc_html__( 'Portfolio Item Height', 'nova' ),
					'description' => esc_html__( 'Set your portfolio item default height', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'base_item_h',
					'value'       => 400,
					'min'         => 100,
					'max'         => 1920,
					'suffix'      => 'px',
					'dependency'  => array(
						'element'   => 'column_type',
						'value'     => 'custom'
					)
				),

				array(
					'heading'     => esc_html__( 'Mobile Column', 'nova' ),
					'type'        => 'nova_column',
					'param_name'  => 'mb_column',
					'unit'        => '',
					'media'       => array(
						'md'	=> 1,
						'sm'	=> 1,
						'xs'	=> 1,
						'mb'	=> 1
					),
					'dependency'  => array(
						'element'   => 'column_type',
						'value'     => 'custom'
					)
				),

				array(
					'heading'     => esc_html__( 'Enable Custom Item Setting', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'custom_item_size',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' ),
					'dependency'  => array(
						'element'   => 'column_type',
						'value'     => 'custom'
					)
				),
				array(
					'heading'     => esc_html__( 'Item Sizes', 'nova' ),
					'type'        => 'param_group',
					'param_name'  => 'item_sizes',
					'params'      => array(
						array(
							'heading'     => esc_html__( 'Width', 'nova' ),
							'description' => esc_html__( 'it will occupy x width of base item width, example: this item will be occupy 2x width of base width you need entered 2', 'nova' ),
							'type'        => 'dropdown',
							'param_name'  => 'w',
							'admin_label' => true,
							'value'       => array(
								esc_html__( '1/2x width', 'nova' ) => '0.5',
								esc_html__( '1x width', 'nova' ) => '1',
								esc_html__( '1.5x width', 'nova' ) => '1.5',
								esc_html__( '2x width', 'nova' ) => '2',
								esc_html__( '2.5x width', 'nova' ) => '2.5',
								esc_html__( '3x width', 'nova' ) => '3',
								esc_html__( '3.5x width', 'nova' ) => '3.5',
								esc_html__( '4x width', 'nova' ) => '4'
							),
							'std'         => '1'
						),

						array(
							'heading'     => esc_html__( 'Height', 'nova' ),
							'description' => esc_html__( 'it will occupy x height of base item height, example: this item will be occupy 2x height of base height you need entered 2', 'nova' ),
							'type'        => 'dropdown',
							'param_name'  => 'h',
							'admin_label' => true,
							'value'       => array(
								esc_html__( '1/2x height', 'nova' ) => '0.5',
								esc_html__( '1x height', 'nova' ) => '1',
								esc_html__( '1.5x height', 'nova' ) => '1.5',
								esc_html__( '2x height', 'nova' ) => '2',
								esc_html__( '2.5x height', 'nova' ) => '2.5',
								esc_html__( '3x height', 'nova' ) => '3',
								esc_html__( '3.5x height', 'nova' ) => '3.5',
								esc_html__( '4x height', 'nova' ) => '4'
							),
							'std'         => '1'
						),

						array(
							'heading'     => esc_html__( 'Custom Image Size', 'nova' ),
							'description' => esc_html__( 'leave blank to inherit from parent settings', 'nova' ),
							'type'        => 'textfield',
							'param_name'  => 's'
						),
					),
					'dependency'  => array(
						'element'   => 'custom_item_size',
						'value'     => 'yes'
					)
				),

				Novaworks_Shortcodes_Helper::getParamItemSpace(),

				array(
					'heading'     => esc_html__( 'Enable Skill Filter', 'nova' ),
					'param_name'  => 'enable_skill_filter',
					'type'        => 'checkbox',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' )
				),
				array(
					'heading'     => esc_html__( 'Skill Filter', 'nova' ),
					'type'        => 'autocomplete',
					'param_name'  => 'filters',
					'settings'    => array(
						'unique_values'  => true,
						'multiple'       => true,
						'sortable'       => true,
						'groups'         => false,
						'min_length'     => 0,
						'auto_focus'     => true,
						'display_inline' => true,
					),
					'dependency'  => array(
						'element'   => 'enable_skill_filter',
						'value'     => 'yes'
					)
				),
				/*
				array(
					'heading'     => esc_html__( 'Filter style', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'filter_style',
					'value'       => array(
						esc_html__( 'Style 01', 'nova' ) => '1',
						esc_html__( 'Style 02', 'nova' ) => '2',
						esc_html__( 'Style 03', 'nova' ) => '3'
					),
					'std'         => '1',
					'dependency'  => array(
						'element'   => 'enable_skill_filter',
						'value'     => 'yes'
					)
				),
				*/
				array(
					'heading'     => esc_html__( 'Enable Load More', 'nova' ),
					'param_name'  => 'enable_loadmore',
					'type'        => 'checkbox',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' )
				),
				array(
					'heading'     => esc_html__( 'Load More Text', 'nova' ),
					'param_name'  => 'load_more_text',
					'type'        => 'textfield',
					'value'       => esc_html__( 'Load more', 'nova' ),
					'dependency'  => array(
						'element'   => 'enable_loadmore',
						'value'     => 'yes'
					)
				),

				array(
					'heading'     => esc_html__( 'Extra Class name', 'nova' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class'
				)
			)		
		) );
		
		// Popup video
		vc_map( array(
			'name'        => esc_html__( 'Popup Video Player', 'nova' ),
			'base'        => 'nova_popup_video',
			'icon'        => 'nova-wpb-icon nova_popup_video',
			'category'    => esc_html__( 'Nova', 'nova' ),
			'description' => esc_html__( 'Embed YouTube/Vimeo player', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Icon library', 'nova' ),
					'description' => esc_html__( 'Select icon library.', 'nova' ),
					'param_name'  => 'icon_type',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Font Awesome', 'nova' ) => 'fontawesome',
						esc_html__( 'Open Iconic', 'nova' ) => 'openiconic',
						esc_html__( 'Typicons', 'nova' ) => 'typicons',
						esc_html__( 'Entypo', 'nova' ) => 'entypo',
						esc_html__( 'Linecons', 'nova' ) => 'linecons',
						esc_html__( 'Mono Social', 'nova' ) => 'monosocial',
						esc_html__( 'Novaworks Icons', 'nova' ) => 'nova_icon_outline',
						esc_html__( 'Nucleo Glyph', 'nova' ) => 'nucleo_glyph',
						esc_html__( 'Material', 'nova' ) => 'material',
						esc_html__( 'Custom Image', 'nova' ) => 'image'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_fontawesome',
					'value'       => 'fa fa-adjust',
					'settings'    => array(
						'emptyIcon'    => false,
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'fontawesome'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_openiconic',
					'value'       => 'vc-oi vc-oi-dial',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'openiconic',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'openiconic'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_typicons',
					'value'       => 'typcn typcn-adjust-brightness',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'typicons',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'typicons'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_entypo',
					'value'       => 'entypo-icon entypo-icon-note',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'entypo',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'entypo'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_linecons',
					'value'       => 'vc_li vc_li-heart',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'linecons',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'linecons'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_monosocial',
					'value'       => 'vc-mono vc-mono-fivehundredpx',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'monosocial',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'monosocial'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_nova_icon_outline',
					'value'       => 'nova-icon nature_bear',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'nova_icon_outline',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'nova_icon_outline'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_nucleo_glyph',
					'value'       => 'nc-icon-glyph nature_bear',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'nucleo_glyph',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'nucleo_glyph'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_material',
					'value'       => 'vc-material vc-material-cake',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'material',
						'iconsPerPage' => 4000
					),
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'material'
					)
				),
				array(
					'heading'     => esc_html__( 'Icon Image', 'nova' ),
					'description' => esc_html__( 'Upload icon image', 'nova' ),
					'type'        => 'attach_image',
					'param_name'  => 'image',
					'value'       => '',
					'dependency'  => array(
						'element'      => 'icon_type',
						'value'        => 'image'
					)
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Video link', 'nova' ),
					'param_name'  => 'link',
					'value'       => '',
					'admin_label' => true,
					'description' => sprintf( __( 'Enter link to video (Note: read more about available formats at WordPress <a href="%s" target="_blank">codex page</a>).', 'nova' ), 'http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Video width', 'nova' ),
					'param_name'  => 'el_width',
					'value'       => array(
						'100%' => '100',
						'90%' => '90',
						'80%' => '80',
						'70%' => '70',
						'60%' => '60',
						'50%' => '50',
						'40%' => '40',
						'30%' => '30',
						'20%' => '20',
						'10%' => '10',
					),
					'description' => esc_html__( 'Select video width (percentage).', 'nova' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Video aspect ration', 'nova' ),
					'param_name'  => 'el_aspect',
					'value'       => array(
						'16:9' => '169',
						'4:3' => '43',
						'2.35:1' => '235',
					),
					'description' => esc_html__( 'Select video aspect ratio.', 'nova' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => esc_html__( 'Alignment', 'nova' ),
					'param_name'  => 'alignment',
					'value' => array(
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Left','nova') => 'left',
						esc_html__( 'Right','nova')	=> 'right',
						esc_html__( 'Inline','nova') => 'inline'
					),
					'std'         => 'center'
				),
				array(
					'heading'     => esc_html__( 'Icon Style', 'nova' ),
					'description' => esc_html__( 'Select icon style', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'style',
					'value'       => array(
						esc_html__( 'Normal', 'nova' ) => 'normal',
						esc_html__( 'Circle', 'nova' ) => 'circle',
						esc_html__( 'Square', 'nova' ) => 'square',
						esc_html__( 'Round', 'nova' ) => 'round',
						esc_html__( 'Advanced', 'nova' ) => 'advanced'
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Size', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'icon_size',
					'value'       => 50,
					'min'         => 10,
					'suffix'      => 'px',
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Width', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'icon_width',
					'value'       => 50,
					'min'         => 10,
					'suffix'      => 'px',
					'dependency'  => array(
						'element' 	=> 'style',
						'value' 	=> array( 'circle', 'square', 'round', 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Padding', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'icon_padding',
					'value'       => 0,
					'min'         => 0,
					'suffix'      => 'px',
					'dependency'  => array(
						'element' 	=> 'style',
						'value' 	=> array( 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading' 	  => esc_html__( 'Icon Color Type', 'nova' ),
					'type' 		  => 'dropdown',
					'param_name'  => 'icon_color_type',
					'value' 	  => array(
						esc_html__( 'Simple', 'nova' ) => 'simple',
						esc_html__( 'Gradient', 'nova' ) => 'gradient',
					),
					'std'		  => 'simple',
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading' 	  => esc_html__( 'Icon Color', 'nova' ),
					'type' 		  => 'colorpicker',
					'param_name'  => 'icon_color',
					'group' 	  => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading' 	  => esc_html__( 'Icon Hover Color', 'nova' ),
					'type' 		  => 'colorpicker',
					'param_name'  => 'icon_h_color',
					'group' 	  => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading' 	  => esc_html__( 'Icon Color #2', 'nova' ),
					'type' 		  => 'colorpicker',
					'param_name'  => 'icon_color2',
					'dependency'  => array(
						'element' 	=> 'icon_color_type',
						'value' 	=> array( 'gradient' )
					),
					'group' 	  => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading' 	  => esc_html__( 'Icon Hover Color #2', 'nova' ),
					'type' 		  => 'colorpicker',
					'param_name'  => 'icon_h_color2',
					'dependency'  => array(
						'element' 	=> 'icon_color_type',
						'value' 	=> array('gradient')
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Background Type', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'icon_bg_type',
					'value'       => array(
						esc_html__( 'Simple', 'nova' ) => 'simple',
						esc_html__( 'Gradient', 'nova' ) => 'gradient',
					),
					'std'         => 'simple',
					'dependency'  => array(
						'element' 	=> 'style',
						'value' 	=> array( 'circle', 'square', 'round', 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Background Color', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'icon_bg',
					'dependency'  => array(
						'element' 	=> 'style',
						'value' 	=> array( 'circle', 'square', 'round', 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Hover Background Color', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'icon_h_bg',
					'dependency'  => array(
						'element' 	=> 'style',
						'value' 	=> array( 'circle', 'square', 'round', 'advanced' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Background Color #2', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'icon_bg2',
					'dependency'  => array(
						'element' 	=> 'icon_bg_type',
						'value' 	=> array( 'gradient' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Hover Background Color #2', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'icon_h_bg2',
					'dependency'  => array(
						'element' 	=> 'icon_bg_type',
						'value' 	=> array( 'gradient' )
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Border Style', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'icon_border_style',
					'value'       => array(
						esc_html__( 'None', 'nova' ) => '',
						esc_html__( 'Solid', 'nova' ) => 'solid',
						esc_html__( 'Dashed', 'nova' ) => 'dashed',
						esc_html__( 'Dotted', 'nova' ) => 'dotted',
						esc_html__( 'Double', 'nova' ) => 'double',
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Border Width', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'icon_border_width',
					'value'       => 1,
					'min'         => 1,
					'max'         => 10,
					'suffix'      => 'px',
					'dependency'  => array(
						'element'   => 'icon_border_style',
						'not_empty'	=> true
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Border Color', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'icon_border_color',
					'dependency'  => array(
						'element'   => 'icon_border_style',
						'not_empty' => true
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Hover Border Color', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'icon_h_border_color',
					'dependency'  => array(
						'element'   => 'icon_border_style',
						'not_empty' => true
					),
					'group'       => esc_html__( 'Icon Settings', 'nova' )
				),
				array(
					'heading'     => esc_html__( 'Icon Border Radius', 'nova' ),
					'description' => esc_html__( '0 pixel value will create a square border. As you increase the value, the shape convert in circle slowly. (e.g 500 pixels).', 'nova' ),
					'type'        => 'nova_number',
					'param_name'  => 'icon_border_radius',
					'value'       => 500,
					'min'         => 1,
					'suffix'      => 'px',
					'dependency'  => array(
						'element' 	=> 'style',
						'value' 	=> array( 'advanced' )
					),
					'group'       => esc_html__('Icon Settings', 'nova')
				),
				vc_map_add_css_animation(),
				Novaworks_Shortcodes_Helper::fieldExtraClass()
			)
		) );
	}

	/**
	 * Get Icon URL
	 *
	 * @param string $file_name The icon file name with extension
	 *
	 * @return string Full URL of icon image
	 */
	protected function get_icon( $file_name ) {

		if ( file_exists( NOVA_ADDONS_DIR . 'assets/icons/' . $file_name ) ) {
			$url = NOVA_ADDONS_URL . 'assets/icons/' . $file_name;
		} else {
			$url = NOVA_ADDONS_URL . 'assets/icons/default.png';
		}

		return $url;
	}

	/**
	 * Get category for auto complete field
	 *
	 * @param string $taxonomy Taxnomy to get terms
	 *
	 * @return array
	 */
	protected function get_terms( $taxonomy = 'product_cat' ) {
		// We don't want to query all terms again
		if ( isset( $this->terms[ $taxonomy ] ) ) {
			return $this->terms[ $taxonomy ];
		}

		$cats = get_terms( $taxonomy );
		if ( ! $cats || is_wp_error( $cats ) ) {
			return array();
		}

		$categories = array();
		foreach ( $cats as $cat ) {
			$categories[] = array(
				'label' => $cat->name,
				'value' => $cat->slug,
				'group' => 'category'
			);
		}

		// Store this in order to avoid double query this
		$this->terms[ $taxonomy ] = $categories;

		return $categories;
	}

	/**
	 * Add new fonts into Google font list
	 *
	 * @param array $fonts Array of objects
	 *
	 * @return array
	 */
	public function add_google_fonts( $fonts ) {
		$fonts[] = ( object ) array(
			'font_family' => 'Amatic SC',
			'font_styles' => '400,700',
			'font_types'  => '400 regular:400:normal,700 regular:700:normal'
		);

		$fonts[] = (object) array(
			'font_family' => 'Montez',
			'font_styles' => '400',
			'font_types'  => '400 regular:400:normal'
		);

		usort( $fonts, array( $this, 'sort_fonts' ) );

		return $fonts;
	}

	/**
	 * Sort fonts base on name
	 *
	 * @param object $a
	 * @param object $b
	 *
	 * @return int
	 */
	private function sort_fonts( $a, $b ) {
		return strcmp( $a->font_family, $b->font_family );
	}
}

