jQuery( document ).ready( function ( $ ) {
	'use strict';

	/**
	 * Init isotope
	 */
	$( '.nova-product-grid.filterable.filter-type-isotope ul.products' ).each( function () {
		var $grid = $( this );

		$grid.imagesLoaded().always( function () {
			$grid.isotope( {
				itemSelector      : '.product',
				transitionDuration: 700,
				layoutMode        : 'fitRows',
				isOriginLeft      : !( novaData && novaData.isRTL && novaData.isRTL === '1' ),
				hiddenStyle: {
					opacity: 0,
					transform: 'translate3d(0, 50px, 0)'
				},
				visibleStyle: {
					opacity: 1,
					transform: 'none'
				}
			} );
		} );
	} );

	/**
	 * Handle filter
	 */
	$( '.nova-product-grid.filterable' ).on( 'click', '.filter li', function ( e ) {
		e.preventDefault();

		var $this = $( this ),
			$grid = $this.closest( '.nova-product-grid' ),
			$products = $grid.find( '.products' );

		if ( $this.hasClass( 'active' ) ) {
			return;
		}

		$this.addClass( 'active' ).siblings( '.active' ).removeClass( 'active' );

		if ( $grid.hasClass( 'filter-type-isotope' ) ) {
			$products.isotope( {filter: $this.data( 'filter' )} );
		} else {
			var filter = $this.attr( 'data-filter' ),
				$container = $grid.find( '.products-grid' );

			filter = filter.replace( /\./g, '' );
			filter = filter.replace( /product_cat-/g, '' );

			var data = {
				columns  : $grid.data( 'columns' ),
				per_page : $grid.data( 'per_page' ),
				load_more: $grid.data( 'load_more' ),
				type     : '',
				nonce    : $grid.data( 'nonce' )
			};

			if ( $grid.hasClass( 'filter-by-group' ) ) {
				data.type = filter;
			} else {
				data.category = filter;
			}

			$grid.addClass( 'loading' );

			wp.ajax.send( 'nova_load_products', {
				data   : data,
				success: function ( response ) {
					var $_products = $( response );

					$grid.removeClass( 'loading' );

					$_products.find( 'ul.products > li' ).addClass( 'product novaFadeIn novaAnimation' );
					console.log( response );

					$container.children( 'div.woocommerce, .load-more' ).remove();
					$container.append( $_products );

					$( '.product-thumbnail-zoom', $container ).each( function () {
						var $el = $( this );

						$el.zoom( {
							url: $el.attr( 'data-zoom_image' )
						} );
					} );

					$( '.product-images__slider', $container ).owlCarousel( {
						items: 1,
						lazyLoad: true,
						dots: false,
						nav: true,
						rtl: !!( novaData && novaData.isRTL && novaData.isRTL === '1' ),
						navText: ['<svg viewBox="0 0 14 20"><use xlink:href="#left"></use></svg>', '<svg viewBox="0 0 14 20"><use xlink:href="#right"></use></svg>']
					} );
				}
			} );
		}
	} );

	/**
	 * Ajax load more products
	 */
	$( document.body ).on( 'click', '.ajax-load-products', function ( e ) {
		e.preventDefault();

		var $el = $( this ),
			page = $el.data( 'page' );

		if ( $el.hasClass( 'loading' ) ) {
			return;
		}

		$el.addClass( 'loading' );

		wp.ajax.send( 'nova_load_products', {
			data   : {
				page    : page,
				columns : $el.data( 'columns' ),
				per_page: $el.data( 'per_page' ),
				category: $el.data( 'category' ),
				type    : $el.data( 'type' ),
				nonce   : $el.data( 'nonce' )
			},
			success: function ( data ) {
				$el.data( 'page', page + 1 ).attr( 'page', page + 1 );
				$el.removeClass( 'loading' );

				var $data = $( data ),
					$products = $data.find( 'ul.products > li' ),
					$button = $data.find( '.ajax-load-products' ),
					$container = $el.closest( '.nova-products' ),
					$grid = $container.find( 'ul.products' );

				// If has products
				if ( $products.length ) {
					// Add classes before append products to grid
					$products.addClass( 'product' );

					$( '.product-thumbnail-zoom', $products ).each( function () {
						var $el = $( this );

						$el.zoom( {
							url: $el.attr( 'data-zoom_image' )
						} );
					} );

					$( '.product-images__slider', $products ).owlCarousel( {
						items: 1,
						lazyLoad: true,
						dots: false,
						nav: true,
						rtl: !!( novaData && novaData.isRTL && novaData.isRTL === "1" ),
						navText: ['<svg viewBox="0 0 14 20"><use xlink:href="#left"></use></svg>', '<svg viewBox="0 0 14 20"><use xlink:href="#right"></use></svg>']
					} );

					if ( $container.hasClass( 'filter-type-isotope' ) ) {
						var index = 0;
						$products.each( function() {
							var $product = $( this );

							setTimeout( function() {
								$grid.isotope( 'insert', $product );
							}, index * 100 );

							index++;
						} );

						setTimeout(function() {
							$grid.isotope( 'layout' );
						}, index * 100 );
					} else {
						for ( var index = 0; index < $products.length; index++ ) {
							$( $products[index] ).css( 'animation-delay', index * 100 + 100 + 'ms' );
						}
						$products.addClass( 'novaFadeInUp novaAnimation' );
						$grid.append( $products );
					}

					if ( $button.length ) {
						$el.replaceWith( $button );
					} else {
						$el.slideUp();
					}
				}
			}
		} );
	} );

	/**
	 * Product carousel
	 */
	$( '.nova-product-carousel' ).each( function () {
		var $carousel = $( this ),
			columns = parseInt( $carousel.data( 'columns' ), 10 ),
			autoplay = parseInt( $carousel.data( 'autoplay' ), 10 ),
			loop = $carousel.data( 'loop' );

		autoplay = autoplay === 0 ? false : autoplay;

		$carousel.find( 'ul.products' ).addClass( 'owl-carousel' ).owlCarousel( {
			items          : columns,
			autoplay       : !!autoplay,
			autoplayTimeout: autoplay,
			loop           : loop === 'yes',
			pagination     : true,
			navigation     : false,
			slideSpeed     : 300,
			paginationSpeed: 500,
			rtl            : !!( novaData && novaData.isRTL && novaData.isRTL === '1' ),
			responsive     : {
				0:{
					items:1
				},
				360:{
					items:2
				},
				767:{
					items:columns
				}
			}
		} );
	} );

	/**
	 *  Countdown
	 */
	$( '.nova-countdown' ).each( function () {
		var $el = $( this ),
			$timers = $el.find( '.timers' ),
			output = '';

		$timers.countdown( $timers.data( 'date' ), function ( event ) {
			output = '';
			var day = event.strftime( '%D' );
			for ( var i = 0; i < day.length; i++ ) {
				output += '<span>' + day[i] + '</span>';
			}
			$timers.find( '.day' ).html( output );

			output = '';
			var hour = event.strftime( '%H' );
			for ( i = 0; i < hour.length; i++ ) {
				output += '<span>' + hour[i] + '</span>';
			}
			$timers.find( '.hour' ).html( output );

			output = '';
			var minu = event.strftime( '%M' );
			for ( i = 0; i < minu.length; i++ ) {
				output += '<span>' + minu[i] + '</span>';
			}
			$( this ).find( '.min' ).html( output );

			output = '';
			var secs = event.strftime( '%S' );
			for ( i = 0; i < secs.length; i++ ) {
				output += '<span>' + secs[i] + '</span>';
			}
			$timers.find( '.secs' ).html( output );
		} );
	} );

	/**
	 * Stats Counter
	 */
	$( '.nova-stats-counter' ).each( function () {
		var $el = $( this ),
			$elm = $el.find( '.stats-value' );
		
		var endNum = parseFloat( $elm.data( 'counterup-nums' ) );

		$elm.counterUp( {
			delay: $elm.data( 'speed' ),
			time: 1000
		} );
		
	} );

	/**
	 * Image with Hotspots
	 */
	$( '.nova-image-with-hotspots' ).each( function () {
		var $this = $( this ),
			tooltip_func = $this.data( 'tooltip-func' );

		$( '> img', $this ).on( 'click', function( e ) {
			$this.find( '.nova_hotspot.open, .nttip.open' ).removeClass( 'open' );
		} );
		
		if( tooltip_func == 'hover' ) {

			$( '.nova_hotspot_wrap', $this )
				.on( 'mouseenter', function( e ) {
					if( $( window ).width() > 1024 ) {
						$( this ).find( '>.nova_hotspot, >.nttip' ).addClass( 'open' );
					}
				} )
				.on( 'mouseleave', function( e ) {
					if( $( window ).width() > 1024){
						$( this ).find( '>.nova_hotspot, >.nttip' ).removeClass( 'open' );
					}
				} );
		}
		
		$( '.nova_hotspot_wrap', $this ).on( 'click', function( e ) {
			e.preventDefault();
			$( this ).siblings( '.nova_hotspot_wrap' ).find( '>.nova_hotspot, >.nttip' ).removeClass( 'open' );
			if( $( e.target ).is( '.tipclose' ) || $( e.target ).parent().is( '.tipclose' ) ) {
				$( this ).find( '>.nova_hotspot, >.nttip' ).removeClass( 'open' );
			}
			else{
				$( this ).find( '>.nova_hotspot, >.nttip' ).addClass( 'open' );
			}
		} );
		
		$( '.tipclose', $this ).on( 'click', function( e ) {
			e.preventDefault();
			$( this ).closest( '.nova_hotspot_wrap' ).find( '>.nova_hotspot, >.nttip' ).removeClass( 'open' );
		} );

	} );
	
	/**
	 * Advanced Carousel
	 */
	$( '.nova-slick-slider' ).each( function() {
        var $slider = $( this ),
            slider_config =  $slider.data( 'slider_config' ) || {},
            CustomPaging = $slider.data( 'slick_custompaging' ) || '';

		if( CustomPaging != '' ) {
            slider_config.customPaging = function( slide, i ) {
                return CustomPaging;
            }
        }

        slider_config = $.extend( {
            prevArrow: '<span class="slick-prev default"><svg><use xlink:href="#left-arrow"></use></svg></span>',
            nextArrow: '<span class="slick-next default"><svg><use xlink:href="#right-arrow"></use></svg></span>'
        }, slider_config );

		if( typeof slider_config.arrows !== "undefined" && typeof slider_config.appendArrows === "undefined" && slider_config.arrows == true ) {
			if( $slider.closest( '.woocommerce' ).length && $slider.closest( '.woocommerce' ).closest( '.vc_row' ).length ) {
				slider_config.appendArrows = $( '<div class="nova-slick-nav"></div>' ).prependTo( $slider.parent() );
			}
		}
		if( $slider.closest( '.nova-carousel-wrapper' ).hasClass( 'slider-fade' ) ) {
			slider_config.fade = true;
		}
		$slider.slick( slider_config );
		
	} );
	
	/**
	 * Instagram Feed
	 */
	$( '.nova-instagram-feeds' ).each( function() {
		var $shortcode = $( this );
		
		var $this = $shortcode,
			_configs = $this.data('feed_config'),
			_instagram_token = $this.data('instagram_token'),
			$target, feed_configs, feed;

		if( '' == _instagram_token ){
			$this.addClass('loaded loaded-error');
		}

		$target = $( '.nova-instagram-loop', $this );

		feed_configs = $.extend({
			target: $target.get(0).id,
			accessToken: _instagram_token
		}, _configs);

		feed = new Instafeed(feed_configs);
		feed.run();
		
	} );
	
	/**
	 * Init banner grid layout 5
	 */
	$( '.nova-banner-grid-5' ).each( function () {
		var $items = $( this ).children(),
			chucks = [];

		$items.each( function () {
			var $item = $( this );

			$item.css( 'background-image', function () {
				return 'url(' + $item.find( 'img' ).attr( 'src' ) + ')';
			} );
		} );

		for ( var i = 0; i < $items.length; i += 5 ) {
			var chuck = $items.splice( i, i + 5 ),
				$chuck = $( chuck );

			$chuck.wrapAll( '<div class="banners-wrap"/>' );
			$chuck.filter( ':lt(2)' ).wrapAll( '<div class="banners banners-column-1"/>' );
			$chuck.filter( ':eq(2)' ).wrapAll( '<div class="banners banners-column-2"/>' );
			$chuck.filter( ':gt(2)' ).wrapAll( '<div class="banners banners-column-3"/>' );

			chucks.push( chuck );
		}
	} );

	/**
	 * Init charts
	 */
	$( '.nova-chart' ).circleProgress( {
		emptyFill : 'rgba(0,0,0,0)',
		startAngle: -Math.PI / 2
	} );

	/**
	 * Close message box
	 */
	$( document.body ).on( 'click', '.nova-message-box .close', function ( e ) {
		e.preventDefault();

		$( this ).parent().fadeOut( 'slow' );
	} );

	/**
	 * Initialize map
	 */
	$( '.nova-map' ).each( function () {
		var $map = $( this ),
			latitude = $map.data( 'lat' ),
			longitude = $map.data( 'lng' ),
			zoom = $map.data( 'zoom' ),
			marker_icon = $map.data( 'marker' ),
			info = $map.html();

		var mapOptions = {
			zoom             : zoom,
			// disableDefaultUI : true,
			scrollwheel      : false,
			navigationControl: true,
			mapTypeControl   : false,
			scaleControl     : false,
			draggable        : true,
			center           : new google.maps.LatLng( latitude, longitude ),
			mapTypeId        : google.maps.MapTypeId.ROADMAP
		};

		switch ( $map.data( 'color' ) ) {
			case 'grey':
				mapOptions.styles = [{
					"featureType": "water",
					"elementType": "geometry",
					"stylers"    : [{"color": "#e9e9e9"}, {"lightness": 17}]
				}, {
					"featureType": "landscape",
					"elementType": "geometry",
					"stylers"    : [{"color": "#f5f5f5"}, {"lightness": 20}]
				}, {
					"featureType": "road.highway",
					"elementType": "geometry.fill",
					"stylers"    : [{"color": "#ffffff"}, {"lightness": 17}]
				}, {
					"featureType": "road.highway",
					"elementType": "geometry.stroke",
					"stylers"    : [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]
				}, {
					"featureType": "road.arterial",
					"elementType": "geometry",
					"stylers"    : [{"color": "#ffffff"}, {"lightness": 18}]
				}, {
					"featureType": "road.local",
					"elementType": "geometry",
					"stylers"    : [{"color": "#ffffff"}, {"lightness": 16}]
				}, {
					"featureType": "poi",
					"elementType": "geometry",
					"stylers"    : [{"color": "#f5f5f5"}, {"lightness": 21}]
				}, {
					"featureType": "poi.park",
					"elementType": "geometry",
					"stylers"    : [{"color": "#dedede"}, {"lightness": 21}]
				}, {
					"elementType": "labels.text.stroke",
					"stylers"    : [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
				}, {
					"elementType": "labels.text.fill",
					"stylers"    : [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
				}, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {
					"featureType": "transit",
					"elementType": "geometry",
					"stylers"    : [{"color": "#f2f2f2"}, {"lightness": 19}]
				}, {
					"featureType": "administrative",
					"elementType": "geometry.fill",
					"stylers"    : [{"color": "#fefefe"}, {"lightness": 20}]
				}, {
					"featureType": "administrative",
					"elementType": "geometry.stroke",
					"stylers"    : [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
				}];
				break;

			case 'inverse':
				mapOptions.styles = [{
					"featureType": "all",
					"elementType": "labels.text.fill",
					"stylers"    : [{"saturation": 36}, {"color": "#000000"}, {"lightness": 40}]
				}, {
					"featureType": "all",
					"elementType": "labels.text.stroke",
					"stylers"    : [{"visibility": "on"}, {"color": "#000000"}, {"lightness": 16}]
				}, {
					"featureType": "all",
					"elementType": "labels.icon",
					"stylers"    : [{"visibility": "off"}]
				}, {
					"featureType": "administrative",
					"elementType": "geometry.fill",
					"stylers"    : [{"color": "#000000"}, {"lightness": 20}]
				}, {
					"featureType": "administrative",
					"elementType": "geometry.stroke",
					"stylers"    : [{"color": "#000000"}, {"lightness": 17}, {"weight": 1.2}]
				}, {
					"featureType": "landscape",
					"elementType": "geometry",
					"stylers"    : [{"color": "#000000"}, {"lightness": 20}]
				}, {
					"featureType": "poi",
					"elementType": "geometry",
					"stylers"    : [{"color": "#000000"}, {"lightness": 21}]
				}, {
					"featureType": "road.highway",
					"elementType": "geometry.fill",
					"stylers"    : [{"color": "#000000"}, {"lightness": 17}]
				}, {
					"featureType": "road.highway",
					"elementType": "geometry.stroke",
					"stylers"    : [{"color": "#000000"}, {"lightness": 29}, {"weight": 0.2}]
				}, {
					"featureType": "road.arterial",
					"elementType": "geometry",
					"stylers"    : [{"color": "#000000"}, {"lightness": 18}]
				}, {
					"featureType": "road.local",
					"elementType": "geometry",
					"stylers"    : [{"color": "#000000"}, {"lightness": 16}]
				}, {
					"featureType": "transit",
					"elementType": "geometry",
					"stylers"    : [{"color": "#000000"}, {"lightness": 19}]
				}, {
					"featureType": "water",
					"elementType": "geometry",
					"stylers"    : [{"color": "#000000"}, {"lightness": 17}]
				}];
				break;

			case 'vista-blue':
				mapOptions.styles = [{
					"featureType": "water",
					"elementType": "geometry",
					"stylers"    : [{"color": "#a0d6d1"}, {"lightness": 17}]
				}, {
					"featureType": "landscape",
					"elementType": "geometry",
					"stylers"    : [{"color": "#ffffff"}, {"lightness": 20}]
				}, {
					"featureType": "road.highway",
					"elementType": "geometry.fill",
					"stylers"    : [{"color": "#dedede"}, {"lightness": 17}]
				}, {
					"featureType": "road.highway",
					"elementType": "geometry.stroke",
					"stylers"    : [{"color": "#dedede"}, {"lightness": 29}, {"weight": 0.2}]
				}, {
					"featureType": "road.arterial",
					"elementType": "geometry",
					"stylers"    : [{"color": "#dedede"}, {"lightness": 18}]
				}, {
					"featureType": "road.local",
					"elementType": "geometry",
					"stylers"    : [{"color": "#ffffff"}, {"lightness": 16}]
				}, {
					"featureType": "poi",
					"elementType": "geometry",
					"stylers"    : [{"color": "#f1f1f1"}, {"lightness": 21}]
				}, {
					"elementType": "labels.text.stroke",
					"stylers"    : [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
				}, {
					"elementType": "labels.text.fill",
					"stylers"    : [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
				}, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {
					"featureType": "transit",
					"elementType": "geometry",
					"stylers"    : [{"color": "#f2f2f2"}, {"lightness": 19}]
				}, {
					"featureType": "administrative",
					"elementType": "geometry.fill",
					"stylers"    : [{"color": "#fefefe"}, {"lightness": 20}]
				}, {
					"featureType": "administrative",
					"elementType": "geometry.stroke",
					"stylers"    : [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
				}];
				break;
		}

		var map = new google.maps.Map( this, mapOptions );

		var marker = new google.maps.Marker( {
			position : new google.maps.LatLng( latitude, longitude ),
			map      : map,
			icon     : marker_icon,
			animation: google.maps.Animation.DROP
		} );

		if ( info ) {
			var infoWindow = new google.maps.InfoWindow( {
				content: '<div class="info_content">' + info + '</div>'
			} );

			marker.addListener( 'click', function () {
				infoWindow.open( map, marker );
			} );
		}

	} );

	$( document.body ).on( 'click', '.nova-faq .question', function ( e ) {
		e.preventDefault();

		var $faq = $( this ).closest( '.nova-faq' );

		if ( $faq.hasClass( 'open' ) ) {
			$faq.find( '.accordion-content' ).stop( true, true ).slideUp( function () {
				$faq.removeClass( 'open' );
			} );
		} else {
			$faq.find( '.accordion-content' ).stop( true, true ).slideDown( function () {
				$faq.addClass( 'open' );
			} );
		}
	} );
} );
