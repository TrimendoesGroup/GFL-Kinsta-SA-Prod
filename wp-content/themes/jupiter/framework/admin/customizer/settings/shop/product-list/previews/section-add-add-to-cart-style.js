(function ($) {

	var boxProduct = '.woocommerce-page ul.products li.product';

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_text.
	wp.customize('cs_pl_s_add_to_cart_style_text', function (value) {
		$(boxProduct + '.product-type-simple.instock').find('.button-text').text(value());
		value.bind(function (to) {
			$(boxProduct + '.product-type-simple.instock').find('.button-text').text(to);
		});
	});

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_show_icon.
	wp.customize('cs_pl_s_add_to_cart_style_show_icon', function (value) {
		if ('true' == value()) {
			$(boxProduct).find('svg').show();
		} else {
			$(boxProduct).find('svg').hide();
		}
		value.bind(function (to) {
			if ('true' == to) {
				$(boxProduct).find('svg').show();
			} else {
				$(boxProduct).find('svg').hide();
			}
		});
	});

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_typography.
	wp.customize('cs_pl_s_add_to_cart_style_typography', function (value) {
		var typography = mkPreviewTypography(value(), true);
		$(boxProduct).find('.button-text').css(typography);
		$(boxProduct).find('svg').css({
			width: value()['size'],
			height: value()['size'] 
		});

		value.bind(function (to) {
			var typography = mkPreviewTypography(to);
			var typographySize = jQuery.parseJSON( to )['size'];
			$(boxProduct).find('.button-text').css(typography);
			$(boxProduct).find('svg').css({
				width: typographySize,
				height: typographySize 
			});
		});
	
	});

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_bg_color.
	wp.customize('cs_pl_s_add_to_cart_style_bg_color', function (value) {
		$(boxProduct).find('.button').css({
			'background-color': value()
		});
		value.bind(function (to) {
			$(boxProduct).find('.button').css({
				'background-color': to
			});
		});
	});

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_corner_radius.
	wp.customize('cs_pl_s_add_to_cart_style_corner_radius', function (value) {
		$(boxProduct).find('.button').css({
			'border-radius': value() + 'px'
		});
		value.bind(function (to) {
			$(boxProduct).find('.button').css({
				'border-radius': to + 'px'
			});
		});
	});

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_border.
	wp.customize('cs_pl_s_add_to_cart_style_border', function (value) {
		$(boxProduct).find('.button').css({
			'border-width': value() + 'px'
		});
		value.bind(function (to) {
			$(boxProduct).find('.button').css({
				'border-width': to + 'px'
			});
		});
	});

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_border_color.
	wp.customize('cs_pl_s_add_to_cart_style_border_color', function (value) {
		$(boxProduct).find('.button').css({
			'border-color': value()
		});
		value.bind(function (to) {
			$(boxProduct).find('.button').css({
				'border-color': to
			});
		});
	});

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_icon_color.
	wp.customize('cs_pl_s_add_to_cart_style_icon_color', function (value) {
		$(boxProduct).find('svg').css({
			fill: value()
		});

		value.bind(function (to) {
			$(boxProduct).find('svg').css({
				fill: to
			});
		});
	
	});

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_box_model.
	wp.customize('cs_pl_s_add_to_cart_style_box_model', function (value) {
		$(boxProduct).find('a.button').css(mkPreviewBoxModel(value()));
		value.bind(function (to) {
			$(boxProduct).find('a.button').css(mkPreviewBoxModel(to));
		});
	});

	// Hover style.

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_color_hover.
	wp.customize('cs_pl_s_add_to_cart_style_color_hover', function (value) {
		$(boxProduct).find('a.button').on({
			mouseenter: function () {
				$(this).find('.button-text').css({
					color: value()
				});
			},
			mouseleave: function () {
				$(this).find('.button-text').css({
					color:  mkPreviewTypography(wp.customize('cs_pl_s_add_to_cart_style_typography')())['color']
				});
			}
		});
		value.bind(function (to) {
			$(boxProduct).find('a.button').on({
				mouseenter: function () {
					$(this).find('.button-text').css({
						color: to
					});
				},
				mouseleave: function () {
					$(this).find('.button-text').css({
						color: mkPreviewTypography(wp.customize('cs_pl_s_add_to_cart_style_typography')())['color']
					});
				}
			});
		});
	});

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_bg_color_hover.
	wp.customize('cs_pl_s_add_to_cart_style_bg_color_hover', function (value) {
		$(boxProduct).find('a.button').on({
			mouseenter: function () {
				$(this).css({
					'background-color': value()
				});
			},
			mouseleave: function () {
				$(this).css({
					'background-color': wp.customize('cs_pl_s_add_to_cart_style_bg_color')()
				});
			}
		});
		value.bind(function (to) {
			$(boxProduct).find('a.button').on({
				mouseenter: function () {
					$(this).css({
						'background-color': to
					});
				},
				mouseleave: function () {
					$(this).css({
						'background-color': wp.customize('cs_pl_s_add_to_cart_style_bg_color')()
					});
				}
			});
		});
	});

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_border_color_hover.
	wp.customize('cs_pl_s_add_to_cart_style_border_color_hover', function (value) {
		$(boxProduct).find('a.button').on({
			mouseenter: function () {
				$(this).css({
					'border-color': value()
				});
			},
			mouseleave: function () {
				$(this).css({
					'border-color': wp.customize('cs_pl_s_add_to_cart_style_border_color')()
				});
			}
		});
		value.bind(function (to) {
			$(boxProduct).find('a.button').on({
				mouseenter: function () {
					$(this).css({
						'border-color': to
					});
				},
				mouseleave: function () {
					$(this).css({
						'border-color': wp.customize('cs_pl_s_add_to_cart_style_border_color')()
					});
				}
			});
		});
	});

	// Method for Control's event handlers: cs_pl_s_add_to_cart_style_icon_color_hover.
	wp.customize('cs_pl_s_add_to_cart_style_icon_color_hover', function (value) {
		$(boxProduct).find('a.button').on({
			mouseenter: function () {
				$(this).find('svg').css({
					'fill': value()
				});
			},
			mouseleave: function () {
				$(this).find('svg').css({
					'fill': wp.customize('cs_pl_s_add_to_cart_style_icon_color')()
				});
			}
		});
		value.bind(function (to) {
			$(boxProduct).find('a.button').on({
				mouseenter: function () {
					$(this).find('svg').css({
						'fill': to
					});
				},
				mouseleave: function () {
					$(this).find('svg').css({
						'fill': wp.customize('cs_pl_s_add_to_cart_style_icon_color')()
					});
				}
			});
		});
	});

})(jQuery);