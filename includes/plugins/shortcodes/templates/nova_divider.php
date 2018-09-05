<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}
$nova_fix_css = array();
$height = $el_class = '';

$atts = shortcode_atts(array(
    'height' => '',
    'el_class' => ''
), $atts );
extract( $atts );

$css_class = 'js-el nova-divider nova-unit-responsive' . Novaworks_Shortcodes_Helper::getExtraClass($el_class);
$unique_id = uniqid('nova_divider');
?>
<div id="<?php echo esc_attr($unique_id)?>" class="<?php echo esc_attr($css_class)?>"<?php
if(!empty($height)){
    $default_style = Novaworks_Shortcodes_Helper::getColumnFromShortcodeAtts($height);
    echo Novaworks_Shortcodes_Helper::getResponsiveMediaCss(array(
        'target'		=> "#{$unique_id}",
        'media_sizes' 	=> array(
            'padding-top' 	=> $height,
        )
    ));
    Novaworks_Shortcodes_Helper::renderResponsiveMediaCss($nova_fix_css, array(
        'target'		=> "#{$unique_id}",
        'media_sizes' 	=> array(
            'padding-top' 	=> $height,
        )
    ));
}
?>></div>
<?php Novaworks_Shortcodes_Helper::renderResponsiveMediaStyleTags($nova_fix_css); ?>
