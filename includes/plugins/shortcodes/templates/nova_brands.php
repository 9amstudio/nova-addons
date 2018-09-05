<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}
$atts = shortcode_atts( array(
  'source'              => 'media_library',
  'images'              => '',
  'custom_srcs'         => '',
  'image_size'          => 'full',
  'external_img_size'   => '',
  'custom_links'        => '',
  'custom_links_target' => '_self',
  'layout'              => 'bordered',
  'column'              => '',
  'el_class'            => '',
), $atts, 'nova_' . __FUNCTION__ );
$unique_id = uniqid('nova_brands');
$column_array = Novaworks_Shortcodes_Helper::getColumnFromShortcodeAtts($atts['column']);

$responsive_column = isset($column_array) ? $column_array : array('lg'=> 2,'md'=> 2,'mb'=> 1);
$images        = $logos = array();
$custom_links  = explode( ',', vc_value_from_safe( $atts['custom_links'] ) );
$default_src   = vc_asset_url( 'vc/no_image.png' );
$css_class = "grid-x grid-padding-x grid-padding-y small-up-{$responsive_column['mb']} medium-up-{$responsive_column['md']} large-up-{$responsive_column['lg']} nova-brands" . Novaworks_Shortcodes_Helper::getExtraClass($el_class);


switch ( $atts['source'] ) {
  case 'media_library':
    $images = explode( ',', $atts['images'] );
    break;

  case 'external_link':
    $images = vc_value_from_safe( $atts['custom_srcs'] );
    $images = explode( ',', $images );

    break;
}

foreach ( $images as $i => $image ) {
  $thumbnail = '';

  switch ( $atts['source'] ) {
    case 'media_library':
      if ( $image > 0 ) {
        $img       = wpb_getImageBySize( array(
          'attach_id'  => $image,
          'thumb_size' => $atts['image_size'],
        ) );
        $thumbnail = $img['thumbnail'];
      } else {
        $thumbnail = '<img src="' . $default_src . '" />';
      }
      break;

    case 'external_link':
      $image      = esc_attr( $image );
      $dimensions = vcExtractDimensions( $atts['external_img_size'] );
      $hwstring   = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';
      $thumbnail  = '<img ' . $hwstring . ' src="' . $image . '" />';
      break;
  }

  if ( empty( $custom_links[ $i ] ) ) {
    $logo = '<span class="brand-logo">' . $thumbnail . '</span>';
  } else {
    $logo = sprintf( '<a href="%s" target="%s" class="brand-logo">%s</a>', esc_url( $custom_links[ $i ] ), esc_attr( $atts['custom_links_target'] ), $thumbnail );
  }

  $logos[] = '<div class="cell brand">' . $logo . '</div>';
}

echo sprintf( '<div class="row"><div class="%s">%s</div></div>', esc_attr( $css_class ), implode( ' ', $logos ) );
