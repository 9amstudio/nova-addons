<?php

class Nova_Shortcodes {
	public static $current_banner = 1;

	/**
	 * Init shortcodes
	 */
	public static function init() {
		$shortcodes = array(
			'button',
			'product_grid',
			'product_carousel',
			'product_tabs',
			'post_grid',
			'countdown',
			'stats_counter',
			'category_banner',
			'product',
			'banner2',
			'banner3',
			'banner4',
			'banner_grid_4',
			'banner_grid_5',
			'banner_grid_6',
			'chart',
			'message_box',
			'icon_box',
			'heading',
			'divider',
			'pricing_table',
			'map',
			'testimonial',
			'partners',
			'contact_box',
			'info_list',
			'faq',
			'team_member',
			'hotspot',
			'image_with_hotspots',
			'timeline_item',
			'instagram_feed',
			'portfolio_grid',
			'portfolio_masonry',
			'popup_video'
		);

		foreach ( $shortcodes as $shortcode ) {
			add_shortcode( 'nova_' . $shortcode, array( __CLASS__, $shortcode ) );
		}

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_filter( 'post_class', array( __CLASS__, 'product_class' ), 10, 3 );

		add_action( 'wp_ajax_nopriv_nova_load_products', array( __CLASS__, 'ajax_load_products' ) );
		add_action( 'wp_ajax_nova_load_products', array( __CLASS__, 'ajax_load_products' ) );
	}

	/**
	 * Load scripts
	 */
	public static function enqueue_scripts() {
		wp_deregister_script( 'isotope' );
		wp_register_script( 'isotope', NOVA_ADDONS_URL . 'assets/js/isotope.pkgd.min.js', array(
			'jquery',
			'imagesloaded',
		), '3.0.1', true );
		wp_register_script( 'jquery-countdown', NOVA_ADDONS_URL . 'assets/js/jquery.countdown.js', array( 'jquery' ), '2.0.4', true );
		wp_register_script( 'jquery-counterup', NOVA_ADDONS_URL . 'assets/js/jquery.counterup.js', array( 'jquery' ), '1.0.3', true );
		wp_register_script( 'jquery-circle-progress', NOVA_ADDONS_URL . 'assets/js/circle-progress.js', array( 'jquery' ), '1.1.3', true );
		wp_register_script( 'jquery-instafeed', NOVA_ADDONS_URL . 'assets/js/instafeed.js', array( 'jquery' ), '1.9.3', true );
		wp_enqueue_script( 'nova-shortcodes', NOVA_ADDONS_URL . 'assets/js/shortcodes.js', array(
			'isotope',
			'wp-util',
			'waypoints',
			'jquery-countdown',
			'jquery-counterup',
			'jquery-circle-progress',
			'jquery-instafeed',
		), '20160725', true );
	}

	/**
	 * Add classes to products which are inside loop of shortcodes
	 *
	 * @param array  $classes
	 * @param string $class
	 * @param int    $post_id
	 *
	 * @return array
	 */
	public static function product_class( $classes, $class, $post_id ) {
		if ( ! $post_id || get_post_type( $post_id ) !== 'product' || is_single( $post_id ) ) {
			return $classes;
		}

		global $woocommerce_loop;
		$accept_products = array(
			'nova_product_grid',
			'nova_ajax_products',
		);

		if ( ! isset( $woocommerce_loop['name'] ) || ! in_array( $woocommerce_loop['name'], $accept_products ) ) {
			return $classes;
		}

		// Add class for new products
		$newness = get_theme_mod( 'product_newness', false );
		if ( $newness && ( time() - ( 60 * 60 * 24 * $newness ) ) < strtotime( get_the_time( 'Y-m-d' ) ) ) {
			$classes[] = 'new';
		}

		return $classes;
	}

	/**
	 * Ajax load products
	 */
	public static function ajax_load_products() {
		check_ajax_referer( 'nova_get_products', 'nonce' );

		$atts = array(
			'load_more' => isset( $_POST['load_more'] ) ? $_POST['load_more'] : true,
			'type'      => isset( $_POST['type'] ) ? $_POST['type'] : '',
			'page'      => isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1,
			'per_page'  => isset( $_POST['per_page'] ) ? intval( $_POST['per_page'] ) : 10,
		);

		if ( isset( $_POST['columns'] ) ) {
			$atts['columns'] = intval( $_POST['columns'] );
		}

		if ( isset( $_POST['category'] ) ) {
			$atts['category'] = trim( $_POST['category'] );
		}

		$data = self::product_loop( $atts );

		wp_send_json_success( $data );
	}

