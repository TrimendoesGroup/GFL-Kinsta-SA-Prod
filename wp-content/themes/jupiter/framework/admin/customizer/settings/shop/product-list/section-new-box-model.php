<?php
/**
 * Add Box section of Product List > Styles.
 * Prefix: s -> shop, pl -> product-list, s -> styles
 *
 * @package WordPress
 * @subpackage Jupiter
 * @since 5.9.4
 */

$wp_customize->add_section( 'mk_s_pl_s_new_box_model', array(
		'title' => __( 'New Box Model', 'mk_framework' ),
		'priority' => 1,
		'active_callback' => array( $this, 'hide_sections' ),
		'type' => array(
			'mk-dialog',
			'mk_s_pl_dialog',
		),
	)
);


// Box Model 1.
$wp_customize->add_setting( 'cs_pl_s_new_box_model_2', array(
	'default' => array(
		'margin_right'   => 0,
		'margin_bottom'  => 0,
		'margin_left'    => 7,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'cs_pl_s_new_box_model_2',
		array(
			'section' => 'mk_s_pl_s_new_box_model',
			'column'  => 'mk-col-12',
		)
	)
);

// Box Model 2.
$wp_customize->add_setting( 'cs_pl_s_new_box_model_3', array(
	'default' => array(
		'padding_right'   => 0,
		'padding_bottom'  => 0,
		'padding_left'    => 7,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'cs_pl_s_new_box_model_3',
		array(
			'section' => 'mk_s_pl_s_new_box_model',
			'column'  => 'mk-col-12',
		)
	)
);

// Box Model 3.
$wp_customize->add_setting( 'cs_pl_s_new_box_model_4', array(
	'default' => array(
		'margin_right'   => 0,
		'margin_bottom'  => 0,
		'margin_left'    => 7,
		'padding_left'    => 12,
		'padding_right'    => 1,
		'padding_top'    => 4,
	),
	'transport' => 'postMessage',
) );

$wp_customize->add_control(
	new MK_Box_Model_Control(
		$wp_customize,
		'cs_pl_s_new_box_model_4',
		array(
			'section' => 'mk_s_pl_s_new_box_model',
			'column'  => 'mk-col-12',
		)
	)
);
