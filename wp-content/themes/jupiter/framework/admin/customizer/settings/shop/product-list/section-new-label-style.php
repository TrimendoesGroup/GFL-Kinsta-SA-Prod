<?php
/**
 * Add Box section of Product List > Styles.
 * Prefix: s -> shop, pl -> product-list, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

$wp_customize->add_section( 'mk_s_pl_s_new_label_style', array(
		'title' => __( 'New Label Style', 'mk_framework' ),
		'priority' => 1,
		'active_callback' => array( $this, 'hide_sections' ),
		'type' => array(
			'mk-dialog',
			'mk_s_pl_dialog',
		),
	)
);

// Label 1.
$wp_customize->add_setting( 'cs_pl_s_new_label_style_label_1' );

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'cs_pl_s_new_label_style_label_1',
		array(
			'section' => 'mk_s_pl_s_new_label_style',
			'label' => __( 'Only Margin', 'mk_framework' ),
		)
	)
);

// Label 2.
$wp_customize->add_setting( 'cs_pl_s_new_label_style_label_2' );

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'cs_pl_s_new_label_style_label_2',
		array(
			'section' => 'mk_s_pl_s_new_label_style',
			'label' => __( 'Hover Style', 'mk_framework' ),
			'icon' => 'mk-hover-style-arrow',
		)
	)
);

// Label 3.
$wp_customize->add_setting( 'cs_pl_s_new_label_style_label_3' );

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'cs_pl_s_new_label_style_label_3',
		array(
			'section' => 'mk_s_pl_s_new_label_style',
			'label' => __( 'ACTIVE', 'mk_framework' ),
			'label_type' => 'fancy',
			'color' => 'green',
		)
	)
);

// Label 4.
$wp_customize->add_setting( 'cs_pl_s_new_label_style_label_4' );

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'cs_pl_s_new_label_style_label_4',
		array(
			'section' => 'mk_s_pl_s_new_label_style',
			'label' => __( 'PASSIVE', 'mk_framework' ),
			'label_type' => 'fancy',
			'color' => 'yellow',
		)
	)
);

// Label 5.
$wp_customize->add_setting( 'cs_pl_s_new_label_style_label_5' );

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'cs_pl_s_new_label_style_label_5',
		array(
			'section' => 'mk_s_pl_s_new_label_style',
			'label' => __( 'CUSTOM', 'mk_framework' ),
			'label_type' => 'fancy',
			'color' => 'red',
		)
	)
);

// Label 6.
$wp_customize->add_setting( 'cs_pl_s_new_label_style_label_6' );

$wp_customize->add_control(
	new MK_Label_Control(
		$wp_customize,
		'cs_pl_s_new_label_style_label_6',
		array(
			'section' => 'mk_s_pl_s_new_label_style',
			'label' => __( 'SAMPLE', 'mk_framework' ),
			'label_type' => 'fancy',
			'color' => 'blue',
		)
	)
);