	/**
	 * Product grid shortcode
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function product_grid( $atts ) {
		$atts = shortcode_atts( array(
			'per_page'      => 15,
			'type'          => 'recent',
			'category'      => '',
			'columns'       => 4,
			'load_more'     => false,
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );


		$css_class = array(
			'nova-product-grid',
			'nova-products',
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		if ( $atts['load_more'] ) {
			$css_class[] = 'loadmore-enabled';
		}

		return sprintf(
			'<div class="nova-product-grid nova-products %s">%s</div>',
			esc_attr( trim( implode( ' ', $css_class ) ) ),
			self::product_loop( $atts )
		);
	}

	/**
	 * Product grid filterable
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function product_carousel( $atts ) {
		$atts = shortcode_atts( array(
			'per_page'      => 15,
			'columns'       => 4,
			'type'          => 'recent',
			'category'      => '',
			'autoplay'      => 5000,
			'loop'          => false,
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );


		$css_class = array(
			'nova-product-carousel',
			'nova-products',
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		return sprintf(
			'<div class="%s" data-columns="%s" data-autoplay="%s" data-loop="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $atts['columns'] ),
			esc_attr( $atts['autoplay'] ),
			esc_attr( $atts['loop'] ),
			self::product_loop( $atts )
		);
	}

	/**
	 * Product grid filterable
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function product_tabs( $atts ) {
		$atts = shortcode_atts( array(
			'layout'        => 'grid',
			'per_page'      => 15,
			'columns'       => 4,
			'filter'        => 'category',
			'filter_type'   => 'isotope',
			'filter_style'  => 1,
			'category'      => '',
			'load_more'     => false,
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-product-grid',
			'nova-product-tabs',
			'nova-products',
			'nova-products-filterable',
			$atts['layout'],
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		if ( $atts['filter'] ) {
			$css_class[] = 'filterable';
			$css_class[] = 'filter-by-' . $atts['filter'];
			$css_class[] = 'filter-type-' . $atts['filter_type'];
		}

		if ( $atts['load_more'] ) {
			$css_class[] = 'loadmore-enabled';
		}

		$filter = array();

		if ( 'category' == $atts['filter'] ) {
			if ( empty( $atts['category'] ) ) {
				$categories = get_terms( 'product_cat' );
			} else {
				$categories = get_terms( array(
					'taxonomy' => 'product_cat',
					'slug'     => explode( ',', trim( $atts['category'] ) ),
				) );
			}

			if ( $categories && ! is_wp_error( $categories ) ) {
				if ( 'isotope' == $atts['filter_type'] ) {
					$filter[] = '<li data-filter="*" class=" active">' . esc_html__( 'All', 'nova' ) . '</li>';
				} else {
					$atts['category'] = $categories[0]->slug; // Prepare for product_loop only
				}

				foreach ( $categories as $index => $category ) {
					$filter[] = sprintf(
						'<li data-filter=".product_cat-%s" class=" %s">%s</li>',
						esc_attr( $category->slug ),
						'ajax' == $atts['filter_type'] && ! $index ? 'active' : '',
						esc_html( $category->name )
					);
				}
			}
		} elseif ( 'group' == $atts['filter'] ) {
			$atts['type'] = 'best_sellers'; // Prepare for product_loop only

			if ( 'isotope' == $atts['filter_type'] ) {
				$filter[] = '<li data-filter="*" class=" active">' . esc_html__( 'Best Sellers', 'nova' ) . '</li>';
			} else {
				$filter[] = '<li data-filter=".best_sellers" class=" active">' . esc_html__( 'Best Sellers', 'nova' ) . '</li>';
			}
			$filter[] = '<li data-filter=".new" class="">' . esc_html__( 'New Products', 'nova' ) . '</li>';
			$filter[] = '<li data-filter=".sale" class="">' . esc_html__( 'Sales Products', 'nova' ) . '</li>';
		}

		$loading = '
			<div class="products-loading-overlay">
				<span class="loading-icon">
					<span class="bubble"><span class="dot"></span></span>
					<span class="bubble"><span class="dot"></span></span>
					<span class="bubble"><span class="dot"></span></span>
				</span>
			</div>';

		return sprintf(
			'<div class="%s" data-columns="%s" data-per_page="%s" data-load_more="%s" data-nonce="%s">%s<div class="products-grid">%s%s</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $atts['columns'] ),
			esc_attr( $atts['per_page'] ),
			esc_attr( $atts['load_more'] ),
			esc_attr( wp_create_nonce( 'nova_get_products' ) ),
			empty( $filter ) ? '' : '<div class="product-filter"><ul class="filter filter_style-' . $atts['filter_style'] . '">' . implode( "\n\t", $filter ) . '</ul></div>',
			'ajax' == $atts['filter_type'] ? $loading : '',
			self::product_loop( $atts )
		);
	}

	/**
	 * Post grid
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function post_grid( $atts ) {
		$atts = shortcode_atts( array(
			'style'         => '1',
			'per_page'      => 3,
			'columns'       => 3,
			'category'      => '',
			'hide_meta'     => false,
			'hide_excerpt'  => false,
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-post-grid',
			'post-grid',
			'style-' . $atts['style'],
			'columns-' . $atts['columns'],
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$output = array();

		$args = array(
			'post_type'              => 'post',
			'posts_per_page'         => $atts['per_page'],
			'ignore_sticky_posts'    => 1,
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		if ( $atts['category'] ) {
			$args['category_name'] = trim( $atts['category'] );
		}

		$posts = new WP_Query( $args );

		if ( ! $posts->have_posts() ) {
			return '';
		}

		$column_class = 'col-sm-6 col-md-' . ( 12 / absint( $atts['columns'] ) );
		
		$thumbnail_size = 'nova-blog-grid';
		$post_count = 0;

		while ( $posts->have_posts() ) : $posts->the_post();
			$post_class = get_post_class( $column_class );
			$thumbnail = $meta = $meta_category = $summary = '';
			$post_count ++;
			
			if ( $atts['style'] == 2 && $post_count % 3 == 1 ) {
				$thumbnail_size = 'nova-blog-grid2';
			}
			
			$categories = get_the_terms( get_the_ID(), 'category' );
			$category = $categories[0];
			if( isset( $category ) )
				$meta_category = '<div class="post-grid-category">' . esc_attr( $category->name ) . '</div>';
			if ( $atts['style'] == 3 ) {
				$meta_category = '<div class="post-grid-category"><a href="' . get_term_link( $category->term_id ) . '">' . esc_attr( $category->name ) . '</a></div>';
			}

			if ( ! $atts['hide_meta'] ) {
				ob_start();
				nova_entry_meta();
				$meta = ob_get_contents();
				ob_clean();
				ob_end_flush();
				
				if ( $atts['style'] == 3 ) {
					$meta = $meta_category . '<span class="entry-date">' . get_the_date( 'm/d/Y' ) . '</span>';
				}
			}

			$summary = sprintf(
				'<div class="post-summary">
					%s
					<h3 class="entry-title"><a href="%s" rel="bookmark">%s</a></h3>
					%s
					%s
					<a class="read-more" href="%s">%s</a>
				</div>',
				$atts['style'] == 2 ? $meta_category : '',
				esc_url( get_permalink() ),
				get_the_title(),
				$atts['hide_meta'] ? '' : '<div class="entry-meta">' . $meta . '</div>',
				$atts['hide_excerpt'] ? '' : '<div class="entry-summary">' . get_the_excerpt() . '</div>',
				esc_url( get_permalink() ),
				esc_html__( 'Read the story', 'nova' )
			);

			if ( has_post_thumbnail() ) :
				$icon = '';

				if ( 'gallery' == get_post_format() ) {
					$icon = '<span class="format-icon"><svg viewBox="0 0 20 20"><use xlink:href="#gallery"></use></svg></span>';
				} elseif ( 'video' == get_post_format() ) {
					$icon = '<span class="format-icon"><svg viewBox="0 0 20 20"><use xlink:href="#play"></use></svg></span>';
				}

				$thumbnail = sprintf(
					'<div class="post-thumbnail"><a href="%s"></a>%s%s%s%s</div>',
					esc_url( get_permalink() ),
					get_the_post_thumbnail( get_the_ID(), $thumbnail_size ),
					$icon,
					( $atts['style'] == 2 || $atts['style'] == 3 ) ? '' : $meta_category,
					$atts['style'] == 2 ? $summary : ''
				);
			endif;

			$output[] = sprintf(
				'<div class="%s">
					%s
					%s
				</div>',
				esc_attr( implode( ' ', $post_class ) ),
				$thumbnail,
				$atts['style'] == 2 ? '' : $summary
			);
		endwhile;

		wp_reset_postdata();

		return sprintf(
			'<div class="%s">
				<div class="nova-posts row">%s</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}

	/**
	 * Count down
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function countdown( $atts ) {
		$atts = shortcode_atts( array(
			'style'			=> '1',
			'date'          => '',
			'text_align'    => 'left',
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		if ( empty( $atts['date'] ) ) {
			return '';
		}

		$css_class = array(
			'nova-countdown',
			'style-' . $atts['style'],
			'text-' . $atts['text_align'],
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$output   = array();
		$output[] = sprintf( '<div class="timers" data-date="%s">', esc_attr( $atts['date'] ) );
		$output[] = sprintf( '<div class="timer-day box"><span class="time day"></span><span class="title">%s</span></div>', esc_html__( 'Days', 'nova' ) );
		$output[] = sprintf( '<div class="timer-hour box"><span class="time hour"></span><span class="title">%s</span></div>', esc_html__( 'Hours', 'nova' ) );
		$output[] = sprintf( '<div class="timer-min box"><span class="time min"></span><span class="title">%s</span></div>', esc_html__( 'Mins', 'nova' ) );
		$output[] = sprintf( '<div class="timer-secs box"><span class="time secs"></span><span class="title">%s</span></div>', esc_html__( 'Sec', 'nova' ) );
		$output[] = '</div>';

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}
	
	/**
	 * Stats Counter
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function stats_counter( $atts ) {
		$atts = shortcode_atts( array(
			'icon_pos'               => 'top',
			'icon_style'             => 'normal',
			'icon_size'              => 30,
			'icon_width'             => 30,
			'icon_padding'           => 0,
			'icon_color_type'        => 'simple',
			'icon_color'             => '',
			'icon_color2'            => '',
			'icon_bg_type'           => 'simple',
			'icon_bg'                => '',
			'icon_bg2'               => '',
			'icon_type'              => 'fontawesome',
			'icon_fontawesome'       => 'fa fa-info-circle',
			'icon_openiconic'        => '',
			'icon_typicons'          => '',
			'icon_entypo'            => '',
			'icon_linecons'          => '',
			'icon_monosocial'        => 'vc-mono vc-mono-fivehundredpx',
			'icon_nova_icon_outline' => 'nova-icon design-2_image',
			'icon_nucleo_glyph'      => 'nc-icon-glyph nature_bear',
			'icon_image_id'          => '',
			'title'                  => '',
			'value'                  => 1250,
			'prefix'                 => '',
			'suffix'                 => '',
			'spacer'                 => 'none',
			'spacer_position'        => 'top',
			'line_style'             => 'solid',
			'line_width'             => '',
			'line_height'            => 1,
			'line_color'             => '',
			'el_class'               => '',
			'title__typography'      => '',
			'use_gfont_title'        => '',
			'title_font'             => '',
			'title_fz'               => '',
			'title_lh'               => '',
			'title_color'            => '',
			'value__typography'      => '',
			'use_gfont_value'        => '',
			'value_font'             => '',
			'value_fz'               => '',
			'value_lh'               => '',
			'value_color'            => '',
			'css'                    => '',
			'el_id'                  => '',
			'el_class_value'         => '',
			'el_class_heading'       => ''
		), $atts, 'nova_' . __FUNCTION__ );
		
		$unique_id = ( ! empty( $atts['el_id'] ) ) ? esc_attr( $atts['el_id'] ) : uniqid( 'nova_stats_counter_' );
		
		$css_class = array(
			'nova-stats-counter',
			'icon-pos-' . $atts['icon_pos'],
			nova_shortcode_custom_css_class( $atts['css'], '' ),
			Novaworks_Shortcodes_Helper::getExtraClass( $atts['el_class'] )
		);
		if($atts['spacer'] == 'line') {
			$css_class[] = 'spacer-position-' . $atts['spacer_position'];
		}

		$_icon_html = '';
		$nova_fix_css = array();
		$wapIconCssStyle = $iconCssStyle = array();
		
		if( $atts['icon_pos'] != 'none' ) {

			if( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
				vc_icon_element_fonts_enqueue( $atts['icon_type'] );
			}

			if( ! empty( $atts['icon_size'] ) ) {
				$iconCssStyle[] = 'line-height:' . $atts['icon_size'] . 'px';
				$iconCssStyle[] = 'font-size:' . $atts['icon_size'] . 'px';
				if( ! empty( $atts['icon_width'] ) && $atts['icon_style'] != 'normal' ){
					$iconCssStyle[] = 'width:' . $atts['icon_width'] . 'px';
					$iconCssStyle[] = 'height:' . $atts['icon_width'] . 'px';
				} else {
					$iconCssStyle[] = 'width:' . $atts['icon_size'] . 'px';
					$iconCssStyle[] = 'height:' . $atts['icon_size'] . 'px';
				}
			}
			if( ! empty( $atts['icon_width'] ) && $atts['icon_style'] != 'normal' ) {
				$__padding_tmp = intval( ( $atts['icon_width'] - $atts['icon_size'] ) / 2 );
				$iconCssStyle[] = 'padding:' . $__padding_tmp . 'px';
			}
			if( $atts['icon_style'] != 'normal' && ! empty( $atts['icon_bg'] ) ) {
				if( $atts['icon_bg_type'] == 'gradient' ) {
					$css_class[] = 'iconbg-gradient';
					$wapIconCssStyle[] = 'background-color: ' . $atts['icon_bg'];
					$wapIconCssStyle[] = 'background-image: -webkit-linear-gradient( left, ' . $atts['icon_bg'] . ' 0%, ' . $atts['icon_bg2'] . ' 50%,' . $atts['icon_bg'] . ' 100% )';
					$wapIconCssStyle[] = 'background-image: linear-gradient( to right, ' . $atts['icon_bg'] . ' 0%, ' . $atts['icon_bg2'] . ' 50%,' . $atts['icon_bg'] . ' 100% )';

				} else {
					$wapIconCssStyle[] = 'background-color: ' . $atts['icon_bg'];
				}

			}
			if( $atts['icon_style'] == 'advanced' ) {
				$wapIconCssStyle[] = 'border-radius:' . $atts['icon_border_radius'] . 'px';
				$iconCssStyle[] = 'border-radius:' . $atts['icon_border_radius'] . 'px';
				if( ! empty( $atts['icon_padding'] ) ){
					$wapIconCssStyle[] = 'padding:'. intval( $atts['icon_padding'] ) . 'px';
				}
			}
			if( ! empty( $atts['icon_color'] ) ) {
				if( $atts['icon_color_type'] == 'gradient') {
					$iconCssStyle[] = 'color: ' . $atts['icon_color'];
					$iconCssStyle[] = 'background-image: -webkit-linear-gradient( left, ' . $atts['icon_color'] . ' 0%, ' . $atts['icon_color2'] . ' 50%,' . $atts['icon_color'] . ' 100% )';
					$iconCssStyle[] = 'background-image: linear-gradient( to right, ' . $atts['icon_color'] . ' 0%, ' . $atts['icon_color2'] . ' 50%,' . $atts['icon_color'] . ' 100% )';
					$iconCssStyle[] = '-webkit-background-clip: text';
					$iconCssStyle[] = '-webkit-text-fill-color: transparent';
					$css_class[] = 'icontext-gradient';
				}else{
					$iconCssStyle[] = 'color:' . $atts['icon_color'];
				}
			}
			if( ! empty( $atts['icon_border_style'] ) ) {
				$wapIconCssStyle[] = 'border-style:' . $atts['icon_border_style'];
				$wapIconCssStyle[] = 'border-width:' . $atts['icon_border_width'] . 'px';
				$wapIconCssStyle[] = 'border-color:' . $atts['icon_border_color'];
			}
		}

		if( $atts['icon_type'] == 'custom' ) {
			if( $__icon_html = wp_get_attachment_image( $atts['icon_image_id'], 'full') ) {
				$_icon_html = '<span>' . $__icon_html . '</span>';
			}
		}else{
			$iconClass = isset( $atts['icon_' . $atts['icon_type']] ) ? esc_attr( $atts['icon_' . $atts['icon_type']] ) : 'fa fa-info-circle';
			$_icon_html = '<span><i class="' . esc_attr( $iconClass ) . '"></i></span>';
		}

		$spacer_html = $icon_html = $value_html = $title_html = '';
		
		if( $atts['spacer'] == 'line' ) {
			$lineHtmlAtts = '';
			$lineCssInline = array();
			$parentLineCssInline = array();
			$parentLineCssInline[] = 'height: ' . $atts['line_height'] . 'px';
			if( ! empty( $atts['line_width'] ) ) {
				$lineHtmlAtts = Novaworks_Shortcodes_Helper::getResponsiveMediaCss( array(
					'target'		=> '#' . $unique_id . ' .nova-line',
					'media_sizes' 	=> array(
						'width' 	=> $atts['line_width']
					)
				) );
				Novaworks_Shortcodes_Helper::renderResponsiveMediaCss( $nova_fix_css, array(
					'target'		=> '#' . $unique_id . ' .nova-line',
					'media_sizes' 	=> array(
						'width' 	=> $atts['line_width'],
					)
				));
			}
			$lineCssInline[] = 'border-style:' . $atts['line_style'];
			$lineCssInline[] = 'border-width:' . $atts['line_height'] . 'px 0 0';
			$lineCssInline[] = 'border-color:' . $atts['line_color'];
			$spacer_html = sprintf(
				'<div class="nova-separator" style="%s"><span class="nova-line js-el" style="%s" %s></span></div>',
				esc_attr( implode(';', $parentLineCssInline) ),
				esc_attr( implode(';', $lineCssInline) ),
				$lineHtmlAtts
			);
		}

		if( ! empty( $_icon_html ) ) {
			$icon_html .= '<div class="box-icon-inner ' . ( $atts['icon_type'] == 'custom' ? 'type-img' : 'type-icon' ) . '">';
				$icon_html .= '<div class="wrap-icon">';
					$icon_html .= '<div class="box-icon box-icon-style-' . $atts['icon_style'] . '">';
						$icon_html .= $_icon_html;
					$icon_html .= '</div>';
				$icon_html .= '</div>';
			$icon_html .= '</div>';
		}

		if( ! empty( $atts['title'] ) ) {
			
			$titleHtmlAtts = '';
			$titleCssInline = array();
			
			if( ! empty( $atts['title_fz'] ) || ! empty( $atts['title_lh'] ) ) {
				$titleHtmlAtts = Novaworks_Shortcodes_Helper::getResponsiveMediaCss( array(
					'target' => '#'. $unique_id.' .stats-heading',
					'media_sizes' => array(
						'font-size' => $atts['title_fz'],
						'line-height' => $atts['title_lh']
					),
				) );
				Novaworks_Shortcodes_Helper::renderResponsiveMediaCss( $nova_fix_css, array(
					'target' => '#'. $unique_id.' .stats-heading',
					'media_sizes' => array(
						'font-size' => $atts['title_fz'],
						'line-height' => $atts['title_lh']
					)
				) );
			}


			if( ! empty( $atts['title_color'] ) ) {
				$titleCssInline[] = 'color:' . $atts['title_color'];
			}
			if( ! empty( $atts['use_gfont_title'] ) ) {
				$gfont_data = Novaworks_Shortcodes_Helper::parseGoogleFontAtts( $atts['title_font'] );
				if( isset( $gfont_data['style'] ) ) {
					$titleCssInline[] = $gfont_data['style'];
				}
				if( isset( $gfont_data['font_url'] ) ) {
					wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
				}
			}
			$heading_el_class = 'stats-heading js-el'  . Novaworks_Shortcodes_Helper::getExtraClass( $atts['el_class_heading'] );
			$title_html = '<div class="box-heading"><div class="' . esc_attr( $heading_el_class ) . '" style="' . esc_attr( implode( ';', $titleCssInline ) ) . '" ' . $titleHtmlAtts . '>' . esc_html( $atts['title'] ) . '</div></div>';
		}

		if( ! empty( $atts['value'] ) ) {

			$valueHtmlAtts = '';
			$valueCssInline = array();

			if( ! empty( $atts['value_fz'] ) || ! empty( $atts['value_lh'] ) ) {
				$valueHtmlAtts = Novaworks_Shortcodes_Helper::getResponsiveMediaCss( array(
					'target' => '#'. $unique_id.' .stats-value',
					'media_sizes' => array(
						'font-size' => $atts['value_fz'],
						'line-height' => $atts['value_lh']
					)
				) );
				Novaworks_Shortcodes_Helper::renderResponsiveMediaCss( $nova_fix_css, array(
					'target' => '#'. $unique_id.' .stats-value',
					'media_sizes' => array(
						'font-size' => $atts['value_fz'],
						'line-height' => $atts['value_lh']
					)
				) );
			}

			$valueHtmlAtts .= ' data-decimal="" data-separator="" data-speed="3"';
			$valueHtmlAtts .= ' data-counterup-nums="' . esc_attr( $atts['value'] ) . '"';
			$valueHtmlAtts .= ' data-value-prefix="' . esc_attr( $atts['prefix'] ) . '"';
			$valueHtmlAtts .= ' data-value-suffix="' . esc_attr( $atts['suffix'] ) . '"';

			if( ! empty( $atts['value_color'] ) ) {
				$valueCssInline[] = 'color:' . $atts['value_color'];
			}
			if( ! empty( $atts['use_gfont_value'] ) ) {
				$gfont_data = Novaworks_Shortcodes_Helper::parseGoogleFontAtts( $atts['value_font'] );
				if( isset( $gfont_data['style'] ) ) {
					$valueCssInline[] = $gfont_data['style'];
				}
				if( isset( $gfont_data['font_url'] ) ) {
					wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
				}
			}
			$value_el_class = 'stats-value ' . Novaworks_Shortcodes_Helper::getExtraClass( $atts['el_class_value'] );
			$value_html = '<div class="' . esc_attr( $value_el_class ) . '" style="' . esc_attr( implode( ';', $valueCssInline ) ) . '" ' . $valueHtmlAtts . '>' . esc_attr( $atts['value'] ) . '</div>';
		}

		switch( $atts['spacer_position'] ) {
			case 'top';
				$value_html = $spacer_html . $value_html;
				break;
			case 'bottom';
				$title_html .= $spacer_html;
				break;
			case 'middle';
				$value_html .= $spacer_html;
				break;
		}
		
		$customCss = '';
		if( ! empty( $iconCssStyle ) || ! empty( $wapIconCssStyle ) ) {
			$customCss .= '<span data-nova_component="InsertCustomCSS" class="js-el hidden">';
			if( ! empty( $wapIconCssStyle ) ) {
				$customCss .= '#' . $unique_id . '.nova-stats-counter .wrap-icon .box-icon{' . implode( ';', $wapIconCssStyle ) . '}';
			}
			if( ! empty( $iconCssStyle ) ) {
				$customCss .= '#' . $unique_id . '.nova-stats-counter .wrap-icon .box-icon span{' . implode( ';', $iconCssStyle ) . '}';
			}
			$customCss .= '</span>';
		}
		
		return sprintf(
			'<div id="%s" class="%s">
				<div class="element-inner">
					%s
					%s
					%s
				</div>
			</div>
			%s
			%s',
			esc_attr( $unique_id ),
			esc_attr( implode( ' ', $css_class ) ),
			( ( $atts['icon_pos'] == 'top' || $atts['icon_pos'] == 'left' ) ? '<div class="box-icon-' . esc_attr( $atts['icon_pos'] ) . '">' . $icon_html . '</div>' : '' ),
			'<div class="box-icon-des">' . $value_html . $title_html . '</div>',
			( ( $atts['icon_pos'] == 'right' ) ? '<div class="box-icon-right">' . $icon_html . '</div>' : '' ),
			$customCss,
			Novaworks_Shortcodes_Helper::renderResponsiveMediaStyleTags( $nova_fix_css )
		);
		
	}

	/**
	 * Button
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function button( $atts ) {
		$atts = shortcode_atts( array(
			'label'         => '',
			'link'          => '',
			'style'         => 'normal',
			'size'          => 'normal',
			'align'         => 'inline',
			'color'         => 'dark',
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$attributes = array();

		$css_class = array(
			'nova-button',
			'button-type-' . $atts['style'],
			'button-color-' . $atts['color'],
			'align-' . $atts['align'],
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		if ( 'light' == $atts['style'] ) {
			$css_class[] = 'button-light line-hover';
		} else {
			$css_class[] = 'button';
			$css_class[] = $atts['size'];
			$css_class[] = 'button-' . $atts['size'];
		}

		if ( function_exists( 'vc_build_link' ) && ! empty( $atts['link'] ) ) {
			$link = vc_build_link( $atts['link'] );

			if ( ! empty( $link['url'] ) ) {
				$attributes['href'] = $link['url'];
			}

			if ( ! empty( $link['title'] ) ) {
				$attributes['title'] = $link['title'];
			}

			if ( ! empty( $link['target'] ) ) {
				$attributes['target'] = $link['target'];
			}

			if ( ! empty( $link['rel'] ) ) {
				$attributes['rel'] = $link['rel'];
			}
		}

		$attributes['class'] = implode( ' ', $css_class );
		$attr                = array();

		foreach ( $attributes as $name => $value ) {
			$attr[] = $name . '="' . esc_attr( $value ) . '"';
		}

		$button = sprintf(
			'<%1$s %2$s>%3$s</%1$s>',
			empty( $attributes['href'] ) ? 'span' : 'a',
			implode( ' ', $attr ),
			esc_html( $atts['label'] )
		);

		if ( 'center' == $atts['align'] ) {
			return '<div class="nova-button-wrapper text-center">' . $button . '</div>';
		}

		return $button;
	}

	/**
	 * Category Banner
	 *
	 * @param string $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function category_banner( $atts, $content ) {
		$atts = shortcode_atts( array(
			'image'          => '',
			'image_position' => 'left',
			'title'          => '',
			'text_position'  => 'top-left',
			'link'           => '',
			'button_text'    => '',
			'css_animation'  => '',
			'el_class'       => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-category-banner',
			'text-position-' . $atts['text_position'],
			'image-' . $atts['image_position'],
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$link = vc_build_link( $atts['link'] );

		$src   = '';
		$image = wp_get_attachment_image_src( $atts['image'], 'full' );

		if ( $image ) {
			$src   = $image[0];
			$image = sprintf( '<img alt="%s" src="%s">',
				esc_attr( $atts['image'] ),
				esc_url( $src )
			);
		}

		return sprintf(
			'<div class="%s">
				<div class="banner-inner">
					<a href="%s" target="%s" rel="%s" class="banner-image" style="%s">%s</a>
					<div class="banner-content">
						<h2 class="banner-title">%s</h2>
						<div class="banner-text">%s</div>
						<a href="%s" target="%s" rel="%s" class="nova-button button-light line-hover active">%s</a>
					</div>
				</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_url( $link['url'] ),
			esc_attr( $link['target'] ),
			esc_attr( $link['rel'] ),
			$src ? 'background-image: url(' . esc_url( $src ) . ');' : '',
			$image,
			esc_html( $atts['title'] ),
			$content,
			esc_url( $link['url'] ),
			esc_attr( $link['target'] ),
			esc_attr( $link['rel'] ),
			esc_html( $atts['button_text'] )
		);
	}

	/**
	 * Product
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function product( $atts, $content ) {
		$atts = shortcode_atts( array(
			'style'         => 1,
			'image'         => '',
			'title'         => '',
			'price'         => '',
			'regular_price' => '',
			'link'          => '',
			'css_animation' => '',
			'el_class'      => '',
			'css'           => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-product',
			'style-' . $atts['style'],
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
			nova_shortcode_custom_css_class( $atts['css'] )
		);

		$image = wp_get_attachment_image_src( $atts['image'], 'full' );
		$src   = $image[0];
		$image = $src ? sprintf( '<img alt="%s" src="%s">', esc_attr( $atts['title'] ), esc_url( $image[0] ) ) : '';
		$link  = vc_build_link( $atts['link'] );

		$price = floatval( $atts['price'] );
		$regular_price = floatval( $atts['regular_price'] );
		
		if ( ! $price ) {
			$price = '';			
		} elseif ( $price && $regular_price ) {
			$price = wc_format_sale_price( $regular_price, $price );
		} else {
			$price = wc_price( $price );
		}

		return sprintf(
			'<div class="%s">
				<div class="product-image" style="%s">
					%s
				</div>
				<div class="product-info">
					<h3 class="product-title">%s</h3>
					<div class="product-price">
						<span class="price">%s</span>
						<span class="button">%s</span>
					</div>
					<div class="product-desc">%s</div>
				</div>
				<a href="%s" target="%s" rel="%s" class="overlink">%s</a>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$src ? 'background-image: url(' . esc_url( $src ) . ');' : '',
			$image,
			esc_html( $atts['title'] ),
			$price,
			esc_html__( 'Add to cart', 'nova' ),
			$content,
			esc_url( $link['url'] ),
			esc_url( $link['target'] ),
			esc_url( $link['rel'] ),
			esc_html__( 'Add to cart', 'nova' )
		);
	}

	/**
	 * Banner 2 with buttons
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function banner2( $atts ) {
		$atts = shortcode_atts( array(
			'image'         => '',
			'image_size'    => '',
			'buttons'       => '',
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-banner2',
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);
		$image     = '';

		if ( $atts['image'] ) {
			$size = apply_filters( 'nova_banner_size', $atts['image_size'], $atts, 'nova_banner2' );

			if ( function_exists( 'wpb_getImageBySize' ) ) {
				$image = wpb_getImageBySize( array(
					'attach_id'  => $atts['image'],
					'thumb_size' => $size,
				) );

				$image = $image['thumbnail'];
			} else {
				$size_array = explode( 'x', $size );
				$size       = count( $size_array ) == 1 ? $size : $size_array;

				$image = wp_get_attachment_image_src( $atts['image'], $size );

				if ( $image ) {
					$image = sprintf( '<img alt="%s" src="%s">',
						esc_attr( $atts['image'] ),
						esc_url( $image[0] )
					);
				}
			}
		}

		$buttons        = vc_param_group_parse_atts( $atts['buttons'] );
		$buttons_output = array();
		foreach ( (array) $buttons as $index => $button ) {
			$link = vc_build_link( $button['link'] );

			$buttons_output[] = sprintf(
				'<a href="%s" target="%s" title="%s" rel="%s" class="banner-button banner-button-%s">%s</a>',
				esc_url( $link['url'] ),
				esc_attr( $link['target'] ),
				esc_attr( $link['title'] ),
				esc_attr( $link['rel'] ),
				esc_attr( $index + 1 ),
				esc_html( $button['text'] )
			);
		}

		return sprintf(
			'<div class="%s">%s<div class="banner-buttons">%s</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			$image,
			implode( '', $buttons_output )
		);
	}

	/**
	 * Banner 3
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function banner3( $atts ) {
		$atts = shortcode_atts( array(
			'image'         => '',
			'image_size'    => '',
			'text'          => '',
			'text_align'    => 'left',
			'link'          => '',
			'button_text'   => '',
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-banner3',
			'text-align-' . $atts['text_align'],
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);
		$link      = vc_build_link( $atts['link'] );
		$image     = '';

		if ( $atts['image'] ) {
			$size = apply_filters( 'nova_banner_size', $atts['image_size'], $atts, 'nova_banner3' );

			if ( function_exists( 'wpb_getImageBySize' ) ) {
				$image = wpb_getImageBySize( array(
					'attach_id'  => $atts['image'],
					'thumb_size' => $size,
				) );

				$image = $image['thumbnail'];
			} else {
				$size_array = explode( 'x', $size );
				$size       = count( $size_array ) == 1 ? $size : $size_array;

				$image = wp_get_attachment_image_src( $atts['image'], $size );

				if ( $image ) {
					$image = sprintf( '<img alt="%s" src="%s">',
						esc_attr( $atts['text'] ),
						esc_url( $image[0] )
					);
				}
			}
		}

		return sprintf(
			'<div class="%s">
				<a href="%s" target="%s" rel="%s" title="%s">
					%s
					<span class="banner-content">
						<span class="banner-text">%s</span>
						<span class="nova-button button-light line-hover active">%s</span>
					</span>
				</a>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_url( $link['url'] ),
			esc_attr( $link['target'] ),
			esc_attr( $link['rel'] ),
			esc_attr( $link['title'] ),
			$image,
			esc_html( $atts['text'] ),
			esc_html( $atts['button_text'] )
		);
	}

	/**
	 * Banner 4
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function banner4( $atts, $content ) {
		$atts = shortcode_atts( array(
			'image'            => '',
			'image_size'       => 'full',
			'align_vertical'   => 'top',
			'align_horizontal' => 'left',
			'link'             => '',
			'button_text'      => '',
			'scheme'           => 'dark',
			'css_animation'    => '',
			'el_class'         => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-banner4',
			'horizontal-align-' . $atts['align_horizontal'],
			'vertical-align-' . $atts['align_vertical'],
			$atts['scheme'] . '-scheme',
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);
		$link      = vc_build_link( $atts['link'] );
		$image     = '';

		if ( $atts['image'] ) {
			$size = apply_filters( 'nova_banner_size', $atts['image_size'], $atts, 'nova_banner4' );

			if ( function_exists( 'wpb_getImageBySize' ) ) {
				$image = wpb_getImageBySize( array(
					'attach_id'  => $atts['image'],
					'thumb_size' => $size,
				) );

				$image = $image['thumbnail'];
			} else {
				$size_array = explode( 'x', $size );
				$size       = count( $size_array ) == 1 ? $size : $size_array;

				$image = wp_get_attachment_image_src( $atts['image'], $size );

				if ( $image ) {
					$image = sprintf( '<img alt="%s" src="%s">',
						esc_attr( $atts['text'] ),
						esc_url( $image[0] )
					);
				}
			}
		}

		$content = function_exists( 'wpb_js_remove_wpautop' ) ? wpb_js_remove_wpautop( $content, true ) : $content;

		return sprintf(
			'<div class="%s">
				%s
				<div class="banner-content">
					<span class="banner-text">%s</span>
					%s
				</div>
				%s
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$image,
			do_shortcode( $content ),
			$atts['button_text'] ? '<span class="nova-button button-light line-hover active">' . esc_html( $atts['button_text'] ) . '</span>' : '',
			$link['url'] ? '<a href="' . esc_url( $link['url'] ) . '" target="' . esc_attr( $link['target'] ) . '" rel="' . esc_attr( $link['rel'] ) . '" title="' . esc_attr( $link['title'] ) . '">' . esc_html__( 'View detail', 'nova' ) . '</a>' : ''
		);
	}

	/**
	 * Banner grid 4
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function banner_grid_4( $atts, $content ) {
		$atts = shortcode_atts( array(
			'reverse'  => 'no',
			'el_class' => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array( 'nova-banner-grid-4', $atts['el_class'] );

		if ( 'yes' == $atts['reverse'] ) {
			$css_class[] = 'reverse-order';
		}

		// Reset banner counter
		self::$current_banner = 1;

		add_filter( 'nova_banner_size', array( __CLASS__, 'banner_grid_4_banner_size' ) );
		$content = do_shortcode( $content );
		remove_filter( 'nova_banner_size', array( __CLASS__, 'banner_grid_4_banner_size' ) );

		return '<div class="' . esc_attr( implode( ' ', $css_class ) ) . '">' . $content . '</div>';
	}

	/**
	 * Banner grid 5
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function banner_grid_5( $atts, $content ) {
		$atts = shortcode_atts( array(
			'el_class' => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array( 'nova-banner-grid-5', $atts['el_class'] );

		// Reset banner counter
		self::$current_banner = 1;

		add_filter( 'nova_banner_size', array( __CLASS__, 'banner_grid_5_banner_size' ) );
		$content = do_shortcode( $content );
		remove_filter( 'nova_banner_size', array( __CLASS__, 'banner_grid_5_banner_size' ) );

		return '<div class="' . esc_attr( implode( ' ', $css_class ) ) . '">' . $content . '</div>';
	}

	/**
	 * Banner grid 6
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function banner_grid_6( $atts, $content ) {
		$atts = shortcode_atts( array(
			'reverse'  => 'no',
			'el_class' => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array( 'nova-banner-grid-6', $atts['el_class'] );

		if ( 'yes' == $atts['reverse'] ) {
			$css_class[] = 'reverse-order';
		}

		// Reset banner counter
		self::$current_banner = 1;

		add_filter( 'nova_banner_size', array( __CLASS__, 'banner_grid_6_banner_size' ) );
		$content = do_shortcode( $content );
		remove_filter( 'nova_banner_size', array( __CLASS__, 'banner_grid_6_banner_size' ) );

		return '<div class="' . esc_attr( implode( ' ', $css_class ) ) . '">' . $content . '</div>';
	}

	/**
	 * Chart
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function chart( $atts ) {
		$atts = shortcode_atts( array(
			'value'         => 100,
			'size'          => 200,
			'thickness'     => 8,
			'label_source'  => 'auto',
			'label'         => '',
			'color'         => '#6dcff6',
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-chart',
			'nova-chart-' . $atts['value'],
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$label = 'custom' == $atts['label_source'] ? $atts['label'] : '<span class="unit">%</span>' . esc_html( $atts['value'] );

		return sprintf(
			'<div class="%s" data-value="%s" data-size="%s" data-thickness="%s" data-fill="%s">
				<div class="text" style="color: %s">%s</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( intval( $atts['value'] ) / 100 ),
			esc_attr( $atts['size'] ),
			esc_attr( $atts['thickness'] ),
			esc_attr( json_encode( array( 'color' => $atts['color'] ) ) ),
			esc_attr( $atts['color'] ),
			wp_kses_post( $label )
		);
	}

	/**
	 * Message Box
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function message_box( $atts, $content ) {
		$atts = shortcode_atts( array(
			'type'          => 'success',
			'closeable'     => false,
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-message-box',
			$atts['type'],
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		if ( $atts['closeable'] ) {
			$css_class[] = 'closeable';
		}

		$icon = str_replace( array( 'info', 'danger' ), array( 'information', 'error' ), $atts['type'] );

		return sprintf(
			'<div class="%s">
				<svg viewBox="0 0 20 20" class="message-icon"><use xlink:href="#%s"></use></svg>
				<div class="box-content">%s</div>
				%s
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $icon ),
			$content,
			$atts['closeable'] ? '<a class="close" href="#"><svg viewBox="0 0 14 14"><use xlink:href="#close-delete-small"></use></svg></a>' : ''
		);
	}

	/**
	 * Icon Box
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function icon_box( $atts, $content ) {
		$atts = shortcode_atts( array(
			'icon_type'              => 'fontawesome',
			'icon_fontawesome'       => 'fa fa-adjust',
			'icon_openiconic'        => 'vc-oi vc-oi-dial',
			'icon_typicons'          => 'typcn typcn-adjust-brightness',
			'icon_entypo'            => 'entypo-icon entypo-icon-note',
			'icon_linecons'          => 'vc_li vc_li-heart',
			'icon_monosocial'        => 'vc-mono vc-mono-fivehundredpx',
			'icon_nova_icon_outline' => 'nova-icon nature_bear',
			'icon_nucleo_glyph'      => 'nc-icon-glyph nature_bear',
			'icon_material'          => 'vc-material vc-material-cake',
			'image'                  => '',
			'number'                 => '',
			'style'                  => 'normal',
			'icon_pos'               => 'default',
			'icon_size'              => 50,
			'icon_width'             => 50,
			'icon_padding'           => 0,
			'icon_color_type'        => 'simple',
			'icon_color'             => '',
			'icon_h_color'           => '',
			'icon_color2'            => '',
			'icon_h_color2'          => '',
			'icon_bg_type'           => 'simple',
			'icon_bg'                => '',
			'icon_h_bg'              => '',
			'icon_bg2'               => '',
			'icon_h_bg2'             => '',
			'icon_border_style'      => '',
			'icon_border_width'      => 1,
			'icon_border_color'      => '',
			'icon_h_border_color'    => '',
			'icon_border_radius'     => 500,
			'title__typography'      => '',
			'use_gfont_title'        => '',
			'title_font'             => '',
			'title_fz'               => '',
			'title_lh'               => '',
			'title_color'            => '',
			'desc__typography'       => '',
			'use_gfont_desc'         => '',
			'desc_font'              => '',
			'desc_fz'                => '',
			'desc_lh'                => '',
			'desc_color'             => '',
			'title'                  => esc_html__( 'I am Icon Box', 'nova' ),
			'read_more'              => 'none',
			'link'                   => '',
			'css_animation'          => '',
			'el_class'               => '',
			'css'                    => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-icon-box',
			'icon-type-' . $atts['icon_type'],
			'icon-style-' . $atts['style'],
			'icon-pos-' . $atts['icon_pos'],
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
			nova_shortcode_custom_css_class( $atts['css'] )
		);

		if ( 'image' == $atts['icon_type'] ) {
			$image = wp_get_attachment_image_src( $atts['image'], 'full' );
			$icon  = $image ? sprintf( '<span><img alt="%s" src="%s" /></span>', esc_attr( $atts['title'] ), esc_url( $image[0] ) ) : '';
		} elseif( 'number' == $atts['icon_type'] ) {
			$icon = '<span><b class="type-number">'. $atts['number'] .'</b></span>';
		} else {
			vc_icon_element_fonts_enqueue( $atts['icon_type'] );
			$icon = '<span><i class="' . esc_attr( $atts[ 'icon_' . $atts['icon_type'] ] ) . '"></i></span>';
		}
		
		$link = $a_link_open = $a_link_close = '';
		if( ! empty( $atts['link'] ) ) {
			$link = vc_build_link( $atts['link'] );
			$a_link_open = '<a href="' . esc_attr( $link['url'] ) . '"' . ( $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : '' ) . ( $link['rel'] ? ' rel="' . esc_attr( $link['rel'] ) . '"' : '' ) . ( $link['title'] ? ' title="' . esc_attr( $link['title'] ) . '"' : '' ) . '>';
			$a_link_close = '</a>';
		}

		if( empty( $atts['icon_h_color'] ) ){
			$atts['icon_h_color'] = $atts['icon_color'];
		}
		if( empty( $atts['icon_h_color2'] ) ){
			$atts['icon_h_color2'] = $atts['icon_color2'];
		}
		if( empty( $atts['icon_h_bg'] ) ){
			$atts['icon_h_bg'] = $atts['icon_bg'];
		}
		if( empty( $atts['icon_h_bg2'] ) ){
			$atts['icon_h_bg2'] = $atts['icon_bg2'];
		}
		if( empty( $atts['icon_h_border_color'] ) ){
			$atts['icon_h_border_color'] = $atts['icon_border_color'];
		}
		
		$unique_id = uniqid( 'nova_icon_box_' );
		
		$nova_fix_css = array();
		$wapIconCssStyle = $iconCssStyle = array();
		$wapIconHoverCssStyle = $iconHoverCssStyle = array();
		
		if( ! empty( $atts['icon_size'] ) ){
			$iconCssStyle[] = 'line-height:' . $atts['icon_size'] . 'px';
			$iconCssStyle[] = 'font-size:' . $atts['icon_size'] . 'px';
			if( ! empty( $atts['icon_width'] ) && $atts['style'] != 'normal' ){
				$iconCssStyle[] = 'width:' . $atts['icon_width'] . 'px';
				$iconCssStyle[] = 'height:' . $atts['icon_width'] . 'px';
			}else{
				$iconCssStyle[] = 'width:' . $atts['icon_size'] . 'px';
				$iconCssStyle[] = 'height:' . $atts['icon_size'] . 'px';
			}
		}
		if( ! empty( $atts['icon_width'] ) && $atts['style'] != 'normal' ){
			$__padding_tmp = intval( ( $atts['icon_width'] - $atts['icon_size'] ) / 2 );
			$iconCssStyle[] = 'padding:' . $__padding_tmp . 'px';
		}
		if( $atts['style'] != 'normal' && ! empty( $atts['icon_bg'] ) ){
			if( $atts['icon_bg_type'] == 'gradient' ){
				$css_class[] = 'iconbg-gradient';
				$wapIconCssStyle[] = 'background-color: ' . $atts['icon_bg'];
				$wapIconCssStyle[] = 'background-image: -webkit-linear-gradient(left, ' . $atts['icon_bg'] . ' 0%, ' . $atts['icon_bg2'] . ' 50%,' . $atts['icon_bg'] . ' 100%)';
				$wapIconCssStyle[] = 'background-image: linear-gradient(to right, ' . $atts['icon_bg'] . ' 0%, ' . $atts['icon_bg2'] . ' 50%,' . $atts['icon_bg'] . ' 100%)';

				$wapIconHoverCssStyle[] = 'background-color: ' . $atts['icon_h_bg'];
				$wapIconHoverCssStyle[] = 'background-image: -webkit-linear-gradient(left, ' . $atts['icon_h_bg'] . ' 0%, ' . $atts['icon_h_bg2'] . ' 50%,' . $atts['icon_h_bg'] . ' 100%)';
				$wapIconHoverCssStyle[] = 'background-image: linear-gradient(to right, ' . $atts['icon_h_bg'] . ' 0%, ' . $atts['icon_h_bg2'] . ' 50%,' . $atts['icon_h_bg'] . ' 100%)';

			}else{
				$wapIconCssStyle[] = 'background-color: ' . $atts['icon_bg'] ;
				$wapIconHoverCssStyle[] = 'background-color: ' . $atts['icon_h_bg'] ;
			}

		}
		if( $atts['style'] == 'advanced' ){
			$wapIconCssStyle[] = 'border-radius:' . $atts['icon_border_radius'] . 'px';
			$iconCssStyle[] = 'border-radius:' . $atts['icon_border_radius'] . 'px';
			if( ! empty( $atts['icon_padding'] ) ){
				$wapIconCssStyle[] = 'padding:'. intval( $atts['icon_padding'] ) . 'px';
			}
		}
		if( ! empty( $atts['icon_color'] ) ){
			if( $atts['icon_color_type'] == 'gradient' ){
				$iconCssStyle[] = 'color: ' . $atts['icon_color'];
				$iconCssStyle[] = 'background-image: -webkit-linear-gradient(left, ' . $atts['icon_color'] . ' 0%, ' . $atts['icon_color2'] . ' 50%,' . $atts['icon_color'] . ' 100%)';
				$iconCssStyle[] = 'background-image: linear-gradient(to right, ' . $atts['icon_color'] . ' 0%, ' . $atts['icon_color2'] . ' 50%,' . $atts['icon_color'] . ' 100%)';
				$iconCssStyle[] = '-webkit-background-clip: text';
				$iconCssStyle[] = 'background-clip: text';
				$iconCssStyle[] = '-webkit-text-fill-color: transparent';
				$css_class[] = 'icontext-gradient';

				$iconHoverCssStyle[] = 'color: ' . $atts['icon_h_color'];
				$iconHoverCssStyle[] = 'background-image: -webkit-linear-gradient(left, ' . $atts['icon_h_color'] . ' 0%, ' . $atts['icon_h_color2'] . ' 50%,' . $atts['icon_h_color'] . ' 100%)';
				$iconHoverCssStyle[] = 'background-image: linear-gradient(to right, ' . $atts['icon_h_color'] . ' 0%, ' . $atts['icon_h_color2'] . ' 50%,' . $atts['icon_h_color'] . ' 100%)';
			}else{
				$iconCssStyle[] = 'color:' . $atts['icon_color'];
				$iconHoverCssStyle[] = 'color:' . $atts['icon_h_color'];
			}
		}
		if( ! empty( $atts['icon_border_style'] ) ){
			$wapIconCssStyle[] = 'border-style:' . $atts['icon_border_style'];
			$wapIconCssStyle[] = 'border-width:' . $atts['icon_border_width'] . 'px';
			$wapIconCssStyle[] = 'border-color:' . $atts['icon_border_color'];
			$wapIconHoverCssStyle[] = 'border-color:' . $atts['icon_h_border_color'];
		}

		$titleHtmlAtts = '';
		$titleCssInline = array();
		if( ! empty( $atts['title'] ) ){
			if( ! empty( $atts['title_fz'] ) || ! empty( $atts['title_lh'] ) ){
				$titleHtmlAtts = Novaworks_Shortcodes_Helper::getResponsiveMediaCss( array(
					'target' => '#' . $unique_id.' .box-title',
					'media_sizes' => array(
						'font-size' => $atts['title_fz'],
						'line-height' => $atts['title_lh']
					),
				));
				Novaworks_Shortcodes_Helper::renderResponsiveMediaCss( $nova_fix_css, array(
					'target' => '#' . $unique_id.' .box-title',
					'media_sizes' => array(
						'font-size' => $atts['title_fz'],
						'line-height' => $atts['title_lh']
					),
				));
			}

			if( ! empty( $atts['title_color'] ) ){
				$titleCssInline[] = 'color:' . $atts['title_color'];
			}
			if( ! empty($atts['use_gfont_title'] ) ){
				$gfont_data = Novaworks_Shortcodes_Helper::parseGoogleFontAtts( $atts['title_font'] );
				if(isset($gfont_data['style'])){
					$titleCssInline[] = $gfont_data['style'];
				}
				if(isset($gfont_data['font_url'])){
					wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
				}
			}
		}
		
		$descHtmlAtts = '';
		$descCssInline = array();
		if( ! empty( $content ) ){
			if( ! empty( $atts['desc_fz'] ) || ! empty( $atts['desc_lh'] ) ){
				$descHtmlAtts = Novaworks_Shortcodes_Helper::getResponsiveMediaCss( array(
					'target' => '#' . $unique_id . ' .box-content',
					'media_sizes' => array(
						'font-size' => $atts['desc_fz'],
						'line-height' => $atts['desc_lh']
					),
				));
				Novaworks_Shortcodes_Helper::renderResponsiveMediaCss( $nova_fix_css, array(
					'target' => '#' . $unique_id . ' .box-content',
					'media_sizes' => array(
						'font-size' => $atts['desc_fz'],
						'line-height' => $atts['desc_lh']
					),
				));
			}

			
			if( ! empty( $atts['desc_color'] ) ){
				$descCssInline[] = 'color:' . $atts['desc_color'];
			}
			if( ! empty( $atts['use_gfont_title'] ) ){
				$gfont_data = Novaworks_Shortcodes_Helper::parseGoogleFontAtts( $atts['desc_font'] );
				if( isset( $gfont_data['style'] ) ){
					$descCssInline[] = $gfont_data['style'];
				}
				if( isset( $gfont_data['font_url'] ) ){
					wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
				}
			}
		}

		$customCss = '';
		if( ! empty( $iconCssStyle ) || ! empty( $wapIconCssStyle ) || ! empty( $iconHoverCssStyle ) || ! empty( $wapIconHoverCssStyle ) ){
			$customCss .= '<span data-nova_component="InsertCustomCSS" class="js-el hidden">';
			if( ! empty( $wapIconCssStyle ) ){
				$customCss .= '#' . $unique_id . '.nova-icon-box .box-icon{' . implode( ';', $wapIconCssStyle ) . '}';
			}
			if( ! empty( $iconCssStyle ) ){
				$customCss .= '#' . $unique_id . '.nova-icon-box .box-icon span{' . implode( ';', $iconCssStyle ) . '}';
			}
			if( ! empty( $wapIconHoverCssStyle ) ){
				$customCss .= '#' . $unique_id . '.nova-icon-box:hover .box-icon{' . implode( ';', $wapIconHoverCssStyle ) . '}';
			}
			if( ! empty( $iconHoverCssStyle ) ){
				$customCss .= '#' . $unique_id . '.nova-icon-box:hover .box-icon span{' . implode( ';', $iconHoverCssStyle ) . '}';
			}
			$customCss .= '</span>';
		}
		
		return sprintf(
			'<div id="%s" class="%s">
				<div class="box-icon">%s</div>
				<h3 class="js-el nova-unit-responsive box-title" %s %s>%s</h3>
				<div class="js-el nova-unit-responsive box-content" %s %s>%s</div>
			</div>
			%s
			%s',
			esc_attr( $unique_id ),
			esc_attr( implode( ' ', $css_class ) ),
			( ( $atts['read_more'] == 'icon' && $a_link_open != '' ) ? $a_link_open : '' ) . $icon . ( ( $atts['read_more'] == 'icon' && $a_link_open != '' ) ? $a_link_close : '' ),
			( ! empty( $titleCssInline ) ) ? 'style="'. esc_attr( implode( ';', $titleCssInline ) ).'"' : '',
			$titleHtmlAtts,			
			( ( $atts['read_more'] == 'title' && $a_link_open != '' ) ? $a_link_open : '' ) . esc_html( $atts['title'] ) . ( ( $atts['read_more'] == 'title' && $a_link_open != '' ) ? $a_link_close : '' ),
			( ! empty( $descCssInline ) ) ? 'style="'. esc_attr( implode( ';', $descCssInline ) ).'"' : '',
			$descHtmlAtts,
			Novaworks_Shortcodes_Helper::remove_js_autop($content, true),
			$customCss,
			Novaworks_Shortcodes_Helper::renderResponsiveMediaStyleTags( $nova_fix_css )
		);
		
	}

	/**
	 * Custom Heading
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function heading( $atts, $content ) {
		$atts = shortcode_atts( array(
			'title'                => '',
			'tag'                  => 'h2',
			'alignment'            => 'left',
			'spacer'               => 'none',
			'spacer_position'      => 'top',
			'line_style'           => 'solid',
			'line_width'           => '',
			'line_height'          => 1,
			'line_color'           => '',
			'el_class'             => '',
			'title_class'          => '',
			'subtitle_class'       => '',
			'line_class'           => '',
			'title__typography'    => '',
			'use_gfont_title'      => '',
			'title_font'           => '',
			'title_fz'             => '',
			'title_lh'             => '',
			'title_color'          => '',
			'subtitle__typography' => '',
			'use_gfont_subtitle'   => '',
			'subtitle_font'        => '',
			'subtitle_fz'          => '',
			'subtitle_lh'          => '',
			'subtitle_color'       => '',
			'css' => ''
		), $atts, 'nova_' . __FUNCTION__ );
		
		$css_class = array(
			'nova-headings',
			'text-' . $atts['alignment'],
			$atts['el_class'],
			nova_shortcode_custom_css_class( $atts['css'] )
		);
		
		$unique_id = uniqid( 'nova_heading_' );
		$nova_fix_css = array();
		$spacer_html = '';
		$heading_html = '';
		$subheading_html = '';
		$title_class = 'js-el heading-tag nova-unit-responsive' . Novaworks_Shortcodes_Helper::getExtraClass( $atts['title_class'] );
		$subtitle_class = 'js-el subheading-tag nova-unit-responsive' . Novaworks_Shortcodes_Helper::getExtraClass( $atts['subtitle_class'] );
		
		if( $atts['spacer'] == 'line' ){
			if( $atts['spacer_position'] == 'left' || $atts['spacer_position'] == 'right' ){
				$css_class[] = 'spacer-position-separator sp_at-' . $atts['spacer_position'];
			}
			else{
				$css_class[] = 'spacer-position-' . $atts['spacer_position'];
			}
		}
		

		if( $atts['spacer'] == 'line' ){
			$lineHtmlAtts = '';
			$lineCssInline = array();
			$parentLineCssInline = array();
			$parentLineCssInline[] = 'height:' . $atts['line_height'] . 'px';
			if( $atts['spacer_position'] == 'separator' || $atts['spacer_position'] == 'left' || $atts['spacer_position'] == 'right' ){
				$parentLineCssInline[] = 'margin-top: 0px';
			}
			if( ! empty( $atts['line_width'] ) ){
				$lineHtmlAtts = Novaworks_Shortcodes_Helper::getResponsiveMediaCss( array(
					'target'		=> '#' . $unique_id . ' .nova-line',
					'media_sizes' 	=> array(
						'width' 	=> $atts['line_width'],
					)
				) );

				Novaworks_Shortcodes_Helper::renderResponsiveMediaCss( $nova_fix_css, array(
					'target'		=> '#' . $unique_id . ' .nova-line',
					'media_sizes' 	=> array(
						'width' 	=> $atts['line_width'],
					)
				) );
			}
			$lineCssInline[] = 'border-style:' . $atts['line_style'];
			$lineCssInline[] = 'border-width:' . $atts['line_height'] . 'px 0 0';
			$lineCssInline[] = 'border-color:' . $atts['line_color'];
			$spacer_html = sprintf(
				'<div class="nova-separator %s" style="%s"><span class="nova-line js-el nova-unit-responsive" style="%s" %s></span></div>',
				esc_attr( Novaworks_Shortcodes_Helper::getExtraClass( $atts['line_class'] ) ),
				esc_attr( implode(';', $parentLineCssInline) ),
				esc_attr( implode(';', $lineCssInline) ),
				$lineHtmlAtts
			);
		}
		
		if( ! empty( $atts['title'] ) ){
			$titleHtmlAtts = '';
			$titleCssInline = array();
			if( ! empty( $atts['title_fz'] ) || ! empty( $atts['title_lh'] ) ){
				$titleHtmlAtts = Novaworks_Shortcodes_Helper::getResponsiveMediaCss( array(
					'target' => '#'. $unique_id . ' .heading-tag',
					'media_sizes' => array(
						'font-size' => $atts['title_fz'],
						'line-height' => $atts['title_lh']
					),
				));
				Novaworks_Shortcodes_Helper::renderResponsiveMediaCss( $nova_fix_css, array(
					'target' => '#'. $unique_id . ' .heading-tag',
					'media_sizes' => array(
						'font-size' => $atts['title_fz'],
						'line-height' => $atts['title_lh']
					),
				));
			}
			if( ! empty( $atts['title_color'] ) ){
				$titleCssInline[] = 'color:' . $atts['title_color'];
			}
			if( ! empty( $atts['use_gfont_title'] ) ){
				$gfont_data = Novaworks_Shortcodes_Helper::parseGoogleFontAtts( $atts['title_font'] );
				if( isset( $gfont_data['style'] ) ){
					$titleCssInline[] = $gfont_data['style'];
				}
				if( isset( $gfont_data['font_url'] ) ){
					wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
				}
			}
			$heading_html = sprintf(
				'<%1$s class="%2$s" style="%3$s" %4$s>%5$s</%1$s>',
				$atts['tag'],
				$title_class,
				esc_attr( implode(';', $titleCssInline) ),
				$titleHtmlAtts,
				esc_html( $atts['title'] )
			);
		}
		
		if( ! empty( $content ) ){
			$subtitleHtmlAtts = '';
			$subtitleCssInline = array();
			if( ! empty( $atts['subtitle_fz'] ) || ! empty( $atts['subtitle_lh'] ) ){
				$subtitleHtmlAtts = Novaworks_Shortcodes_Helper::getResponsiveMediaCss( array(
					'target' => '#'. $unique_id.' .subheading-tag',
					'media_sizes' => array(
						'font-size' => $atts['subtitle_fz'],
						'line-height' => $atts['subtitle_lh']
					),
				) );
				Novaworks_Shortcodes_Helper::renderResponsiveMediaCss( $nova_fix_css, array(
					'target' => '#'. $unique_id.' .subheading-tag',
					'media_sizes' => array(
						'font-size' => $atts['subtitle_fz'],
						'line-height' => $atts['subtitle_lh']
					),
				) );
			}
			if( ! empty( $atts['subtitle_color'] ) ){
				$subtitleCssInline[] = 'color:' . $atts['subtitle_color'];
			}
			if( ! empty( $atts['use_gfont_subtitle'] ) ){
				$gfont_data = Novaworks_Shortcodes_Helper::parseGoogleFontAtts( $atts['subtitle_font'] );
				if( isset( $gfont_data['style'] ) ){
					$subtitleCssInline[] = $gfont_data['style'];
				}
				if( isset( $gfont_data['font_url'] ) ){
					wp_enqueue_style( 'vc_google_fonts_' . $gfont_data['font_family'], $gfont_data['font_url'] );
				}
			}
			$subheading_html = sprintf(
				'<div class="%1$s" style="%2$s" %3$s>%4$s</div>',
				$subtitle_class,
				esc_attr( implode(';', $subtitleCssInline) ),
				$subtitleHtmlAtts,
				Novaworks_Shortcodes_Helper::remove_js_autop( $content, true )
			);
		}

		return sprintf(
			'<div id="%s" class="%s">
			%s
			%s
			%s
			%s
			%s
			%s
			%s
			</div>
			',
			esc_attr( $unique_id ),
			esc_attr( implode( ' ', $css_class ) ),
			( $atts['spacer_position'] == 'top' ? $spacer_html : '' ),
			( ( $atts['spacer_position'] == 'separator' || $atts['spacer_position'] == 'left' || $atts['spacer_position'] == 'right' ) ? '<div class="heading-with-line">' . $spacer_html : '' ),
			( empty( $heading_html ) ? $subheading_html : $heading_html ),
			( ( $atts['spacer_position'] == 'separator' || $atts['spacer_position'] == 'left' || $atts['spacer_position'] == 'right' ) ? $spacer_html . '</div>' : '' ),			
			( $atts['spacer_position'] == 'middle' ? $spacer_html : '' ),
			( empty( $heading_html ) ? '' : $subheading_html ),
			( $atts['spacer_position'] == 'bottom' ? $spacer_html : '' )
		);
		
	}
	
	/**
	 * Contact Space
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function divider( $atts ){
		$atts = shortcode_atts( array(
			'height' => '',
			'el_class' => ''
		), $atts, 'nova_' . __FUNCTION__ );
		
		$spaceHtmlAtts = '';
		$nova_fix_css = array();
		$unique_id = uniqid('nova_divider_');
		$css_class = 'js-el nova-divider nova-unit-responsive ' . Novaworks_Shortcodes_Helper::getExtraClass( $atts['el_class'] );
		
		if( ! empty( $atts['height'] ) ){
			$default_style = Novaworks_Shortcodes_Helper::getColumnFromShortcodeAtts( $atts['height'] );
			$spaceHtmlAtts = Novaworks_Shortcodes_Helper::getResponsiveMediaCss( array(
				'target'		=> '#' . $unique_id,
				'media_sizes' 	=> array(
					'padding-top' 	=> $atts['height']
				)
			) );
			Novaworks_Shortcodes_Helper::renderResponsiveMediaCss( $nova_fix_css, array(
				'target'		=> '#' . $unique_id,
				'media_sizes' 	=> array(
					'padding-top' 	=> $atts['height']
				)
			) );
		}
		
		return sprintf(
			'<div id="%s" class="%s" %s></div>
			%s',
			esc_attr( $unique_id ),
			esc_attr( $css_class ),
			$spaceHtmlAtts,
			Novaworks_Shortcodes_Helper::renderResponsiveMediaStyleTags( $nova_fix_css )
		);
		
	}
	
	/**
	 * Pricing Table
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function pricing_table( $atts ) {
		$atts = shortcode_atts( array(
			'name'             => '',
			'price'            => '',
			'currency'         => '$',
			'recurrence'       => esc_html__( 'Per Month', 'nova' ),
			'features'         => '',
			'button_text'      => esc_html__( 'Get Started', 'nova' ),
			'button_link'      => '',
			'color'            => '#6dcff6',
			'package_featured' => '',
			'css_animation'    => '',
			'el_class'         => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-pricing-table',
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);
		
		if($atts['package_featured'] == 'yes'){
			$css_class[] = 'is_box_featured';
		}

		$features = vc_param_group_parse_atts( $atts['features'] );
		$list     = array();
		foreach ( $features as $feature ) {
			$list[] = sprintf( '<li><span class="feature-name">%s</span><span class="feature-value">%s</span></li>', $feature['name'], $feature['value'] );
		}

		$features = $list ? '<ul>' . implode( '', $list ) . '</ul>' : '';
		$link     = vc_build_link( $atts['button_link'] );

		return sprintf(
			'<div class="%s" data-color="%s">
				<div class="table-header" style="background-color: %s">
					<h3 class="plan-name">%s</h3>
					<div class="pricing"><span class="currency">%s</span>%s</div>
					<div class="recurrence">%s</div>
				</div>
				<div class="table-content">%s</div>
				<div class="table-footer">
					<a href="%s" target="%s" rel="%s" title="%s" class="button" style="background-color: %s">%s</a>
				</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $atts['color'] ),
			esc_attr( $atts['color'] ),
			esc_html( $atts['name'] ),
			esc_html( $atts['currency'] ),
			esc_html( $atts['price'] ),
			esc_html( $atts['recurrence'] ),
			$features,
			esc_url( $link['url'] ),
			esc_attr( $link['target'] ),
			esc_attr( $link['rel'] ),
			esc_attr( $link['title'] ),
			esc_attr( $atts['color'] ),
			esc_html( $atts['button_text'] )
		);
	}

	/**
	 * Google Map
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function map( $atts, $content ) {
		$atts = shortcode_atts( array(
			'api_key'       => '',
			'marker'        => '',
			'address'       => '',
			'lat'           => '',
			'lng'           => '',
			'width'         => '100%',
			'height'        => '625px',
			'zoom'          => 15,
			'color'         => '',
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		if ( empty( $atts['api_key'] ) ) {
			return esc_html__( 'Google map requires API Key in order to work.', 'nova' );
		}

		if ( empty( $atts['address'] ) && empty( $atts['lat'] ) && empty( $atts['lng'] ) ) {
			return esc_html__( 'No address', 'nova' );
		}

		if ( ! empty( $atts['address'] ) ) {
			$coordinates = self::get_coordinates( $atts['address'], $atts['api_key'] );
		} else {
			$coordinates = array(
				'lat' => $atts['lat'],
				'lng' => $atts['lng'],
			);
		}

		if ( ! empty( $coordinates['error'] ) ) {
			return $coordinates['error'];
		}

		$css_class = array(
			'nova-map',
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$style = array();
		if ( $atts['width'] ) {
			$style[] = 'width: ' . $atts['width'];
		}

		if ( $atts['height'] ) {
			$style[] = 'height: ' . intval( $atts['height'] ) . 'px';
		}

		$marker = '';

		if ( $atts['marker'] ) {
			if ( filter_var( $atts['marker'], FILTER_VALIDATE_URL ) ) {
				$marker = $atts['marker'];
			} else {
				$attachment_image = wp_get_attachment_image_src( intval( $atts['marker'] ), 'full' );
				$marker           = $attachment_image ? $attachment_image[0] : '';
			}
		}

		wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $atts['api_key'] );

		return sprintf(
			'<div class="%s" style="%s" data-zoom="%s" data-lat="%s" data-lng="%s" data-color="%s" data-marker="%s">%s</div>',
			implode( ' ', $css_class ),
			implode( ';', $style ),
			absint( $atts['zoom'] ),
			esc_attr( $coordinates['lat'] ),
			esc_attr( $coordinates['lng'] ),
			esc_attr( $atts['color'] ),
			esc_attr( $marker ),
			$content
		);
	}

	/**
	 * Testimonial
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function testimonial( $atts, $content ) {
		$atts = shortcode_atts( array(
			'style'         => 1,
			'image'         => '',
			'name'          => '',
			'company'       => '',
			'align'         => 'center',
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-testimonial',
			'testimonial-style-' . $atts['style'],
			'testimonial-align-' . $atts['align'],
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		$image = '';
		if ( $atts['image'] ) {
			if ( function_exists( 'wpb_getImageBySize' ) ) {
				$image = wpb_getImageBySize( array(
					'attach_id'  => $atts['image'],
					'thumb_size' => '160x160',
				) );

				$image = $image['thumbnail'];
			} else {
				$image = wp_get_attachment_image_src( $atts['image'], 'large' );

				if ( $image ) {
					$image = sprintf( '<img alt="%s" src="%s" width="160" height="160">',
						esc_attr( $atts['image'] ),
						esc_url( $image[0] )
					);
				}
			}
		}

		$authors = array();
		
		if ( $atts['name'] )
			$authors[] = '<span class="name">' . esc_html( $atts['name'] ) . '</span>';

		if ( $atts['company'] )
			$authors[] = '<span class="company">' . esc_html( $atts['company'] ) . '</span>';
		if ( $atts['style'] != 2 ) {
			return sprintf(
				'<div class="%s">
					%s
					<div class="testimonial-entry">
						<div class="testimonial-content">%s</div>
						<div class="testimonial-author">%s</div>
					</div>
				</div>',
				esc_attr( implode( ' ', $css_class ) ),
				$image ? '<div class="author-photo">' . $image . '</div>' : '',
				$content,
				implode( ', ', $authors )
			);
		} else {
			return sprintf(
				'<div class="%s">
					<div class="testimonial-entry">
						<div class="testimonial-content">%s</div>
					</div>
					%s
					<div class="testimonial-author">%s</div>
				</div>',
				esc_attr( implode( ' ', $css_class ) ),
				$content,
				$image ? '<div class="author-photo">' . $image . '</div>' : '',
				implode( ' ', $authors )
			);
		}
	}

	/**
	 * Partners
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function partners( $atts ) {
		$atts = shortcode_atts( array(
			'source'              => 'media_library',
			'images'              => '',
			'custom_srcs'         => '',
			'image_size'          => 'full',
			'external_img_size'   => '',
			'custom_links'        => '',
			'custom_links_target' => '_self',
			'layout'              => 'bordered',
			'column'              => '',
			'css_animation'       => '',
			'el_class'            => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$unique_id = uniqid( 'nova_partners_' );
		$column_array = Novaworks_Shortcodes_Helper::getColumnFromShortcodeAtts( $atts['column'] );
		$responsive_column = isset( $column_array ) ? $column_array : array( 'lg'=> 2, 'md'=> 2, 'mb'=> 1 );		
		
		$css_class     = array(
			'nova-partners',
			$atts['layout'] . '-layout',
			$atts['el_class'],
			'grid-x',
			'grid-padding-x',
			'grid-padding-y',
			'small-up-' . $responsive_column['mb'],
			'medium-up-' . $responsive_column['md'],
			'large-up-' . $responsive_column['lg']
		);
		$css_animation = self::get_css_animation( $atts['css_animation'] );
		$images        = $logos = array();
		$custom_links  = explode( ',', vc_value_from_safe( $atts['custom_links'] ) );
		$default_src   = vc_asset_url( 'vc/no_image.png' );

		switch ( $atts['source'] ) {
			case 'media_library':
				$images = explode( ',', $atts['images'] );
				break;

			case 'external_link':
				$images = vc_value_from_safe( $atts['custom_srcs'] );
				$images = explode( ',', $images );

				break;
		}

		foreach ( $images as $i => $image ) {
			$thumbnail = '';

			switch ( $atts['source'] ) {
				case 'media_library':
					if ( $image > 0 ) {
						$img       = wpb_getImageBySize( array(
							'attach_id'  => $image,
							'thumb_size' => $atts['image_size'],
						) );
						$thumbnail = $img['thumbnail'];
					} else {
						$thumbnail = '<img src="' . $default_src . '" />';
					}
					break;

				case 'external_link':
					$image      = esc_attr( $image );
					$dimensions = vcExtractDimensions( $atts['external_img_size'] );
					$hwstring   = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';
					$thumbnail  = '<img ' . $hwstring . ' src="' . $image . '" />';
					break;
			}

			if ( empty( $custom_links[ $i ] ) ) {
				$logo = '<span class="partner-logo">' . $thumbnail . '</span>';
			} else {
				$logo = sprintf( '<a href="%s" target="%s" class="partner-logo">%s</a>', esc_url( $custom_links[ $i ] ), esc_attr( $atts['custom_links_target'] ), $thumbnail );
			}

			$logos[] = '<div class="cell partner' . esc_attr( $css_animation ) . '">' . $logo . '</div>';
		}

		return sprintf( '<div class="%s">%s</div>', esc_attr( implode( ' ', $css_class ) ), implode( ' ', $logos ) );
	}

	/**
	 * Contact Box
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function contact_box( $atts ) {
		$atts = shortcode_atts( array(
			'address'       => '',
			'phone'         => '',
			'fax'           => '',
			'email'         => '',
			'website'       => '',
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-contact-box',
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);
		$contact   = array();

		foreach ( array( 'address', 'phone', 'fax', 'email', 'website' ) as $info ) {
			if ( empty( $atts[ $info ] ) ) {
				continue;
			}

			$icon   = $name = '';
			$detail = esc_html( $atts[ $info ] );
			switch ( $info ) {
				case 'address':
					$name = esc_html__( 'Address', 'nova' );
					$icon = '<svg width="20" height="20" class="info-icon"><use xlink:href="#home"></use></svg>';
					break;

				case 'phone':
					$name   = esc_html__( 'Phone', 'nova' );
					$icon   = '<svg width="20" height="20" class="info-icon"><use xlink:href="#phone"></use></svg>';
					$detail = '<a href="tel:' . esc_attr( $atts[ $info ] ) . '">' . $detail . '</a>';
					break;

				case 'fax':
					$name = esc_html__( 'Fax', 'nova' );
					$icon = '<i class="info-icon fa fa-fax"></i>';
					break;

				case 'email':
					$name   = esc_html__( 'Email', 'nova' );
					$icon   = '<svg width="20" height="20" class="info-icon"><use xlink:href="#mail"></use></svg>';
					$detail = '<a href="mailto:' . esc_attr( $atts[ $info ] ) . '">' . $detail . '</a>';
					break;

				case 'website':
					$name   = esc_html__( 'Website', 'nova' );
					$icon   = '<i class="info-icon fa fa-globe"></i>';
					$detail = '<a href="' . esc_url( $atts[ $info ] ) . '" target="_blank" rel="nofollow">' . $detail . '</a>';
					break;
			}

			$contact[] = sprintf(
				'<div class="contact-info">
					%s
					<span class="info-name">%s</span>
					<span class="info-value">%s</span>
				</div>',
				$icon,
				$name,
				$detail
			);
		}

		return sprintf( '<div class="%s">%s</div>', esc_attr( implode( ' ', $css_class ) ), implode( ' ', $contact ) );
	}

	/**
	 * Info List
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function info_list( $atts ) {
		$atts = shortcode_atts( array(
			'info' => urlencode( json_encode( array(
				array(
					'icon' => 'fa fa-home',
					'label' => esc_html__( 'Address', 'nova' ),
					'value' => '9606 North MoPac Expressway',
				),
				array(
					'icon' => 'fa fa-phone',
					'label' => esc_html__( 'Phone', 'nova' ),
					'value' => '+1 248-785-8545',
				),
				array(
					'icon' => 'fa fa-fax',
					'label' => esc_html__( 'Fax', 'nova' ),
					'value' => '123123123',
				),
				array(
					'icon' => 'fa fa-envelope',
					'label' => esc_html__( 'Email', 'nova' ),
					'value' => 'nova@uix.store',
				),
				array(
					'icon' => 'fa fa-globe',
					'label' => esc_html__( 'Website', 'nova' ),
					'value' => 'http://uix.store',
				),
			) ) ),
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		if ( function_exists( 'vc_param_group_parse_atts' ) ) {
			$info = (array) vc_param_group_parse_atts( $atts['info'] );
		} else {
			$info = json_decode( urldecode( $atts['info'] ), true );
		}

		$css_class = array(
			'nova-info-list',
			$atts['el_class'],
		);

		$animation = self::get_css_animation( $atts['css_animation'] );

		$list = array();
		foreach ( $info as $item ) {
			$list[] = sprintf(
				'<li class="%s">
					<i class="info-icon %s"></i>
					<span class="info-name">%s</span>
					<span class="info-value">%s</span>
				</li>',
				$animation,
				$item['icon'],
				$item['label'],
				$item['value']
			);
		}

		if ( ! $list ) {
			return '';
		}

		return sprintf( '<div class="%s"><ul>%s</ul></div>', esc_attr( implode( ' ', $css_class ) ), implode( '', $list ) );
	}

	/**
	 * FAQ
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function faq( $atts, $content ) {
		$atts = shortcode_atts( array(
			'title'         => esc_html__( 'Question content goes here', 'nova' ),
			'open'          => 'false',
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-faq',
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		if ( 'true' == $atts['open'] ) {
			$css_class[] = 'open';
		}

		return sprintf(
			'<div class="%s">
				<div class="question">
					<span class="question-label">%s</span>
					<span class="question-icon"><span class="toggle-icon"></span></span>
					<span class="question-title">%s</span>
				</div>
				<div class="answer"><span class="answer-label">%s</span>%s</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_html__( 'Question', 'nova' ),
			esc_html( $atts['title'] ),
			esc_html__( 'Answer', 'nova' ),
			$content
		);
	}

	/**
	 * Team member
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function team_member( $atts ) {
		$atts = shortcode_atts( array(
			'image'         => '',
			'image_size'    => 'full',
			'name'          => '',
			'job'           => '',
			'facebook'      => '',
			'twitter'       => '',
			'google'        => '',
			'pinterest'     => '',
			'linkedin'      => '',
			'youtube'       => '',
			'instagram'     => '',
			'css_animation' => '',
			'el_class'      => '',
		), $atts, 'nova_' . __FUNCTION__ );

		$css_class = array(
			'nova-team-member',
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);

		if ( $atts['image'] ) {
			if ( function_exists( 'wpb_getImageBySize' ) ) {
				$image = wpb_getImageBySize( array(
					'attach_id'  => $atts['image'],
					'thumb_size' => $atts['image_size'],
				) );

				$image = $image['thumbnail'];
			} else {
				$image = wp_get_attachment_image_src( $atts['image'], $atts['image_size'] );

				if ( $image ) {
					$image = sprintf( '<img src="%s" alt="%s" width="%s" height="%s">',
						esc_url( $image[0] ),
						esc_attr( $atts['name'] ),
						esc_attr( $image[1] ),
						esc_attr( $image[2] )
					);
				}
			}
		} else {
			$image = plugins_url( 'assets/images/man-placeholder.png', dirname( dirname( __FILE__ ) ) );
			$image = sprintf( '<img src="%s" alt="%s" width="360" height="430">',
				esc_url( $image ),
				esc_attr( $atts['name'] )
			);
		}

		$socials = array( 'facebook', 'twitter', 'google', 'pinterest', 'linkedin', 'youtube', 'instagram' );
		$links   = array();

		foreach ( $socials as $social ) {
			if ( empty( $atts[ $social ] ) ) {
				continue;
			}

			$icon = str_replace( array( 'google', 'pinterest', 'youtube' ), array(
				'google-plus',
				'pinterest-p',
				'youtube-play',
			), $social );

			$links[] = sprintf( '<a href="%s" target="_blank"><i class="fa fa-%s"></i></a>', esc_url( $atts[ $social ] ), esc_attr( $icon ) );
		}

		return sprintf(
			'<div class="%s">
				<div class="nova-team-member__item-thumbnail">
					%s
					<div class="item--social member-social">%s</div>
				</div>
				<div class="nova-team-member__item-info">
					<h4 class="nova-team-member__item-title">%s</h4>
					<span class="nova-team-member__item-role">%s</span>
				</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$image,
			implode( '', $links ),
			esc_html( $atts['name'] ),
			esc_html( $atts['job'] )
		);
	}
	
	/**
	 * Image with hotspots
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function image_with_hotspots( $atts, $content ) {
		$atts = shortcode_atts( array(
			'product_viewer' => false,
			'image'          => '',
			'preview'        => '',
			'el_class'       => '',
			'color'          => 'primary',
			'hotspot_icon'   => 'plus_sign',
			'start_number'   => 1,
			'tooltip'        => 'hover',
			'tooltip_shadow' => 'none',
			'animation'      => ''
		), $atts, 'nova_' . __FUNCTION__ );
		
		$style = 'color_pulse';

		$image_el = null;
		$image_class = 'no-img';

		if( ! empty( $atts['image'] ) ) {
			if( ! preg_match( '/^\d+$/', $atts['image'] ) ) {
				$image_el = '<img src="' . $atts['image'] . '" alt="hotspot image" />';
			} else {
				$image_el = wp_get_attachment_image( $atts['image'], 'full' );
			}
			$image_class = null;
		}


		$atts['el_class'] = Novaworks_Shortcodes_Helper::getExtraClass( $image_class . $atts['el_class'] );

		$css_class = array(
			'nova-image-with-hotspots',
			$atts['el_class']
		);
		$GLOBALS['nova-image_hotspot-icon'] = $atts['hotspot_icon'];
		$GLOBALS['nova-image_hotspot-count'] = ( int ) $atts['start_number'];
		$GLOBALS['nova-image_hotspot-tooltip-func'] = $atts['tooltip'];
		$GLOBALS['nova-image_hotspot-product-viewer'] = $atts['product_viewer'];
		
		return sprintf(
			'<div class="%s" data-style="%s" data-product-viewer="%s" data-hotspot-icon="%s" data-size="medium" data-color="%s" data-tooltip-func="%s" data-tooltip_shadow="%s" data-animation="%s">
				%s
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $style ),
			esc_attr( $atts['product_viewer'] ),
			esc_attr( $atts['hotspot_icon'] ),
			esc_attr( $atts['color'] ),
			esc_attr( $atts['tooltip'] ),
			esc_attr( $atts['tooltip_shadow'] ),
			esc_attr( $atts['animation'] ),
			$image_el . Novaworks_Shortcodes_Helper::remove_js_autop( $content )
		);
	}
	
	/**
	 * Hotspot
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function hotspot( $atts, $content ) {
		$atts = shortcode_atts( array(
			'product_viewer' => null,
			'product_id'     => '',
			'top'            => '',
			'left'           => '',
			'position'       => '',
			'title'          => ''
		), $atts, 'nova_' . __FUNCTION__ );
		
		$styles = array();
		$styles[] = 'top:' . $atts['top'];
		$styles[] = 'left:' . $atts['left'];
		
		$product_id = ( int ) $atts['product_id'];

		$hotspot_icon = '';
		if( $GLOBALS['nova-image_hotspot-icon'] == 'numerical' ) $hotspot_icon = $GLOBALS['nova-image_hotspot-count'];
		if( $GLOBALS['nova-image_hotspot-icon'] == 'custom_title' ) $hotspot_icon = $atts['title'];
		$click_class = ( $GLOBALS['nova-image_hotspot-tooltip-func'] == 'click' ) ? ' click' : null;

		$tooltip_content_class = ( empty( $content ) ) ? 'nttip empty-tip' : 'nttip';
		
		if( $GLOBALS['nova-image_hotspot-product-viewer'] == true && ( int ) $product_id > 0 ) {
			if( ! function_exists( 'wc_get_product') ) return false;
			$product = wc_get_product( $product_id );
			
			$tooltip_content_html = '
			<div class="nttip product-viewer" data-tooltip-position="' . esc_attr( $atts['position'] ) . '">
				<div class="inner">
					<div class="public-hotspot-info__product-image-holder">
						<div class="public-hotspot-info__product-image-inner">
							<a class="public-hotspot-info__product-image" target="_blank" href="'. esc_url( get_post_permalink( $product_id ) ) . '">' . $product->get_image() . '</a>
						</div>
					</div>
					<a class="public-hotspot-info__btn-buy" target="_blank" href="' . esc_url( get_post_permalink( $product_id ) ) . '">
						<span class="btn_txt">' . esc_html__( 'BUY', 'nova' ) . '</span>
						<span class="btn_ico">
							<i class="nova-icon-arrow-tail-right"></i>
						</span>
					</a>
					<div class="public-hotspot-info__first-line">
						<div class="public-hotspot-info__price">' . $product->get_price_html() . '</div>
					</div>
					<div class="public-hotspot-info__second-line">
						<a target="_blank" href="' . esc_url( get_post_permalink( $product_id ) ) . '">' . $product->get_title() . '</a>
					</div>
				</div>
			</div>
			';
		} else {
			$tooltip_content_html = '
			<div class="' . esc_attr( $tooltip_content_class ) . '" data-tooltip-position="' . esc_attr( $atts['position'] ) . '">
				<div class="inner">' . Novaworks_Shortcodes_Helper::remove_js_autop( $content ) . '</div>
			</div>
			';
		}
		
		return sprintf(
			'<div class="nova_hotspot_wrap" style="%s">
				<div class="nova_hotspot %s">
					<span>%s</span>
				</div>
				%s
			</div>',
			esc_attr( implode( ';', $styles ) ),
			esc_attr( $click_class ),
			esc_attr( $hotspot_icon ),
			$tooltip_content_html
		);
		
		$GLOBALS['nova-image_hotspot-count']++;		
	}
	
	/**
	 * Timeline Item
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function timeline_item( $atts, $content ) {
		$title = $subtitle = $time_link = $time_link_apply = $time_read_text = $el_class = $css_animation = $dot_color = '';

		$atts = shortcode_atts( array(
			'title'           => '',
			'subtitle'        => '',
			'time_link'       => '',
			'time_link_apply' => '',
			'time_read_text'  => '',
			'el_class'        => '',
			'css_animation'   => '',
			'dot_color'       => ''
		), $atts, 'nova_' . __FUNCTION__ );
		
		extract( $atts );
		
		$el_class = Novaworks_Shortcodes_Helper::getExtraClass( $el_class );
		$css_class = "timeline-block" . $el_class;
		if( ! empty( $css_animation ) && 'none' != $css_animation ) {
			$css_class .= ' wpb_animate_when_almost_visible nova-animation animated';
		}
		//parse link
		$attributes = $a_href = $a_title = $a_target = '';
		$time_link = ( '||' === $time_link ) ? '' : $time_link;
		$time_link = nova_build_link_from_atts( $time_link );
		$use_link = false;
		if ( strlen( $time_link['url'] ) > 0 ) {
			$use_link = true;
			$a_href = $time_link['url'];
			$a_title = $time_link['title'];
			$a_target = $time_link['target'];
		}

		if ( $use_link ) {
			$attributes[] = "href='" . esc_url( trim( $a_href ) ) . "'";
			$attributes[] = "title='" . esc_attr( trim( $a_title ) ) . "'";
			if ( ! empty( $a_target ) ) {
				$attributes[] = "target='" . esc_attr( trim( $a_target ) ) . "'";
			}
			$attributes = implode( ' ', $attributes );
		}

		ob_start();
		?>
		<div class="<?php echo esc_attr( $css_class ) ?>" data-animation-class="<?php echo esc_attr( $css_animation ) ?>">
			<div class="timeline-dot"<?php echo $dot_color ? ' style="background-color:' . esc_attr( $dot_color ) . '"' : ''?>></div>
			<div class="timeline-arrow"></div>
			<div class="timeline-content-wrapper">
				<div class="timeline-content">
					<?php
						if( ! empty( $subtitle ) ) {
							printf( '<div class="timeline-subtitle">%s</div>', $subtitle );
						}
					?>
					<h3 class="timeline-title">
						<?php
						if( $time_link_apply == 'title' && $use_link ) {
							echo "<a {$attributes}>{$title}</a>";
						} else {
							echo $title;
						}
						?>
					</h3>
					<div class="timeline-entry"><?php echo Novaworks_Shortcodes_Helper::remove_js_autop( $content ); ?></div>
					<?php if( $time_link_apply == 'more' && $use_link && ! empty( $time_read_text ) ) {
						echo "<div class='readmore-link'><a {$attributes}>{$time_read_text}</a></div>";
					} ?>
					<?php if( $time_link_apply == 'box' && $use_link ) {
						echo "<a {$attributes} class='readmore-box'></a>";
					} ?>
				</div>
			</div>
		</div>
		<?php
		$timeline_html = ob_get_contents();
		ob_clean();
		ob_end_flush();
		
		return $timeline_html;
	}

	/**
	 * Instagram Feed
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function instagram_feed( $atts ) {

	$instagram_token = $feed_type = $hashtag = $location_id = $user_id = $sort_by = $limit = $image_size = $el_class = $enable_carousel = $column = $item_space = $output = $image_aspect_ration = '';

	$atts = shortcode_atts( array(
		'instagram_token'     => '',
		'feed_type'           => 'user',
		'hashtag'             => '',
		'location_id'         => '',
		'user_id'             => '',
		'sort_by'             => 'none',
		'column'              => '',
		'item_space'          => 'default',
		'enable_carousel'     => '',
		'limit'               => 5,
		'image_size'          => 'thumbnail',
		'image_aspect_ration' => '11',
		'el_class'            => '',
		'slider_type'         => 'horizontal',
		'slide_to_scroll'     => 'all',
		'infinite_loop'       => '',
		'speed'               => '300',
		'autoplay'            => '',
		'autoplay_speed'      => '5000',
		'arrows'              => '',
		'arrow_style'         => 'default',
		'arrow_bg_color'      => '',
		'arrow_border_color'  => '',
		'border_size'         => '2',
		'arrow_color'         => '#333333',
		'arrow_size'          => '24',
		'next_icon'           => 'right-arrow',
		'prev_icon'           => 'left-arrow',
		'custom_nav'          => '',
		'dots'                => '',
		'dots_color'          => '#333333',
		'dots_icon'           => 'dlicon-dot7',
		'draggable'           => 'yes',
		'touch_move'          => 'yes',
		'rtl'                 => '',
		'adaptive_height'     => '',
		'pauseohover'         => '',
		'centermode'          => '',
		'autowidth'           => ''
	), $atts, 'nova_' . __FUNCTION__ );

	extract( $atts );

	$unique_id = uniqid( 'nova_instagram_feed_' );

	$loopCssClass = array( 'nova-loop', 'nova-instagram-loop' );

	$responsive_column = Novaworks_Shortcodes_Helper::getColumnFromShortcodeAtts( $column );

	$carousel_configs = false;

	if( $enable_carousel == 'yes' ) {
		$carousel_configs .= Novaworks_Shortcodes_Helper::getParamCarouselShortCode( $atts, 'column' );
		$loopCssClass[] = 'nova-instagram-slider nova-slick-slider';
	}
	else {
		$loopCssClass[] = 'grid-items';
		foreach( $responsive_column as $screen => $value ) {
			$loopCssClass[]  =  sprintf( '%s-grid-%s-items', $screen, $value );
		}
	}

	$loopCssClass[] = 'grid-space-' . $item_space;
	$loopCssClass[] = 'image-as-' . $image_aspect_ration;

	ob_start();
	?>
	<div class="nova-instagram-feeds <?php echo Novaworks_Shortcodes_Helper::getExtraClass( $el_class ); ?>" data-feed_config="<?php echo esc_attr( wp_json_encode( array(
		'get' => $feed_type,
		'tagName' => $hashtag,
		'locationId' => $location_id,
		'userId' => $user_id,
		'sortBy' => $sort_by,
		'limit' => $limit,
		'resolution' => $image_size,
		'template' => '<div class="grid-item"><div class="instagram-item"><a target="_blank" href="{{link}}" title="{{caption}}" style="background-image: url({{image}});" class="thumbnail"><span class="item--overlay"><i class="fa fa-instagram"></i></span></a><div class="instagram-info"><span class="instagram-like"><i class="fa-heart"></i>{{likes}}</span><span class="instagram-comments"><i class="fa-comments"></i>{{comments}}</span></div></div></div>'
	) ) ) ?>" data-instagram_token="<?php echo esc_attr( $instagram_token ) ?>">
		<div class="instagram-feed-inner">
			<div id="<?php echo esc_attr( $unique_id ) ?>" class="<?php echo esc_attr( implode( ' ', $loopCssClass ) ) ?>"<?php
			if( $carousel_configs ){
				echo $carousel_configs;
			}
			?>>
			</div>
			<div class="nova-shortcode-loading"><div class="content"><div class="nova-loader spinner3"><div class="dot1"></div><div class="dot2"></div><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div></div>
		</div>
	</div>

	<?php
		$instagram_html = ob_get_contents();
		ob_clean();
		ob_end_flush();
		
		return $instagram_html;
	}

	/**
	 * Portfolio Grid
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function portfolio_grid( $atts ) {
		$enable_skill_filter = $filters = $item_space = $layout = $grid_style = $list_style = $masonry_style = $category__in = $category__not_in = $post__in = $post__not_in = $orderby = $order = $per_page = $title_tag = $img_size = $column = $enable_carousel = $enable_loadmore = $per_page_loadmore = $load_more_text = $el_class =  $output = '';
		
		$atts = shortcode_atts( array(
			'layout'             => 'grid',
			'grid_style'         => '1',
			'list_style'         => '1',
			'category__in'       => '',
			'category__not_in'   => '',
			'post__in'           => '',
			'post__not_in'       => '',
			'orderby'            => '',
			'order'              => '',
			'per_page'           => -1,
			//'paged'              => '1',
			'title_tag'          => 'h3',
			'img_size'           => 'nova-portfolio',
			'column'             => '',
			'item_space'         => '30',
			'enable_carousel'    => '',
			'enable_loadmore'    => '',
			'load_more_text'     => 'Load more',
			'el_class'           => '',
			'slider_type'        => 'horizontal',
			'slide_to_scroll'    => 'all',
			'infinite_loop'      => '',
			'speed'              => '300',
			'autoplay'           => '',
			'autoplay_speed'     => '5000',
			'arrows'             => '',
			'arrow_style'        => 'default',
			'arrow_bg_color'     => '',
			'arrow_border_color' => '',
			'border_size'        => '2',
			'arrow_color'        => '#333333',
			'arrow_size'         => '24',
			'next_icon'          => 'right-arrow',
			'prev_icon'          => 'left-arrow',
			'custom_nav'         => '',
			'dots'               => '',
			'dots_color'         => '#333333',
			'dots_icon'          => 'dlicon-dot7',
			'draggable'          => 'yes',
			'touch_move'         => 'yes',
			'rtl'                => '',
			'adaptive_height'    => '',
			'pauseohover'        => '',
			'centermode'         => '',
			'autowidth'          => ''
		), $atts, 'nova_' . __FUNCTION__ );

		$excerpt_length = 15;

		if( 0 === $per_page ) $per_page = 1;

		global $paged;
		
		if( empty( $paged ) ) {
			$paged = 1;
		}

		extract( $atts );

		$css_class = array(
			'wpb_content_element',
			'nova-portfolio-grid'
		);

		$css_class[] = Novaworks_Shortcodes_Helper::getExtraClass( $el_class );
		$unique_id = uniqid( 'nova-portfolio-grid-' );

		$query_args = array(
			'post_type'           => 'portfolio',
			'post_status'		  => 'publish',
			'orderby'             => $orderby,
			'order'               => $order,
			'ignore_sticky_posts' => 1,
			'paged'               => $paged,
			'posts_per_page'      => $per_page
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
		if ( ! empty( $category__in ) && ! empty( $category__not_in ) ){
			$tax_query['relation'] = 'AND';
		}
		if ( ! empty ( $category__in ) ) {
			$tax_query[] = array(
				'taxonomy' => 'portfolio_type',
				'field'    => 'term_id',
				'terms'    => $category__in
			);
		}
		if ( ! empty ( $category__not_in ) ) {
			$tax_query[] = array(
				'taxonomy' => 'portfolio_type',
				'field'    => 'term_id',
				'terms'    => $category__not_in,
				'operator' => 'NOT IN'
			);
		}
		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = $tax_query;
		}
		if ( ! empty ( $post__in ) ) {
			$query_args['post__in'] = $post__in;
		}
		if ( ! empty ( $post__not_in ) ) {
			$query_args['post__not_in'] = $post__not_in;
		}

		$globalVar = apply_filters( 'Novaworks/global_loop_variable', 'nova_loop' );
		$globalVarTmp = ( isset( $GLOBALS[$globalVar] ) ? $GLOBALS[$globalVar] : '' );
		$globalParams = array();

		$layout_style = ${$layout . '_style'};

		$globalParams['loop_id'] = $unique_id;
		$globalParams['item_space']  = $item_space;
		$globalParams['loop_layout'] = $layout;
		$globalParams['loop_style'] = $layout_style;
		$globalParams['responsive_column'] = Novaworks_Shortcodes_Helper::getColumnFromShortcodeAtts( $column );
		$globalParams['image_size'] = Novaworks_Shortcodes_Helper::getImageSizeFormString( $img_size );
		$globalParams['title_tag'] = $title_tag;
		$globalParams['excerpt_length'] = $excerpt_length;

		if( 'grid' == $layout && $enable_carousel ) {
			$globalParams['slider_configs'] = Novaworks_Shortcodes_Helper::getParamCarouselShortCode( $atts, 'column' );
		}

		$GLOBALS[$globalVar] = $globalParams;

		$the_query = new WP_Query( $query_args );

		ob_start();

		if( $the_query->have_posts() ) {
			?><div id="<?php echo esc_attr( $unique_id ); ?>" class="<?php echo esc_attr( implode( ' ', $css_class ) ) ?>"><?php

			add_filter( 'excerpt_length', function() use ( $excerpt_length ) {
				return $excerpt_length;
			}, 1011 );

			do_action( 'Novaworks/shortcodes/before_loop/', 'shortcode', 'nova_portfolio_grid', $atts );

			$start_tpl = $end_tpl = $loop_tpl = array();

			$start_tpl[] = "template-parts/shortcode-portfolio-start-{$layout}-{$layout_style}.php";
			$start_tpl[] = "template-parts/shortcode-portfolio-start-{$layout}.php";
			$start_tpl[] = "template-parts/shortcode-portfolio-start.php";
			$loop_tpl[]  = "template-parts/shortcode-portfolio-loop-{$layout}-{$layout_style}.php";
			$loop_tpl[]  = "template-parts/shortcode-portfolio-loop-{$layout}.php";
			$loop_tpl[]  = "template-parts/shortcode-portfolio-loop.php";
			$end_tpl[]   = "template-parts/shortcode-portfolio-end-{$layout}-{$layout_style}.php";
			$end_tpl[]   = "template-parts/shortcode-portfolio-end-{$layout}.php";
			$end_tpl[]   = "template-parts/shortcode-portfolio-end.php";

			locate_template( $start_tpl, true, false );

			while( $the_query->have_posts() ) {

				$the_query->the_post();

				locate_template( $loop_tpl, true, false );

			}

			locate_template( $end_tpl, true, false );

			do_action( 'Novaworks/shortcodes/after_loop', 'shortcode', 'nova_portfolio_grid', $atts );

			remove_all_filters( 'excerpt_length', 1011 );

			if( $enable_loadmore ) {
				printf(
					'<nav class="navigation portfolio-navigation ajax-navigation" role="navigation">%s</nav>',
					get_next_posts_link( '<span class="button-text">' . esc_html( $load_more_text ) . '</span><span class="loading-icon"><span class="bubble"><span class="dot"></span></span><span class="bubble"><span class="dot"></span></span><span class="bubble"><span class="dot"></span></span></span>', $the_query->max_num_pages )
				);
			}
			?>
			</div><?php
		}
		
		$portfolio_html = ob_get_contents();
		ob_clean();
		ob_end_flush();
		
		$GLOBALS[$globalVar] = $globalVarTmp;
		wp_reset_postdata();
		
		return $portfolio_html;
	}
	
	/**
	 * Portfolio Grid
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function portfolio_masonry( $atts ) {
		$mb_column = $category__in = $category__not_in = $post__in = $post__not_in = $orderby = $order = $per_page = $enable_skill_filter = $enable_loadmore = $load_more_text = $filters = $filter_style = $masonry_style = $column_type = $column = $base_item_h = $base_item_w = $custom_item_size = $item_sizes = $title_tag = $img_size = $el_class = $item_space = '';
		
		$atts = shortcode_atts( array(
			'category__in'        => '',
			'category__not_in'    => '',
			'post__in'            => '',
			'post__not_in'        => '',
			'orderby'             => 'date',
			'order'               => 'desc',
			'per_page'            => 10,
			//'paged'               => '1',
			'enable_skill_filter' => '',
			'enable_loadmore'     => '',
			'load_more_text'      => 'Load more',
			'filters'             => '',
			'filter_style'        => '1',
			'masonry_style'       => '1',
			'title_tag'           => 'h5',
			'img_size'            => 'full',
			'item_space'          => '30',
			'column_type'         => 'default',
			'column'              => '',
			'base_item_w'         => 400,
			'base_item_h'         => 400,
			'mb_column'           => '',
			'custom_item_size'    => '',
			'item_sizes'          => '',
			'el_class'            => ''
		), $atts, 'nova_' . __FUNCTION__ );
		

		$excerpt_length = 15;

		if( 0 === $per_page ) $per_page = 1;

		global $paged;
		
		if( empty( $paged ) ) {
			$paged = 1;
		}

		extract( $atts );

		$css_class = array(
			'nova-portfolio-masonry'
		);
		$css_class[] = Novaworks_Shortcodes_Helper::getExtraClass( $el_class );
		$unique_id = uniqid( 'nova-portfolio-masonry-' );

		$query_args = array(
			'post_type'             => 'portfolio',
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
		if ( ! empty( $category__in ) && ! empty( $category__not_in ) ){
			$tax_query['relation'] = 'AND';
		}
		if ( ! empty ( $category__in ) ) {
			$tax_query[] = array(
				'taxonomy' => 'portfolio_type',
				'field'    => 'term_id',
				'terms'    => $category__in
			);
		}
		if ( ! empty ( $category__not_in ) ) {
			$tax_query[] = array(
				'taxonomy' => 'portfolio_type',
				'field'    => 'term_id',
				'terms'    => $category__not_in,
				'operator' => 'NOT IN'
			);
		}
		if ( ! empty($tax_query) ) {
			$query_args['tax_query'] = $tax_query;
		}
		if ( ! empty ( $post__in ) ) {
			$query_args['post__in'] = $post__in;
		}
		if ( ! empty ( $post__not_in ) ) {
			$query_args['post__not_in'] = $post__not_in;
		}

		$globalVar = apply_filters( 'Novaworks/global_loop_variable', 'nova_loop' );
		$globalVarTmp = ( isset($GLOBALS[$globalVar] ) ? $GLOBALS[$globalVar] : '' );
		$globalParams = array();

		$layout = 'masonry';

		$globalParams['loop_id']            = $unique_id;
		$globalParams['item_space']         = $item_space;
		$globalParams['loop_layout']        = $layout;
		$globalParams['loop_style']         = $masonry_style;
		$globalParams['responsive_column']  = Novaworks_Shortcodes_Helper::getColumnFromShortcodeAtts( $column );
		$globalParams['image_size']         = Novaworks_Shortcodes_Helper::getImageSizeFormString( $img_size );
		$globalParams['title_tag']          = $title_tag;
		$globalParams['excerpt_length']     = $excerpt_length;
		$globalParams['column_type']        = $column_type;
		$globalParams['mb_column']          = Novaworks_Shortcodes_Helper::getColumnFromShortcodeAtts( $mb_column );
		$globalParams['base_item_w']        = $base_item_w;
		$globalParams['base_item_h']        = $base_item_h;

		if( $custom_item_size == 'yes' ) {
			$_item_sizes = ( array ) vc_param_group_parse_atts( $item_sizes );
			$__new_item_sizes = array();
			if( ! empty( $_item_sizes ) ) {
				foreach( $_item_sizes as $k => $size ) {
					$__new_item_sizes[$k] = $size;
					if( ! empty( $size['s'] ) ) {
						$__new_item_sizes[$k]['s'] = Novaworks_Shortcodes_Helper::getImageSizeFormString( $size['s'] );
					}
				}
			}
			$globalParams['item_sizes'] = $__new_item_sizes;
		}

		if( $enable_skill_filter == 'yes' ) {
			$globalParams['enable_skill_filter'] = true;
			$globalParams['filters'] = $filters;
			$globalParams['filter_style'] = $filter_style;
		}

		$GLOBALS[$globalVar] = $globalParams;

		$the_query = new WP_Query($query_args);
		
		ob_start();

		if( $the_query->have_posts() ) {

			?><div id="<?php echo esc_attr( $unique_id ); ?>" class="<?php echo esc_attr( implode( ' ', $css_class ) ) ?>"><?php

			add_filter( 'excerpt_length', function() use ( $excerpt_length ) {
				return $excerpt_length;
			}, 1011 );

			do_action( 'Novaworks/shortcodes/before_loop', 'shortcode', 'nova_portfolio_masonry', $atts );

			$start_tpl = $end_tpl = $loop_tpl = array();


			$start_tpl[] = "template-parts/shortcode-portfolio-start-{$layout}-{$masonry_style}.php";
			$start_tpl[] = "template-parts/shortcode-portfolio-start-{$layout}.php";
			$start_tpl[] = "template-parts/shortcode-portfolio-start.php";
			$loop_tpl[]  = "template-parts/shortcode-portfolio-loop-{$layout}-{$masonry_style}.php";
			$loop_tpl[]  = "template-parts/shortcode-portfolio-loop-{$layout}.php";
			$loop_tpl[]  = "template-parts/shortcode-portfolio-loop.php";
			$end_tpl[]   = "template-parts/shortcode-portfolio-end-{$layout}-{$masonry_style}.php";
			$end_tpl[]   = "template-parts/shortcode-portfolio-end-{$layout}.php";
			$end_tpl[]   = "template-parts/shortcode-portfolio-end.php";

			locate_template( $start_tpl, true, false );

			while( $the_query->have_posts() ){

				$the_query->the_post();

				locate_template( $loop_tpl, true, false );

			}

			locate_template( $end_tpl, true, false );

			do_action( 'Novaworks/shortcodes/after_loop', 'shortcode', 'nova_portfolio_masonry', $atts );

			remove_all_filters( 'excerpt_length', 1011 );

			if( $enable_loadmore && $the_query->max_num_pages > $paged ) {
				printf(
					'<nav class="navigation portfolio-navigation ajax-navigation" role="navigation">%s</nav>',
					get_next_posts_link( '<span class="button-text">' . esc_html( $load_more_text ) . '</span><span class="loading-icon"><span class="bubble"><span class="dot"></span></span><span class="bubble"><span class="dot"></span></span><span class="bubble"><span class="dot"></span></span></span>', $the_query->max_num_pages )
				);
			}
			?>
			</div><?php
		}
		
		$portfolio_html = ob_get_contents();
		ob_clean();
		ob_end_flush();
		
		$GLOBALS[$globalVar] = $globalVarTmp;
		wp_reset_postdata();
		
		return $portfolio_html;
	}

	/**
	 * Portfolio Grid
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function popup_video( $atts ) {
		$atts = shortcode_atts( array(
			'icon_type'              => 'fontawesome',
			'icon_fontawesome'       => 'fa fa-adjust',
			'icon_openiconic'        => 'vc-oi vc-oi-dial',
			'icon_typicons'          => 'typcn typcn-adjust-brightness',
			'icon_entypo'            => 'entypo-icon entypo-icon-note',
			'icon_linecons'          => 'vc_li vc_li-heart',
			'icon_monosocial'        => 'vc-mono vc-mono-fivehundredpx',
			'icon_nova_icon_outline' => 'nova-icon nature_bear',
			'icon_nucleo_glyph'      => 'nc-icon-glyph nature_bear',
			'icon_material'          => 'vc-material vc-material-cake',
			'image'                  => '',
			'style'                  => 'normal',
			'icon_size'              => 50,
			'icon_width'             => 50,
			'icon_padding'           => 0,
			'icon_color_type'        => 'simple',
			'icon_color'             => '',
			'icon_h_color'           => '',
			'icon_color2'            => '',
			'icon_h_color2'          => '',
			'icon_bg_type'           => 'simple',
			'icon_bg'                => '',
			'icon_h_bg'              => '',
			'icon_bg2'               => '',
			'icon_h_bg2'             => '',
			'icon_border_style'      => '',
			'icon_border_width'      => 1,
			'icon_border_color'      => '',
			'icon_h_border_color'    => '',
			'icon_border_radius'     => 500,
			'link'                   => '',
			'el_width'               => '100',
			'el_aspect'              => '169',
			'alignment'              => 'center',
			'css_animation'          => '',
			'el_class'               => '',
		), $atts, 'nova_' . __FUNCTION__ );

		if ( '' === $atts['link'] ) {
			return null;
		}
		
		$css_class = array(
			'nova-popup-video',
			'icon-type-' . $atts['icon_type'],
			'icon-style-' . $atts['style'],
			'text-' . $atts['alignment'],
			self::get_css_animation( $atts['css_animation'] ),
			$atts['el_class'],
		);
		
		if ( 'image' == $atts['icon_type'] ) {
			$image = wp_get_attachment_image_src( $atts['image'], 'full' );
			$icon  = $image ? sprintf( '<span><img alt="%s" src="%s" /></span>', esc_attr( $atts['title'] ), esc_url( $image[0] ) ) : '';
		} else {
			vc_icon_element_fonts_enqueue( $atts['icon_type'] );
			$icon = '<span><i class="' . esc_attr( $atts[ 'icon_' . $atts['icon_type'] ] ) . '"></i></span>';
		}
		
		if( empty( $atts['icon_h_color'] ) ){
			$atts['icon_h_color'] = $atts['icon_color'];
		}
		if( empty( $atts['icon_h_color2'] ) ){
			$atts['icon_h_color2'] = $atts['icon_color2'];
		}
		if( empty( $atts['icon_h_bg'] ) ){
			$atts['icon_h_bg'] = $atts['icon_bg'];
		}
		if( empty( $atts['icon_h_bg2'] ) ){
			$atts['icon_h_bg2'] = $atts['icon_bg2'];
		}
		if( empty( $atts['icon_h_border_color'] ) ){
			$atts['icon_h_border_color'] = $atts['icon_border_color'];
		}
		
		$unique_id = uniqid( 'nova_popup_video_' );

		$wapIconCssStyle = $iconCssStyle = array();
		$wapIconHoverCssStyle = $iconHoverCssStyle = array();
		
		if( ! empty( $atts['icon_size'] ) ){
			$iconCssStyle[] = 'line-height:' . $atts['icon_size'] . 'px';
			$iconCssStyle[] = 'font-size:' . $atts['icon_size'] . 'px';
			if( ! empty( $atts['icon_width'] ) && $atts['style'] != 'normal' ){
				$iconCssStyle[] = 'width:' . $atts['icon_width'] . 'px';
				$iconCssStyle[] = 'height:' . $atts['icon_width'] . 'px';
			}else{
				$iconCssStyle[] = 'width:' . $atts['icon_size'] . 'px';
				$iconCssStyle[] = 'height:' . $atts['icon_size'] . 'px';
			}
		}
		if( ! empty( $atts['icon_width'] ) && $atts['style'] != 'normal' ){
			$__padding_tmp = intval( ( $atts['icon_width'] - $atts['icon_size'] ) / 2 );
			$iconCssStyle[] = 'padding:' . $__padding_tmp . 'px';
		}
		if( $atts['style'] != 'normal' && ! empty( $atts['icon_bg'] ) ){
			if( $atts['icon_bg_type'] == 'gradient' ){
				$css_class[] = 'iconbg-gradient';
				$wapIconCssStyle[] = 'background-color: ' . $atts['icon_bg'];
				$wapIconCssStyle[] = 'background-image: -webkit-linear-gradient(left, ' . $atts['icon_bg'] . ' 0%, ' . $atts['icon_bg2'] . ' 50%,' . $atts['icon_bg'] . ' 100%)';
				$wapIconCssStyle[] = 'background-image: linear-gradient(to right, ' . $atts['icon_bg'] . ' 0%, ' . $atts['icon_bg2'] . ' 50%,' . $atts['icon_bg'] . ' 100%)';

				$wapIconHoverCssStyle[] = 'background-color: ' . $atts['icon_h_bg'];
				$wapIconHoverCssStyle[] = 'background-image: -webkit-linear-gradient(left, ' . $atts['icon_h_bg'] . ' 0%, ' . $atts['icon_h_bg2'] . ' 50%,' . $atts['icon_h_bg'] . ' 100%)';
				$wapIconHoverCssStyle[] = 'background-image: linear-gradient(to right, ' . $atts['icon_h_bg'] . ' 0%, ' . $atts['icon_h_bg2'] . ' 50%,' . $atts['icon_h_bg'] . ' 100%)';

			}else{
				$wapIconCssStyle[] = 'background-color: ' . $atts['icon_bg'] ;
				$wapIconHoverCssStyle[] = 'background-color: ' . $atts['icon_h_bg'] ;
			}

		}
		if( $atts['style'] == 'advanced' ){
			$wapIconCssStyle[] = 'border-radius:' . $atts['icon_border_radius'] . 'px';
			$iconCssStyle[] = 'border-radius:' . $atts['icon_border_radius'] . 'px';
			if( ! empty( $atts['icon_padding'] ) ){
				$wapIconCssStyle[] = 'padding:'. intval( $atts['icon_padding'] ) . 'px';
			}
		}
		if( ! empty( $atts['icon_color'] ) ){
			if( $atts['icon_color_type'] == 'gradient' ){
				$iconCssStyle[] = 'color: ' . $atts['icon_color'];
				$iconCssStyle[] = 'background-image: -webkit-linear-gradient(left, ' . $atts['icon_color'] . ' 0%, ' . $atts['icon_color2'] . ' 50%,' . $atts['icon_color'] . ' 100%)';
				$iconCssStyle[] = 'background-image: linear-gradient(to right, ' . $atts['icon_color'] . ' 0%, ' . $atts['icon_color2'] . ' 50%,' . $atts['icon_color'] . ' 100%)';
				$iconCssStyle[] = '-webkit-background-clip: text';
				$iconCssStyle[] = 'background-clip: text';
				$iconCssStyle[] = '-webkit-text-fill-color: transparent';
				$css_class[] = 'icontext-gradient';

				$iconHoverCssStyle[] = 'color: ' . $atts['icon_h_color'];
				$iconHoverCssStyle[] = 'background-image: -webkit-linear-gradient(left, ' . $atts['icon_h_color'] . ' 0%, ' . $atts['icon_h_color2'] . ' 50%,' . $atts['icon_h_color'] . ' 100%)';
				$iconHoverCssStyle[] = 'background-image: linear-gradient(to right, ' . $atts['icon_h_color'] . ' 0%, ' . $atts['icon_h_color2'] . ' 50%,' . $atts['icon_h_color'] . ' 100%)';
			}else{
				$iconCssStyle[] = 'color:' . $atts['icon_color'];
				$iconHoverCssStyle[] = 'color:' . $atts['icon_h_color'];
			}
		}
		if( ! empty( $atts['icon_border_style'] ) ){
			$wapIconCssStyle[] = 'border-style:' . $atts['icon_border_style'];
			$wapIconCssStyle[] = 'border-width:' . $atts['icon_border_width'] . 'px';
			$wapIconCssStyle[] = 'border-color:' . $atts['icon_border_color'];
			$wapIconHoverCssStyle[] = 'border-color:' . $atts['icon_h_border_color'];
		}

		$customCss = '';
		if( ! empty( $iconCssStyle ) || ! empty( $wapIconCssStyle ) || ! empty( $iconHoverCssStyle ) || ! empty( $wapIconHoverCssStyle ) ){
			$customCss .= '<span data-nova_component="InsertCustomCSS" class="js-el hidden">';
			if( ! empty( $wapIconCssStyle ) ){
				$customCss .= '#' . $unique_id . '.nova-popup-video .video-icon{' . implode( ';', $wapIconCssStyle ) . '}';
			}
			if( ! empty( $iconCssStyle ) ){
				$customCss .= '#' . $unique_id . '.nova-popup-video .video-icon span{' . implode( ';', $iconCssStyle ) . '}';
			}
			if( ! empty( $wapIconHoverCssStyle ) ){
				$customCss .= '#' . $unique_id . '.nova-popup-video:hover .video-icon{' . implode( ';', $wapIconHoverCssStyle ) . '}';
			}
			if( ! empty( $iconHoverCssStyle ) ){
				$customCss .= '#' . $unique_id . '.nova-popup-video:hover .video-icon span{' . implode( ';', $iconHoverCssStyle ) . '}';
			}
			$customCss .= '</span>';
		}
		
		$video_css_class = array(
			'wpb_video_widget',
			'wpb_content_element',
			'vc_clearfix',
			'vc_video-aspect-ratio-' . esc_attr( $atts['el_aspect'] ),
			'vc_video-el-width-' . esc_attr( $atts['el_width'] ),
			'vc_video-align-' . esc_attr( $atts['alignment'] ),
		);

		$video_w = 500;
		$video_h = $video_w / 1.61; //1.61 golden ratio
		/** @var WP_Embed $wp_embed */
		global $wp_embed;
		$embed = '';
		if ( is_object( $wp_embed ) ) {
			$embed = $wp_embed->run_shortcode( '[embed width="' . $video_w . '"' . $video_h . ']' . $atts['link'] . '[/embed]' );
		}
		
