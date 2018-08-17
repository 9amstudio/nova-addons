<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$output = $excerpt_length = '';
$per_page = '';
$style = $ids = $enable_carousel = $column = $img_size = $el_class = $title_tag = '';
$enable_loadmore = false;
$paged = 1;
$load_more_text = __('Load More', 'lastudio');
$item_space = $el_id = '';
$atts = shortcode_atts( array(
    'style' => '1',
    'ids' => '',
    'per_page' => 4,
    'column' => '',
    'item_space' => 'default',
    'enable_carousel' => '',
    'img_size' => 'thumbnail',
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
), $atts );

extract( $atts );

$_tmp_class = 'la-team-member';
$el_class = $_tmp_class . LaStudio_Shortcodes_Helper::getExtraClass($el_class);

if(!empty($ids)){
    $ids = explode(',', $ids);
    $ids = array_map('trim', $ids);
    $ids = array_map('absint', $ids);
}

$unique_id = !empty($el_id) ? esc_attr($el_id) : uniqid('la_team_');
$query_args = array(
    'post_type' => 'la_team_member',
    'posts_per_page' => $per_page,
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
$globalParams['title_tag'] = $title_tag;
$globalParams['item_space'] = $item_space;
$globalParams['excerpt_length'] = $excerpt_length;
if($enable_carousel){
    $globalParams['slider_configs'] = LaStudio_Shortcodes_Helper::getParamCarouselShortCode($atts, 'column');
}

$GLOBALS[$globalVar] = $globalParams;

$the_query = new WP_Query($query_args);

if( $the_query->have_posts() ){
    ?><div id="<?php echo $unique_id;?>" class="<?php echo esc_attr($el_class)?>">
        <?php

        add_filter('excerpt_length', function() use ( $excerpt_length ) {
            return $excerpt_length;
        }, 1011);

        do_action('LaStudio/shortcodes/before_loop/', 'shortcode', 'la_team_member', $atts);

        get_template_part('templates/team-member/loop','start');

        while($the_query->have_posts()){

            $the_query->the_post();

            get_template_part('templates/team-member/loop', $style);

        }

        get_template_part('templates/team-member/loop','end');

        do_action('LaStudio/shortcodes/after_loop','shortcode', 'la_team_member', $atts);

        remove_all_filters('excerpt_length', 1011);

        if($enable_loadmore){
            echo sprintf(
                '<div class="elm-loadmore-ajax" data-query-settings="%s" data-request="%s" data-paged="%s" data-max-page="%s" data-container="#%s .team-member-loop" data-item-class=".team-member-item">%s<a href="#">%s</a></div>',
                esc_attr( wp_json_encode( array(
                    'tag' => 'la_team_member',
                    'atts' => $atts
                ) ) ),
                esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
                esc_attr($paged),
                esc_attr($the_query->max_num_pages),
                esc_attr($unique_id),
                LaStudio_Shortcodes_Helper::getLoadingIcon(),
                esc_html($load_more_text)
            );
        }
        ?>
    </div><?php
}
$GLOBALS[$globalVar] = $globalVarTmp;
wp_reset_postdata();