<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$la_fix_css = array();
$icon_type = $icon_fontawesome = $icon_la_icon_outline = $icon_image_id = $icon_fz = $icon_lh = $icon_color = $icon_bg_color = $icon_bg_color2 = '';
$package_title = $use_gfont_package_title = $package_title_font = $package_title_fz = $package_title_lh = $package_title_color = '';
$package_price = $use_gfont_package_price = $package_price_font = $package_price_fz = $package_price_lh = $package_price_color = '';
$price_unit = $use_gfont_package_price_unit = $package_price_unit_font = $package_price_unit_fz = $package_price_unit_lh = $package_price_unit_color = '';
$features = $use_gfont_package_features = $package_features_font = $package_features_fz = $package_features_lh = $package_features_color = $package_features_highlight_color = '';
$desc_before = $desc_after = $use_gfont_package_desc = $package_desc_font = $package_desc_fz = $package_desc_lh = $package_desc_color = '';
$button_text = $button_link = $use_gfont_package_button = $package_button_font = $package_button_fz = $package_button_lh = $package_button_color = $package_button_bg_color = $package_button_hover_color = $package_button_hover_bg_color = '';

$style = $custom_badge = $package_featured = $el_class = $main_bg_color = $main_text_color = '';

$atts = shortcode_atts( array(
    'style' => '1',
    'icon_type' => 'fontawesome',
    'icon_fontawesome' => 'fa fa-info-circle',
    'icon_openiconic' => '',
    'icon_typicons' => '',
    'icon_entypo' => '',
    'icon_linecons' => '',
    'icon_monosocial' => 'vc-mono vc-mono-fivehundredpx',
    'icon_la_icon_outline' => 'la-icon design-2_image',
    'icon_nucleo_glyph' => 'nc-icon-glyph nature_bear',
    'icon_image_id' => '',
    'main_bg_color' => '',
    'main_text_color' => '',
    'package_title' => '',
    'package_price' => '',
    'price_unit' => '',
    'features' => '',
    'desc_before' => '',
    'desc_after' => '',
    'button_text' => 'View More',
    'button_link' => '',
    'package_featured' => '',
    'custom_badge' => 'Recommended',
    'el_class' => '',
    'icon__typography' => '',
    'icon_lh' => '',
    'icon_fz' => '',
    'icon_color' => '',
    'icon_bg_color' => '',
    'icon_bg_color2' => '',
    'package_title__typography' => '',
    'use_gfont_package_title' => '',
    'package_title_font' => '',
    'package_title_fz' => '',
    'package_title_lh' => '',
    'package_title_color' => '',
    'package_price__typography' => '',
    'use_gfont_package_price' => '',
    'package_price_font' => '',
    'package_price_fz' => '',
    'package_price_lh' => '',
    'package_price_color' => '',
    'package_price_unit__typography' => '',
    'use_gfont_package_price_unit' => '',
    'package_price_unit_font' => '',
    'package_price_unit_fz' => '',
    'package_price_unit_lh' => '',
    'package_price_unit_color' => '',
    'package_desc__typography' => '',
    'use_gfont_package_desc' => '',
    'package_desc_font' => '',
    'package_desc_fz' => '',
    'package_desc_lh' => '',
    'package_desc_color' => '',
    'package_features__typography' => '',
    'use_gfont_package_features' => '',
    'package_features_font' => '',
    'package_features_fz' => '',
    'package_features_lh' => '',
    'package_features_color' => '',
    'package_features_highlight_color' => '',
    'icon__package_button' => '',
    'use_gfont_package_button' => '',
    'package_button_font' => '',
    'package_button_fz' => '',
    'package_button_lh' => '',
    'package_button_color' => '',
    'package_button_bg_color' => '',
    'package_button_hover_color' => '',
    'package_button_hover_bg_color' => '',
), $atts );

extract( $atts );

$unique_id = uniqid('la_pricing_box_');
$features = json_decode(urldecode($features),true);

