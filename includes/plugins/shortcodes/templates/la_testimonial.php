<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$img_size = $paged = $style = $ids = $enable_carousel = $column = $item_space = $el_class = $slider_type = $slide_to_scroll = $infinite_loop = $speed = $autoplay = $autoplay_speed = $arrows = $arrow_style = $arrow_bg_color = $arrow_border_color = $border_size = $arrow_color = $arrow_size = $next_icon = $prev_icon = $custom_nav = $dots = $dots_color = $dots_icon = $draggable = $touch_move = $rtl = $adaptive_height = $pauseohover = $centermode = $autowidth = "";

$el_id = '';

$default_atts = array(
    'style' => '1',
    'ids' => '',
    'enable_carousel' => '',
    'column' => '',
    'item_space' => 'default',
    'el_class' => '',
    'slider_type' => 'horizontal',
    'slide_to_scroll' => 'all',
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
    'el_id' => ''
);

$default_atts['paged'] = 1;
$default_atts['img_size'] = 'full';
$default_atts['excerpt_length'] = '20';

$atts = shortcode_atts( $default_atts, $atts );

extract( $atts );

$_tmp_class = 'wpb_content_element la-testimonials';
$el_class = $_tmp_class . LaStudio_Shortcodes_Helper::getExtraClass($el_class);

if(!empty($ids)){
    $ids = explode(',', $ids);
    $ids = array_map('trim', $ids);
    $ids = array_map('absint', $ids);
}

$unique_id = !empty($el_id) ? esc_attr($el_id) : uniqid('la_testimonial_');
$query_args = array(
    'post_type' => 'la_testimonial',
    'posts_per_page' => -1,
    'paged'=> $paged
);
if(!empty($ids)){
    $query_args['post__in'] = $ids;
    $query_args['orderby'] = 'post__in';
}

$globalVar = apply_filters('LaStudio/global_loop_variable', 'lastudio_loop');
$globalVarTmp = (isset($GLOBALS[$globalVar]) ? $GLOBALS[$globalVar] : '');
$globalParams = array();
$globalParams['loop_id'] = $unique_id;
$globalParams['loop_style'] = $style;
$globalParams['responsive_column'] = LaStudio_Shortcodes_Helper::getColumnFromShortcodeAtts($column);
$globalParams['image_size'] = LaStudio_Shortcodes_Helper::getImageSizeFormString($img_size);
$globalParams['excerpt_length'] = $excerpt_length;
$globalParams['item_space'] = $item_space;
if($enable_carousel){
    $globalParams['slider_configs'] = LaStudio_Shortcodes_Helper::getParamCarouselShortCode($atts, 'column');
}

$GLOBALS[$globalVar] = $globalParams;

$the_query = new WP_Query($query_args);
if( $the_query->have_posts() ){
    ?><div id="<?php echo $unique_id;?>" class="<?php echo esc_attr($el_class)?>">
        <?php

        do_action('LaStudio/shortcodes/before_loop/', 'shortcode', 'la_testimonial', $atts);

        get_template_part('templates/testimonial/loop','start');

        while($the_query->have_posts()){

            $the_query->the_post();

            get_template_part('templates/testimonial/loop', $style);

        }

        get_template_part('templates/testimonial/loop','end');

        do_action('LaStudio/shortcodes/after_loop','shortcode', 'la_testimonial', $atts);

        ?>
    </div><?php
}
$GLOBALS[$globalVar] = $globalVarTmp;
wp_reset_postdata();