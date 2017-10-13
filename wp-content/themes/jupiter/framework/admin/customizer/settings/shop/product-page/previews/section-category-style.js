(function( $ ) {

	var productPostedIn = '.woocommerce-page.single-product div.product .product_meta>span.posted_in';

	// Method for Control's event handlers: cs_pp_s_category_style_typography.
	wp.customize('cs_pp_s_category_style_typography', function (value) {
		$(productPostedIn).css(
			mkPreviewTypography(value(), true, ['weight'])
		);
		$(productPostedIn).find('a').css(
			mkPreviewTypography(value(), false)
		);
		value.bind(function (to) {
			$(productPostedIn).css(
				mkPreviewTypography(to, false, ['weight'])
			);
			$(productPostedIn).find('a').css(
				mkPreviewTypography(to, false)
			);
		});
	});

	// Method for Control's event handlers: cs_pp_s_category_style_box_model.
	wp.customize('cs_pp_s_category_style_box_model', function (value) {
		$(productPostedIn).css(
			mkPreviewBoxModel(value())
		);
		value.bind(function (to) {
			$(productPostedIn).css(
				mkPreviewBoxModel(to)
			);
		});
	});

} )( jQuery );