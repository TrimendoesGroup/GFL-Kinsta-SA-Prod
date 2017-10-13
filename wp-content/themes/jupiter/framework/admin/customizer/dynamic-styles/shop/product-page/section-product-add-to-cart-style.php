<?php
/**
 * Customizer Dynamic Styles: Section Add to Cart Button Style.
 *
 * Prefix: pp -> product page, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '';

// Control: cs_pp_s_add_to_cart_style_show_icon.
$css .= '.woocommerce-page.single-product div.product .single_add_to_cart_button svg {';
$show_icon = ( 'true' === get_theme_mod( 'cs_pp_s_add_to_cart_style_show_icon', 'true' ) ) ? 'inline-block' : 'none';
$css .= 'display:' . $show_icon . ';';
$css .= 'fill: ' . get_theme_mod( 'cs_pp_s_add_to_cart_style_icon_color' ) . ';';
$css .= '}';

$css .= '.woocommerce-page.single-product div.product .single_add_to_cart_button {';
$css .= mk_cs_typography( 'cs_pp_s_add_to_cart_style_typography' );
$css .= mk_cs_box_model( 'cs_pp_s_add_to_cart_style_box_model' );

$background_color = get_theme_mod( 'cs_pp_s_add_to_cart_style_bg_color' );
if ( $background_color ) {
	$css .= "background-color: {$background_color} !important;";
}

$border_radius = get_theme_mod( 'cs_pp_s_add_to_cart_style_corner_radius' );
if ( $border_radius ) {
	$css .= "border-radius: {$border_radius}px;";
}

$border_width = get_theme_mod( 'cs_pp_s_add_to_cart_style_border' );
if ( $border_width ) {
	$css .= "border-width: {$border_width}px;";
}

$border_color = get_theme_mod( 'cs_pp_s_add_to_cart_style_border_color' );
if ( $border_color ) {
	$css .= "border-color: {$border_color};";
}

$css .= '}';

$css .= '.woocommerce-page.single-product div.product .single_add_to_cart_button:hover {';

$text_hover_color = get_theme_mod( 'cs_pp_s_add_to_cart_style_color_hover' );
if ( $text_hover_color ) {
	$css .= "color: {$text_hover_color};";
}

$background_hover_color = get_theme_mod( 'cs_pp_s_add_to_cart_style_bg_color_hover' );
if ( $background_hover_color ) {
	$css .= "background-color: {$background_hover_color} !important;";
}

$border_hover_color = get_theme_mod( 'cs_pp_s_add_to_cart_style_border_color_hover' );
if ( $border_hover_color ) {
	$css .= "border-color: {$border_hover_color};";
}

$css .= '}';
$css .= '.woocommerce-page.single-product div.product .single_add_to_cart_button:hover svg {';
$css .= 'fill: ' . get_theme_mod( 'cs_pp_s_add_to_cart_style_icon_color_hover' ) . ';';
$css .= '}';

return $css;
