<?php
/**
 * Customizer Dynamic Styles: Section Tags Style.
 *
 * Prefix: pp -> product page, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '';

$networks = get_theme_mod( 'cs_pp_s_social_share_style_networks' );

if ( $networks ) {
	$css .= '.woocommerce-page.single-product .product .social-share .share-by {';
	$css .= 'display:none;';
	$css .= '}';
	if ( is_string( $networks ) ) {
		$networks = explode( ',', $networks );
	}
	$networks_style = array();
	foreach ( $networks as $network ) {
		$networks_style[] = '.woocommerce-page.single-product .product .social-share .share-by-' . $network;
	}

	$css .= implode( ', ', $networks_style ) . '{';
	$css .= 'display:inline-block;';
	$css .= '}';
}

$css .= '.woocommerce-page.single-product .product .social-share .share-by a {';
$css .= 'background-color:' . get_theme_mod( 'cs_pp_s_social_share_style_bg_color', 'rgba(200, 200, 200, 0)' ) . ';';
$css .= 'border-color:' . get_theme_mod( 'cs_pp_s_social_share_style_border_color', 'rgba(34, 34, 34, 0)' ) . ';';
$css .= 'border-width:' . get_theme_mod( 'cs_pp_s_social_share_style_border', '0' ) . 'px;';
$css .= 'border-radius:' . get_theme_mod( 'cs_pp_s_social_share_style_corner_radius', '0' ) . 'px;';
$css .= '}';

$css .= '.woocommerce-page.single-product .product .social-share .share-by:hover a {';
$css .= 'background-color:' . get_theme_mod( 'cs_pp_s_social_share_style_bg_color_hover', 'rgba(200, 200, 200, 0)' ) . ';';
$css .= 'border-color:' . get_theme_mod( 'cs_pp_s_social_share_style_border_color_hover', 'rgba(34, 34, 34, 0)' ) . ';';
$css .= '}';

$css .= '.woocommerce-page.single-product .product .social-share .share-by svg {';
$css .= 'fill:' . get_theme_mod( 'cs_pp_s_social_share_style_fill_color', 'rgba(34, 34, 34, 1)' ) . ';';
$css .= '}';

$css .= '.woocommerce-page.single-product .product .social-share .share-by:hover svg {';
$css .= 'fill:' . get_theme_mod( 'cs_pp_s_social_share_style_fill_color_hover', 'rgba(34, 34, 34, 1)' ) . ';';
$css .= '}';

$css .= '.woocommerce-page.single-product .product .social-share {';
$css .= mk_cs_box_model( 'cs_pp_s_social_share_style_box_model' );
$css .= '}';

return $css;
