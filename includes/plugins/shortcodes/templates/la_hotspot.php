<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 * Shortcode attributes
 * @var $top
 * @var $left
 * @var $position
 */

$top = $left = $position = '';

$atts = shortcode_atts( array(

), $atts );
extract( $atts );
$styles = array();
$styles[] = 'top:' . $top;
$styles[] = 'left:' . $left;

$hotspot_icon = ($GLOBALS['la-image_hotspot-icon'] == 'plus_sign') ? '': $GLOBALS['la-image_hotspot-count'];
$click_class = ($GLOBALS['la-image_hotspot-tooltip-func'] == 'click') ? ' click': null;

$tooltip_content_class = (empty($content)) ? 'nttip empty-tip' : 'nttip';
?>
<div class="la_hotspot_wrap" style="<?php echo esc_attr(join(';', $styles))?>">
    <div class="la_hotspot<?php echo $click_class;?>"><span><?php echo $hotspot_icon; ?></span></div>
    <div class="<?php echo esc_attr($tooltip_content_class) ?>" data-tooltip-position="<?php echo esc_attr($position) ?>"><div class="inner"><?php echo LaStudio_Shortcodes_Helper::remove_js_autop($content);?></div></div>
</div><?php

$GLOBALS['la-image_hotspot-count']++;