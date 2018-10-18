jQuery( document ).ready( function( $ ) {
	'use strict';

	var wp = window.wp,
		data = window.novaFilter,
		$body = $( 'body' ),
		template = wp.template( 'nova-product-filter' );

	$body.on( 'click', '.nova-product-filter-add-new', function( e ) {
		e.preventDefault();

		var $this = $( this ),
			$filters = $this.parent().prev( '.nova-product-filters' ),
			$title = $filters.closest( '.widget-content' ).find( 'input' ).first();

		data.number = $this.data( 'number' );
		data.name = $this.data( 'name' );
		data.count = $this.data( 'count' );

		$this.data( 'count', data.count + 1 );
		$filters.append( template( data ) );
		$filters.trigger( 'appended' );
		$title.trigger( 'change' ); // Support customize preview
	} );

	$body.on( 'change', '.nova-product-filter-fields select.filter-by', function() {
		var $this = $( this ),
			source = $this.val(),
			template = wp.template( 'nova-product-filter-options' );

		$this.closest( '.source' ).next( '.display' ).find( 'select.display-type' ).html( template( { options: data.display[source] } ) );

		if ( 'attribute' == source ) {
			$this.next( 'select' ).removeClass( 'hidden' );
			$this.closest( '.source' ).next( '.display' ).find( 'select:last-child' ).removeClass( 'hidden' );
		} else {
			$this.next( 'select' ).addClass( 'hidden' );
			$this.closest( '.source' ).next( '.display' ).find( 'select:last-child' ).addClass( 'hidden' );
		}
	} );

	$body.on( 'change', '.nova-product-filter-fields select.display-type', function() {
		var $this = $( this ),
			display = $this.val(),
			source = $this.closest( '.display' ).prev( '.source' ).find( 'select.filter-by' ).val();

		if ( 'attribute' != source || 'dropdown' == display ) {
			$this.next( 'select' ).addClass( 'hidden' );
		} else {
			$this.next( 'select' ).removeClass( 'hidden' );
		}
	} );

	$body.on( 'click', '.remove-filter', function( e ) {
		e.preventDefault();
		var $filters = $( this ).closest( '.nova-product-filters' ),
			$title = $filters.closest( '.widget-content' ).find( 'input' ).first();

		$( this ).closest( '.nova-product-filter-fields' ).slideUp( 300, function () {
			$( this ).remove();
			$filters.trigger( 'truncated' );
			$title.trigger( 'change' ); // Support customize preview
		} );
	} );

	$body.on( 'appended truncated', '.nova-product-filters', function() {
		var $filters = $( this ).children( '.nova-product-filter-fields' );

		if ( $filters.length ) {
			$( this ).children( '.no-filter' ).addClass( 'hidden' );
		} else {
			$( this ).children( '.no-filter' ).removeClass( 'hidden' );
		}
	} );
} );