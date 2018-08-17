<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$enable_skill_filter = $filters = $item_space = '';
$layout = $grid_style = $masonry_style = $category__in = $category__not_in = $post__in = $post__not_in = $orderby = $order = $per_page = $paged = $title_tag = $img_size = $column = $enable_carousel = $enable_loadmore = $per_page_loadmore = $load_more_text = $el_class =  $output = '';
$atts = shortcode_atts( array(
    'layout' => 'grid',
    'grid_style' => '1',
    'list_style' => '1',
    'category__in' => '',
    'category__not_in' => '',
    'post__in' => '',
    'post__not_in' => '',
    'orderby' => '',
    'order' => '',
    'per_page' => -1,
    'paged' => '1',
    'title_tag' => 'h3',
    'img_size' => 'thumbnail',
    'column' => '',
    'item_space' => '30',
    'enable_carousel' => '',
    'enable_loadmore' => '',
    'load_more_text' => 'Load more',
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
    'autowidth' => ''
), $atts );
$excerpt_length = 15;

if( 0 === $per_page ) $per_page = 1;
if(empty($paged)){
    $paged = 1;
}

extract( $atts );

$_tmp_class = 'wpb_content_element la-show-portfolios';
$el_class = $_tmp_class . LaStudio_Shortcodes_Helper::getExtraClass($el_class);
$unique_id = uniqid('la-show-portfolios-');

$query_args = array(
    'post_type'             => 'la_portfolio',
    'post_status'		    => 'publish',
    'orderby'               => $orderby,
    'order'                 => $order,
    'ignore_sticky_posts'   => 1,
    'paged'                 => $paged,
    'posts_per_page'        => $per_page
);

if ( $category__in ) {
    $category__in = explode( ',', $category__in );
    $category__in = array_map( 'trim', $category__in );
}
if ( $category__not_in ) {
    $category__not_in = explode( ',', $category__not_in );
    $category__not_in = array_map( 'trim', $category__not_in );
}
if ( $post__in ) {
    $post__in = explode( ',', $post__in );
    $post__in = array_map( 'trim', $post__in );
}
if ( $post__not_in ) {
    $post__not_in = explode( ',', $post__not_in );
    $post__not_in = array_map( 'trim', $post__not_in );
}
$tax_query = array();
if ( !empty( $category__in ) && !empty( $category__not_in ) ){
    $tax_query['relation'] = 'AND';
}
if ( !empty ( $category__in ) ) {
    $tax_query[] = array(
        'taxonomy' => 'la_portfolio_category',
        'field'    => 'term_id',
        'terms'    => $category__in
    );
}
if ( !empty ( $category__not_in ) ) {
    $tax_query[] = array(
        'taxonomy' => 'la_portfolio_category',
        'field'    => 'term_id',
        'terms'    => $category__not_in,
        'operator' => 'NOT IN'
    );
}
if ( !empty($tax_query) ) {
    $query_args['tax_query'] = $tax_query;
}
if ( !empty ( $post__in ) ) {
    $query_args['post__in'] = $post__in;
}
if ( !empty ( $post__not_in ) ) {
    $query_args['post__not_in'] = $post__not_in;
}

$globalVar = apply_filters('LaStudio/global_loop_variable', 'lastudio_loop');
$globalVarTmp = (isset($GLOBALS[$globalVar]) ? $GLOBALS[$globalVar] : '');
$globalParams = array();

$layout_style = ${$layout . '_style'};

$globalParams['loop_id'] = $unique_id;
$globalParams['item_space']  = $item_space;
$globalParams['loop_layout'] = $layout;
$globalParams['loop_style'] = $layout_style;
$globalParams['responsive_column'] = LaStudio_Shortcodes_Helper::getColumnFromShortcodeAtts($column);
$globalParams['image_size'] = LaStudio_Shortcodes_Helper::getImageSizeFormString($img_size);
$globalParams['title_tag'] = $title_tag;
$globalParams['excerpt_length'] = $excerpt_length;

if('grid' == $layout && $enable_carousel){
    $globalParams['slider_configs'] = LaStudio_Shortcodes_Helper::getParamCarouselShortCode($atts, 'column');
}

$GLOBALS[$globalVar] = $globalParams;

$the_query = new WP_Query($query_args);

if( $the_query->have_posts() ){

    ?><div id="<?php echo esc_attr($unique_id);?>" class="<?php echo esc_attr($el_class)?>"><?php

    add_filter('excerpt_length', function() use ( $excerpt_length ) {
        return $excerpt_length;
    }, 1011);

    do_action('LaStudio/shortcodes/before_loop/', 'shortcode', 'la_show_portfolios', $atts);

    $start_tpl = $end_tpl = $loop_tpl = array();

    $start_tpl[] = "templates/portfolios/{$layout}/start-{$layout_style}.php";
    $start_tpl[] = "templates/portfolios/{$layout}/start.php";
    $start_tpl[] = "templates/portfolios/start.php";
    $loop_tpl[] = "templates/portfolios/{$layout}/loop-{$layout_style}.php";
    $loop_tpl[] = "templates/portfolios/{$layout}/loop.php";
    $loop_tpl[] = "templates/portfolios/loop.php";
    $end_tpl[] = "templates/portfolios/{$layout}/end-{$layout_style}.php";
    $end_tpl[] = "templates/portfolios/{$layout}/end.php";
    $end_tpl[] = "templates/portfolios/end.php";

    locate_template($start_tpl, true, false);

    while($the_query->have_posts()){

        $the_query->the_post();

        locate_template($loop_tpl, true, false);

    }

    locate_template($end_tpl, true, false);

    do_action('LaStudio/shortcodes/after_loop','shortcode', 'la_show_portfolios', $atts);

    remove_all_filters('excerpt_length', 1011);

    if($enable_loadmore){
        echo sprintf(
            '<div class="elm-loadmore-ajax" data-query-settings="%s" data-request="%s" data-paged="%s" data-max-page="%s" data-container="#%s .portfolios-loop" data-item-class=".portfolio-item">%s<a href="#" class="btn btn-style-outline btn-size-md btn-color-gay">%s</a></div>',
            esc_attr( wp_json_encode( array(
                'tag' => 'la_show_portfolios',
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