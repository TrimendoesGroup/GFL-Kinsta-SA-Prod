<?php
/**
 * Customizer Dynamic Styles: Section Add to Cart Button Style.
 *
 * Prefix: pl -> product list, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '';

// Control: cs_pl_s_add_to_cart_style_show_icon.
$css .= '.woocommerce-page ul.products li.product svg {';
$show_icon = ( 'true' === get_theme_mod( 'cs_pl_s_add_to_cart_style_show_icon', 'true' ) ) ? 'inline-block' : 'none';
$css .= 'display:' . $show_icon . ';';
$icon_color = get_theme_mod( 'cs_pl_s_add_to_cart_style_icon_color' );
if ( $icon_color ) {
	$css .= "fill: {$icon_color};";
}
$css .= '}';

$css .= '.woocommerce-page ul.products li.product .button-text {';
$css .= mk_cs_typography( 'cs_pl_s_add_to_cart_style_typography' );
$css .= '}';

$css .= '.woocommerce-page ul.products li.product a.button {';
$css .= mk_cs_box_model( 'cs_pl_s_add_to_cart_style_box_model' );
$css .= 'background-color: ' . get_theme_mod( 'cs_pl_s_add_to_cart_style_bg_color', '#454ea2' ) . ';';
$css .= 'border-width: ' . get_theme_mod( 'cs_pl_s_add_to_cart_style_border', '0' ) . 'px;';
$css .= 'border-color: ' . get_theme_mod( 'cs_pl_s_add_to_cart_style_border_color', '#000' ) . ';';
$css .= 'border-radius: ' . get_theme_mod( 'cs_pl_s_add_to_cart_style_corner_radius', '0' ) . 'px;';
$css .= '}';

$css .= '.woocommerce-page ul.products li.product a.button:hover {';
$css .= 'background-color: ' . get_theme_mod( 'cs_pl_s_add_to_cart_style_bg_color_hover', '#454ea2' ) . ';';
$css .= 'border-color: ' . get_theme_mod( 'cs_pl_s_add_to_cart_style_border_color_hover', '#000' ) . ';';
$css .= 'color: ' . get_theme_mod( 'cs_pl_s_add_to_cart_style_color_hover', '#fff' ) . ';';
$css .= '}';

$css .= '.woocommerce-page ul.products li.product a.button:hover .button-text {';
$css .= 'color: ' . get_theme_mod( 'cs_pl_s_add_to_cart_style_color_hover', '#fff' ) . ';';
$css .= '}';
$css .= '.woocommerce-page ul.products li.product a.button:hover svg {';
$icon_hover_color = get_theme_mod( 'cs_pl_s_add_to_cart_style_icon_color_hover' );
if ( $icon_hover_color ) {
	$css .= "fill: {$icon_hover_color};";
}
$css .= '}';

return $css;
