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

/**
 * TẠO CẤU HÌNH BANNER BẢN ĐỒ ADTEC GROUP TRONG CUSTOMIZER
 */
function adtec_customize_register_group_banner( $wp_customize ) {
    // Section Adtec Group
    $wp_customize->add_section( 'adtec_group_section', array(
        'title'    => __( 'Banner Adtec Group (Địa điểm)', 'adtec' ),
        'priority' => 35,
    ) );

    // Setting upload ảnh map banner
    $wp_customize->add_setting( 'adtec_group_map_banner', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    // Control chọn ảnh
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'adtec_group_map_banner', array(
        'label'    => __( 'Ảnh Bản đồ Mạng lưới toàn cầu', 'adtec' ),
        'section'  => 'adtec_group_section',
        'settings' => 'adtec_group_map_banner',
        'description' => __( 'Tải lên ảnh bản đồ mạng lưới các chi nhánh trên thế giới.', 'adtec' ),
    ) ) );
}
add_action( 'customize_register', 'adtec_customize_register_group_banner' );

/**
 * SECTION: Footer Settings (Hỗ trợ đa ngôn ngữ VI/EN/JA)
 */
function adtec_customize_register_footer_slogan( $wp_customize ) {
    $section_priority = 40;
    $wp_customize->add_section('adtec_footer_section', array(
        'title'    => __('Footer Settings', 'adtec'),
        'priority' => $section_priority,
    ));

    // ─── LOGO ───
    $wp_customize->add_setting('footer_logo', array(
        'default'           => get_template_directory_uri() . '/images/logo-footer.png',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control(
        $wp_customize, 'footer_logo',
        array(
            'label'    => __('Logo Footer', 'adtec'),
            'section'  => 'adtec_footer_section',
            'settings' => 'footer_logo',
        )
    ));

    // ─── SLOGAN (3 ngôn ngữ) ───
    $slogans = array(
        'vi' => array('label' => 'Slogan Footer - Tiếng Việt', 'default' => 'Nhà cung cấp máy nguồn cao tần và bộ phối hợp trở kháng RF hàng đầu Việt Nam'),
        'en' => array('label' => 'Slogan Footer - English', 'default' => "Vietnam's leading supplier of RF generators and impedance matching networks"),
        'ja' => array('label' => 'Slogan Footer - 日本語', 'default' => 'ベトナムを代表する高周波電源およびRFマッチングボックスのサプライヤー'),
    );
    foreach ($slogans as $lang => $cfg) {
        $wp_customize->add_setting('footer_slogan_' . $lang, array(
            'default'           => $cfg['default'],
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('footer_slogan_' . $lang, array(
            'label'    => __($cfg['label'], 'adtec'),
            'section'  => 'adtec_footer_section',
            'type'     => 'textarea',
        ));
    }

    // ─── BẢN QUYỀN (3 ngôn ngữ) ───
    foreach (array(
        'vi' => array('label' => 'Bản quyền - Tiếng Việt', 'default' => '© {year} ADTEC Plasma Technology Vietnam. All rights reserved.'),
        'en' => array('label' => 'Copyright - English', 'default' => '© {year} ADTEC Plasma Technology Vietnam. All rights reserved.'),
        'ja' => array('label' => '著作権 - 日本語', 'default' => '© {year} ADTEC Plasma Technology Vietnam. All rights reserved.'),
    ) as $lang => $cfg) {
        $wp_customize->add_setting('footer_copyright_' . $lang, array(
            'default'           => $cfg['default'],
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control('footer_copyright_' . $lang, array(
            'label'    => __($cfg['label'], 'adtec'),
            'section'  => 'adtec_footer_section',
            'type'     => 'text',
        ));
    }

    // ─── SOCIAL NETWORKS REPEATER ───
    $wp_customize->add_setting('footer_socials', array(
        'default'           => '',
        'sanitize_callback' => 'adtec_sanitize_footer_socials',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new Adtec_Footer_Socials_Control(
        $wp_customize, 'footer_socials',
        array(
            'label'    => __('Mạng xã hội', 'adtec'),
            'section'  => 'adtec_footer_section',
            'settings' => 'footer_socials',
        )
    ));

    // ─── SHOW/HIDE SOCIALS ───
    $wp_customize->add_setting('footer_show_socials', array(
        'default'           => 1,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('footer_show_socials', array(
        'label'    => __('Hiển thị mạng xã hội', 'adtec'),
        'section'  => 'adtec_footer_section',
        'type'     => 'checkbox',
    ));
}
add_action( 'customize_register', 'adtec_customize_register_footer_slogan' );

/**
 * Sanitize footer socials repeater data.
 */
function adtec_sanitize_footer_socials($value) {
    if (empty($value)) return '';
    $data = json_decode(urldecode($value), true);
    if (!is_array($data)) return '';
    $clean = array();
    foreach ($data as $item) {
        if (!is_array($item)) continue;
        $platform = sanitize_key($item['platform'] ?? '');
        $url = esc_url_raw($item['url'] ?? '');
        if ($platform && $url) {
            $clean[] = array('platform' => $platform, 'url' => $url);
        }
    }
    return urlencode(wp_json_encode($clean));
}

/**
 * Customizer control: Footer Social Networks Repeater.
 */
if (class_exists('WP_Customize_Control')) {
    class Adtec_Footer_Socials_Control extends WP_Customize_Control {
        public $type = 'footer_socials';

        public function render_content() {
            $value = $this->value();
            $items = array();
            if (!empty($value)) {
                $decoded = json_decode(urldecode($value), true);
                if (is_array($decoded)) $items = $decoded;
            }

            $platforms = array(
                'facebook'  => 'Facebook',
                'youtube'   => 'Youtube',
                'linkedin'  => 'LinkedIn',
                'twitter'   => 'Twitter / X',
                'instagram' => 'Instagram',
                'tiktok'    => 'TikTok',
                'zalo'      => 'Zalo',
            );

            $platform_icons = array(
                'facebook'  => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
                'youtube'   => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>',
                'linkedin'  => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
                'twitter'   => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4l11.733 16H20L8.267 4zM4 20l6.768-6.768m2.46-2.46L20 4"></path></svg>',
                'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
                'tiktok'    => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>',
                'zalo'      => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M8 11h8M8 14h5"></path></svg>',
            );

            $platform_colors = array(
                'facebook'  => '#1877F2',
                'youtube'   => '#FF0000',
                'linkedin'  => '#0A66C2',
                'twitter'   => '#000000',
                'instagram' => '#E1306C',
                'tiktok'    => '#000000',
                'zalo'      => '#0068FF',
            );

            // Hidden input de luu gia tri (WordPress se bind setting nay)
            echo '<input type="hidden" id="adtec_footer_socials_input" data-customize-setting-link="footer_socials" value="' . esc_attr($value) . '" />';
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <span class="description" style="font-weight:400;color:#666;font-size:12px;">
                    Thêm liên kết mạng xã hội. Mỗi mục gồm: nền tảng + URL.
                </span>
            </label>

            <div class="adtec-socials-repeater" id="adtec-socials-repeater" style="margin-top:8px;">
                <div class="repeater-items" id="repeater-items">
                    <?php if (!empty($items)) : foreach ($items as $i => $item) : ?>
                    <div class="repeater-item" style="display:flex;gap:8px;align-items:center;margin-bottom:8px;padding:8px;background:#f7f7f7;border-radius:4px;" data-index="<?php echo $i; ?>">
                        <div class="platform-preview" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:4px;background:<?php echo esc_attr($platform_colors[$item['platform']] ?? '#666'); ?>;color:#fff;">
                            <?php echo $platform_icons[$item['platform']] ?? ''; ?>
                        </div>
                        <select class="platform-select" style="flex:0 0 130px;padding:4px 6px;height:32px;border:1px solid #ddd;border-radius:4px;font-size:13px;">
                            <?php foreach ($platforms as $key => $label) : ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php selected($item['platform'], $key); ?>><?php echo esc_html($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="url" class="url-input" placeholder="https://..." value="<?php echo esc_attr($item['url'] ?? ''); ?>"
                               style="flex:1;padding:4px 8px;height:32px;border:1px solid #ddd;border-radius:4px;font-size:13px;" />
                        <button type="button" class="remove-item button-secondary" style="flex:0 0 auto;height:32px;padding:0 10px;color:#d63638;border-color:#d63638;" title="Xóa">
                            &#10005;
                        </button>
                    </div>
                    <?php endforeach; endif; ?>
                </div>

                <button type="button" id="add-social-item" class="button-primary" style="margin-top:4px;">
                    + Thêm mạng xã hội
                </button>
            </div>

            <template id="social-item-template">
                <div class="repeater-item" style="display:flex;gap:8px;align-items:center;margin-bottom:8px;padding:8px;background:#f7f7f7;border-radius:4px;" data-index="__INDEX__">
                    <div class="platform-preview" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:4px;background:#1877F2;color:#fff;">
                        <?php echo $platform_icons['facebook']; ?>
                    </div>
                    <select class="platform-select" style="flex:0 0 130px;padding:4px 6px;height:32px;border:1px solid #ddd;border-radius:4px;font-size:13px;">
                        <?php foreach ($platforms as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="url" class="url-input" placeholder="https://..." value=""
                           style="flex:1;padding:4px 8px;height:32px;border:1px solid #ddd;border-radius:4px;font-size:13px;" />
                    <button type="button" class="remove-item button-secondary" style="flex:0 0 auto;height:32px;padding:0 10px;color:#d63638;border-color:#d63638;" title="Xóa">
                        &#10005;
                    </button>
                </div>
            </template>

            <script>
            (function() {
                // Su dung ID cua hidden input thay vi selector
                var container = document.getElementById('repeater-items');
                var addBtn    = document.getElementById('add-social-item');
                var template  = document.getElementById('social-item-template');
                var input     = document.getElementById('adtec_footer_socials_input');

                var platformIcons = <?php echo wp_json_encode($platform_icons); ?>;
                var platformColors = <?php echo wp_json_encode($platform_colors); ?>;

                function setItems(items) {
                    var jsonStr = JSON.stringify(items);
                    input.value = encodeURIComponent(jsonStr);
                    // Trigger change de WordPress Customizer biet gia tri da thay doi
                    var event = new Event('change');
                    input.dispatchEvent(event);
                }

                function updatePreview(itemEl) {
                    var sel  = itemEl.querySelector('.platform-select');
                    var prev = itemEl.querySelector('.platform-preview');
                    var key  = sel.value;
                    prev.style.background = platformColors[key] || '#666';
                    prev.innerHTML = platformIcons[key] || '';
                }

                function saveAll() {
                    var items = [];
                    container.querySelectorAll('.repeater-item').forEach(function(el) {
                        var url = el.querySelector('.url-input').value.trim();
                        var platform = el.querySelector('.platform-select').value;
                        if (url && platform) {
                            items.push({ platform: platform, url: url });
                        }
                    });
                    setItems(items);
                }

                container.addEventListener('input', saveAll);
                container.addEventListener('change', saveAll);

                container.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
                        var btn = e.target.classList.contains('remove-item') ? e.target : e.target.closest('.remove-item');
                        btn.closest('.repeater-item').remove();
                        saveAll();
                    }
                });

                container.addEventListener('change', function(e) {
                    if (e.target.classList.contains('platform-select')) {
                        updatePreview(e.target.closest('.repeater-item'));
                    }
                });

                addBtn.addEventListener('click', function() {
                    var tpl = template.innerHTML;
                    var idx = container.querySelectorAll('.repeater-item').length;
                    tpl = tpl.replace(/__INDEX__/g, idx);
                    var div = document.createElement('div');
                    div.innerHTML = tpl;
                    container.appendChild(div.firstElementChild);
                    updatePreview(container.querySelector('.repeater-item:last-child'));
                });
            })();
            </script>
            <?php
        }
    }
}