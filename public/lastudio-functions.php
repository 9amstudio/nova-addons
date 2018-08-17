<?php

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}


if (!function_exists('la_log')) {
    function la_log($log)
    {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}


/**
 *
 * Add framework element
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('la_fw_add_element')) {
    function la_fw_add_element($field = array(), $value = '', $unique = '')
    {
        $output = '';
        $depend = '';
        $sub = (isset($field['sub'])) ? 'sub-' : '';
        $unique = (isset($unique)) ? $unique : '';
        $class = 'LaStudio_Theme_Options_Field_' . strtolower($field['type']);
        $wrap_class = (isset($field['wrap_class'])) ? ' ' . $field['wrap_class'] : '';
        $el_class = (isset($field['title'])) ? sanitize_title($field['title']) : 'no-title';
        $hidden = '';
        $is_pseudo = (isset($field['pseudo'])) ? ' la-pseudo-field' : '';

        if (isset($field['dependency'])) {
            $hidden = ' hidden';
            $depend .= ' data-' . $sub . 'controller="' . $field['dependency'][0] . '"';
            $depend .= ' data-' . $sub . 'condition="' . $field['dependency'][1] . '"';
            $depend .= ' data-' . $sub . 'value="' . $field['dependency'][2] . '"';
        }

        $output .= '<div class="la-element la-element-' . $el_class . ' la-field-' . $field['type'] . $is_pseudo . $wrap_class . $hidden . '"' . $depend . '>';

        if (isset($field['title'])) {
            $field_desc = (isset($field['desc'])) ? '<p class="la-text-desc">' . $field['desc'] . '</p>' : '';
            $output .= '<div class="la-title"><h4>' . $field['title'] . '</h4>' . $field_desc . '</div>';
        }

        $output .= (isset($field['title'])) ? '<div class="la-fieldset">' : '';

        $value = (!isset($value) && isset($field['default'])) ? $field['default'] : $value;
        $value = (isset($field['value'])) ? $field['value'] : $value;

        if (class_exists($class)) {
            ob_start();
            $element = new $class($field, $value, $unique);
            $element->output();
            $output .= ob_get_clean();
        } else {
            $output .= '<p>' . __('This field class is not available!', 'lastudio') . '</p>';
        }

        $output .= (isset($field['title'])) ? '</div>' : '';
        $output .= '<div class="clear"></div>';
        $output .= '</div>';

        return $output;

    }
}


/**
 *
 * Array search key & value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('la_array_search')) {
    function la_array_search($array, $key, $value)
    {
        $results = array();
        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }
            foreach ($array as $sub_array) {
                $results = array_merge($results, la_array_search($sub_array, $key, $value));
            }
        }
        return $results;
    }
}

/**
 *
 * Get google font from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('la_get_google_fonts')) {
    function la_get_google_fonts()
    {
        $cache = wp_cache_get('google_font', 'la_studio');
        if (empty($cache)) {
            $file = plugin_dir_path(dirname(__FILE__)) . 'public/fonts/google-fonts.json';
            if (file_exists($file)) {
                $tmp = @file_get_contents($file);
                if (!is_wp_error($tmp)) {
                    $tmp = json_decode($tmp, false);
                    wp_cache_set('google_font', maybe_serialize($tmp), 'la_studio');
                    return $tmp;
                }
            }
        }
        if (empty($cache)) {
            return array();
        }
        return maybe_unserialize($cache);
    }
}


/**
 *
 * Getting POST Var
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('la_get_var')) {
    function la_get_var($var, $default = '')
    {
        if (isset($_POST[$var])) {
            return $_POST[$var];
        }
        if (isset($_GET[$var])) {
            return $_GET[$var];
        }
        return $default;
    }
}

/**
 *
 * Getting POST Vars
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('la_get_vars')) {
    function la_get_vars($var, $depth, $default = '')
    {
        if (isset($_POST[$var][$depth])) {
            return $_POST[$var][$depth];
        }
        if (isset($_GET[$var][$depth])) {
            return $_GET[$var][$depth];
        }
        return $default;
    }
}

if (!function_exists('la_convert_option_to_customize')) {
    function la_convert_option_to_customize($options)
    {
        $panels = array();
        foreach ($options as $section) {
            if (empty($section['sections']) && empty($section['fields'])) {
                continue;
            }

            $panel = array(
                'name' => (isset($section['name']) ? $section['name'] : uniqid()),
                'title' => $section['title'],
                'description' => (isset($section['description']) ? $section['description'] : '')
            );

            if (!empty($section['sections'])) {
                $sub_panel = array();
                foreach ($section['sections'] as $sub_section) {
                    if (!empty($sub_section['fields'])) {
                        $sub_panel2 = array(
                            'name' => (isset($sub_section['name']) ? $sub_section['name'] : uniqid()),
                            'title' => $sub_section['title'],
                            'description' => (isset($sub_section['description']) ? $sub_section['description'] : '')
                        );
                        $fields = array();
                        foreach ($sub_section['fields'] as $field) {
                            $fields[] = la_convert_field_option_to_customize($field);
                        }
                        $sub_panel2['settings'] = $fields;
                        $sub_panel[] = $sub_panel2;
                    }
                }
                $panel['sections'] = $sub_panel;
                $panels[] = $panel;
            } elseif (!empty($section['fields'])) {
                $fields = array();

                foreach ($section['fields'] as $field) {
                    $fields[] = la_convert_field_option_to_customize($field);
                }
                $panel['settings'] = $fields;
                $panels[] = $panel;
            }
        }
        return $panels;
    }
}

if (!function_exists('la_convert_field_option_to_customize')) {
    function la_convert_field_option_to_customize($field)
    {
        $backup_field = $field;
        if (isset($backup_field['id'])) {
            $field_id = $backup_field['id'];
            unset($backup_field['id']);
        } else {
            $field_id = uniqid();
        }
        if (isset($backup_field['type']) && 'wp_editor' === $backup_field['type']) {
            $backup_field['type'] = 'textarea';
        }
        $tmp = array(
            'name' => $field_id,
            'control' => array(
                'type' => 'la_field',
                'options' => $backup_field
            )
        );
        if (isset($backup_field['default'])) {
            $tmp['default'] = $backup_field['default'];
            unset($backup_field['default']);
        }
        return $tmp;
    }
}


if (!function_exists('hex2rgbUltParallax')) {
    function hex2rgbUltParallax($hex, $opacity)
    {
        $hex = str_replace("#", "", $hex);
        if (preg_match("/^([a-f0-9]{3}|[a-f0-9]{6})$/i", $hex)):
            if (strlen($hex) == 3) { // three letters code
                $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
            } else { // six letters coode
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
            }
            return 'rgba(' . implode(",", array($r, $g, $b)) . ',' . $opacity . ')';
        else:
            return "";
        endif;
    }
}
if (!function_exists('rgbaToHexUltimate')) {
    function rgbaToHexUltimate($r, $g, $b)
    {
        $hex = "#";
        $hex .= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
        return $hex;
    }
}

if (!function_exists('la_fw_get_child_shortcode_nested')) {
    function la_fw_get_child_shortcode_nested($content, $atts = null)
    {
        $res = array();
        $reg = get_shortcode_regex();
        preg_match_all('~' . $reg . '~', $content, $matches);
        if (isset($matches[2]) && !empty($matches[2])) {
            foreach ($matches[2] as $key => $name) {
                $res[$name] = $name;
            }
        }
        return $res;
    }
}

if (!function_exists('la_fw_override_shortcodes')) {
    function la_fw_override_shortcodes($content = null)
    {
        if (!empty($content)) {
            global $shortcode_tags, $backup_shortcode_tags;
            $backup_shortcode_tags = $shortcode_tags;
            $child_exists = la_fw_get_child_shortcode_nested($content);
            if (!empty($child_exists)) {
                foreach ($child_exists as $tag) {
                    $shortcode_tags[$tag] = 'la_fw_wrap_shortcode_in_div';
                }
            }
        }
    }
}

if (!function_exists('la_fw_wrap_shortcode_in_div')) {
    function la_fw_wrap_shortcode_in_div($attr, $content = null, $tag)
    {
        global $backup_shortcode_tags;
        return '<div class="la-item-wrap">' . call_user_func($backup_shortcode_tags[$tag], $attr, $content, $tag) . '</div>';
    }
}
if (!function_exists('la_fw_restore_shortcodes')) {
    function la_fw_restore_shortcodes()
    {
        global $shortcode_tags, $backup_shortcode_tags;
        // Restore the original callbacks
        if (isset($backup_shortcode_tags)) {
            $shortcode_tags = $backup_shortcode_tags;
        }
    }
}

if (!function_exists('la_pagespeed_detected')) {
    function la_pagespeed_detected()
    {
        return (
            isset($_SERVER['HTTP_USER_AGENT'])
            && preg_match('/GTmetrix|Page Speed/i', $_SERVER['HTTP_USER_AGENT'])
        );
    }
}

if (!function_exists('la_shortcode_custom_css_class')) {
    function la_shortcode_custom_css_class($param_value, $prefix = '')
    {
        $css_class = preg_match('/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value) ? $prefix . preg_replace('/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value) : '';
        return $css_class;
    }
}

if (!function_exists('la_build_link_from_atts')) {
    function la_build_link_from_atts($value)
    {
        $result = array('url' => '', 'title' => '', 'target' => '', 'rel' => '');
        $params_pairs = explode('|', $value);
        if (!empty($params_pairs)) {
            foreach ($params_pairs as $pair) {
                $param = preg_split('/\:/', $pair);
                if (!empty($param[0]) && isset($param[1])) {
                    $result[$param[0]] = rawurldecode($param[1]);
                }
            }
        }
        return $result;
    }
}


if (!function_exists('la_get_blank_image_src')) {
    function la_get_blank_image_src()
    {
        return 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
    }
}

if (!function_exists('la_get_product_grid_style')) {
    function la_get_product_grid_style()
    {
        return array(
            __('Design 01', 'lastudio') => '1',
            __('Design 02', 'lastudio') => '2',
            __('Design 03', 'lastudio') => '3',
            __('Design 04', 'lastudio') => '4',
            __('Design 05', 'lastudio') => '5',
            __('Design 06', 'lastudio') => '6',
            __('Design 07', 'lastudio') => '7'
        );
    }
}

if (!function_exists('la_get_product_list_style')) {
    function la_get_product_list_style()
    {
        return array(
            __('Default', 'lastudio') => 'default',
            __('Mini', 'lastudio') => 'mini'
        );
    }
}

if (!function_exists('la_export_options')) {
    function la_export_options()
    {
        $unique = isset($_REQUEST['unique']) ? $_REQUEST['unique'] : 'la_options';
        header('Content-Type: plain/text');
        header('Content-disposition: attachment; filename=backup-' . esc_attr($unique) . '-' . gmdate('d-m-Y') . '.txt');
        header('Content-Transfer-Encoding: binary');
        header('Pragma: no-cache');
        header('Expires: 0');
        echo wp_json_encode(get_option($unique));
        die();
    }

    add_action('wp_ajax_la-export-options', 'la_export_options');
}

if (!function_exists('la_add_script_to_compare')) {
    function la_add_script_to_compare()
    {
        echo '<script type="text/javascript">var redirect_to_cart=true;</script>';
    }

    add_action('yith_woocompare_after_main_table', 'la_add_script_to_compare');
}

if (!function_exists('la_add_script_to_quickview_product')) {
    function la_add_script_to_quickview_product()
    {
        global $product;
        if (isset($_GET['product_quickview']) && is_product()) {
            if ($product->get_type() == 'variable') {
                wp_print_scripts('underscore');
                wc_get_template('single-product/add-to-cart/variation.php');
                ?>
                <script type="text/javascript">
                    /* <![CDATA[ */
                    var _wpUtilSettings = <?php echo wp_json_encode(array(
                        'ajax' => array('url' => admin_url('admin-ajax.php', 'relative'))
                    ));?>;
                    var wc_add_to_cart_variation_params = <?php echo wp_json_encode(array(
                        'i18n_no_matching_variations_text' => esc_attr__('Sorry, no products matched your selection. Please choose a different combination.', 'lastudio'),
                        'i18n_make_a_selection_text' => esc_attr__('Select product options before adding this product to your cart.', 'lastudio'),
                        'i18n_unavailable_text' => esc_attr__('Sorry, this product is unavailable. Please choose a different combination.', 'lastudio')
                    )); ?>;
                    /* ]]> */
                </script>
                <script type="text/javascript"
                        src="<?php echo esc_url(includes_url('js/wp-util.min.js')) ?>"></script>
                <script type="text/javascript"
                        src="<?php echo esc_url(WC()->plugin_url()) . '/assets/js/frontend/add-to-cart-variation.min.js' ?>"></script>
                <?php
            } else {
                ?>
                <script type="text/javascript">
                    /* <![CDATA[ */
                    var wc_single_product_params = <?php echo wp_json_encode(array(
                        'i18n_required_rating_text' => esc_attr__('Please select a rating', 'lastudio'),
                        'review_rating_required' => get_option('woocommerce_review_rating_required'),
                        'flexslider' => apply_filters('woocommerce_single_product_carousel_options', array(
                            'rtl' => is_rtl(),
                            'animation' => 'slide',
                            'smoothHeight' => false,
                            'directionNav' => false,
                            'controlNav' => 'thumbnails',
                            'slideshow' => false,
                            'animationSpeed' => 500,
                            'animationLoop' => false, // Breaks photoswipe pagination if true.
                        )),
                        'zoom_enabled' => 0,
                        'photoswipe_enabled' => 0,
                        'flexslider_enabled' => 1,
                    ));?>;
                    /* ]]> */
                </script>
                <?php
            }
        }
    }

    add_action('woocommerce_after_single_product', 'la_add_script_to_quickview_product');
}

if (!function_exists('la_theme_fix_wc_track_product_view')) {
    function la_theme_fix_wc_track_product_view()
    {
        if (!is_singular('product')) {
            return;
        }
        if (!function_exists('wc_setcookie')) {
            return;
        }
        global $post;
        if (empty($_COOKIE['woocommerce_recently_viewed'])) {
            $viewed_products = array();
        } else {
            $viewed_products = (array)explode('|', $_COOKIE['woocommerce_recently_viewed']);
        }
        if (!in_array($post->ID, $viewed_products)) {
            $viewed_products[] = $post->ID;
        }
        if (sizeof($viewed_products) > 15) {
            array_shift($viewed_products);
        }
        wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
    }

    add_action('template_redirect', 'la_theme_fix_wc_track_product_view', 30);

}

