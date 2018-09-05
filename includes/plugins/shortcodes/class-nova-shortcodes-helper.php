<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

class Novaworks_Shortcodes_Helper{

    public function __construct(){

    }

    public static function remove_js_autop($content, $autop = false){
        if ( $autop ) {
            $content = preg_replace( '/<\/?p\>/', "\n", $content );
            $content = preg_replace( '/<p[^>]*><\\/p[^>]*>/', "", $content );
            $content = wpautop( $content . "\n" );
        }
        return do_shortcode( shortcode_unautop( $content ) );
    }

    public static function fieldIconType($dependency = array(), $emptyIcon = false){
        return array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'Icon library', 'nova' ),
                'value' => array(
                    __( 'Font Awesome', 'nova' ) => 'fontawesome',
                    __( 'Open Iconic', 'nova' ) => 'openiconic',
                    __( 'Typicons', 'nova' ) => 'typicons',
                    __( 'Entypo', 'nova' ) => 'entypo',
                    __( 'Linecons', 'nova' ) => 'linecons',
                    __( 'Mono Social', 'nova' ) => 'monosocial',
                    __( 'Novaworks Icon', 'nova' ) => 'nova_icon_outline',
                    __( 'Nucleo Glyph', 'nova' ) => 'nucleo_glyph',
                    __( 'Custom Image', 'nova') => 'custom',
                ),
                'param_name' => 'icon_type',
                'description' => __( 'Select icon library.', 'nova' ),
                'dependency' => $dependency
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'nova' ),
                'param_name' => 'icon_fontawesome',
                'value' => 'fa fa-info-circle',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'fontawesome',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'nova' ),
                'param_name' => 'icon_openiconic',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'openiconic',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'openiconic',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'nova' ),
                'param_name' => 'icon_typicons',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'typicons',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'typicons',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'nova' ),
                'param_name' => 'icon_entypo',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'entypo',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'entypo',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'nova' ),
                'param_name' => 'icon_linecons',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'linecons',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'linecons',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'nova' ),
                'param_name' => 'icon_monosocial',
                'value' => 'vc-mono vc-mono-fivehundredpx',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'monosocial',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'monosocial',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'nova' ),
                'param_name' => 'icon_nova_icon_outline',
                'value' => 'nova-icon design-2_image',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'nova_icon_outline',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'nova_icon_outline',
                )
            ),
            array(
                'type' => 'iconpicker',
                'heading' => __( 'Icon', 'nova' ),
                'param_name' => 'icon_nucleo_glyph',
                'value' => 'nc-icon-glyph nature_bear',
                'settings' => array(
                    'emptyIcon' => $emptyIcon,
                    'type' => 'nucleo_glyph',
                    'iconsPerPage' => 30,
                ),
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'nucleo_glyph',
                )
            ),
            array(
                'type' => 'attach_image',
                'heading' => __('Upload the custom image icon', 'nova'),
                'param_name' => "icon_image_id",
                'dependency' => array(
                    'element' => 'icon_type',
                    'value' => 'custom',
                ),
            )
        );
    }

    public static function fieldColumn($options = array()){
        return array_merge(array(
            'type' 			=> 'nova_column',
            'heading' 		=> __('Column', 'nova'),
            'param_name' 	=> 'column',
            'unit'			=> '',
            'media'			=> array(
                'xlg'	=> 1,
                'lg'	=> 1,
                'md'	=> 1,
                'sm'	=> 1,
                'xs'	=> 1,
                'mb'	=> 1
            )
        ), $options);
    }
    public static function fieldColumnGrid($options = array()){
        return array_merge(array(
            'type' 			=> 'nova_column',
            'heading' 		=> __('Column', 'nova'),
            'param_name' 	=> 'column',
            'unit'			=> '',
            'media'			=> array(
                'lg'	=> 1,
                'md'	=> 1,
                'mb'	=> 1
            )
        ), $options);
    }
    public static function fieldImageSize($options = array()){
        return array_merge(
            array(
                'type' 			=> 'textfield',
                'heading' 		=> __('Image size', 'nova'),
                'param_name' 	=> 'img_size',
                'value'			=> 'thumbnail',
                'description' 	=> __('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'nova'),
            ),
            $options
        );
    }

    public static function fieldCssAnimation($options = array()){
        return array_merge(
            array(
                'type' => 'animation_style',
                'heading' => __( 'CSS Animation', 'nova' ),
                'param_name' => 'css_animation',
                'value' => 'none',
                'settings' => array(
                    'type' => array(
                        'in',
                        'other',
                    ),
                ),
                'description' => __( 'Select initial loading animation for element.', 'nova' ),
            ),
            $options
        );
    }

    public static function fieldExtraClass($options = array()){
        return array_merge(
            array(
                'type' 			=> 'textfield',
                'heading' 		=> __('Extra Class name', 'nova'),
                'param_name' 	=> 'el_class',
                'description' 	=> __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'nova')
            ),
            $options
        );
    }

    public static function fieldElementID($options = array()){
        return array_merge(
            array(
                'type' => 'textfield',
                'heading' => __( 'Element ID', 'nova' ),
                'param_name' => 'shortcode_id',
                'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'nova' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
            ),
            $options
        );
    }

    public static function fieldCssClass($options = array()){
        return array_merge(
            array(
                'type' 			=> 'css_editor',
                'heading' 		=> __('CSS box', 'nova'),
                'param_name' 	=> 'css',
                'group' 		=> __('Design Options', 'nova')
            ),
            $options
        );
    }

    public static function getColumnFromShortcodeAtts( $atts ){
        $array = array(
            'xlg'	=> 1,
            'lg' 	=> 1,
            'md' 	=> 1,
            'sm' 	=> 1,
            'xs' 	=> 1,
            'mb' 	=> 1
        );
        $atts = explode(';',$atts);
        if(!empty($atts)){
            foreach($atts as $val){
                $val = explode(':',$val);
                if(isset($val[0]) && isset($val[1])){
                    if(isset($array[$val[0]])){
                        $array[$val[0]] = absint($val[1]);
                    }
                }
            }
        }
        return $array;
    }

    public static function fieldTitleGFont( $name = 'title', $title = 'Title',  $dependency = array() ){
        $group = __('Typography', 'nova');
        $array = array();
        $array[] = array(
            'type' 			=> 'nova_heading',
            'param_name' 	=> $name . '__typography',
            'text' 			=> $title . __(' settings', 'nova'),
            'group' 		=> $group,
            'dependency'    => $dependency
        );
        $array[] = array(
            'type' => 'checkbox',
            'heading' => __( 'Use google fonts family?', 'nova' ),
            'param_name' => 'use_gfont_' . $name,
            'value' => array( __( 'Yes', 'nova' ) => 'yes' ),
            'description' => __( 'Use font family from the theme.', 'nova' ),
            'group' 		=> $group,
            'dependency'    => $dependency
        );
        $array[] = array(
            'type' 			=> 'google_fonts',
            'param_name' 	=> $name . '_font',
            'dependency' 	=> array(
                'element' => 'use_gfont_' . $name,
                'value' => 'yes',
            ),
            'group' 		=> $group
        );
        $array[] = array(
            'type' 			=> 'nova_column',
            'heading' 		=> __('Font size', 'nova'),
            'param_name' 	=> $name . '_fz',
            'unit' 			=> 'px',
            'media' => array(
                'xlg'	=> '',
                'lg'    => '',
                'md'    => '',
                'sm'    => '',
                'xs'	=> '',
                'mb'	=> ''
            ),
            'group' 		=> $group,
            'dependency'    => $dependency
        );
        $array[] = array(
            'type' 			=> 'nova_column',
            'heading' 		=> __('Line Height', 'nova'),
            'param_name' 	=> $name . '_lh',
            'unit' 			=> 'px',
            'media' => array(
                'xlg'	=> '',
                'lg'    => '',
                'md'    => '',
                'sm'    => '',
                'xs'	=> '',
                'mb'	=> ''
            ),
            'group' 		=> $group,
            'dependency'    => $dependency
        );
        $array[] = array(
            'type' 			=> 'colorpicker',
            'param_name' 	=> $name . '_color',
            'heading' 		=> __('Color', 'nova'),
            'group' 		=> $group,
            'dependency'    => $dependency
        );
        return $array;
    }

    public static function getResponsiveMediaCss( $args = array() ){
        $content = '';
        if(!empty($args) && !empty($args['target']) && !empty($args['media_sizes'])){
            $content .=  ' data-nova_component="UnitResponsive" ';
            $content .=  " data-el_target='".esc_attr($args['target'])."' ";
            $content .=  " data-el_json_data='".esc_attr(wp_json_encode($args['media_sizes']))."' ";
        }
        return $content;
    }

    public static function renderResponsiveMediaCss(&$css = array(), $args = array()){

        if(!empty($args) && !empty($args['target']) && !empty($args['media_sizes'])){
            $target = $args['target'];
            foreach( $args['media_sizes'] as $css_attribute => $items ){
                $media_sizes =  explode(';', $items);
                if(!empty($media_sizes)){
                    foreach($media_sizes as $value ){
                        $tmp = explode(':', $value);
                        if(!empty($tmp[1])){
                            if(!isset($css[$tmp[0]])){
                                $css[$tmp[0]] = '';
                            }
                            $css[$tmp[0]] .= $target . '{' . $css_attribute . ':'. $tmp[1] .'}';
                        }
                    }
                }
            }
        }
        return $css;
    }

    public static function renderResponsiveMediaStyleTags( $custom_css = array() ){
        $output = '';
        if(function_exists('vc_is_inline') && vc_is_inline() && !empty($custom_css)){
            foreach($custom_css as $media => $value){
                switch($media){
                    case 'lg':
                        $output .= $value;
                        break;
                    case 'xlg':
                        $output .= '@media (min-width: 1824px){'.$value.'}';
                        break;
                    case 'md':
                        $output .= '@media (max-width: 1199px){'.$value.'}';
                        break;
                    case 'sm':
                        $output .= '@media (max-width: 991px){'.$value.'}';
                        break;
                    case 'xs':
                        $output .= '@media (max-width: 767px){'.$value.'}';
                        break;
                    case 'mb':
                        $output .= '@media (max-width: 479px){'.$value.'}';
                        break;
                }
            }
        }
        if(!empty($output)){
            echo '<style type="text/css">'.$output.'</style>';
        }
    }

    public static function parseGoogleFontAtts( $value ){
        $fields = array();
        $styles = array();
        $settings = get_option( 'wpb_js_google_fonts_subsets' );
        if ( is_array( $settings ) && ! empty( $settings ) ) {
            $subsets = '&subset=' . implode( ',', $settings );
        } else {
            $subsets = '';
        }
        $value = vc_parse_multi_attribute($value);
        if(isset($value['font_family']) && isset($value['font_style'])){
            $google_fonts_family = explode( ':', $value['font_family'] );
            $styles[] = 'font-family:' . $google_fonts_family[0];
            $google_fonts_styles = explode( ':', $value['font_style'] );
            $styles[] = 'font-weight:' . $google_fonts_styles[1];
            $styles[] = 'font-style:' . $google_fonts_styles[2];
            $fields['font_url'] = '//fonts.googleapis.com/css?family=' . rawurlencode($value['font_family']) . $subsets;
            $fields['font_family'] = vc_build_safe_css_class($value['font_family']);

            $fields['style'] = implode(';',$styles);
        }
        return $fields;
    }

    public static function getImageSizeFormString($size, $default = 'thumbnail'){
        if(empty($size)){
            return $default;
        }
        $ignore = array(
            'thumbnail',
            'thumb',
            'medium',
            'large',
            'full'
        );
        if(false !== strpos($size, 'la_')){
            return $size;
        }
        $_wp_additional_image_sizes = wp_get_additional_image_sizes();
        if(is_string($size) && (in_array($size, $ignore) || (!empty($_wp_additional_image_sizes[$size]) && is_array($_wp_additional_image_sizes[$size]) ))){
            return $size;
        }
        else{
            preg_match_all( '/\d+/', $size, $thumb_matches );
            if ( isset( $thumb_matches[0] ) ) {
                $thumb_size = array();
                if ( count( $thumb_matches[0] ) > 1 ) {
                    $thumb_size[] = $thumb_matches[0][0]; // width
                    $thumb_size[] = $thumb_matches[0][1]; // height
                } elseif ( count( $thumb_matches[0] ) > 0 && count( $thumb_matches[0] ) < 2 ) {
                    $thumb_size[] = $thumb_matches[0][0]; // width
                    $thumb_size[] = 0; //$thumb_matches[0][0]; // height
                } else {
                    $thumb_size = $default;
                }
            }else{
                $thumb_size = $default;
            }
            return $thumb_size;
        }
    }

    public static function getSliderConfigs($default = array()){
        $configs = array_merge($configs = array(
            'infinite' => false,
            'xlg' => 1,
            'lg' => 1,
            'md' => 1,
            'sm' => 1,
            'xs' => 1,
            'mb' => 1,
            'dots' => false,
            'autoplay' => false,
            'arrows' => false,
            'speed' => 1000,
            'autoplaySpeed' => 3000,
            'custom_nav' => '',
            'centerMode' => false,
            'variableWidth' => false
        ), $default);
        $slider_config = array(
            'infinite' => $configs['infinite'],
            'dots' => $configs['dots'],
            'slidesToShow' => $configs['xlg'],
            'slidesToScroll' => $configs['xlg'],
            'autoplay' => $configs['autoplay'],
            'arrows' => $configs['arrows'],
            'speed' => $configs['speed'],
            'autoplaySpeed' => $configs['autoplaySpeed'],
            'centerMode' => $configs['centerMode'],
            'variableWidth' => $configs['variableWidth'],
            'responsive' => array(
                array(
                    'breakpoint' => 1824,
                    'settings' => array(
                        'slidesToShow' => $configs['lg'],
                        'slidesToScroll' => $configs['lg']
                    )
                ),
                array(
                    'breakpoint' => 1200,
                    'settings' => array(
                        'slidesToShow' => $configs['md'],
                        'slidesToScroll' => $configs['md']
                    )
                ),
                array(
                    'breakpoint' => 992,
                    'settings' => array(
                        'slidesToShow' => $configs['sm'],
                        'slidesToScroll' => $configs['sm']
                    )
                ),
                array(
                    'breakpoint' => 768,
                    'settings' => array(
                        'slidesToShow' => $configs['xs'],
                        'slidesToScroll' => $configs['xs']
                    )
                ),
                array(
                    'breakpoint' => 480,
                    'settings' => array(
                        'slidesToShow' => $configs['mb'],
                        'slidesToScroll' => $configs['mb']
                    )
                )
            )
        );
        if(isset($configs['custom_nav']) && !empty($configs['custom_nav'])){
            $slider_config['appendArrows'] = 'jQuery("'.esc_attr($configs['custom_nav']).'")';
        }
        return wp_json_encode($slider_config);
    }

    public static function getLoadingIcon(){
        return '<div class="la-shortcode-loading"><div class="content"><div class="la-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>';
    }

    public static function getExtraClass( $el_class ) {
        $output = '';
        if ( '' !== $el_class ) {
            $output = ' ' . str_replace( '.', '', $el_class );
        }

        return $output;
    }

    public static function getLoopProducts($query_args, $atts, $loop_name){

        $globalVar      = apply_filters('Novaworks/global_loop_variable', 'nova_loop');
        $globalVarTmp   = (isset($GLOBALS[$globalVar]) ? $GLOBALS[$globalVar] : '');
        $globalParams   = array();

        $unique_id      = uniqid($loop_name . '_');
        $css_class      = 'woocommerce' . self::getExtraClass($atts['el_class']);
        $columns        = self::getColumnFromShortcodeAtts(isset($atts['columns']) ? $atts['columns'] : '');
        $layout         = isset($atts['layout']) ? $atts['layout'] : 'grid';
        $style          = $atts[$atts['layout'] . '_style'];
        $item_space     = isset($atts['item_space']) ? $atts['item_space'] : 'default';

        $loopCssClass 	= array();
        $carousel_configs = $disable_alt_image = $image_size = false;
        if(isset($atts['enable_custom_image_size']) && $atts['enable_custom_image_size'] == 'yes'){
            $image_size = true;
        }
        if(isset($atts['disable_alt_image']) && $atts['disable_alt_image'] == 'yes'){
            $disable_alt_image = true;
        }
        if($layout == 'grid'){
            if(isset($atts['enable_carousel']) && $atts['enable_carousel'] == 'yes'){

                $carousel_configs = ' data-la_component="AutoCarousel" ';
                $carousel_configs .= Novaworks_Shortcodes_Helper::getParamCarouselShortCode($atts);
                $loopCssClass[] = 'js-el la-slick-slider';
            }
        }
        $globalParams['loop_id']        = $unique_id;
        $globalParams['loop_layout']    = $layout;
        $globalParams['loop_style']     = $style;
        $globalParams['item_space']     = $item_space;
        if($image_size){
            $globalParams['image_size'] = Novaworks_Shortcodes_Helper::getImageSizeFormString($atts['img_size']);
        }
        if($disable_alt_image){
            $globalParams['disable_alt_image'] = true;
        }
        $GLOBALS[$globalVar] = $globalParams;


        $loopCssClass[] = 'products';
        $loopCssClass[] = 'products-' . $layout;
        $loopCssClass[] = 'products-' . $layout . '-' . $style;
        $loopCssClass[] = 'grid-items';

        if($layout != 'list'){
            foreach( $columns as $screen => $value ){
                $loopCssClass[]  =  sprintf('%s-grid-%s-items', $screen, $value);
            }
            $loopCssClass[] = 'grid-space-' . $item_space;
        }

        $products = new WP_Query(apply_filters( 'woocommerce_shortcode_products_query', $query_args, $atts, $loop_name ));

        $GLOBALS[$globalVar] = $globalParams;

        $loop_tpl = array();
        $loop_tpl[] = "woocommerce/content-product-{$layout}-{$style}.php";
        $loop_tpl[] = "woocommerce/content-product-{$layout}.php";
        $loop_tpl[] = "woocommerce/content-product.php";

        ob_start();

        if($products->have_posts()){

            do_action('Novaworks/shortcodes/before_loop', 'woo_shortcode', $loop_name, $atts);

            printf('<div class="row"><div class="col-xs-12"><ul class="%s"%s>',
                esc_attr(implode(' ', $loopCssClass)),
                $carousel_configs ? $carousel_configs : ''
            );

            while( $products->have_posts() ){
                $products->the_post();
                locate_template($loop_tpl, true, false);
            }

            printf('</ul></div></div>');

            do_action('Novaworks/shortcodes/after_loop', 'woo_shortcode', $loop_name, $atts);

        }

        if($products->max_num_pages > 1 && isset($atts['enable_loadmore']) && $atts['enable_loadmore'] == 'yes'){
            echo sprintf(
                '<div class="elm-loadmore-ajax" data-query-settings="%s" data-request="%s" data-paged="%s" data-max-page="%s" data-container="#%s ul.products" data-item-class=".product_item">%s<a href="#">%s</a></div>',
                esc_attr( wp_json_encode( array(
                    'tag' => $loop_name,
                    'atts' => $atts
                ) ) ),
                esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
                esc_attr($atts['paged']),
                esc_attr($products->max_num_pages),
                esc_attr($unique_id),
                Novaworks_Shortcodes_Helper::getLoadingIcon(),
                esc_html($atts['load_more_text'])
            );
        }

        $GLOBALS[$globalVar] = $globalVarTmp;
        wp_reset_postdata();
        $output = ob_get_clean();

        printf('<div id="%s" class="%s">%s</div>',
            esc_attr($unique_id),
            esc_attr($css_class),
            $output
        );

    }

    public static function paramCarouselShortCode( $full_control = true ){
        if($full_control){
            $general_name = esc_html__('General', 'nova');
            $dependency = array();
        }else{
            $general_name = esc_html__('Slider Setting', 'nova');
            $dependency =  array(
                'element' => 'enable_carousel',
                'value' => 'yes'
            );
        }
        $params = array(
            array(
                'type'       => 'dropdown',
                'heading'    => __( 'Slider Type', 'nova' ),
                'param_name' => 'slider_type',
                'value'      => array(
                    esc_html__('Horizontal', 'nova')            => 'horizontal',
                    esc_html__('Vertical', 'nova')              => 'vertical'
                ),
                'group'      => $general_name,
                'dependency' => $dependency
            ),
            array(
                'type'       => 'dropdown',
                'heading'    => __( 'Slides to Scroll', 'nova' ),
                'param_name' => 'slide_to_scroll',
                'value'      => array(
                    esc_html__('All visible', 'nova') => 'all',
                    esc_html__('One at a Time', 'nova') => 'single'
                ),
                'group'      => $general_name,
                'dependency' => $dependency
            ),
            Novaworks_Shortcodes_Helper::fieldColumn(array(
                'heading' 		=> __('Items to Show', 'nova'),
                'param_name' 	=> 'slides_column',
                'group'      => $general_name,
                'dependency' => $dependency
            )),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Infinite loop', 'nova'),
                'description'	=> __( 'Restart the slider automatically as it passes the last slide.', 'nova' ),
                'param_name' 	=> 'infinite_loop',
                'value' 		=> array(
                    __('Yes', 'nova') => 'yes'
                ),
                'group'      => $general_name,
                'dependency' => $dependency

            ),
            array(
                'type'        => 'nova_number',
                'heading'     => __( 'Transition speed', 'nova' ),
                'param_name'  => 'speed',
                'value'       => '300',
                'min'         => '100',
                'max'         => '10000',
                'step'        => '100',
                'suffix'      => 'ms',
                'description' => __( 'Speed at which next slide comes.', 'nova' ),
                'group'      => $general_name,
                'dependency' => $dependency
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Autoplay Slides', 'nova'),
                'param_name' 	=> 'autoplay',
                'value' 		=> array(
                    __('Yes', 'nova') => 'yes'
                ),
                'group'      => $general_name,
                'dependency' => $dependency
            ),
            array(
                'type'       => 'nova_number',
                'heading'    => __( 'Autoplay Speed', 'nova' ),
                'param_name' => 'autoplay_speed',
                'value'      => '5000',
                'min'        => '100',
                'max'        => '10000',
                'step'       => '10',
                'suffix'     => 'ms',
                'dependency' => array(
                    'element' => 'autoplay', 'value' => 'yes'
                ),
                'group'      => $general_name
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Navigation Arrows', 'nova'),
                'description' 	=> __( 'Display next / previous navigation arrows', 'nova' ),
                'param_name' 	=> 'arrows',
                'value' 		=> array(
                    __('Show', 'nova') => 'yes'
                ),
                'group'      	=> 'Navigation',
                'dependency' => $dependency
            ),
            array(
                'type'       => 'dropdown',
                'heading'    => __( 'Arrow Style', 'nova' ),
                'param_name' => 'arrow_style',
                'value'      => array(
                    'Default'           => 'default',
                    'Circle Background' => 'circle-bg',
                    'Square Background' => 'square-bg',
                    'Circle Border'     => 'circle-border',
                    'Square Border'     => 'square-border',
                ),
                'dependency' => array(
                    'element' => 'arrows', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'colorpicker',
                'heading'    => __( 'Background Color', 'nova' ),
                'param_name' => 'arrow_bg_color',
                'dependency' => array(
                    'element' => 'arrow_style',
                    'value'   => array( 'circle-bg', 'square-bg' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'colorpicker',
                'heading'    => __( 'Border Color', 'nova' ),
                'param_name' => 'arrow_border_color',
                'dependency' => array(
                    'element' => 'arrow_style',
                    'value'   => array( 'circle-border', 'square-border' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'nova_number',
                'heading'    => __( 'Border Size', 'nova' ),
                'param_name' => 'border_size',
                'value'      => '2',
                'min'        => '1',
                'max'        => '100',
                'step'       => '1',
                'suffix'     => 'px',
                'dependency' => array(
                    'element' => 'arrow_style',
                    'value'   => array( 'circle-border', 'square-border' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'colorpicker',
                'heading'    => __( 'Arrow Color', 'nova' ),
                'param_name' => 'arrow_color',
                'value'      => '#333333',
                'dependency' => array(
                    'element' => 'arrows', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'nova_number',
                'heading'    => __( 'Arrow Size', 'nova' ),
                'param_name' => 'arrow_size',
                'value'      => '24',
                'min'        => '10',
                'max'        => '75',
                'step'       => '1',
                'suffix'     => 'px',
                'dependency' => array(
                    'element' => 'arrows', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'la_slides_navigation',
                'heading'    => __( "Select icon for 'Next Arrow'", 'nova' ),
                'param_name' => 'next_icon',
                'value'      => 'dlicon-arrow-right1',
                'dependency' => array(
                    'element' => 'arrows', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'la_slides_navigation',
                'heading'    => __( "Select icon for 'Previous Arrow'", 'nova' ),
                'param_name' => 'prev_icon',
                'value'      => 'dlicon-arrow-left1',
                'dependency' => array(
                    'element' => 'arrows', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),

            array(
                'type' 			=> 'textfield',
                'heading' 		=> __( 'Custom Navigation Carousel Element', 'nova' ),
                'param_name' 	=> 'custom_nav',
                'description' 	=> 'Enter classname OR ID of Navigation, Ex: ".navigation_carousel", "#navigation_carousel"',
                'dependency' 	=> array(
                    'element' => 'arrows', 'value' => array( 'yes' )
                ),
                'group' 		=> 'Navigation'
            ),

            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Dots Navigation', 'nova'),
                'description' 	=> __( 'Display dot navigation', 'nova' ),
                'param_name' 	=> 'dots',
                'value' 		=> array(
                    __('Show', 'nova') => 'yes'
                ),
                'group'      	=> 'Navigation',
                'dependency' => $dependency
            ),

            array(
                'type'       => 'colorpicker',
                'heading'    => __( 'Color of dots', 'nova' ),
                'param_name' => 'dots_color',
                'value'      => '#333333',
                'dependency' => array(
                    'element' => 'dots', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type'       => 'la_slides_navigation',
                'heading'    => __( "Select icon for 'Navigation Dots'", 'nova' ),
                'param_name' => 'dots_icon',
                'value'      => 'dlicon-dot7',
                'dependency' => array(
                    'element' => 'dots', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Navigation'
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Draggable Effect', 'nova'),
                'description' 	=> __( 'Allow slides to be draggable', 'nova' ),
                'param_name' 	=> 'draggable',
                'value' 		=> array(
                    __('Yes', 'nova') => 'yes'
                ),
                'std'           => 'yes',
                'group'      	=> 'Advanced',
                'dependency' => $dependency
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Touch Move', 'nova'),
                'description' 	=> __( 'Enable slide moving with touch', 'nova' ),
                'param_name' 	=> 'touch_move',
                'value' 		=> array(
                    __('Yes', 'nova') => 'yes'
                ),
                'std'           => 'yes',
                'dependency'  => array(
                    'element' => 'draggable', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Advanced'
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('RTL Mode', 'nova'),
                'description' 	=> __( 'Turn on RTL mode', 'nova' ),
                'param_name' 	=> 'rtl',
                'value' 		=> array(
                    __('Yes', 'nova') => 'yes'
                ),
                'group'      	=> 'Advanced',
                'dependency' => $dependency
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Adaptive Height', 'nova'),
                'description' 	=> __('Turn on Adaptive Height', 'nova' ),
                'param_name' 	=> 'adaptive_height',
                'value' 		=> array(
                    __('Yes', 'nova') => 'yes'
                ),
                'group'      	=> 'Advanced',
                'dependency' => $dependency
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Pause on hover', 'nova'),
                'description' 	=> __('Pause the slider on hover', 'nova' ),
                'param_name' 	=> 'pauseohover',
                'value' 		=> array(
                    __('Yes', 'nova') => 'yes'
                ),
                'dependency'  => array(
                    'element' => 'autoplay', 'value' => array( 'yes' )
                ),
                'group'      	=> 'Advanced'
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Center mode', 'nova'),
                'description' 	=> __("Enables centered view with partial prev/next slides. <br>Animations do not work with center mode.<br>Slides to scroll -> 'All Visible' do not work with center mode.", 'nova'),
                'param_name' 	=> 'centermode',
                'value' 		=> array(
                    __('Yes', 'nova') => 'yes'
                ),
                'group'      	=> 'Advanced',
                'dependency' => $dependency
            ),
            array(
                'type' 			=> 'checkbox',
                'heading' 		=> __('Item Auto Width', 'nova'),
                'description' 	=> __('Variable width slides', 'nova' ),
                'param_name' 	=> 'autowidth',
                'value' 		=> array(
                    __('Yes', 'nova') => 'yes'
                ),
                'group'      	=> 'Advanced',
                'dependency' => $dependency
            )
        );
        if($full_control){
            $params[] = array(
                'type'       => 'nova_number',
                'heading'    => __( 'Space between two items', 'nova' ),
                'param_name' => 'item_space',
                'value'      => '15',
                'min'        => 0,
                "max"        => '1000',
                'step'       => 1,
                'suffix'     => 'px',
                'group'      => 'Advanced'
            );
            $params[] = array(
                'type'       => 'textfield',
                'heading'    => __( 'Extra Class', 'nova' ),
                'param_name' => 'el_class',
                'group'      => esc_html__('General', 'nova')
            );
            $params[] = array(
                'type'             => 'css_editor',
                'heading'          => __( 'Css', 'nova' ),
                'param_name'       => 'css_ad_carousel',
                'group'            => __( 'Design ', 'nova' )
            );
        }
        return $params;
    }

    public static function getParamItemSpace($options = array()){
        return array_merge(array(
            'type' => 'dropdown',
            'heading' => __( 'Item Space', 'nova' ),
            'value' => array (
                __( 'Default', 'nova' ) => 'default',
                __( '0px', 'nova' ) => '0',
                __( '5px', 'nova' ) => '5',
                __( '10px', 'nova' ) => '10',
                __( '15px', 'nova' ) => '15',
                __( '20px', 'nova' ) => '20',
                __( '25px', 'nova' ) => '25',
                __( '30px', 'nova' ) => '30',
                __( '35px', 'nova' ) => '35',
                __( '40px', 'nova' ) => '40',
                __( '45px', 'nova' ) => '45',
                __( '50px', 'nova' ) => '50',
                __( '55px', 'nova' ) => '55',
                __( '60px', 'nova' ) => '60',
                __( '65px', 'nova' ) => '65',
                __( '70px', 'nova' ) => '70',
                __( '75px', 'nova' ) => '75',
                __( '80px', 'nova' ) => '80'
            ),
            'param_name' => 'item_space',
            'std' => '30'
        ), $options);
    }

    public static function getParamIndex($array, $attr){
        foreach ($array as $index => $entry) {
            if ($entry['param_name'] == $attr) {
                return $index;
            }
        }
        return -1;
    }

    public static function getParamCarouselShortCode( $atts, $param_column = 'columns' ){
        $slider_type    = $slide_to_scroll = $speed = $infinite_loop = $autoplay = $autoplay_speed = '';
        $lazyload       = $arrows = $dots = $dots_icon = $next_icon = $prev_icon = $dots_color = $draggable = $touch_move = '';
        $rtl            = $arrow_color = $arrow_size = $arrow_style = $arrow_bg_color = $arrow_border_color = $border_size = $el_class = '';
        $slides_column = $autowidth = $css_ad_carousel = $pauseohover = $centermode = $adaptive_height = $custom_nav = '';

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

        if(isset($atts[$param_column])){
            $slides_column = $atts[$param_column];
        }

        $slides_column = Novaworks_Shortcodes_Helper::getColumnFromShortcodeAtts($slides_column);

        $custom_dots = $arr_style = $wrap_data = '';


        if ( $slide_to_scroll == 'all' ) {
            $slide_to_scroll = $slides_column['xlg'];
        } else {
            $slide_to_scroll = 1;
        }

        $setting_obj = array();
        $setting_obj['slidesToShow'] = absint($slides_column['xlg']);
        $setting_obj['slidesToScroll'] = absint($slide_to_scroll);


        $arr_style .= 'color:' . $arrow_color . ';';
        $arr_style .= 'font-size:' . $arrow_size . 'px;';
        $arr_style .= 'width:' . $arrow_size . 'px;';
        $arr_style .= 'height:' . $arrow_size . 'px;';
        $arr_style .= 'line-height:' . $arrow_size . 'px;';

        if ( $arrow_style == "circle-bg" || $arrow_style == "square-bg" ) {
            $arr_style .= "background:" . $arrow_bg_color . ";";
        } elseif ( $arrow_style == "circle-border" || $arrow_style == "square-border" ) {
            $arr_style .= "border:" . $border_size . "px solid " . $arrow_border_color . ";";
        }

        if ( $dots == 'yes' ) {
            $setting_obj['dots'] = true;
        } else {
            $setting_obj['dots'] = false;
        }
        if ( $autoplay == 'yes' ) {
            $setting_obj['autoplay'] = true;
        }
        if ( $autoplay_speed !== '' ) {
            $setting_obj['autoplaySpeed'] = absint($autoplay_speed);
        }
        if ( $speed !== '' ) {
            $setting_obj['speed'] = absint($speed);
        }
        if ( $infinite_loop == 'yes' ) {
            $setting_obj['infinite'] = true;
        } else {
            $setting_obj['infinite'] = false;
        }
        if ( $lazyload == 'yes' ) {
            $setting_obj['lazyLoad'] = true;
        }

        if ( is_rtl() ) {
            $setting_obj['rtl'] = true;
            if ( $arrows == 'yes' ) {
                $setting_obj['arrows'] = true;
                $setting_obj['nextArrow'] = '<button type="button" style="' . esc_attr($arr_style) . '" class="slick-next ' . esc_attr($arrow_style) . '"><svg><use xlink:href="#'.esc_attr($prev_icon).'"></use></svg></button>';
                $setting_obj['prevArrow'] = '<button type="button" style="' . esc_attr($arr_style) . '" class="slick-prev ' . esc_attr($arrow_style) . '"><svg><use xlink:href="#'.esc_attr($next_icon).'"></use></svg></button>';
            } else {
                $setting_obj['false'] = false;
            }
        } else {
            if ( $arrows == 'yes' ) {
                $setting_obj['arrows'] = true;
                $setting_obj['nextArrow'] = '<button type="button" style="' . esc_attr($arr_style) . '" class="slick-next ' . esc_attr($arrow_style) . '"><svg><use xlink:href="#'.esc_attr($next_icon).'"></use></svg></button>';
                $setting_obj['prevArrow'] = '<button type="button" style="' . esc_attr($arr_style) . '" class="slick-prev ' . esc_attr($arrow_style) . '"><svg><use xlink:href="#'.esc_attr($prev_icon).'"></use></svg></button>';
            } else {
                $setting_obj['arrows'] = false;
            }
        }

        if ( $draggable == 'yes' ) {
            $setting_obj['swipe'] = true;
            $setting_obj['draggable'] = true;
        } else {
            $setting_obj['swipe'] = false;
            $setting_obj['draggable'] = false;
        }

        if ( $touch_move == 'yes' ) {
            $setting_obj['touchMove'] = true;
        } else {
            $setting_obj['touchMove'] = false;
        }

        if ( $rtl == 'yes' ) {
            $setting_obj['rtl'] = true;
        }

        if ( $slider_type == 'vertical' ) {
            $setting_obj['vertical'] = true;
        }

        if ( $pauseohover == 'yes' ) {
            $setting_obj['pauseOnHover'] = true;
        } else {
            $setting_obj['pauseOnHover'] = false;
        }

        if ( $centermode == 'yes' ) {
            $setting_obj['centerMode'] = true;
            $setting_obj['centerPadding'] = '20%';
        }

        if ( $autowidth == 'yes' ) {
            $setting_obj['variableWidth'] = true;
            $wrap_data .= ' aria-autowidth="true"';
        }

        if ( $adaptive_height == 'yes' ) {
            $setting_obj['adaptiveHeight'] = true;
        }

        $setting_obj['responsive'] = array(
            array(
                'breakpoint' => 1824,
                'settings' => array(
                    'slidesToShow' => $slides_column['lg'],
                    'slidesToScroll' => $slides_column['lg']
                )
            ),
            array(
                'breakpoint' => 1200,
                'settings' => array(
                    'slidesToShow' => $slides_column['md'],
                    'slidesToScroll' => $slides_column['md']
                )
            ),
            array(
                'breakpoint' => 992,
                'settings' => array(
                    'slidesToShow' => $slides_column['sm'],
                    'slidesToScroll' => $slides_column['sm']
                )
            ),
            array(
                'breakpoint' => 768,
                'settings' => array(
                    'slidesToShow' => $slides_column['xs'],
                    'slidesToScroll' => $slides_column['xs']
                )
            ),
            array(
                'breakpoint' => 480,
                'settings' => array(
                    'slidesToShow' => $slides_column['mb'],
                    'slidesToScroll' => $slides_column['mb']
                )
            )
        );

        $setting_obj['pauseOnDotsHover'] = true;

        if(!empty($custom_nav)){
            $setting_obj['appendArrows'] = 'jQuery("'.esc_attr($custom_nav).'")';
        }

        if ( $dots == 'yes' ) {
            if ( $dots_icon !== 'off' && $dots_icon !== '' ) {
                if ( $dots_color !== 'off' && $dots_color !== '' ) {
                    $custom_dots = ' style="color:' . esc_attr( $dots_color ) . ';"';
                }
                $wrap_data .= 'data-slick_customPaging="'. esc_attr('<span'.$custom_dots.'><svg><use xlink:href="#'.$dots_icon.'"></use></svg></span>') .'" ';
            }
        }

        $wrap_data .= 'data-slider_config="'. esc_attr(wp_json_encode($setting_obj)) .'"';

        return $wrap_data;
    }

}
