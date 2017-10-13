<?php
/**
 * Dynamic styles for Sale Price Style section in Product Page > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '.woocommerce-page.single-product .entry-summary .price ins .amount,';
$css .= '.woocommerce-page.single-product .entry-summary .price ins .mk-price-variation-seprator {';
$css .= mk_cs_box_model( 'cs_pp_s_sale_price_style_box_model' );
$css .= mk_cs_typography( 'cs_pp_s_sale_price_style_typography' );
$css .= '}';

return $css;