$css_class = array(
    'la-pricing-box-wrap',
    'wpb_content_element',
    'style-' . $style
);
if($package_featured == 'yes'){
    $css_class[] = 'is_box_featured';
}

$css_class = implode(' ', $css_class) . LaStudio_Shortcodes_Helper::getExtraClass( $el_class );


$button_link = ( '||' === $button_link ) ? '' : $button_link;
$button_link = vc_parse_multi_attribute( $button_link, array( 'url' => '', 'title' => '', 'target' => '_self', 'rel' => '' ) );

// Build title
$packageTitleCssInline = array();
$packageTitleHtmlAtts = '';
if(!empty($package_title_fz) || !empty($package_title_lh)){
    $packageTitleHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target' => '#' . $unique_id . ' .la-pricing-box .pricing-heading',
        'media_sizes' => array(
            'font-size' => $package_title_fz,
            'line-height' => $package_title_lh
        )
    ));
    LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
        'target' => '#' . $unique_id . ' .la-pricing-box .pricing-heading',
        'media_sizes' => array(
            'font-size' => $package_title_fz,
            'line-height' => $package_title_lh
        )
    ));
}
if(!empty($package_title_color)){
    $packageTitleCssInline[] = "color:{$package_title_color}";
}
if(!empty($use_gfont_package_title)){
    $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($package_title_font);
    if(isset($gfont_data['style'])){
        $packageTitleCssInline[] = $gfont_data['style'];
    }
    if(isset($gfont_data['font_url'])){
        wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
    }
}


// Build price
$packagePriceCssInline = array();
$packagePriceHtmlAtts = '';
if(!empty($package_price_fz) || !empty($package_price_lh)){
    $packagePriceHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target' => '#' . $unique_id . ' .la-pricing-box .price-box .price-value',
        'media_sizes' => array(
            'font-size' => $package_price_fz,
            'line-height' => $package_price_lh
        )
    ));
    LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
        'target' => '#' . $unique_id . ' .la-pricing-box .price-box .price-value',
        'media_sizes' => array(
            'font-size' => $package_price_fz,
            'line-height' => $package_price_lh
        )
    ));
}
if(!empty($package_price_color)){
    $packagePriceCssInline[] = "color:{$package_price_color}";
}
if(!empty($use_gfont_package_price)){
    $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($package_price_font);
    if(isset($gfont_data['style'])){
        $packagePriceCssInline[] = $gfont_data['style'];
    }
    if(isset($gfont_data['font_url'])){
        wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
    }
}

// Build price unit
$packagePriceUnitCssInline = array();
$packagePriceUnitHtmlAtts = '';
if(!empty($package_price_unit_fz) || !empty($package_price_unit_lh)){
    $packagePriceUnitHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target' => '#' . $unique_id . ' .la-pricing-box .price-box .price-unit',
        'media_sizes' => array(
            'font-size' => $package_price_unit_fz,
            'line-height' => $package_price_unit_lh
        )
    ));
    LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
        'target' => '#' . $unique_id . ' .la-pricing-box .price-box .price-unit',
        'media_sizes' => array(
            'font-size' => $package_price_unit_fz,
            'line-height' => $package_price_unit_lh
        )
    ));
}
if(!empty($package_price_unit_color)){
    $packagePriceUnitCssInline[] = "color:{$package_price_unit_color}";
}
if(!empty($use_gfont_package_price_unit)){
    $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($package_price_unit_font);
    if(isset($gfont_data['style'])){
        $packagePriceUnitCssInline[] = $gfont_data['style'];
    }
    if(isset($gfont_data['font_url'])){
        wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
    }
}

