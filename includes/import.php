<?php
/**
 * Register one click import demo data
 */

add_filter( 'nova_demo_packages', 'nova_addons_import_register' );

function nova_addons_import_register() {
	return array(
		array(
			'name'       => '',
			'content'    => '',
			'widgets'    => '',
			'preview'    => '',
			'customizer' => '',
			'sliders'    => '',
			'pages'      => array(
				'front_page' => 'Home Page 1',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
				'order_tracking' => 'Order Tracking',
				'portfolio'  => 'Portfolio',
			),
			'menus'      => array(
				'primary'   => 'primary-menu',
				'secondary' => 'secondary-menu',
				'topbar'    => 'topbar-menu',
				'footer'    => 'footer-menu',
				'socials'   => 'socials',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 433,
					'height' => 516,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 897,
					'height' => 908,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 80,
					'height' => 100,
					'crop'   => 1,
				),
			),
		),
	);
}

add_action( 'nova_after_setup_pages', 'nova_addons_import_order_tracking' );

/**
 * Update more page options
 *
 * @param $pages
 */
function nova_addons_import_order_tracking( $pages ) {
	if ( isset( $pages['order_tracking'] ) ) {
		$order = get_page_by_title( $pages['order_tracking'] );

		if ( $order ) {
			update_option( 'nova_order_tracking_page_id', $order->ID );
		}
	}

	if ( isset( $pages['portfolio'] ) ) {
		$portfolio = get_page_by_title( $pages['portfolio'] );

		if ( $portfolio ) {
			update_option( 'nova_portfolio_page_id', $portfolio->ID );
		}
	}
}

add_action( 'nova_before_import_content', 'nova_addons_import_product_attributes' );

/**
 * Prepare product attributes before import demo content
 *
 * @param $file
 */
function nova_addons_import_product_attributes( $file ) {
	global $wpdb;

	if ( ! class_exists( 'WXR_Parser' ) ) {
		require_once WP_PLUGIN_DIR . '/soo-demo-importer/includes/parsers.php';
	}

	$parser      = new WXR_Parser();
	$import_data = $parser->parse( $file );

	if ( isset( $import_data['posts'] ) ) {
		$posts = $import_data['posts'];

		if ( $posts && sizeof( $posts ) > 0 ) {
			foreach ( $posts as $post ) {
				if ( 'product' === $post['post_type'] ) {
					if ( ! empty( $post['terms'] ) ) {
						foreach ( $post['terms'] as $term ) {
							if ( strstr( $term['domain'], 'pa_' ) ) {
								if ( ! taxonomy_exists( $term['domain'] ) ) {
									$attribute_name = wc_sanitize_taxonomy_name( str_replace( 'pa_', '', $term['domain'] ) );

									// Create the taxonomy
									if ( ! in_array( $attribute_name, wc_get_attribute_taxonomies() ) ) {
										$attribute = array(
											'attribute_label'   => $attribute_name,
											'attribute_name'    => $attribute_name,
											'attribute_type'    => 'select',
											'attribute_orderby' => 'menu_order',
											'attribute_public'  => 0
										);
										$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );
										delete_transient( 'wc_attribute_taxonomies' );
									}

									// Register the taxonomy now so that the import works!
									register_taxonomy(
										$term['domain'],
										apply_filters( 'woocommerce_taxonomy_objects_' . $term['domain'], array( 'product' ) ),
										apply_filters( 'woocommerce_taxonomy_args_' . $term['domain'], array(
											'hierarchical' => true,
											'show_ui'      => false,
											'query_var'    => true,
											'rewrite'      => false,
										) )
									);
								}
							}
						}
					}
				}
			}
		}
	}
}