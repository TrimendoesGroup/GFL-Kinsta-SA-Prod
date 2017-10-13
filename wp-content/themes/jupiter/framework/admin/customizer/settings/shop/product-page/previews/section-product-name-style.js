(function ($) {

	var productTitle = '.single-product div.product .product_title';

	// Method for Control's event handlers: cs_pp_s_name_style_typography.
	wp.customize('cs_pp_s_name_style_typography', function (value) {
		$(productTitle).css(
			mkPreviewTypography(value(), true)
		);
		value.bind(function (to) {
			$(productTitle).css(
				mkPreviewTypography(to)
			);
		});
	});

	// Method for Control's event handlers: cs_pp_s_name_style_box_model.
	wp.customize('cs_pp_s_name_style_box_model', function (value) {
		$(productTitle).css(
			mkPreviewBoxModel(value())
		);
		value.bind(function (to) {
			$(productTitle).css(
				mkPreviewBoxModel(to)
			);
		});
	});

})(jQuery);