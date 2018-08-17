<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 * Shortcode attributes
 * @var $title
 * @var $image
 * @var $style
 * @var $color
 * @var $hotspot_icon
 * @var $tooltip
 * @var $tooltip_shadow
 * @var $animation
 * @var $el_class
 */

$title = $image = $style = $color = $hotspot_icon  = $tooltip = $tooltip_shadow = $animation = $el_class = '';

$atts = shortcode_atts(array(
    'image' => '',
    'preview' => '',
    'el_class' => '',
    'color' => 'primary',
    'hotspot_icon' => 'plus_sign',
    'tooltip' => 'hover',
    'tooltip_shadow' => 'none',
    'animation' => ''
), $atts);

extract( $atts );

$style = 'color_pulse';

$image_el = null;
$image_class = 'no-img';

if(!empty($image)) {
    if(!preg_match('/^\d+$/',$image)){
        $image_el = '<img src="'.$image.'" alt="hotspot image" />';
    } else {
        $image_el = wp_get_attachment_image($image, 'full');
    }

    $image_class = null;
}


$el_class = LaStudio_Shortcodes_Helper::getExtraClass($image_class . $el_class);

$css_class = "la-image-with-hotspots" . $el_class;
$GLOBALS['la-image_hotspot-icon'] = $hotspot_icon;
$GLOBALS['la-image_hotspot-count'] = 1;
$GLOBALS['la-image_hotspot-tooltip-func'] = $tooltip;

?>
<div
    class="<?php echo esc_attr($css_class)?>"
    data-style="<?php echo esc_attr($style) ?>"
    data-hotspot-icon="<?php echo esc_attr($hotspot_icon) ?>"
    data-size="medium"
    data-color="<?php echo esc_attr($color)?>"
    data-tooltip-func="<?php echo esc_attr($tooltip)?>"
    data-tooltip_shadow="<?php echo esc_attr($tooltip_shadow) ?>"
    data-animation="<?php echo esc_attr($animation) ?>"
>
    <?php echo $image_el . LaStudio_Shortcodes_Helper::remove_js_autop($content);?>
</div>