		$video_html = '
			<div class="' . implode( ' ', $video_css_class ) . '">
				<div class="wpb_wrapper">
					<div class="wpb_video_wrapper">' . $embed . '</div>
				</div>
			</div>
		';
		
		$video_wrapper_html = '
			<div id="__' . esc_attr( $unique_id ) . '" class="video-modal nova-modal" tabindex="-1" role="dialog">
				<div class="modal-header">
					<a href="#" class="close-modal">
						<svg viewBox="0 0 20 20">
							<use xlink:href="#close-delete"></use>
						</svg>
					</a>

					<h2>&nbsp;</h2>
				</div>

				<div class="modal-content">
					<div class="container">
						' . $video_html . '
					</div>
				</div>
			</div>
		';
		
		add_action( 'wp_footer', function () use ( $video_wrapper_html ) { echo $video_wrapper_html; } );
		
		return sprintf(
			'<div id="%s" class="%s">
				<div class="video-icon">%s</div>
			</div>
			%s',
			esc_attr( $unique_id ),
			esc_attr( implode( ' ', $css_class ) ),
			'<a class="video-link" data-toggle="modal" data-target="__' . esc_attr( $unique_id ) . '" href="#">' . $icon . '</a>',
			$customCss
		);
	}

	/**
	 * Get coordinates
	 *
	 * @param string $address
	 * @param bool   $refresh
	 *
	 * @return array
	 */
	public static function get_coordinates( $address, $key = '', $refresh = false ) {
		$address_hash = md5( $address );
		$coordinates  = get_transient( $address_hash );
		$results      = array( 'lat' => '', 'lng' => '' );

		if ( $refresh || $coordinates === false ) {
			$args     = array( 'address' => urlencode( $address ), 'sensor' => 'false', 'key' => $key );
			$url      = add_query_arg( $args, 'https://maps.googleapis.com/maps/api/geocode/json' );
			$response = wp_remote_get( $url );

			if ( is_wp_error( $response ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'nova' );

				return $results;
			}

			$data = wp_remote_retrieve_body( $response );

			if ( is_wp_error( $data ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'nova' );

				return $results;
			}

			if ( $response['response']['code'] == 200 ) {
				$data = json_decode( $data );

				if ( $data->status === 'OK' ) {
					$coordinates = $data->results[0]->geometry->location;

					$results['lat']     = $coordinates->lat;
					$results['lng']     = $coordinates->lng;
					$results['address'] = (string) $data->results[0]->formatted_address;

					// cache coordinates for 3 months
					set_transient( $address_hash, $results, 3600 * 24 * 30 * 3 );
				} elseif ( $data->status === 'ZERO_RESULTS' ) {
					$results['error'] = esc_html__( 'No location found for the entered address.', 'nova' );
				} elseif ( $data->status === 'INVALID_REQUEST' ) {
					$results['error'] = esc_html__( 'Invalid request. Did you enter an address?', 'nova' );
				} else {
					$results['error'] = $data->error_message;
				}
			} else {
				$results['error'] = esc_html__( 'Unable to contact Google API service.', 'nova' );
			}
		} else {
			$results = $coordinates; // return cached results
		}

		return $results;
	}

	/**
	 * Loop over found products.
	 *
	 * @param  array  $atts
	 * @param  string $loop_name
	 *
	 * @return string
	 * @internal param array $columns
	 */
	protected static function product_loop( $atts, $loop_name = 'nova_product_grid' ) {
		global $woocommerce_loop;

		$query_args = self::get_query( $atts );

		if ( isset( $atts['type'] ) && 'top_rated' == $atts['type'] ) {
			add_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );
		} elseif ( isset( $atts['type'] ) && 'best_sellers' == $atts['type'] ) {
			add_filter( 'posts_clauses', array( __CLASS__, 'order_by_popularity_post_clauses' ) );
		}

		$products = new WP_Query( $query_args );

		if ( isset( $atts['type'] ) && 'top_rated' == $atts['type'] ) {
			remove_filter( 'posts_clauses', array( 'WC_Shortcodes', 'order_by_rating_post_clauses' ) );
		} elseif ( isset( $atts['type'] ) && 'best_sellers' == $atts['type'] ) {
			remove_filter( 'posts_clauses', array( __CLASS__, 'order_by_popularity_post_clauses' ) );
		}

		$woocommerce_loop['name'] = $loop_name;
		$columns                  = isset( $atts['columns'] ) ? absint( $atts['columns'] ) : null;

		if ( $columns ) {
			$woocommerce_loop['columns'] = $columns;
		}

		ob_start();

		if ( $products->have_posts() ) {
			woocommerce_product_loop_start();

			while ( $products->have_posts() ) : $products->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile; // end of the loop.

			woocommerce_product_loop_end();
		}

		$return = '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';

		if ( isset( $atts['load_more'] ) && $atts['load_more'] && $products->max_num_pages > 1 ) {
			$paged = max( 1, $products->get( 'paged' ) );
			$type  = isset( $atts['type'] ) ? $atts['type'] : 'recent';

			if ( $paged < $products->max_num_pages ) {
				$button = sprintf(
					'<div class="load-more text-center">
						<a href="#" class="button ajax-load-products" data-page="%s" data-columns="%s" data-per_page="%s" data-type="%s" data-category="%s" data-nonce="%s" rel="nofollow">
							<span class="button-text">%s</span>
							<span class="loading-icon">
								<span class="bubble"><span class="dot"></span></span>
								<span class="bubble"><span class="dot"></span></span>
								<span class="bubble"><span class="dot"></span></span>
							</span>
						</a>
					</div>',
					esc_attr( $paged + 1 ),
					esc_attr( $columns ),
					esc_attr( $query_args['posts_per_page'] ),
					esc_attr( $type ),
					isset( $atts['category'] ) ? esc_attr( $atts['category'] ) : '',
					esc_attr( wp_create_nonce( 'nova_get_products' ) ),
					esc_html__( 'Load More', 'nova' )
				);

				$return .= $button;
			}
		}

		woocommerce_reset_loop();
		wp_reset_postdata();

		return $return;
	}

	/**
	 * Build query args from shortcode attributes
	 *
	 * @param array $atts
	 *
	 * @return array
	 */
	private static function get_query( $atts ) {
		$args = array(
			'post_type'              => 'product',
			'post_status'            => 'publish',
			'orderby'                => get_option( 'woocommerce_default_catalog_orderby' ),
			'order'                  => 'DESC',
			'ignore_sticky_posts'    => 1,
			'posts_per_page'         => $atts['per_page'],
			'meta_query'             => WC()->query->get_meta_query(),
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		if( version_compare( WC()->version, '3.0.0', '>=' ) ) {
			$args['tax_query'] = WC()->query->get_tax_query();
		}

		// Ordering
		if ( 'menu_order' == $args['orderby'] || 'price' == $args['orderby'] ) {
			$args['order'] = 'ASC';
		}

		if ( 'price-desc' == $args['orderby'] ) {
			$args['orderby'] = 'price';
		}

		if ( method_exists( WC()->query, 'get_catalog_ordering_args' ) ) {
			$ordering_args   = WC()->query->get_catalog_ordering_args( $args['orderby'], $args['order'] );
			$args['orderby'] = $ordering_args['orderby'];
			$args['order']   = $ordering_args['order'];

			if ( $ordering_args['meta_key'] ) {
				$args['meta_key'] = $ordering_args['meta_key'];
			}
		}

		// Improve performance
		if ( ! isset( $atts['load_more'] ) || ! $atts['load_more'] ) {
			$args['no_found_rows'] = true;
		}

		if ( ! empty( $atts['category'] ) ) {
			$args['product_cat'] = $atts['category'];
			unset( $args['update_post_term_cache'] );
		}

		if ( ! empty( $atts['page'] ) ) {
			$args['paged'] = absint( $atts['page'] );
		}

		if ( isset( $atts['type'] ) ) {
			switch ( $atts['type'] ) {
				case 'featured':
					if( version_compare( WC()->version, '3.0.0', '<' ) ) {
						$args['meta_query'][] = array(
							'key'   => '_featured',
							'value' => 'yes',
						);
					} else {
						$args['tax_query'][] = array(
							'taxonomy' => 'product_visibility',
							'field'    => 'name',
							'terms'    => 'featured',
							'operator' => 'IN',
						);
					}

					unset( $args['update_post_meta_cache'] );
					break;

				case 'sale':
					$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
					break;

				case 'best_sellers':
					$args['meta_key'] = 'total_sales';
					$args['orderby']  = 'meta_value_num';
					$args['order']    = 'DESC';
					unset( $args['update_post_meta_cache'] );

					add_filter( 'posts_clauses', array( __CLASS__, 'order_by_popularity_post_clauses' ) );
					break;

				case 'new':
					$newness = intval( nova_get_option( 'product_newness' ) );

					if ( $newness > 0 ) {
						$args['date_query'] = array(
							'after' => date( 'Y-m-d', strtotime( '-' . $newness . ' days' ) )
						);
					} else {
						$meta_query[] = array(
							'key'   => '_is_new',
							'value' => 'yes',
						);
					}
					break;

				case 'top_rated':
					unset( $args['product_cat'] );
					$args          = self::_maybe_add_category_args( $args, $atts['category'] );
					$args['order'] = 'DESC';
					break;
			}
		}

		return $args;
	}

	/**
	 * Adds a tax_query index to the query to filter by category.
	 *
	 * @param array $args
	 * @param string $category
	 *
	 * @return array;
	 */
	protected static function _maybe_add_category_args( $args, $category ) {
		if ( ! empty( $category ) ) {
			if ( empty( $args['tax_query'] ) ) {
				$args['tax_query'] = array();
			}
			$args['tax_query'][] = array(
				array(
					'taxonomy' => 'product_cat',
					'terms'    => array_map( 'sanitize_title', explode( ',', $category ) ),
					'field'    => 'slug',
					'operator' => 'IN',
				),
			);
		}

		return $args;
	}

	/**
	 * WP Core doens't let us change the sort direction for invidual orderby params - https://core.trac.wordpress.org/ticket/17065.
	 *
	 * This lets us sort by meta value desc, and have a second orderby param.
	 *
	 * @access public
	 * @param array $args
	 * @return array
	 */
	public static function order_by_popularity_post_clauses( $args ) {
		global $wpdb;
		$args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_date DESC";
		return $args;
	}

	/**
	 * Change banner size while it is inside a banner grid 4
	 *
	 * @param string $size
	 *
	 * @return string
	 */
	public static function banner_grid_4_banner_size( $size ) {
		switch ( self::$current_banner % 8 ) {
			case 1:
			case 7:
				$size = '920x820';
				break;

			case 2:
			case 3:
			case 5:
			case 6:
				$size = '460x410';
				break;

			case 0:
			case 4:
				$size = '920x410';
				break;
		}

		self::$current_banner ++;

		return $size;
	}

	/**
	 * Change banner size while it is inside a banner grid 5
	 *
	 * @param string $size
	 *
	 * @return string
	 */
	public static function banner_grid_5_banner_size( $size ) {
		switch ( self::$current_banner % 5 ) {
			case 1:
			case 0:
				$size = '520x400';
				break;

			case 3:
				$size = '750x920';
				break;

			case 2:
			case 4:
				$size = '520x500';
				break;
		}

		self::$current_banner ++;

		return $size;
	}

	/**
	 * Change banner size while it is inside a banner grid 6
	 *
	 * @param string $size
	 *
	 * @return string
	 */
	public static function banner_grid_6_banner_size( $size ) {
		switch ( self::$current_banner % 6 ) {
			case 1:
				$size = '640x800';
				break;

			case 2:
			case 3:
				$size = '640x395';
				break;

			case 4:
			case 5:
			case 0:
				$size = '426x398';
				break;
		}

		self::$current_banner ++;

		return $size;
	}

	/**
	 * Get CSS classes for animation
	 *
	 * @param string $css_animation
	 *
	 * @return string
	 */
	public static function get_css_animation( $css_animation ) {
		$output = '';

		if ( '' !== $css_animation && 'none' !== $css_animation ) {
			wp_enqueue_script( 'waypoints' );
			wp_enqueue_style( 'animate-css' );
			$output = ' wpb_animate_when_almost_visible wpb_' . $css_animation . ' ' . $css_animation;
		}

		return $output;
	}
}
