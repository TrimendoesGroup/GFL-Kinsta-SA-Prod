(function ($) {

	var selector = '.woocommerce-page.single-product .product .social-share';

	// Method for Control's event handlers: cs_pp_s_social_share_style_networks.
	wp.customize('cs_pp_s_social_share_style_networks', function (value) {
		$(selector + ' .share-by').hide();
		var networks = typeof value.get() === 'object' ? value.get() : value.get().split(',');
		for (var i = 0, len = networks.length; i < len; i++) {
			$(selector + ' .share-by-' + networks[i]).show();
		}
		value.bind(function (to) {
			$(selector + ' .share-by').hide();
			var networks = typeof to === 'object' ? to : to.split(',');
			for (var i = 0, len = networks.length; i < len; i++) {
				$(selector + ' .share-by-' + networks[i]).show();
			}
		});
	});

	// Method for Control's event handlers: cs_pp_s_social_share_style_fill_color.
	wp.customize('cs_pp_s_social_share_style_fill_color', function (value) {
		$(selector + ' svg').css({
			'fill': value.get()
		});
		value.bind(function (to) {
			$(selector + ' svg').css({
				'fill': to
			});
		});
	});

	// Method for Control's event handlers: cs_pp_s_social_share_style_fill_color_hover.
	wp.customize('cs_pp_s_social_share_style_fill_color_hover', function (value) {
		$(selector).find('svg').on({
			mouseenter: function () {
				$(this).css({
					'fill': value()
				});
			},
			mouseleave: function () {
				$(this).css({
					'fill': wp.customize('cs_pp_s_social_share_style_fill_color')()
				});
			}
		});
		value.bind(function (to) {
			$(selector).find('svg').on({
				mouseenter: function () {
					$(this).css({
						'fill': to
					});
				},
				mouseleave: function () {
					$(this).css({
						'fill': wp.customize('cs_pp_s_social_share_style_fill_color')()
					});
				}
			});
		});
	});

	// Method for Control's event handlers: cs_pp_s_social_share_style_bg_color.
	wp.customize('cs_pp_s_social_share_style_bg_color', function (value) {
		$(selector + ' a').css({
			'background-color': value.get()
		});
		value.bind(function (to) {
			$(selector + ' a').css({
				'background-color': to
			});
		});
	});

	// Method for Control's event handlers: cs_pp_s_social_share_style_bg_color_hover.
	wp.customize('cs_pp_s_social_share_style_bg_color_hover', function (value) {
		$(selector).find('a').on({
			mouseenter: function () {
				$(this).css({
					'background-color': value()
				});
				$(this).find('svg').css({
					'fill': wp.customize('cs_pp_s_social_share_style_fill_color_hover')()
				});
			},
			mouseleave: function () {
				$(this).css({
					'background-color': wp.customize('cs_pp_s_social_share_style_bg_color')()
				});
				$(this).find('svg').css({
					'fill': wp.customize('cs_pp_s_social_share_style_fill_color')()
				});
			}
		});
		value.bind(function (to) {
			$(selector).find('a').on({
				mouseenter: function () {
					$(this).css({
						'background-color': to
					});
				},
				mouseleave: function () {
					$(this).css({
						'background-color': wp.customize('cs_pp_s_social_share_style_bg_color')()
					});
				}
			});
		});
	});

	// Method for Control's event handlers: cs_pp_s_social_share_style_corner_radius.
	wp.customize('cs_pp_s_social_share_style_corner_radius', function (value) {
		$(selector + ' a').css({
			'border-radius': value.get() + 'px'
		});
		value.bind(function (to) {
			$(selector + ' a').css({
				'border-radius': to + 'px'
			});
		});
	});

	// Method for Control's event handlers: cs_pp_s_social_share_style_border.
	wp.customize('cs_pp_s_social_share_style_border', function (value) {
		$(selector + ' a').css({
			'border-width': value.get() + 'px'
		});
		value.bind(function (to) {
			$(selector + ' a').css({
				'border-width': to + 'px'
			});
		});
	});

	// Method for Control's event handlers: cs_pp_s_social_share_style_border_color.
	wp.customize('cs_pp_s_social_share_style_border_color', function (value) {
		$(selector + ' a').css({
			'border-color': value.get()
		});
		value.bind(function (to) {
			$(selector + ' a').css({
				'border-color': to
			});
		});
	});

	// Method for Control's event handlers: cs_pp_s_social_share_style_border_color_hover.
	wp.customize('cs_pp_s_social_share_style_border_color_hover', function (value) {
		$(selector).find('a').on({
			mouseenter: function () {
				$(this).css({
					'border-color': value()
				});
			},
			mouseleave: function () {
				$(this).css({
					'border-color': wp.customize('cs_pp_s_social_share_style_border_color')()
				});
			}
		});
		value.bind(function (to) {
			$(selector).find('a').on({
				mouseenter: function () {
					$(this).css({
						'border-color': to
					});
				},
				mouseleave: function () {
					$(this).css({
						'border-color': wp.customize('cs_pp_s_social_share_style_border_color')()
					});
				}
			});
		});
	});

	// Method for Control's event handlers: cs_pp_s_social_share_style_box_model.
	wp.customize('cs_pp_s_social_share_style_box_model', function (value) {
		$(selector).css(
			mkPreviewBoxModel(value.get())
		);
		value.bind(function (to) {
			$(selector).css(
				mkPreviewBoxModel(to)
			);
		});
	});

})(jQuery);