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
$load_more_text = __('Load More', 'nova');
$item_space = $el_id = '';
$atts = shortcode_atts( array(
    'style' => '1',
    'ids' => '',
    'per_page' => 4,
    'column' => '',
    'el_class' => '',
    'el_id' => ''
), $atts );

extract( $atts );

$_tmp_class = 'nova-team-member';
$el_class = $_tmp_class . Novaworks_Shortcodes_Helper::getExtraClass($el_class);

if(!empty($ids)){
    $ids = explode(',', $ids);
    $ids = array_map('trim', $ids);
    $ids = array_map('absint', $ids);
}

$unique_id = !empty($el_id) ? esc_attr($el_id) : uniqid('nova_team_');
$query_args = array(
    'post_type' => 'team_member',
    'posts_per_page' => $per_page,
    'paged'=> $paged
);
if(!empty($ids)){
    $query_args['post__in'] = $ids;
    $query_args['orderby'] = 'post__in';
}

$globalVar = apply_filters('Novaworks/global_loop_variable', 'nova_loop');

$globalVarTmp = (isset($GLOBALS[$globalVar]) ? $GLOBALS[$globalVar] : '');
$globalParams = array();
$globalParams['responsive_column'] = Novaworks_Shortcodes_Helper::getColumnFromShortcodeAtts($column);

$GLOBALS[$globalVar] = $globalParams;

$the_query = new WP_Query($query_args);

if( $the_query->have_posts() ){
    ?><div id="<?php echo $unique_id;?>" class="row <?php echo esc_attr($el_class)?>">
        <?php
        get_template_part('templates/team-member/loop','start');

        while($the_query->have_posts()){

            $the_query->the_post();

            get_template_part('templates/team-member/loop', $style);

        }

        get_template_part('templates/team-member/loop','end');
        ?>
    </div><?php
}
$GLOBALS[$globalVar] = $globalVarTmp;
wp_reset_postdata();
