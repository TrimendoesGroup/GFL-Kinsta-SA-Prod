<?php
/**
 * Customizer Dynamic Styles: Section Rating Style.
 *
 * Prefix: pp -> product page, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$star_rating = '.woocommerce-page.single-product div.woocommerce-product-rating';

$css = $star_rating . ' .star-rating {';

$font_size = get_theme_mod( 'cs_pp_s_rating_style_font_size' );
if ( $font_size ) {
	$css .= "font-size: {$font_size}px;";
}

$css .= '}';
$css .= $star_rating . '  .star-rating span::before {';

$star_color = get_theme_mod( 'cs_pp_s_rating_style_active_star_color' );
if ( $star_color ) {
	$css .= "color: {$star_color} !important;";
}

$css .= '}';
$css .= $star_rating . ' .star-rating::before {';

$star_active_color = get_theme_mod( 'cs_pp_s_rating_style_star_color' );
if ( $star_active_color ) {
	$css .= "color: {$star_active_color};";
}

$css .= '}';
$css .= $star_rating . ' .woocommerce-review-link {';
$css .= mk_cs_typography( 'cs_pp_s_rating_style_typography' );
$css .= '}';
$css .= $star_rating . ' {';
$css .= mk_cs_box_model( 'cs_pp_s_rating_style_box_model' );
$css .= '}';

return $css;