// Build Description
$packageDescCssInline = array();
$packageDescHtmlAtts = '';
if(!empty($package_desc_fz) || !empty($package_desc_lh)){
    $packageDescHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target' => '#' . $unique_id . ' .la-pricing-box .desc-features',
        'media_sizes' => array(
            'font-size' => $package_desc_fz,
            'line-height' => $package_desc_lh
        )
    ));
    LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
        'target' => '#' . $unique_id . ' .la-pricing-box .desc-features',
        'media_sizes' => array(
            'font-size' => $package_desc_fz,
            'line-height' => $package_desc_lh
        )
    ));
}
if(!empty($package_desc_color)){
    $packageDescCssInline[] = "color:{$package_desc_color}";
}
if(!empty($use_gfont_package_desc)){
    $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($package_desc_font);
    if(isset($gfont_data['style'])){
        $packageDescCssInline[] = $gfont_data['style'];
    }
    if(isset($gfont_data['font_url'])){
        wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
    }
}

// Build Package Featured
$packageFeaturesCssInline = array();
$packageFeaturesHtmlAtts = '';
if(!empty($package_features_fz) || !empty($package_features_lh)){
    $packageFeaturesHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target' => '#' . $unique_id . ' .la-pricing-box .package-features',
        'media_sizes' => array(
            'font-size' => $package_features_fz,
            'line-height' => $package_features_lh
        )
    ));
    LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
        'target' => '#' . $unique_id . ' .la-pricing-box .package-features',
        'media_sizes' => array(
            'font-size' => $package_features_fz,
            'line-height' => $package_features_lh
        )
    ));
}
if(!empty($package_features_color)){
    $packageFeaturesCssInline[] = "color:{$package_features_color}";
}
if(!empty($use_gfont_package_features)){
    $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($package_features_font);
    if(isset($gfont_data['style'])){
        $packageFeaturesCssInline[] = $gfont_data['style'];
    }
    if(isset($gfont_data['font_url'])){
        wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
    }
}

// Build action
$packageActionCssInline = array();
$packageActionHtmlAtts = '';
if(!empty($package_button_fz) || !empty($package_button_lh)){
    $packageActionHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target' => '#' . $unique_id . ' .la-pricing-box .pricing-action a',
        'media_sizes' => array(
            'font-size' => $package_button_fz,
            'line-height' => $package_button_lh
        )
    ));
    LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
        'target' => '#' . $unique_id . ' .la-pricing-box .pricing-action a',
        'media_sizes' => array(
            'font-size' => $package_button_fz,
            'line-height' => $package_button_lh
        )
    ));
}

if(!empty($use_gfont_package_button)){
    $gfont_data = LaStudio_Shortcodes_Helper::parseGoogleFontAtts($package_button_font);
    if(isset($gfont_data['style'])){
        $packageActionCssInline[] = $gfont_data['style'];
    }
    if(isset($gfont_data['font_url'])){
        wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
    }
}

// Build Package Icon
$iconInnerHTML = '';
$packageIconCssInline = array();
$packageIconHtmlAtts = '';
if($icon_type == 'custom'){
    if( $__icon_html = wp_get_attachment_image($icon_image_id, 'full') ) {
        $iconInnerHTML = $__icon_html;
    }
}else{
    if(!empty( ${'icon_' . $icon_type} )){
        $iconInnerHTML = '<i class="'.esc_attr(${'icon_' . $icon_type}).'"></i>';
        if(function_exists('vc_icon_element_fonts_enqueue')){
            vc_icon_element_fonts_enqueue( $icon_type );
        }
    }
}
$packageIconHtmlAtts = LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
    'target' => '#' . $unique_id . ' .la-pricing-box .wrap-icon .icon-inner',
    'media_sizes' => array(
        'font-size' => $icon_fz,
        'line-height' => $icon_lh,
        'width' => $icon_lh,
        'height' => $icon_lh
    )
));
LaStudio_Shortcodes_Helper::renderResponsiveMediaCss($la_fix_css, array(
    'target' => '#' . $unique_id . ' .la-pricing-box .wrap-icon .icon-inner',
    'media_sizes' => array(
        'font-size' => $icon_fz,
        'line-height' => $icon_lh,
        'width' => $icon_lh,
        'height' => $icon_lh
    )
));
if(!empty($icon_color)){
    $packageIconCssInline[] = "color:{$icon_color}";
}

