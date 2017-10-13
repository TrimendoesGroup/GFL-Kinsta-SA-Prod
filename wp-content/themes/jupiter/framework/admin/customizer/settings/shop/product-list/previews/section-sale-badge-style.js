(function( $ ) {

	var saleBadge = '.woocommerce-page li.product .onsale';

	// Text.
	wp.customize( 'cs_pl_s_sale_badge_style_text', function( value ) {
		value.bind( function( to ) {
			$(saleBadge).text( to );
		} );
	} );

	// Typography.
	wp.customize( 'cs_pl_s_sale_badge_style_typography', function( value ) {

		$(saleBadge).css(
			mkPreviewTypography( value(), true )
		);

		value.bind( function( to ) {
			$(saleBadge).css(
				mkPreviewTypography( to )
			);
		} );

	});

	// Background color.
	wp.customize( 'cs_pl_s_sale_badge_style_background_color', function( value ) {

		$(saleBadge).css( 'background-color', value() );

		value.bind( function( to ) {
			$(saleBadge).css( 'background-color', to );
		} );

	} );

	// Border radius.
	wp.customize( 'cs_pl_s_sale_badge_style_border_radius', function( value ) {

		$(saleBadge).css( 'border-radius', value() + 'px' );

		value.bind( function( to ) {
			$(saleBadge).css( 'border-radius', to + 'px' );
		} );

	} );

	// Border width.
	wp.customize( 'cs_pl_s_sale_badge_style_border_width', function( value ) {

		$(saleBadge).css( 'border-width', value() );

		value.bind( function( to ) {
			$(saleBadge).css( 'border-width', to );
		} );

	} );

	// Border color.
	wp.customize( 'cs_pl_s_sale_badge_style_border_color', function( value ) {

		$(saleBadge).css( 'border-color', value() );

		value.bind( function( to ) {
			$(saleBadge).css( 'border-color', to );
		} );

	} );

	// Box Model.
	wp.customize( 'cs_pl_s_sale_badge_style_box_model', function( value ) {
		
		$(saleBadge).css(
			mkPreviewBoxModel( value(), true )
		);

		value.bind( function (to) {
			$(saleBadge).css(
				mkPreviewBoxModel( to )
			);
		} );

	} );

} )( jQuery );