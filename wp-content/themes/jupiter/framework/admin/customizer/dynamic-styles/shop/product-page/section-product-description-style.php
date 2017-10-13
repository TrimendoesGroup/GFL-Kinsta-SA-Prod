<?php
/**
 * Dynamic styles for Description Style section in Product Page > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '.woocommerce-page.single-product div.product .woocommerce-product-details__short-description {';
$css .= mk_cs_box_model( 'cs_pp_s_description_style_box_model' );

$background_color = get_theme_mod( 'cs_pp_s_description_style_background_color' );
if ( $background_color ) {
	$css .= "background-color: {$background_color};";
}

$css .= '}';
$css .= '.woocommerce-page.single-product div.product .woocommerce-product-details__short-description,';
$css .= '.woocommerce-page.single-product div.product .woocommerce-product-details__short-description p {';
$css .= mk_cs_typography( 'cs_pp_s_description_style_typography' );
$css .= '}';

return $css;
