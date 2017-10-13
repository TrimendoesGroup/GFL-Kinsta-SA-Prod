<?php
/**
 * Customizer Dynamic Styles: Section Product Name Style.
 *
 * Prefix: pp -> product page, s -> styles.
 *
 * @package Jupiter
 * @subpackage MK_Customizer
 * @since 5.9.4
 */

$css = '';

// Dynamic styles for cs_pp_s_name_style_typography.
$css .= '.single-product div.product .product_title {' . mk_cs_typography( 'cs_pp_s_name_style_typography' ) . '}';

// Dynamic styles for cs_pp_s_name_style_box_model.
$css .= '.single-product div.product .product_title {' . mk_cs_box_model( 'cs_pp_s_name_style_box_model' ) . '}';

return $css;
