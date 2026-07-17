<?php
/**
 * adtec Theme Customizer
 *
 * @package adtec
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function adtec_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'adtec_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'adtec_customize_partial_blogdescription',
			)
		);
	}

	// =================================================================
	// SECTION: Banner Tuyển dụng
	// =================================================================
	$wp_customize->add_section('career_banner_section', array(
		'title'    => 'Banner Tuyển dụng',
		'priority' => 30,
	));

	// 1. Image upload
	$wp_customize->add_setting('career_banner_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage',
	));
	$wp_customize->add_control(new WP_Customize_Image_Control(
		$wp_customize, 'career_banner_image',
		array(
			'label'    => 'Ảnh Banner',
			'section'  => 'career_banner_section',
			'settings' => 'career_banner_image',
		)
	));

	// 2. Title
	$wp_customize->add_setting('career_banner_title', array(
		'default'           => 'TUYỂN DỤNG',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	));
	$wp_customize->add_control('career_banner_title', array(
		'label'    => 'Tiêu đề Banner',
		'section'  => 'career_banner_section',
		'type'     => 'text',
	));

	// 3. Subtitle
	$wp_customize->add_setting('career_banner_subtitle', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'postMessage',
	));
	$wp_customize->add_control('career_banner_subtitle', array(
		'label'    => 'Phụ đề Banner',
		'section'  => 'career_banner_section',
		'type'     => 'textarea',
	));

	// 4. Height
	$wp_customize->add_setting('career_banner_height', array(
		'default'           => '400px',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	));
	$wp_customize->add_control('career_banner_height', array(
		'label'    => 'Chiều cao Banner',
		'section'  => 'career_banner_section',
		'type'     => 'select',
		'choices'  => array(
			'300px' => 'Nhỏ (300px)',
			'400px' => 'Vừa (400px)',
			'500px' => 'Lớn (500px)',
		),
	));

	// 5. Overlay opacity
	$wp_customize->add_setting('career_banner_overlay_opacity', array(
		'default'           => '0.4',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	));
	$wp_customize->add_control('career_banner_overlay_opacity', array(
		'label'    => 'Độ mờ Overlay',
		'section'  => 'career_banner_section',
		'type'     => 'select',
		'choices'  => array(
			'0.2' => '20%',
			'0.3' => '30%',
			'0.4' => '40%',
			'0.5' => '50%',
			'0.6' => '60%',
		),
	));
	// =================================================================
}
add_action( 'customize_register', 'adtec_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function adtec_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function adtec_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function adtec_customize_preview_js() {
	wp_enqueue_script( 'adtec-custom-newsizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'adtec_customize_preview_js' );
