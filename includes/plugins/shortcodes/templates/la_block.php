<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$id = $name = $el_class = '';

$atts = shortcode_atts( array(
    'id' => '',
    'name' => '',
    'el_class' => ''
), $atts );

extract($atts);

if ($id || $name) {
    $el_class = LaStudio_Shortcodes_Helper::getExtraClass( $el_class );
    $query_args = array(
        'post_type' => 'la_block',
        'posts_per_page' => 1
    );
    if ($id){
        $query_args['p'] = (int) $id;
    }
    if ($name){
        $query_args['name'] = $name;
    }

    $the_query = get_posts($query_args);

    if($the_query){
        foreach($the_query as $post){
            wp_enqueue_script('wpb_composer_front_js');
            $wpb_post_custom_css = get_post_meta( $post->ID, '_wpb_post_custom_css', true );
            $wpb_shortcodes_custom_css = get_post_meta( $post->ID, '_wpb_shortcodes_custom_css', true );
            ?>
            <div class="la-static-block<?php echo esc_attr($el_class)?>">
                <?php
                echo LaStudio_Shortcodes_Helper::remove_js_autop($post->post_content);
                ?>
            </div>
            <?php if($wpb_post_custom_css || $wpb_shortcodes_custom_css):?>
                <span data-la_component="InsertCustomCSS" class="js-el hidden"><?php echo $wpb_post_custom_css . $wpb_shortcodes_custom_css;?></span>
            <?php endif;?>
            <?php
        }
    }
}