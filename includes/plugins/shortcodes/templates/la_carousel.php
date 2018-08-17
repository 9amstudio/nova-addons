<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$slider_type = $item_space = $css_ad_carousel = $item_animation = $el_class = '';

extract( shortcode_atts( array(
    'slider_type' => 'horizontal',
    'slide_to_scroll' => 'all',
    'slides_column' => '',
    'infinite_loop' => '',
    'speed' => '300',
    'autoplay' => '',
    'autoplay_speed' => '5000',
    'arrows' => '',
    'arrow_style' => 'default',
    'arrow_bg_color' => '',
    'arrow_border_color' => '',
    'border_size' => '2',
    'arrow_color' => '#333333',
    'arrow_size' => '24',
    'next_icon' => 'dlicon-arrow-right1',
    'prev_icon' => 'dlicon-arrow-left1',
    'custom_nav' => '',
    'dots' => '',
    'dots_color' => '#333333',
    'dots_icon' => 'dlicon-dot7',
    'draggable' => 'yes',
    'touch_move' => 'yes',
    'rtl' => '',
    'adaptive_height' => '',
    'pauseohover' => '',
    'centermode' => '',
    'autowidth' => '',
    'item_space' => '15',
    'el_class' => '',
    'css_ad_carousel' => ''
), $atts ) );


$uid = uniqid( rand() );

$design_style = la_shortcode_custom_css_class( $css_ad_carousel, ' ' );

$el_class = LaStudio_Shortcodes_Helper::getExtraClass($el_class);

$wrap_data = LaStudio_Shortcodes_Helper::getParamCarouselShortCode($atts);

$elem_css = 'la-carousel-wrapper la_carousel_' . $slider_type . $design_style . $el_class;
?>
<div id="la_carousel_<?php echo esc_attr($uid)?>" class="<?php echo esc_attr($elem_css) ?>" data-gutter="<?php echo esc_attr($item_space)?>">
    <div data-la_component="AutoCarousel" class="js-el la-slick-slider" <?php echo $wrap_data ?>>
        <?php
        la_fw_override_shortcodes( $content );
        echo LaStudio_Shortcodes_Helper::remove_js_autop( $content );
        la_fw_restore_shortcodes();
        ?>
    </div>
</div>
<span data-la_component="InsertCustomCSS" class="js-el hidden">
    #la_carousel_<?php echo esc_attr($uid)?>{
        margin-left: -<?php echo absint($item_space); ?>px;
        margin-right: -<?php echo absint($item_space); ?>px;
    }
    #la_carousel_<?php echo esc_attr($uid)?> .la-item-wrap.slick-slide{
        padding-left: <?php echo absint($item_space); ?>px;
        padding-right: <?php echo absint($item_space); ?>px;
    }
</span>