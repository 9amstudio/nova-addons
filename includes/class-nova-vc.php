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
			'weight'      => 0,
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
				'value'   => 'yes',
			),
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
					'value'       => 15,
				),
				array(
					'heading'     => esc_html__( 'Columns', 'nova' ),
					'description' => esc_html__( 'Display products in how many columns', 'nova' ),
					'param_name'  => 'columns',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( '4 Columns', 'nova' ) => 4,
						esc_html__( '5 Columns', 'nova' ) => 5,
						esc_html__( '6 Columns', 'nova' ) => 6,
					),
				),
				array(
					'heading'     => esc_html__( 'Category', 'nova' ),
					'description' => esc_html__( 'Select what categories you want to use. Leave it empty to use all categories.', 'nova' ),
					'param_name'  => 'category',
					'type'        => 'autocomplete',
					'value'       => '',
					'settings'    => array(
						'multiple' => true,
						'sortable' => true,
						'values'   => $this->get_terms(),
					),
				),
				array(
					'heading'     => esc_html__( 'Product Type', 'nova' ),
					'description' => esc_html__( 'Select product type you want to show', 'nova' ),
					'param_name'  => 'type',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Recent Products', 'nova' )       => 'recent',
						esc_html__( 'Featured Products', 'nova' )     => 'featured',
						esc_html__( 'Sale Products', 'nova' )         => 'sale',
						esc_html__( 'Best Selling Products', 'nova' ) => 'best_sellers',
						esc_html__( 'Top Rated Products', 'nova' )    => 'top_rated',
					),
				),
				array(
					'heading'     => esc_html__( 'Load More Button', 'nova' ),
					'description' => esc_html__( 'Show load more button with ajax loading', 'nova' ),
					'param_name'  => 'load_more',
					'type'        => 'checkbox',
					'value'       => array(
						esc_html__( 'Yes', 'nova' ) => 'yes',
					),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => '',
				),
			),
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
					'heading'     => esc_html__( 'Number Of Products', 'nova' ),
					'param_name'  => 'per_page',
					'type'        => 'textfield',
					'value'       => 15,
					'description' => esc_html__( 'Total number of products will be display in single tab', 'nova' ),
				),
				array(
					'heading'     => esc_html__( 'Columns', 'nova' ),
					'param_name'  => 'columns',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( '4 Columns', 'nova' ) => 4,
						esc_html__( '5 Columns', 'nova' ) => 5,
						esc_html__( '6 Columns', 'nova' ) => 6,
					),
					'description' => esc_html__( 'Display products in how many columns', 'nova' ),
				),
				array(
					'heading'     => esc_html__( 'Tabs', 'nova' ),
					'description' => esc_html__( 'Select how to group products in tabs', 'nova' ),
					'param_name'  => 'filter',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Group by category', 'nova' ) => 'category',
						esc_html__( 'Group by feature', 'nova' )  => 'group',
					),
				),
				array(
					'heading'     => esc_html__( 'Categories', 'nova' ),
					'description' => esc_html__( 'Select what categories you want to use. Leave it empty to use all categories.', 'nova' ),
					'param_name'  => 'category',
					'type'        => 'autocomplete',
					'value'       => '',
					'settings'    => array(
						'multiple' => true,
						'sortable' => true,
						'values'   => $this->get_terms(),
					),
					'dependency'  => array(
						'element' => 'filter',
						'value'   => 'category',
					),
				),
				array(
					'heading'     => esc_html__( 'Tabs Effect', 'nova' ),
					'description' => esc_html__( 'Select the way tabs load products', 'nova' ),
					'param_name'  => 'filter_type',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Isotope Toggle', 'nova' ) => 'isotope',
						esc_html__( 'Ajax Load', 'nova' )      => 'ajax',
					),
				),
				array(
					'heading'     => esc_html__( 'Load More Button', 'nova' ),
					'param_name'  => 'load_more',
					'type'        => 'checkbox',
					'value'       => array(
						esc_html__( 'Yes', 'nova' ) => 'yes',
					),
					'description' => esc_html__( 'Show load more button with ajax loading', 'nova' ),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => '',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
				),
			),
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
					'value'       => 15,
				),
				array(
					'heading'     => esc_html__( 'Columns', 'nova' ),
					'description' => esc_html__( 'Display products in how many columns', 'nova' ),
					'param_name'  => 'columns',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( '3 Columns', 'nova' ) => 3,
						esc_html__( '4 Columns', 'nova' ) => 4,
						esc_html__( '5 Columns', 'nova' ) => 5,
						esc_html__( '6 Columns', 'nova' ) => 6,
					),
				),
				array(
					'heading'     => esc_html__( 'Product Type', 'nova' ),
					'description' => esc_html__( 'Select product type you want to show', 'nova' ),
					'param_name'  => 'type',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Recent Products', 'nova' )       => 'recent',
						esc_html__( 'Featured Products', 'nova' )     => 'featured',
						esc_html__( 'Sale Products', 'nova' )         => 'sale',
						esc_html__( 'Best Selling Products', 'nova' ) => 'best_sellers',
						esc_html__( 'Top Rated Products', 'nova' )    => 'top_rated',
					),
				),
				array(
					'heading'     => esc_html__( 'Categories', 'nova' ),
					'description' => esc_html__( 'Select what categories you want to use. Leave it empty to use all categories.', 'nova' ),
					'param_name'  => 'category',
					'type'        => 'autocomplete',
					'value'       => '',
					'settings'    => array(
						'multiple' => true,
						'sortable' => true,
						'values'   => $this->get_terms(),
					),
				),
				array(
					'heading'     => esc_html__( 'Auto Play', 'nova' ),
					'description' => esc_html__( 'Auto play speed in miliseconds. Enter "0" to disable auto play.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'autoplay',
					'value'       => 5000,
				),
				array(
					'heading'    => esc_html__( 'Loop', 'nova' ),
					'type'       => 'checkbox',
					'param_name' => 'loop',
					'value'      => array( esc_html__( 'Yes', 'nova' ) => 'yes' ),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => '',
				),
			),
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
					'description' => esc_html__( 'Number of posts you want to show', 'nova' ),
					'heading'     => esc_html__( 'Number of posts', 'nova' ),
					'param_name'  => 'per_page',
					'type'        => 'textfield',
					'value'       => 3,
				),
				array(
					'heading'     => esc_html__( 'Columns', 'nova' ),
					'description' => esc_html__( 'Display posts in how many columns', 'nova' ),
					'param_name'  => 'columns',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( '3 Columns', 'nova' ) => 3,
						esc_html__( '4 Columns', 'nova' ) => 4,
					),
				),
				array(
					'heading'     => esc_html__( 'Category', 'nova' ),
					'description' => esc_html__( 'Enter categories name', 'nova' ),
					'param_name'  => 'category',
					'type'        => 'autocomplete',
					'settings'    => array(
						'multiple' => true,
						'sortable' => true,
						'values'   => $this->get_terms( 'category' ),
					),
				),
				array(
					'heading'     => esc_html__( 'Hide Post Meta', 'nova' ),
					'description' => esc_html__( 'Hide information about date, category', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'hide_meta',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' ),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => '',
				),
			),
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
					'heading'     => esc_html__( 'Date', 'nova' ),
					'description' => esc_html__( 'Enter the date in format: YYYY/MM/DD', 'nova' ),
					'admin_label' => true,
					'type'        => 'textfield',
					'param_name'  => 'date',
				),
				array(
					'heading'     => esc_html__( 'Text Align', 'nova' ),
					'description' => esc_html__( 'Select text alignment', 'nova' ),
					'param_name'  => 'text_align',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Left', 'nova' )   => 'left',
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Right', 'nova' )  => 'right',
					),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => '',
				),
			),
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
					'param_name'  => 'label',
				),
				array(
					'heading'    => esc_html__( 'URL (Link)', 'nova' ),
					'type'       => 'vc_link',
					'param_name' => 'link',
				),
				array(
					'heading'     => esc_html__( 'Style', 'nova' ),
					'description' => esc_html__( 'Select button style', 'nova' ),
					'param_name'  => 'style',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Normal', 'nova' )  => 'normal',
						esc_html__( 'Outline', 'nova' ) => 'outline',
						esc_html__( 'Light', 'nova' )   => 'light',
					),
				),
				array(
					'heading'     => esc_html__( 'Size', 'nova' ),
					'description' => esc_html__( 'Select button size', 'nova' ),
					'param_name'  => 'size',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Normal', 'nova' ) => 'normal',
						esc_html__( 'Large', 'nova' )  => 'large',
						esc_html__( 'Small', 'nova' )  => 'small',
					),
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'normal', 'outline' ),
					),
				),
				array(
					'heading'     => esc_html__( 'Color', 'nova' ),
					'description' => esc_html__( 'Select button color', 'nova' ),
					'param_name'  => 'color',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Dark', 'nova' )  => 'dark',
						esc_html__( 'White', 'nova' ) => 'white',
					),
					'dependency'  => array(
						'element' => 'style',
						'value'   => array( 'outline' ),
					),
				),
				array(
					'heading'     => esc_html__( 'Alignment', 'nova' ),
					'description' => esc_html__( 'Select button alignment', 'nova' ),
					'param_name'  => 'align',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Inline', 'nova' ) => 'inline',
						esc_html__( 'Left', 'nova' )   => 'left',
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Right', 'nova' )  => 'right',
					),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => '',
				),
			),
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
					'type'        => 'attach_image',
				),
				array(
					'heading'     => esc_html__( 'Image size', 'nova' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'value'       => '',
				),
				array(
					'heading'     => esc_html__( 'Banner description', 'nova' ),
					'description' => esc_html__( 'A short text display before the banner text', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'desc',
				),
				array(
					'heading'     => esc_html__( 'Banner Text', 'nova' ),
					'description' => esc_html__( 'Enter the banner text', 'nova' ),
					'type'        => 'textarea',
					'param_name'  => 'content',
					'admin_label' => true,
				),
				array(
					'heading'     => esc_html__( 'Banner Text Position', 'nova' ),
					'description' => esc_html__( 'Select text position', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'text_position',
					'value'       => array(
						esc_html__( 'Left', 'nova' )   => 'left',
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Right', 'nova' )  => 'right',
					),
				),
				array(
					'type'       => 'font_container',
					'param_name' => 'font_container',
					'value'      => '',
					'settings'   => array(
						'fields' => array(
							'font_size',
							'line_height',
							'color',
							'font_size_description'   => esc_html__( 'Enter text font size.', 'nova' ),
							'line_height_description' => esc_html__( 'Enter text line height.', 'nova' ),
							'color_description'       => esc_html__( 'Select text color.', 'nova' ),
						),
					),
				),
				array(
					'heading'     => esc_html__( 'Use theme default font family?', 'nova' ),
					'description' => esc_html__( 'Use font family from the theme.', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'use_theme_fonts',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' ),
				),
				array(
					'type'       => 'google_fonts',
					'param_name' => 'google_fonts',
					'value'      => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
					'settings'   => array(
						'fields' => array(
							'font_family_description' => esc_html__( 'Select font family.', 'nova' ),
							'font_style_description'  => esc_html__( 'Select font styling.', 'nova' ),
						),
					),
					'dependency' => array(
						'element'            => 'use_theme_fonts',
						'value_not_equal_to' => 'yes',
					),
				),
				array(
					'heading'    => esc_html__( 'Link (URL)', 'nova' ),
					'type'       => 'vc_link',
					'param_name' => 'link',
				),
				array(
					'heading'     => esc_html__( 'Button Type', 'nova' ),
					'description' => esc_html__( 'Select button type', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'button_type',
					'value'       => array(
						esc_html__( 'Light Button', 'nova' )  => 'light',
						esc_html__( 'Normal Button', 'nova' ) => 'normal',
						esc_html__( 'Arrow Icon', 'nova' )    => 'arrow_icon',
					),
				),
				array(
					'heading'     => esc_html__( 'Button Text', 'nova' ),
					'description' => esc_html__( 'Enter the text for banner button', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'button_text',
					'dependency'  => array(
						'element' => 'button_type',
						'value'   => array( 'light', 'normal' ),
					),
				),
				array(
					'heading'     => esc_html__( 'Button Visibility', 'nova' ),
					'description' => esc_html__( 'Select button visibility', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'button_visibility',
					'value'       => array(
						esc_html__( 'Always visible', 'nova' ) => 'always',
						esc_html__( 'When hover', 'nova' )     => 'hover',
						esc_html__( 'Hidden', 'nova' )         => 'hidden',
					),
				),
				array(
					'heading'     => esc_html__( 'Banner Color Scheme', 'nova' ),
					'description' => esc_html__( 'Select color scheme for description, button color', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'scheme',
					'value'       => array(
						esc_html__( 'Dark', 'nova' )  => 'dark',
						esc_html__( 'Light', 'nova' ) => 'light',
					),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => '',
				),
				array(
					'heading'    => esc_html__( 'CSS box', 'nova' ),
					'type'       => 'css_editor',
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'nova' ),
				),
			),
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
					'admin_label' => true,
				),
				array(
					'heading'     => esc_html__( 'Image size', 'nova' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'value'       => '',
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
							'param_name' => 'text',
						),
						array(
							'heading'    => esc_html__( 'Button Link', 'nova' ),
							'type'       => 'vc_link',
							'param_name' => 'link',
						),
					),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => '',
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
					'type'        => 'attach_image',
				),
				array(
					'heading'     => esc_html__( 'Image size', 'nova' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'value'       => '',
				),
				array(
					'heading'     => esc_html__( 'Banner Text', 'nova' ),
					'description' => esc_html__( 'Enter banner text', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'text',
					'admin_label' => true,
				),
				array(
					'heading'     => esc_html__( 'Banner Text Position', 'nova' ),
					'description' => esc_html__( 'Select text position', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'text_align',
					'value'       => array(
						esc_html__( 'Left', 'nova' )   => 'left',
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Right', 'nova' )  => 'right',
					),
				),
				array(
					'heading'    => esc_html__( 'Link (URL)', 'nova' ),
					'type'       => 'vc_link',
					'param_name' => 'link',
				),
				array(
					'heading'     => esc_html__( 'Button Text', 'nova' ),
					'description' => esc_html__( 'Enter the text for banner button', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'button_text',
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => '',
				),
			),
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
					'type'        => 'attach_image',
				),
				array(
					'heading'     => esc_html__( 'Image size', 'nova' ),
					'description' => esc_html__( 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'value'       => 'full',
				),
				array(
					'heading'    => esc_html__( 'Link (URL)', 'nova' ),
					'type'       => 'vc_link',
					'param_name' => 'link',
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => '',
				),
				array(
					'heading'    => esc_html__( 'Banner Content', 'nova' ),
					'type'       => 'textarea_html',
					'param_name' => 'content',
					'group'      => esc_html__( 'Text', 'nova' ),
				),
				array(
					'heading'     => esc_html__( 'Button Text', 'nova' ),
					'description' => esc_html__( 'Enter the text for banner button', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'button_text',
					'group'       => esc_html__( 'Text', 'nova' ),
				),
				array(
					'heading'     => esc_html__( 'Text Color Scheme', 'nova' ),
					'description' => esc_html__( 'Select color scheme for banner content', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'scheme',
					'group'       => esc_html__( 'Text', 'nova' ),
					'value'       => array(
						esc_html__( 'Dark', 'nova' )  => 'dark',
						esc_html__( 'Light', 'nova' ) => 'light',
					),
				),
				array(
					'heading'     => esc_html__( 'Content Horizontal Alignment', 'nova' ),
					'description' => esc_html__( 'Horizontal alignment of banner text', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'align_horizontal',
					'group'       => esc_html__( 'Text', 'nova' ),
					'value'       => array(
						esc_html__( 'Left', 'nova' )   => 'left',
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Right', 'nova' )  => 'right',
					),
				),
				array(
					'heading'     => esc_html__( 'Content Vertical Alignment', 'nova' ),
					'description' => esc_html__( 'Vertical alignment of banner text', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'align_vertical',
					'group'       => esc_html__( 'Text', 'nova' ),
					'value'       => array(
						esc_html__( 'Top', 'nova' )    => 'top',
						esc_html__( 'Middle', 'nova' ) => 'middle',
						esc_html__( 'Bottom', 'nova' ) => 'bottom',
					),
				),
			),
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
					'type'        => 'attach_image',
				),
				array(
					'heading'     => esc_html__( 'Image Position', 'nova' ),
					'description' => esc_html__( 'Select image position', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'image_position',
					'value'       => array(
						esc_html__( 'Left', 'nova' )         => 'left',
						esc_html__( 'Right', 'nova' )        => 'right',
						esc_html__( 'Top', 'nova' )          => 'top',
						esc_html__( 'Bottom', 'nova' )       => 'bottom',
						esc_html__( 'Top Left', 'nova' )     => 'top-left',
						esc_html__( 'Top Right', 'nova' )    => 'top-right',
						esc_html__( 'Bottom Left', 'nova' )  => 'bottom-left',
						esc_html__( 'Bottom Right', 'nova' ) => 'bottom-right',
					),
				),
				array(
					'heading'     => esc_html__( 'Title', 'nova' ),
					'description' => esc_html__( 'The banner title', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'heading'     => esc_html__( 'Description', 'nova' ),
					'description' => esc_html__( 'The banner description', 'nova' ),
					'type'        => 'textarea',
					'param_name'  => 'content',
				),
				array(
					'heading'     => esc_html__( 'Text Position', 'nova' ),
					'description' => esc_html__( 'Select the position for title and description', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'text_position',
					'value'       => array(
						esc_html__( 'Top Left', 'nova' )     => 'top-left',
						esc_html__( 'Top Right', 'nova' )    => 'top-right',
						esc_html__( 'Middle Left', 'nova' )  => 'middle-left',
						esc_html__( 'Middle Right', 'nova' ) => 'middle-right',
						esc_html__( 'Bottom Left', 'nova' )  => 'bottom-left',
						esc_html__( 'Bottom Right', 'nova' ) => 'bottom-right',
					),
				),
				array(
					'heading'    => esc_html__( 'Link (URL)', 'nova' ),
					'type'       => 'vc_link',
					'param_name' => 'link',
				),
				array(
					'heading'     => esc_html__( 'Button Text', 'nova' ),
					'description' => esc_html__( 'Enter the text for banner button', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'button_text',
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
					'value'       => '',
				),
				array(
					'heading'    => __( 'CSS box', 'nova' ),
					'type'       => 'css_editor',
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'nova' ),
				),
			),
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
					'heading'     => esc_html__( 'Images', 'nova' ),
					'description' => esc_html__( 'Upload a product image', 'nova' ),
					'param_name'  => 'image',
					'type'        => 'attach_image',
					'value'       => '',
				),
				array(
					'heading'     => esc_html__( 'Product name', 'nova' ),
					'description' => esc_html__( 'Enter product name', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'heading'     => esc_html__( 'Product description', 'nova' ),
					'description' => esc_html__( 'Enter product description', 'nova' ),
					'type'        => 'textarea',
					'param_name'  => 'content',
				),
				array(
					'heading'     => esc_html__( 'Product price', 'nova' ),
					'description' => esc_html__( 'Enter product price. Only allow number.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'price',
				),
				array(
					'heading'    => esc_html__( 'Product URL', 'nova' ),
					'type'       => 'vc_link',
					'param_name' => 'link',
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
				),
			),
		) );

		// Banner Grid 4
		vc_map( array(
			'name'                    => esc_html__( 'Banner Grid 4', 'nova' ),
			'description'             => esc_html__( 'Arrange 4 banners per row with unusual structure.', 'nova' ),
			'base'                    => 'nova_banner_grid_4',
			'icon'                    => $this->get_icon( 'banner-grid-4.png' ),
			'category'                => esc_html__( 'Nova', 'nova' ),
			'js_view'                 => 'VcColumnView',
			'content_element'         => true,
			'show_settings_on_create' => false,
			'as_parent'               => array( 'only' => 'nova_banner,nova_banner2,nova_banner3' ),
			'params'                  => array(
				array(
					'heading'     => esc_html__( 'Reverse Order', 'nova' ),
					'description' => esc_html__( 'Reverse the order of banners inside this grid', 'nova' ),
					'param_name'  => 'reverse',
					'type'        => 'checkbox',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' ),
				),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
				),
			),
		) );

		// Banner Grid 5
		vc_map( array(
			'name'                    => esc_html__( 'Banner Grid 5', 'nova' ),
			'description'             => esc_html__( 'Arrange 5 banners in 3 columns.', 'nova' ),
			'base'                    => 'nova_banner_grid_5',
			'icon'                    => $this->get_icon( 'banner-grid-5.png' ),
			'category'                => esc_html__( 'Nova', 'nova' ),
			'js_view'                 => 'VcColumnView',
			'content_element'         => true,
			'show_settings_on_create' => false,
			'as_parent'               => array( 'only' => 'nova_banner,nova_banner2,nova_banner3' ),
			'params'                  => array(
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
				),
			),
		) );

		// Banner Grid 6
		vc_map( array(
			'name'                    => esc_html__( 'Banner Grid 6', 'nova' ),
			'description'             => esc_html__( 'Arrange 6 banners in 4 columns.', 'nova' ),
			'base'                    => 'nova_banner_grid_6',
			'icon'                    => $this->get_icon( 'banner-grid-6.png' ),
			'category'                => esc_html__( 'Nova', 'nova' ),
			'js_view'                 => 'VcColumnView',
			'content_element'         => true,
			'show_settings_on_create' => false,
			'as_parent'               => array( 'only' => 'nova_banner,nova_banner2,nova_banner3' ),
			'params'                  => array(
				array(
					'heading'     => esc_html__( 'Reverse Order', 'nova' ),
					'description' => esc_html__( 'Reverse the order of banners inside this grid', 'nova' ),
					'param_name'  => 'reverse',
					'type'        => 'checkbox',
					'value'       => array( esc_html__( 'Yes', 'nova' ) => 'yes' ),
				),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
				),
			),
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
					'admin_label' => true,
				),
				array(
					'heading'     => esc_html__( 'Circle Size', 'nova' ),
					'description' => esc_html__( 'Width of the circle', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'size',
					'value'       => 200,
				),
				array(
					'heading'     => esc_html__( 'Circle thickness', 'nova' ),
					'description' => esc_html__( 'Width of the arc', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'thickness',
					'value'       => 8,
				),
				array(
					'heading'     => esc_html__( 'Color', 'nova' ),
					'description' => esc_html__( 'Pick color for the circle', 'nova' ),
					'type'        => 'colorpicker',
					'param_name'  => 'color',
					'value'       => '#6dcff6',
				),
				array(
					'heading'     => esc_html__( 'Label Source', 'nova' ),
					'description' => esc_html__( 'Chart label source', 'nova' ),
					'param_name'  => 'label_source',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Auto', 'nova' )   => 'auto',
						esc_html__( 'Custom', 'nova' ) => 'custom',
					),
				),
				array(
					'heading'     => esc_html__( 'Custom label', 'nova' ),
					'description' => esc_html__( 'Text label for the chart', 'nova' ),
					'param_name'  => 'label',
					'type'        => 'textfield',
					'dependency'  => array(
						'element' => 'label_source',
						'value'   => 'custom',
					),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class',
				),
			),
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
					'heading'          => esc_html__( 'Type', 'nova' ),
					'description'      => esc_html__( 'Select message box type', 'nova' ),
					'edit_field_class' => 'vc_col-xs-12 vc_message-type',
					'type'             => 'dropdown',
					'param_name'       => 'type',
					'default'          => 'success',
					'admin_label'      => true,
					'value'            => array(
						esc_html__( 'Success', 'nova' )       => 'success',
						esc_html__( 'Informational', 'nova' ) => 'info',
						esc_html__( 'Error', 'nova' )         => 'danger',
						esc_html__( 'Warning', 'nova' )       => 'warning',
					),
				),
				array(
					'heading'    => esc_html__( 'Message Text', 'nova' ),
					'type'       => 'textarea_html',
					'param_name' => 'content',
					'holder'     => 'div',
				),
				array(
					'heading'     => esc_html__( 'Closeable', 'nova' ),
					'description' => esc_html__( 'Display close button for this box', 'nova' ),
					'type'        => 'checkbox',
					'param_name'  => 'closeable',
					'value'       => array(
						esc_html__( 'Yes', 'nova' ) => true,
					),
				),
				vc_map_add_css_animation(),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
				),
			),
		) );

		// Icon Box
		vc_map( array(
			'name'        => esc_html__( 'Icon Box', 'nova' ),
			'description' => esc_html__( 'Information box with icon', 'nova' ),
			'base'        => 'nova_icon_box',
			'icon'        => $this->get_icon( 'icon-box.png' ),
			'category'    => esc_html__( 'Nova', 'nova' ),
			'params'      => array(
				array(
					'heading'     => esc_html__( 'Icon library', 'nova' ),
					'description' => esc_html__( 'Select icon library.', 'nova' ),
					'param_name'  => 'icon_type',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Font Awesome', 'nova' ) => 'fontawesome',
						esc_html__( 'Open Iconic', 'nova' )  => 'openiconic',
						esc_html__( 'Typicons', 'nova' )     => 'typicons',
						esc_html__( 'Entypo', 'nova' )       => 'entypo',
						esc_html__( 'Linecons', 'nova' )     => 'linecons',
						esc_html__( 'Mono Social', 'nova' )  => 'monosocial',
						esc_html__( 'Material', 'nova' )     => 'material',
						esc_html__( 'Custom Image', 'nova' ) => 'image',
					),
				),
				array(
					'heading'     => esc_html__( 'Icon', 'nova' ),
					'description' => esc_html__( 'Select icon from library.', 'nova' ),
					'type'        => 'iconpicker',
					'param_name'  => 'icon_fontawesome',
					'value'       => 'fa fa-adjust',
					'settings'    => array(
						'emptyIcon'    => false,
						'iconsPerPage' => 4000,
					),
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => 'fontawesome',
					),
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
						'iconsPerPage' => 4000,
					),
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => 'openiconic',
					),
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
						'iconsPerPage' => 4000,
					),
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => 'typicons',
					),
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
						'iconsPerPage' => 4000,
					),
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => 'entypo',
					),
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
						'iconsPerPage' => 4000,
					),
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => 'linecons',
					),
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
						'iconsPerPage' => 4000,
					),
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => 'monosocial',
					),
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
						'iconsPerPage' => 4000,
					),
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => 'material',
					),
				),
				array(
					'heading'     => esc_html__( 'Icon Image', 'nova' ),
					'description' => esc_html__( 'Upload icon image', 'nova' ),
					'type'        => 'attach_image',
					'param_name'  => 'image',
					'value'       => '',
					'dependency'  => array(
						'element' => 'icon_type',
						'value'   => 'image',
					),
				),
				array(
					'heading'     => esc_html__( 'Icon Style', 'nova' ),
					'description' => esc_html__( 'Select icon style', 'nova' ),
					'param_name'  => 'style',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Normal', 'nova' ) => 'normal',
						esc_html__( 'Circle', 'nova' ) => 'circle',
						esc_html__( 'Round', 'nova' )  => 'round',
					),
				),
				array(
					'heading'     => esc_html__( 'Title', 'nova' ),
					'description' => esc_html__( 'The box title', 'nova' ),
					'admin_label' => true,
					'param_name'  => 'title',
					'type'        => 'textfield',
					'value'       => esc_html__( 'I am Icon Box', 'nova' ),
				),
				array(
					'heading'     => esc_html__( 'Content', 'nova' ),
					'description' => esc_html__( 'The box title', 'nova' ),
					'holder'      => 'div',
					'param_name'  => 'content',
					'type'        => 'textarea_html',
					'value'       => esc_html__( 'I am icon box. Click edit button to change this text.', 'nova' ),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class',
				),
			),
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
					'type'        => 'textfield',
				),
				array(
					'heading'     => esc_html__( 'Price', 'nova' ),
					'description' => esc_html__( 'Plan pricing', 'nova' ),
					'param_name'  => 'price',
					'type'        => 'textfield',
				),
				array(
					'heading'     => esc_html__( 'Currency', 'nova' ),
					'description' => esc_html__( 'Price currency', 'nova' ),
					'param_name'  => 'currency',
					'type'        => 'textfield',
					'value'       => '$',
				),
				array(
					'heading'     => esc_html__( 'Recurrence', 'nova' ),
					'description' => esc_html__( 'Recurring payment unit', 'nova' ),
					'param_name'  => 'recurrence',
					'type'        => 'textfield',
					'value'       => esc_html__( 'Per Month', 'nova' ),
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
							'type'       => 'textfield',
						),
						array(
							'heading'    => esc_html__( 'Feature value', 'nova' ),
							'param_name' => 'value',
							'type'       => 'textfield',
						),
					),
				),
				array(
					'heading'    => esc_html__( 'Button Text', 'nova' ),
					'param_name' => 'button_text',
					'type'       => 'textfield',
					'value'      => esc_html__( 'Get Started', 'nova' ),
				),
				array(
					'heading'    => esc_html__( 'Button Link', 'nova' ),
					'param_name' => 'button_link',
					'type'       => 'vc_link',
					'value'      => esc_html__( 'Get Started', 'nova' ),
				),
				array(
					'heading'     => esc_html__( 'Table color', 'nova' ),
					'description' => esc_html__( 'Pick color scheme for this table. It will be applied to table header and button.', 'nova' ),
					'param_name'  => 'color',
					'type'        => 'colorpicker',
					'value'       => '#6dcff6',
				),
				vc_map_add_css_animation(),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
				),
			),
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
					'param_name'  => 'api_key',
				),
				array(
					'heading'     => esc_html__( 'Address', 'nova' ),
					'description' => esc_html__( 'Enter address for map marker. If this option does not work correctly, use the Latitude and Longitude options bellow.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'address',
					'admin_label' => true,
				),
				array(
					'heading'          => esc_html__( 'Latitude', 'nova' ),
					'type'             => 'textfield',
					'edit_field_class' => 'vc_col-xs-6',
					'param_name'       => 'lat',
					'admin_label'      => true,
				),
				array(
					'heading'          => esc_html__( 'Longitude', 'nova' ),
					'type'             => 'textfield',
					'param_name'       => 'lng',
					'edit_field_class' => 'vc_col-xs-6',
					'admin_label'      => true,
				),
				array(
					'heading'     => esc_html__( 'Marker', 'nova' ),
					'description' => esc_html__( 'Upload custom marker icon or leave this to use default marker.', 'nova' ),
					'param_name'  => 'marker',
					'type'        => 'attach_image',
				),
				array(
					'heading'     => esc_html__( 'Width', 'nova' ),
					'description' => esc_html__( 'Map width in pixel or percentage.', 'nova' ),
					'param_name'  => 'width',
					'type'        => 'textfield',
					'value'       => '100%',
				),
				array(
					'heading'     => esc_html__( 'Height', 'nova' ),
					'description' => esc_html__( 'Map height in pixel.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'height',
					'value'       => '625px',
				),
				array(
					'heading'     => esc_html__( 'Zoom', 'nova' ),
					'description' => esc_html__( 'Enter zoom level. The value is between 1 and 20.', 'nova' ),
					'param_name'  => 'zoom',
					'type'        => 'textfield',
					'value'       => '15',
				),
				array(
					'heading'          => esc_html__( 'Color', 'nova' ),
					'description'      => esc_html__( 'Select map color style', 'nova' ),
					'edit_field_class' => 'vc_col-xs-12 vc_btn3-colored-dropdown vc_colored-dropdown',
					'param_name'       => 'color',
					'type'             => 'dropdown',
					'value'            => array(
						esc_html__( 'Default', 'nova' )       => '',
						esc_html__( 'Grey', 'nova' )          => 'grey',
						esc_html__( 'Classic Black', 'nova' ) => 'inverse',
						esc_html__( 'Vista Blue', 'nova' )    => 'vista-blue',
					),
				),
				array(
					'heading'     => esc_html__( 'Content', 'nova' ),
					'description' => esc_html__( 'Enter content of info window.', 'nova' ),
					'type'        => 'textarea_html',
					'param_name'  => 'content',
					'holder'      => 'div',
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class',
				),
			),
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
					'heading'     => esc_html__( 'Photo', 'nova' ),
					'description' => esc_html__( 'Author photo or avatar. Recommend 160x160 in dimension.', 'nova' ),
					'type'        => 'attach_image',
					'param_name'  => 'image',
				),
				array(
					'heading'     => esc_html__( 'Name', 'nova' ),
					'description' => esc_html__( 'Enter full name of the author', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'name',
					'admin_label' => true,
				),
				array(
					'heading'     => esc_html__( 'Company', 'nova' ),
					'description' => esc_html__( 'Enter company name of author', 'nova' ),
					'param_name'  => 'company',
					'type'        => 'textfield',
					'admin_label' => true,
				),
				array(
					'heading'     => esc_html__( 'Alignment', 'nova' ),
					'description' => esc_html__( 'Select testimonial alignment', 'nova' ),
					'param_name'  => 'align',
					'type'        => 'dropdown',
					'value'       => array(
						esc_html__( 'Center', 'nova' ) => 'center',
						esc_html__( 'Left', 'nova' )   => 'left',
						esc_html__( 'Right', 'nova' )  => 'right',
					),
				),
				array(
					'heading'     => esc_html__( 'Content', 'nova' ),
					'description' => esc_html__( 'Testimonial content', 'nova' ),
					'type'        => 'textarea_html',
					'param_name'  => 'content',
					'holder'      => 'div',
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class',
				),
			),
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
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
				),
			),
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
					'holder'      => 'p',
				),
				array(
					'heading'     => esc_html__( 'Phone', 'nova' ),
					'description' => esc_html__( 'The phone number', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'phone',
					'holder'      => 'p',
				),
				array(
					'heading'     => esc_html__( 'Fax', 'nova' ),
					'description' => esc_html__( 'The fax number', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'fax',
					'holder'      => 'p',
				),
				array(
					'heading'     => esc_html__( 'Email', 'nova' ),
					'description' => esc_html__( 'The email adress', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'email',
					'holder'      => 'p',
				),
				array(
					'heading'     => esc_html__( 'Website', 'nova' ),
					'description' => esc_html__( 'The phone number', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'website',
					'holder'      => 'p',
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class',
				),
			),
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
							'value' => '9606 North MoPac Expressway',
						),
						array(
							'icon'  => 'fa fa-phone',
							'label' => esc_html__( 'Phone', 'nova' ),
							'value' => '+1 248-785-8545',
						),
						array(
							'icon'  => 'fa fa-fax',
							'label' => esc_html__( 'Fax', 'nova' ),
							'value' => '123123123',
						),
						array(
							'icon'  => 'fa fa-envelope',
							'label' => esc_html__( 'Email', 'nova' ),
							'value' => 'nova@uix.store',
						),
						array(
							'icon'  => 'fa fa-globe',
							'label' => esc_html__( 'Website', 'nova' ),
							'value' => 'http://uix.store',
						),
					) ) ),
					'params'      => array(
						array(
							'type'       => 'iconpicker',
							'heading'    => esc_html__( 'Icon', 'nova' ),
							'param_name' => 'icon',
							'settings'   => array(
								'emptyIcon'    => false,
								'iconsPerPage' => 4000,
							),
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Label', 'nova' ),
							'param_name'  => 'label',
							'admin_label' => true,
						),
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Value', 'nova' ),
							'param_name'  => 'value',
							'admin_label' => true,
						),
					),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'el_class',
				),
			),
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
					'value'       => esc_html__( 'Question content goes here', 'nova' ),
				),
				array(
					'heading'     => esc_html__( 'Answer', 'nova' ),
					'description' => esc_html__( 'Toggle block content.', 'nova' ),
					'type'        => 'textarea_html',
					'holder'      => 'div',
					'class'       => 'vc_toggle_content',
					'param_name'  => 'content',
					'value'       => esc_html__( 'Answer content goes here, click edit button to change this text.', 'nova' ),
				),
				array(
					'heading'     => esc_html__( 'Default state', 'nova' ),
					'description' => esc_html__( 'Select "Open" if you want toggle to be open by default.', 'nova' ),
					'type'        => 'dropdown',
					'param_name'  => 'open',
					'value'       => array(
						esc_html__( 'Closed', 'nova' ) => 'false',
						esc_html__( 'Open', 'nova' )   => 'true',
					),
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
				),
			),
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
					'type'        => 'attach_image',
				),
				array(
					'heading'     => esc_html__( 'Image Size', 'nova' ),
					'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)). Leave empty to use "thumbnail" size.', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'value'       => 'full',
				),
				array(
					'heading'     => esc_html__( 'Full Name', 'nova' ),
					'description' => esc_html__( 'Member name', 'nova' ),
					'type'        => 'textfield',
					'param_name'  => 'name',
					'admin_label' => true,
				),
				array(
					'heading'     => esc_html__( 'Job', 'nova' ),
					'description' => esc_html__( 'The job/position name of member in your team', 'nova' ),
					'param_name'  => 'job',
					'type'        => 'textfield',
					'admin_label' => true,
				),
				array(
					'heading'    => esc_html__( 'Facebook', 'nova' ),
					'type'       => 'textfield',
					'param_name' => 'facebook',
				),
				array(
					'heading'    => esc_html__( 'Twitter', 'nova' ),
					'type'       => 'textfield',
					'param_name' => 'twitter',
				),
				array(
					'heading'    => esc_html__( 'Google Plus', 'nova' ),
					'type'       => 'textfield',
					'param_name' => 'google',
				),
				array(
					'heading'    => esc_html__( 'Pinterest', 'nova' ),
					'type'       => 'textfield',
					'param_name' => 'pinterest',
				),
				array(
					'heading'    => esc_html__( 'Linkedin', 'nova' ),
					'type'       => 'textfield',
					'param_name' => 'linkedin',
				),
				array(
					'heading'    => esc_html__( 'Youtube', 'nova' ),
					'type'       => 'textfield',
					'param_name' => 'youtube',
				),
				array(
					'heading'    => esc_html__( 'Instagram', 'nova' ),
					'type'       => 'textfield',
					'param_name' => 'instagram',
				),
				vc_map_add_css_animation(),
				array(
					'heading'     => esc_html__( 'Extra class name', 'nova' ),
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'nova' ),
					'param_name'  => 'el_class',
					'type'        => 'textfield',
				),
			),
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

		if ( file_exists( SOBER_ADDONS_DIR . 'assets/icons/' . $file_name ) ) {
			$url = SOBER_ADDONS_URL . 'assets/icons/' . $file_name;
		} else {
			$url = SOBER_ADDONS_URL . 'assets/icons/default.png';
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
				'group' => 'category',
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
		$fonts[] = (object) array(
			'font_family' => 'Amatic SC',
			'font_styles' => '400,700',
			'font_types'  => '400 regular:400:normal,700 regular:700:normal',
		);

		$fonts[] = (object) array(
			'font_family' => 'Montez',
			'font_styles' => '400',
			'font_types'  => '400 regular:400:normal',
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