?>
<div id="<?php echo esc_attr($unique_id);?>" class="<?php echo esc_attr($css_class)?>">
    <div class="la-pricing-box">
        <?php
        if($package_featured == 'yes'){
            printf('<div class="pricing-badge"><span>%s</span></div>', esc_html($custom_badge));
        }
        ?>
        <div class="pricing-heading-wrap">
            <div class="wrap2">
                <?php

                if(!empty($iconInnerHTML) && $style == 1){

                    $iconInnerHTML .= sprintf(
                        '<span class="hidden js-el"%s></span>',
                        LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
                            'target' => '#' . $unique_id . ' .la-pricing-box',
                            'media_sizes' => array(
                                'margin-top' => preg_replace_callback(
                                    '/(\\d+)/',
                                    function($match){
                                        return absint($match[0]/2);
                                    },
                                    $icon_lh
                                )
                            )
                        ))
                    );
                    $iconInnerHTML .= sprintf(
                        '<span class="hidden js-el"%s></span>',
                        LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
                            'target' => '#' . $unique_id . ' .la-pricing-box .pricing-heading-wrap',
                            'media_sizes' => array(
                                'padding-top' => preg_replace_callback(
                                    '/(\\d+)/',
                                    function($match){
                                        return absint($match[0]/2);
                                    },
                                    $icon_lh
                                )
                            )
                        ))
                    );
                    $iconInnerHTML .= sprintf(
                        '<span class="hidden js-el"%s></span>',
                        LaStudio_Shortcodes_Helper::getResponsiveMediaCss(array(
                            'target' => '#' . $unique_id . ' .la-pricing-box .wrap-icon',
                            'media_sizes' => array(
                                'margin-top' => preg_replace_callback(
                                    '/(\\d+)/',
                                    function($match){
                                        return '-' . absint($match[0]/2);
                                    },
                                    $icon_lh
                                )
                            )
                        ))
                    );

                    printf('<div class="wrap-icon"><div class="icon-inner js-el la-unit-responsive" style="%s"%s>%s</div></div>',
                        esc_attr( implode(';', $packageIconCssInline) ),
                        $packageIconHtmlAtts,
                        $iconInnerHTML
                    );
                }

                if(!empty($package_title)){
                    printf('<div class="pricing-heading js-el la-unit-responsive" style="%s"%s>%s</div>',
                        esc_attr( implode(';', $packageTitleCssInline) ),
                        $packageTitleHtmlAtts,
                        esc_html($package_title)
                    );
                }

                if(!empty($package_price) || !empty($price_unit)){
                    $supPrice = preg_replace('/[^0-9\.\,]+/', '<sup>$0</sup>', $package_price);
                    printf('<div class="price-box-wrap"><div class="price-box"><div class="price-value js-el la-unit-responsive" style="%s"%s>%s</div><div class="price-unit js-el la-unit-responsive" style="%s"%s>%s</div></div></div>',
                        esc_attr( implode(';', $packagePriceCssInline) ),
                        $packagePriceHtmlAtts,
                        $supPrice,
                        esc_attr( implode(';', $packagePriceUnitCssInline) ),
                        $packagePriceUnitHtmlAtts,
                        $price_unit
                    );
                }
                ?>
            </div>
        </div>
        <div class="pricing-body">
            <?php
            if(!empty($desc_before)){
                printf('<div class="desc-features before-features js-el la-unit-responsive" style="%s"%s>%s</div>',
                    esc_attr( implode(';', $packageDescCssInline) ),
                    $packageDescHtmlAtts,
                    $desc_before
                );
            }

            if(count($features) > 0){
                $featuresInnerHTML = '<ul>';
                foreach ($features as $feature){
                    $featuresInnerHTML .= sprintf('<li><span>%s</span>%s</li>',
                        ( !empty($feature['highlight']) ? '<strong>'.$feature['highlight'].' </strong>' : '' ) . ( !empty($feature['text']) ? $feature['text'] : ''),
                        ( !empty($feature['icon']) ? '<i class="'.esc_attr($feature['icon']).'"></i>' : '')
                    );
                }
                $featuresInnerHTML .= '</ul>';
                printf('<div class="package-features js-el la-unit-responsive" style="%s"%s>%s</div>',
                    esc_attr( implode(';', $packageFeaturesCssInline) ),
                    $packageFeaturesHtmlAtts,
                    $featuresInnerHTML
                );
            }

            if(!empty($desc_after)){
                printf('<div class="desc-features after-features js-el la-unit-responsive" style="%s"%s>%s</div>',
                    esc_attr( implode(';', $packageDescCssInline) ),
                    $packageDescHtmlAtts,
                    $desc_after
                );
            }
            ?>
        </div>
        <?php
            if(!empty($button_link['url'])){
                printf('<div class="pricing-action"><a class="js-el la-unit-responsive" href="%s" target="%s" title="%s" style="%s"%s>%s</a></div>',
                    esc_url($button_link['url']),
                    esc_attr($button_link['target']),
                    esc_attr($button_link['title']),
                    esc_attr( implode(';', $packageActionCssInline) ),
                    $packageActionHtmlAtts,
                    esc_html($button_text)
                );
            }
        ?>
    </div>
