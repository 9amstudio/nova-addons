<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

$shortcode_params = array(
    array(
        'type' => 'textfield',
        'heading' => __('Heading', 'nova'),
        'param_name' => 'title',
        'admin_label' => true,
    ),
    array(
        'type' => 'textarea_html',
        'heading' => __('Sub Heading(Optional)', 'nova'),
        'param_name' => 'content'
    ),
    array(
        'type' => 'dropdown',
        'heading' => __('Heading tag','nova'),
        'param_name' => 'tag',
        'value' => array(
            __('Default','nova') => 'h2',
            __('H1','nova') => 'h1',
            __('H3','nova') => 'h3',
            __('H4','nova') => 'h4',
            __('H5','nova') => 'h5',
            __('H6','nova') => 'h6',
            __('DIV','nova') => 'div',
            __('p','nova') => 'p',
        ),
        'default' => 'h2',
        'description' => __('Default is H2', 'nova'),
    ),
    array(
        'type'  => 'dropdown',
        'heading' => __('Alignment','nova'),
        'param_name'    => 'alignment',
        'value' => array(
            __('Center','nova')	    =>	'center',
            __('Left','nova')	    =>	'left',
            __('Right','nova')	    =>	'right',
            __('Inline','nova')	    =>	'inline'
        ),
        'default' => 'left',
    ),
    array(
        'type'  => 'dropdown',
        'heading' => __('Separator','nova'),
        'param_name'    => 'spacer',
        'value' => array(
            __('No Separator','nova')	=>	'none',
            __('Line','nova')	        =>	'line',
        ),
        'default' => 'none',
        'dependency' => array(
            'element'   => 'alignment',
            'value'     => array('center', 'left', 'right' )
        )
    ),
    array(
        'type'  => 'dropdown',
        'heading' => __('Separator Position','nova'),
        'param_name'    => 'spacer_position',
        'value' => array(
            __('Top','nova')	                        =>	'top',
            __('Bottom','nova')	                    =>	'bottom',
            __('Left', 'nova')	                        =>	'left',
            __('Right', 'nova')	                    =>	'right',
            __('Between Heading & Subheading', 'nova') =>	'middle',
            __('Title between separator', 'nova')	    =>	'separator'
        ),
        'default' => 'top',
        'dependency' => array(
            'element'   => 'spacer',
            'value'     => 'line'
        )
    ),
    array(
        'type'      => 'dropdown',
        'heading'   => __('Line Style', 'nova'),
        'param_name'    => 'line_style',
        'value'         => array(
            __('Solid', 'nova') => 'solid',
            __('Dashed', 'nova') => 'dashed',
            __('Dotted', 'nova') => 'dotted',
            __('Double', 'nova') => 'double'
        ),
        'default' => 'solid',
        'dependency' => array(
            'element'   => 'spacer',
            'value'     => 'line'
        )
    ),
    array(
        'type' 			=> 'nova_column',
        'heading' 		=> __('Line Width', 'nova'),
        'param_name' 	=> 'line_width',
        'unit'			=> 'px',
        'media'			=> array(
            'xlg'	=> '',
            'lg'	=> '',
            'md'	=> '',
            'sm'	=> '',
            'xs'	=> '',
            'mb'	=> ''
        ),
        'dependency' => array(
            'element'   => 'spacer',
            'value'     => 'line'
        )
    ),
    array(
        'type' => 'nova_number',
        'heading' => __('Line Height', 'nova'),
        'param_name' => 'line_height',
        'value' => 1,
        'min' => 1,
        'suffix' => 'px',
        'dependency' => array(
            'element'   => 'spacer',
            'value'     => 'line'
        )
    ),
    array(
        'type' => 'colorpicker',
        'heading' => __('Line Color', 'nova'),
        'param_name' => 'line_color',
        'dependency' => array(
            'element'   => 'spacer',
            'value'     => 'line'
        )
    ),
    Novaworks_Shortcodes_Helper::fieldExtraClass(),
    Novaworks_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra Class for heading', 'nova'),
        'param_name' 	=> 'title_class',
    )),
    Novaworks_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra Class for subheading', 'nova'),
        'param_name' 	=> 'subtitle_class',
    )),
    Novaworks_Shortcodes_Helper::fieldExtraClass(array(
        'heading' 		=> __('Extra Class for Line', 'nova'),
        'param_name' 	=> 'line_class',
        'dependency' => array(
            'element'   => 'spacer',
            'value'     => 'line'
        )
    ))
);

$title_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont();
$desc_google_font_param = Novaworks_Shortcodes_Helper::fieldTitleGFont('subtitle', __('Subheading', 'nova'));

$shortcode_params = array_merge( $shortcode_params, $title_google_font_param, $desc_google_font_param, array(Novaworks_Shortcodes_Helper::fieldCssClass()));

return apply_filters(
    'Novaworks/shortcodes/configs',
    array(
        'name'			=> __('Title Heading', 'nova'),
        'base'			=> 'nova_heading',
        'icon'          => 'nova-wpb-icon fa fa-font nova_heading',
        'category'  	=> __('9AM Studio', 'nova'),
        'description' 	=> __('Awesome heading styles.','nova'),
        'params' 		=> $shortcode_params
    ),
    'nova_heading'
);
