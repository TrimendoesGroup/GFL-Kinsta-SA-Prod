<?php
/**
 * Dynamic styles for Regular Price Style section in Product Page > Styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '.woocommerce-page.single-product .entry-summary .price > .amount,';
$css .= '.woocommerce-page.single-product .entry-summary .price del .amount,';
$css .= '.woocommerce-page.single-product .entry-summary .price > .mk-price-variation-seprator,';
$css .= '.woocommerce-page.single-product .entry-summary .price del .mk-price-variation-seprator {';
$css .= mk_cs_box_model( 'cs_pp_s_price_style_box_model' );
$css .= mk_cs_typography( 'cs_pp_s_price_style_typography' );
$css .= '}';

return $css;
