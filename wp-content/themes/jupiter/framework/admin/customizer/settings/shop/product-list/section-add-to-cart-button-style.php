<?php
/**
 * Add Add to Cart Button section of Product List > Styles.
 * Prefix: s -> shop, pl -> product-list, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

$wp_customize->add_section( 'mk_s_pl_s_add_to_cart_button', array(
		'title' => __( 'Add to Cart Button', 'mk_framework' ),
		'priority' => 70,
		'active_callback' => array( $this, 'hide_sections' ),
		'type' => array(
			'mk-dialog',
			'mk_s_pl_dialog',
		),
	)
);

// Text.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_text', array(
	'default' => __( 'Add to Cart', 'mk_framework' ),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_text',
		array(
			'section' => 'mk_s_pl_s_add_to_cart_button',
			'column'  => 'mk-col-8',
			'text' => __( 'Text', 'mk_framework' ),
		)
	)
);

// Show Icon.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_show_icon', array(
	'default' => 'true',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Toggle_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_show_icon',
		array(
			'section' => 'mk_s_pl_s_add_to_cart_button',
			'column'  => 'mk-col-4',
			'sublabel' => __( 'Show Icon', 'mk_framework' ),
		)
	)
);

// Typography.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_typography', array(
	'default' => array(
		'family' => 'inherit',
		'size' => 12,
		'weight' => 700,
		'style' => 'normal',
		'color' => '#fff',
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Typography_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_typography',
		array(
			'section' => 'mk_s_pl_s_add_to_cart_button',
			'column'  => 'mk-col-12',
		)
	)
);

// Background Color.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_bg_color', array(
	'default'   => '#454ea2',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_bg_color',
		array(
			'section'  => 'mk_s_pl_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Corner Radius.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_corner_radius', array(
	'default'   => 0, // Inherited from assets/stylesheet/plugins/min/woocommerce.css.
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_corner_radius',
		array(
			'section'  => 'mk_s_pl_s_add_to_cart_button',
			'column'   => 'mk-col-3-alt',
			'icon'     => 'mk-corner-radius',
			'unit'     => 'px',
			'input_type' => 'number',
			'input_attrs' 	=> array(
				'min' => '0',
			),
		)
	)
);

// Border.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_border', array(
	'default'   => 0, // Inherited from assets/stylesheet/plugins/min/woocommerce.css.
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Input_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_border',
		array(
			'section'  => 'mk_s_pl_s_add_to_cart_button',
			'column'   => 'mk-col-3-alt',
			'icon'     => 'mk-border',
			'unit'     => 'px',
			'input_type' => 'number',
			'input_attrs' 	=> array(
				'min' => '0',
			),
		)
	)
);

// Border Color.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_border_color', array(
	'default'   => '#000',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_border_color',
		array(
			'section'  => 'mk_s_pl_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt mk-col-last',
			'icon'     => 'mk-border-color',
		)
	)
);

// Icon Color.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_icon_color', array(
	'default'   => '#ffffff',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_icon_color',
		array(
			'section'  => 'mk_s_pl_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-icon-color',
		)
	)
);

// Divider 1.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_divider_1' );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_divider_1',
		array(
			'section' => 'mk_s_pl_s_add_to_cart_button',
		)
	)
);

// Hover Label.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_label' );

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_label',
		array(
			'section' => 'mk_s_pl_s_add_to_cart_button',
			'label' => __( 'Hover Style', 'mk_framework' ),
			'icon' => 'mk-hover-style-arrow',
		)
	)
);

// Font Color Hover.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_color_hover', array(
	'default'   => '#ffffff', // Inherited from assets/stylesheet/plugins/min/woocommerce.css.
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_color_hover',
		array(
			'section'  => 'mk_s_pl_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-font-color',
		)
	)
);

// Background Color Hover.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_bg_color_hover', array(
	'default'   => '#454ea2',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_bg_color_hover',
		array(
			'section'  => 'mk_s_pl_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-background-color',
		)
	)
);

// Border Color Hover.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_border_color_hover', array(
	'default'   => '#000',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_border_color_hover',
		array(
			'section'  => 'mk_s_pl_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-border-color',
		)
	)
);

// Icon Color Hover.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_icon_color_hover', array(
	'default'   => '#ffffff',
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Color_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_icon_color_hover',
		array(
			'section'  => 'mk_s_pl_s_add_to_cart_button',
			'column'   => 'mk-col-2-alt',
			'icon'     => 'mk-icon-color',
		)
	)
);

// Divider 2.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_divider_2' );

$wp_customize->add_control(
	new MK_Divider_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_divider_2',
		array(
			'section' => 'mk_s_pl_s_add_to_cart_button',
		)
	)
);

// Box Model.
$wp_customize->add_setting( 'cs_pl_s_add_to_cart_style_box_model', array(
	'default' => array(
		'margin_top' => 10,
		'margin_right' => 0,
		'margin_bottom' => 0,
		'margin_left' => 0,
		'padding_top' => 13,
		'padding_right' => 30,
		'padding_bottom' => 13,
		'padding_left' => 30,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'cs_pl_s_add_to_cart_style_box_model',
		array(
			'section' => 'mk_s_pl_s_add_to_cart_button',
			'column'  => 'mk-col-12',
		)
	)
);

