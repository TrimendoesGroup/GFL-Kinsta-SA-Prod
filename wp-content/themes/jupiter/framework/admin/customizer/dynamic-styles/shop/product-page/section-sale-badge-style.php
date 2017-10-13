<?php
/**
 * Dynamic styles for Sale Badge Style section in Product List > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '.woocommerce-page div.product .mk-single-product-badges .onsale {';

$background_color = get_theme_mod( 'cs_pp_s_sale_badge_style_background_color' );
if ( $background_color ) {
	$css .= "background-color: {$background_color};";
}

$border_radius = get_theme_mod( 'cs_pp_s_sale_badge_style_border_radius' );
if ( $border_radius ) {
	$css .= "border-radius: {$border_radius}px;";
}

$border_width = get_theme_mod( 'cs_pp_s_sale_badge_style_border_width' );
if ( isset( $border_width ) ) {
	$css .= "border-width: {$border_width}px;";
}

$border_color = get_theme_mod( 'cs_pp_s_sale_badge_style_border_color' );
if ( $border_color ) {
	$css .= "border-color: {$border_color};";
}

$css .= mk_cs_box_model( 'cs_pp_s_sale_badge_style_box_model' );

$css .= mk_cs_typography( 'cs_pp_s_sale_badge_style_typography' );

$css .= '}';

return $css;