</div>
<span data-la_component="InsertCustomCSS" class="js-el hidden">
    #<?php echo $unique_id ?> .la-pricing-box{
        background-color: <?php echo $main_bg_color;?>;
        color : <?php echo $main_text_color;?>;
    }
    #<?php echo $unique_id ?> .la-pricing-box .package-features li strong{
        color : <?php echo $package_features_highlight_color;?>;
    }
    #<?php echo $unique_id ?> .pricing-action a{
        color: <?php echo $package_button_color ?>;
        background-color: <?php echo $package_button_bg_color ?>;
    }
    #<?php echo $unique_id ?> .la-pricing-box:hover .pricing-action a{
        color: <?php echo $package_button_hover_color; ?>;
        background-color: <?php echo $package_button_hover_bg_color; ?>;
        border-color: <?php echo $package_button_hover_bg_color; ?>;
    }
    <?php if($style == 1): ?>
        #<?php echo $unique_id ?> .la-pricing-box .icon-inner{
            background-color: <?php echo $icon_bg_color; ?>;
            background-image: -moz-linear-gradient(180deg, <?php echo $icon_bg_color; ?> 0%, <?php echo $icon_bg_color2; ?> 100%);
            background-image: -webkit-linear-gradient(180deg, <?php echo $icon_bg_color; ?> 0%, <?php echo $icon_bg_color2; ?> 100%);
            background-image: -ms-linear-gradient(180deg, <?php echo $icon_bg_color; ?> 0%, <?php echo $icon_bg_color2; ?> 100%);
        }
    <?php endif; ?>
    <?php if($style == 2): ?>
        #<?php echo $unique_id ?> .la-pricing-box:before{
            background-color: <?php echo $package_button_bg_color ?>;
        }
        #<?php echo $unique_id ?>.is_box_featured .la-pricing-box:before,
        #<?php echo $unique_id ?> .la-pricing-box:hover:before{
            background-color: <?php echo $package_button_hover_bg_color ?>;
        }
    <?php endif; ?>
    <?php if($style == 3): ?>
        #<?php echo $unique_id ?> .la-pricing-box:hover .pricing-heading-wrap{
            background-color: <?php echo $package_button_hover_bg_color ?>;
        }
        #<?php echo $unique_id ?> .la-pricing-box:hover .pricing-heading-wrap:before{
            border-top-color: <?php echo $package_button_hover_bg_color ?>;
        }
    <?php endif; ?>
</span>
<?php LaStudio_Shortcodes_Helper::renderResponsiveMediaStyleTags($la_fix_css); ?>