<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$atts = Novaworks_Shortcodes_WooCommerce::render_default_atts( $atts );

$loop_name = $atts['scenario'];
unset($atts['scenario']);

$shortcode = new Novaworks_Shortcodes_WooCommerce($atts, 'sale_products');

echo $shortcode->get_content();