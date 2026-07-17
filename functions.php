<?php
/**
 * adtec functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package adtec
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

function adtec_setup() {
	load_theme_textdomain( 'adtec', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'adtec' ),
            'menu-2' => esc_html__( 'Footer Menu', 'adtec' ),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'adtec_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'adtec_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function adtec_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'adtec_content_width', 640 );
}
add_action( 'after_setup_theme', 'adtec_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function adtec_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'adtec' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'adtec' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'adtec_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
/**
 * Enqueue scripts and styles.
 */
function adtec_scripts() {
    // 1. Nhúng file style.css chính của theme
    wp_enqueue_style( 'adtec-style', get_stylesheet_uri(), array(), _S_VERSION );
    wp_style_add_data( 'adtec-style', 'rtl', 'replace' );

    // ==========================================================================
    // 2. TỰ ĐỘNG NHẬN DIỆN TRANG SLIDER ĐA NGÔN NGỮ ĐỂ NHÚNG SWIPER
    // Sử dụng helper function để tự động nhận diện tất cả CPT slider
    // ==========================================================================
    $is_slider_page = adtec_is_slider_page();

    // Chỉ nạp Swiper CSS & JS khi ở Trang chủ hoặc Trang slider (Tin tức, Sự kiện, v.v.)
    if ( is_front_page() || $is_slider_page ) {
        wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0' );
        wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true );
    }
    // ==========================================================================

    // 3. Nhúng file điều hướng mặc định của theme
    wp_enqueue_script( 'adtec-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

    // 4. Nhúng file custom JS của ông (Chứa logic khởi chạy Slider trang chủ & slider Tin tức)
    wp_enqueue_script( 'adtec-custom-news', get_template_directory_uri() . '/js/adtec-custom-news.js', array('jquery'), _S_VERSION, true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'adtec_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

require get_template_directory() . '/languages.php';
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

//Chạy plugin Meta Box ('rwmb_meta_boxes') -> gọi hàm 'adtec_register_meta_boxes' để đăng ký các ô nhập liệu cho các CPT
add_filter( 'rwmb_meta_boxes', 'adtec_register_meta_boxes' );

//Gọi adtec_enqueue_custom_styles() để load file custom-style.css
function adtec_enqueue_custom_styles() {
    // Gọi file custom-style.css nằm trong thư mục css của theme
    wp_enqueue_style(
        'adtec-custom-news-style', 
        get_stylesheet_directory_uri() . '/css/custom-style.css', 
        array(), 
        '1.0.0', 
        'all'
    );
}
add_action('wp_enqueue_scripts', 'adtec_enqueue_custom_styles');

//Tạo form nhập liệu cho các CPT (Custom Post Type) bằng Meta Box
function adtec_register_meta_boxes( $meta_boxes ) {

    // 1. Ô NHẬP LIỆU CHO CPT "SẢN PHẨM" (products)
    $meta_boxes[] = [
        'id'         => 'mb_product_details',
        'title'      => 'Thông tin Chi tiết Sản phẩm',
        'post_types' => ['products'],
        'fields'     => [
            [
                'name'  => 'Mã sản phẩm',
                'id'    => 'product_code',
                'type'  => 'text',
                'clone' => false,
            ],
            [
                'name'   => 'Bảng thông số kỹ thuật (Thêm nhiều dòng)',
                'id'     => 'product_specs',
                'type'   => 'group',
                'clone'  => true, // BẬT tính năng lặp lại (Repeater) tại đây
                'fields' => [
                    [
                        'name'  => 'Tên thông số (VD: Điện áp)',
                        'id'    => 'spec_name',
                        'type'  => 'text',
                        'clone' => false,
                    ],
                    [
                        'name'  => 'Giá trị (VD: 220V)',
                        'id'    => 'spec_value',
                        'type'  => 'text',
                        'clone' => false,
                    ],
                ],
            ],
            [
                'name'             => 'File Catalog (PDF)',
                'id'               => 'product_catalog',
                'type'             => 'file_advanced',
                'max_file_uploads' => 1,
                'clone'            => false,
            ],
            [
                'name'  => 'Thư viện ảnh sản phẩm',
                'id'    => 'product_gallery',
                'type'  => 'image_advanced',
                'clone' => false,
            ],
            [
                'name'  => 'Video giới thiệu (URL Youtube)',
                'id'    => 'product_video',
                'type'  => 'url',
                'clone' => false,
            ],
            [
                'name'       => 'Sản phẩm liên quan',
                'id'         => 'related_products',
                'type'       => 'post',
                'post_type'  => 'products',
                'field_type' => 'select_advanced',
                'multiple'   => true,
                'clone'      => false,
            ],
        ],
    ];

    // 2. Ô NHẬP LIỆU CHO CPT "KIẾN THỨC" (knowledge)
    $meta_boxes[] = [
        'id'         => 'mb_knowledge_details',
        'title'      => 'Thông tin Tài liệu',
        'post_types' => ['knowledge'],
        'fields'     => [
            [
                'name'             => 'Tài liệu đính kèm (PDF)',
                'id'               => 'knowledge_pdf',
                'type'             => 'file_advanced',
                'max_file_uploads' => 1,
                'clone'            => false,
            ],
            [
                'name'  => 'Tác giả',
                'id'    => 'knowledge_author',
                'type'  => 'text',
                'clone' => false,
            ],
            [
                'name'    => 'Cấp độ',
                'id'      => 'knowledge_level',
                'type'    => 'select',
                'options' => [
                    'coban'   => 'Cơ bản',
                    'nangcao' => 'Nâng cao',
                ],
                'clone'   => false,
            ],
        ],
    ];

    // 3. Ô NHẬP LIỆU CHO CPT "TIN TỨC" (tin_tuc)
    $meta_boxes[] = [
        'id'         => 'mb_news_details',
        'title'      => 'Chi tiết Tin tức',
        'post_types' => ['tin_tuc'],
        'context'    => 'normal',
        'priority'   => 'high',
        'fields'     => [
            [
                'name'       => 'Ngày hiển thị tin tức',
                'id'         => 'news_date',
                'type'       => 'date',
                'js_options' => [
                    'dateFormat'      => 'yy-mm-dd',
                    'changeMonth'     => true,
                    'changeYear'      => true,
                    'showButtonPanel' => true,
                ],
                'clone'      => false,
            ],
            [
                'name'             => 'Thư viện ảnh tin tức',
                'id'               => 'news_gallery',
                'type'             => 'image_advanced',
                'max_file_uploads' => 30, 
                'force_delete'     => false,
                'clone'            => false,
            ],
        ],
    ];

    // 3.1 Ô NHẬP LIỆU CHO CPT "SỰ KIỆN NĂM" (su_kien_nam)
    $meta_boxes[] = [
        'id'         => 'mb_events_details',
        'title'      => 'Chi tiết Sự Kiện Năm',
        'post_types' => ['su_kien_nam'],
        'context'    => 'normal',
        'priority'   => 'high',
        'fields'     => [
            [
                'name'       => 'Ngày hiển thị tin tức',
                'id'         => 'events_date',
                'type'       => 'date',
                'js_options' => [
                    'dateFormat'      => 'yy-mm-dd',
                    'changeMonth'     => true,
                    'changeYear'      => true,
                    'showButtonPanel' => true,
                ],
                'clone'      => false,
            ],
            [
                'name'             => 'Thư viện ảnh sự kiện',
                'id'               => 'events_gallery',
                'type'             => 'image_advanced',
                'max_file_uploads' => 30, 
                'force_delete'     => false,
                'clone'            => false,
            ],
        ],
    ];

    // 4. Ô NHẬP LIỆU CHO CPT "TUYỂN DỤNG" (tuyen_dung)
    $meta_boxes[] = [
        'id'         => 'mb_career_details',
        'title'      => 'Thông tin Đăng tuyển',
        'post_types' => ['tuyen_dung'],
        'fields'     => [
            [
                'name'  => 'Hạn nộp hồ sơ',
                'id'    => 'career_deadline',
                'type'  => 'date',
                'clone' => false,
            ],
            [
                'name'  => 'Số lượng tuyển',
                'id'    => 'career_quantity',
                'type'  => 'number',
                'clone' => false,
            ],
            [
                'name'  => 'Mức lương',
                'id'    => 'career_salary',
                'type'  => 'text',
                'desc'  => 'Có thể nhập số hoặc ghi "Thỏa thuận"',
                'clone' => false,
            ],
            [
                'name'  => 'Địa điểm làm việc',
                'id'    => 'career_work_location',
                'type'  => 'text',
                'desc'  => 'VD: KCN Biên Hòa 2, Đồng Nai',
                'clone' => false,
            ],
            [
                'name'    => 'Loại hình công việc',
                'id'      => 'career_work_type',
                'type'    => 'select',
                'options' => [
                    'vi_tri_dac_biet' => 'Vị trí đặc biệt',
                    'nhan_vien'        => 'Nhân viên',
                    'cong_nhan'        => 'Công nhân',
                    'ky_thuat_vien'    => 'Kỹ thuật viên',
                ],
                'desc'   => 'Chọn loại hình công việc (Vị trí đặc biệt/Nhân viên/Công nhân/Kỹ thuật viên)',
                'clone'   => false,
            ],
            [
                'name'  => 'Yêu cầu chung',
                'id'    => 'career_general_requirements',
                'type'  => 'textarea',
                'desc'  => 'Nhập các yêu cầu chung cho vị trí tuyển dụng',
                'clone' => false,
            ],
            [
                'name'  => 'Link Microsoft Form ứng tuyển',
                'id'    => 'career_form_link',
                'type'  => 'url',
                'desc'  => 'Dán link chia sẻ từ Microsoft Forms vào đây',
                'clone' => false,
            ],
            [
                'name'    => 'Trạng thái tin',
                'id'      => 'career_status',
                'type'    => 'select',
                'options' => [
                    'dangtuyen' => 'Đang tuyển',
                    'tamdung'   => 'Tạm dừng',
                    'dadong'    => 'Đã đóng',
                ],
                'clone'   => false,
            ],
            [
                'name'  => 'Đánh dấu Tin nổi bật',
                'id'    => 'career_featured',
                'type'  => 'checkbox',
                'desc'  => 'Tick chọn để hiển thị ở mục "Tuyển dụng đặc biệt"',
                'clone' => false,
            ],
        ],
    ];

    // 5. Ô NHẬP LIỆU CHO CPT "CÂU CHUYỆN NHÂN VIÊN" (employee-stories)
    $meta_boxes[] = [
        'id'         => 'mb_employee_details',
        'title'      => 'Thông tin Nhân viên',
        'post_types' => ['employee-stories'],
        'fields'     => [
            [
                'name'  => 'Chức danh / Phòng ban',
                'id'    => 'employee_role',
                'type'  => 'text',
                'clone' => false,
            ],
            [
                'name'  => 'Trích dẫn nổi bật',
                'id'    => 'employee_quote',
                'type'  => 'textarea',
                'clone' => false,
            ],
            [
                'name'  => 'Số năm gắn bó',
                'id'    => 'employee_years',
                'type'  => 'number',
                'clone' => false,
            ],
        ],
    ];

    // ==========================================================================
    // BỔ SUNG Ô NHẬP LIỆU CHI TIẾT CHO CPT "CỘT MỐC LỊCH SỬ" (cot_moc)
    // ==========================================================================
    $meta_boxes[] = [
        'id'         => 'mb_cot_moc_details',
        'title'      => 'Chi Tiết Cột Mốc Lịch Sử',
        'post_types' => ['cot_moc'],
        'fields'     => [
            [
                'name'       => 'Thời gian cụ thể (Ngày/Tháng/Năm)',
                'id'         => 'cot_moc_date',
                'type'       => 'date',
                'desc'       => 'Chọn ngày tháng năm diễn ra sự kiện.',
                'clone'      => false,
            ],
            [
                'name'       => 'Năm hiển thị (Để sắp xếp/lọc)',
                'id'         => 'cot_moc_year',
                'type'       => 'number',
                'desc'       => 'Nhập số năm (VD: 2026) để hệ thống làm trục thời gian.',
                'clone'      => false,
            ],
            // Lưu ý: Phần Tiêu đề lấy từ Title mặc định, Ảnh lấy từ Featured Image mặc định của WP.
        ],
    ];

    // ==========================================================================
    // BỔ SUNG Ô NHẬP LIỆU CHI TIẾT CHO CPT "ĐỊA ĐIỂM ADTEC GROUP" (dia_diem)
    // ==========================================================================
    $meta_boxes[] = [
        'id'         => 'mb_dia_diem_details',
        'title'      => 'Thông Tin Chi Nhánh / Văn Phòng',
        'post_types' => ['dia_diem'],
        'fields'     => [
            [
                'name'    => 'Phân loại địa điểm',
                'id'      => 'dia_diem_type',
                'type'    => 'select',
                'options' => [
                    'hq'      => 'Trụ sở chính (Headquarters)',
                    'sale'    => 'Văn phòng thương mại (Sale Office)',
                    'factory' => 'Nhà máy sản xuất (Factory)',
                    'branch'  => 'Chi nhánh (Branch Office)',
                ],
                'desc'    => 'Lựa chọn mô hình của cơ sở này.',
                'clone'   => false,
            ],
            [
                'name'  => 'Địa chỉ chi tiết',
                'id'    => 'dia_diem_address',
                'type'  => 'text',
                'clone' => false,
            ],
            [
                'name'  => 'Số điện thoại (TEL)',
                'id'    => 'dia_diem_tel',
                'type'  => 'text',
                'clone' => false,
            ],
            [
                'name'  => 'Số Fax (FAX)',
                'id'    => 'dia_diem_fax',
                'type'  => 'text',
                'clone' => false,
            ],
            [
                'name'  => 'Đường dẫn Website (URL)',
                'id'    => 'dia_diem_url',
                'type'  => 'url',
                'desc'  => 'Nhập link website riêng của chi nhánh nếu có.',
                'clone' => false,
            ],
        ],
    ];

    return $meta_boxes;
}

//Khai báo các Custom Post Type (CPT-Khung chứa) và Taxonomy cho website
function adv_register_all_custom_elements() {
    // Đăng ký các custom element cho Elementor tại đây
    
    // 0. Đăng ký CPT Tin tức
    register_post_type('tin_tuc', array(
        'label'        => 'Tin tức',
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => false,
        'rewrite'             => array(
            'slug'       => 'tin-tuc',
            'with_front' => false,
            ),
        'menu_icon'    => 'dashicons-media-document',
        'supports'     => array('title', 'editor', 'thumbnail'),
    ));
    // 1. Đăng ký CPT Tuyển dụng
    register_post_type('tuyen_dung', array(
        'label'        => 'Tuyển dụng',
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-groups',
        'supports'     => array('title', 'editor', 'thumbnail'),
    ));

    // 2. Đăng ký CPT Sự kiện (Giữ lại theo yêu cầu User)
    register_post_type('su_kien_nam', array(
        'label'        => 'Sự kiện năm',
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => false,
        'rewrite'             => array(
            'slug'       => 'su-kien-nam',
            'with_front' => false,
            ),
        'menu_icon'    => 'dashicons-calendar-alt',
        'supports'     => array('title', 'editor', 'thumbnail'),
    ));

    // 3. Đăng ký CPT Câu chuyện nhân viên (Bổ sung mới)
    register_post_type('cau_chuyen', array(
        'label'        => 'Câu chuyện nhân viên',
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-testimonial',
        'supports'     => array('title', 'editor', 'thumbnail'), // title: Tên NV, editor: Lời chia sẻ, thumbnail: Ảnh chân dung
    ));

    //4. Đăng ký CPT Cột mốc (Quá trình phát triển)
    register_post_type('cot_moc', array(
        'label'=> 'Cột mốc',
        'public'=>true,
        'show_in_rest'=>true,
        'has_archive'=>false,
        'menu_icon'=>'dashicons-flag',
        'supports'=>array('title','editor','thumbnail'), // title: Năm, editor: Nội dung, thumbnail: Ảnh minh họa
    ));

    //5. Đăng ký CPT địa điểm (Adtec Group)
    register_post_type('dia_diem', array(
        'label'        => 'Địa điểm',
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-location',
        'supports'     => array('title', 'editor', 'thumbnail'),
    ));

    //6. Đăng ký CPT môi trường làm việc (Adtec )
    register_post_type('moi_truong_lam_viec', array(
        'label'        => 'Môi trường làm việc',
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-images-alt2',
        'supports'     => array('title', 'editor', 'thumbnail'),
    ));
}
add_action('init', 'adv_register_all_custom_elements');

function adv_register_all_taxonomies() {

    // Đăng ký Taxonomy Năm cho CPT Tin tức
    register_taxonomy('nam_tin_tuc', 'tin_tuc', array(
        'label'        => 'Năm',
        'hierarchical' => true,
        'show_in_rest' => true,
    ));

    // Đăng ký Taxonomy Phòng ban cho CPT Tuyển dụng
    register_taxonomy('phong_ban', 'tuyen_dung', array(
        'label'        => 'Phòng ban',
        'hierarchical' => true,
        'show_in_rest' => true,
    ));

    // Đăng ký Taxonomy Loại vị trí cho CPT Tuyển dụng (Tuyển dụng đặc biệt, Nhân viên, Công nhân)
    register_taxonomy('loai_vi_tri', 'tuyen_dung', array(
        'label'        => 'Loại vị trí',
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
    ));

    // Đăng ký Taxonomy Năm cho CPT Sự kiện
    register_taxonomy('nam_su_kien', 'su_kien_nam', array(
        'label'        => 'Năm',
        'hierarchical' => true,
        'show_in_rest' => true,
    ));

    // Đăng ký Taxonomy Năm cho CPT Cột mốc
    register_taxonomy('nam_lich_su', 'cot_moc', array(
        'label'        => 'Năm',
        'hierarchical' => true,
        'show_in_rest' => true,
    ));

    register_taxonomy('quoc_gia', 'dia_diem', array(
        'label'        => 'Quốc gia',
        'hierarchical' => true,
        'show_in_rest' => true,
    ));

    // Đăng ký Taxonomy Loại cho CPT Môi trường làm việc
    register_taxonomy('khu_vuc_nha_may', 'moi_truong_lam_viec', array(
        'label'        => 'Khu vực nhà máy',
        'hierarchical' => true,
        'show_in_rest' => true,
    ));
}
add_action('init', 'adv_register_all_taxonomies');

// Tạo default terms cho taxonomy loai_vi_tri khi theme activate
function adtec_create_default_job_type_terms() {
    $terms = array(
        'tuyen-dung-dac-biet' => 'Tuyển dụng đặc biệt',
        'nhan-vien' => 'Nhân viên',
        'cong-nhan' => 'Công nhân',
    );

    foreach ($terms as $slug => $name) {
        if (!term_exists($name, 'loai_vi_tri')) {
            wp_insert_term(
                $name,
                'loai_vi_tri',
                array(
                    'slug' => $slug,
                )
            );
        }
    }
}
add_action('after_setup_theme', 'adtec_create_default_job_type_terms');

/**
 * Hàm hỗ trợ tìm kiếm phần tử cha trong mảng Menu Items
 */
function adv_find_menu_item($items, $id) {
    foreach ($items as $item) {
        if ((int) $item->ID === $id) {
            return $item;
        }
    }
    return null;
}

/**
 * Hàm lấy chuỗi dữ liệu Breadcrumb tự sinh theo cấu trúc Menu chính
 */
function adv_get_breadcrumb() {
    if (is_front_page() || is_home()) {
        return array();
    }

    $post_id = get_the_ID();
    
    // 1. Nhận diện ngôn ngữ từ Polylang để bốc đúng Menu tương ứng
    if ( function_exists('pll_current_language') ) {
        $current_lang = pll_current_language(); // Trả về 'vi', 'en', 'ja'
        
        if ( $current_lang == 'en' ) {
            $menu_name = 'Primary Menu English';
        } elseif ( $current_lang == 'ja' ) {
            $menu_name = 'Primary Menu Japanese';
        } else {
            $menu_name = 'Primary Menu'; // Mặc định tiếng Việt
        }
    } else {
        $menu_name = 'Primary Menu';
    }

    // 2. Lấy các mục menu
    $menu_items = wp_get_nav_menu_items($menu_name);
    
    if (empty($menu_items)) {
        return array();
    }

    $current = null;
    // Tìm mục menu có object_id trùng với ID của trang/post hiện tại
    foreach ($menu_items as $item) {
        if ((int) $item->object_id === (int) $post_id) {
            $current = $item;
            break;
        }
    }

    $trail = array();
    // Trace ngược từ vị trí hiện tại lên tới gốc (parent = 0)
    while ($current) {
        $trail[] = array(
            'title' => $current->title, 
            'url'   => $current->url
        );
        $parent_id = (int) $current->menu_item_parent; // Lấy ID phần tử cha
        $current = $parent_id ? adv_find_menu_item($menu_items, $parent_id) : null;
    }

    // 3. Tự động dịch chữ "Home" đầu chuỗi theo ngôn ngữ đang xem
    $home_label = 'Home';
    if (function_exists('pll_current_language')) {
        $lang = pll_current_language();
        if ($lang == 'vi') {
            $home_label = 'Trang chủ';
        } elseif ($lang == 'ja') {
            $home_label = 'ホーム';
        }
    }

    $trail[] = array(
        'title' => adtec_get_label('home'), 
        'url'   => home_url('/')
    );
    
    // Đảo ngược mảng để chạy đúng thứ tự: Home > Cha > Con
    $trail = array_reverse($trail);
    
    return $trail;
}

/**
 * Hàm hiển thị cấu trúc HTML của Breadcrumb
 */
function adv_display_breadcrumb() {
    $breadcrumbs = adv_get_breadcrumb();
    if (empty($breadcrumbs)) {
        return;
    }

    echo '<div class="adv-breadcrumb-row">';
    echo '<div class="adv-breadcrumb-container">';
    echo '<nav class="adv-breadcrumb" aria-label="Breadcrumb">';
    $count = count($breadcrumbs);
    foreach ($breadcrumbs as $index => $crumb) {
        if ($index === $count - 1) {
            // Mục cuối cùng (trang hiện tại)
            echo '<span class="current-page">' . esc_html($crumb['title']) . '</span>';
        } else {
            echo '<a href="' . esc_url($crumb['url']) . '">' . esc_html($crumb['title']) . '</a>';
            echo '<span class="separator">></span>';
        }
    }
    echo '</nav>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * Xóa sạch cache transient của breadcrumb mỗi khi Update Menu để giao diện cập nhật ngay lập tức
 */
function adv_clear_breadcrumb_transients() {
    global $wpdb;
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_adv_breadcrumb_post_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_adv_breadcrumb_post_%'");
}
add_action('wp_update_nav_menu', 'adv_clear_breadcrumb_transients');
add_action('save_post', 'adv_clear_breadcrumb_transients');

/* ==========================================================================
   CUSTOM WALKER ĐỂ THÊM DIV BỌC NGOÀI SUB-MENU
   ========================================================================== */
class Adtec_Mega_Menu_Walker extends Walker_Nav_Menu {
    // Hàm này chạy khi BẮT ĐẦU một menu con (tương đương thẻ mở ul)
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );
        // Đẻ ra thẻ Div vỏ (giống code của ông) và thẻ ul lõi
        $output .= "\n$indent<div class=\"mGlobalNaviChild__container\">\n$indent<ul class=\"sub-menu mGlobalNaviChild__holder\">\n";
    }

    // Hàm này chạy khi KẾT THÚC một menu con (tương đương thẻ đóng ul)
    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat( "\t", $depth );
        // Đóng thẻ ul và thẻ Div vỏ lại
        $output .= "$indent</ul>\n$indent</div>\n";
    }
}

// --- XỬ LÝ AJAX LOAD MORE (HỖ TRỢ ĐA CPT-slider)
add_action('wp_ajax_adtec_load_more_posts', 'adtec_load_more_posts_callback');
add_action('wp_ajax_nopriv_adtec_load_more_posts', 'adtec_load_more_posts_callback');

function adtec_load_more_posts_callback() {
    // 1. Nhận các tham số động từ JS gửi lên
    $page      = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'tin_tuc';
    $lang      = isset($_POST['lang']) ? sanitize_text_field($_POST['lang']) : 'vi';
    $next_page = $page + 1;

    // 2. Cấu hình Query động theo Post Type và Ngôn ngữ hiện tại
    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => 4,
        'paged'          => $next_page,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'lang'           => $lang,
    );

    // 3. Sử dụng helper function để lấy meta key ngày tự động cho CPT
    //    Nếu CPT có config trong adtec_get_slider_cpts_config() sẽ dùng meta key từ config
    //    Nếu không có config, sẽ dùng 'news_date' làm mặc định
    $date_meta = adtec_get_cpt_date_meta( $post_type );
    $args['meta_key'] = $date_meta;
    $args['orderby']  = 'meta_value';

    $ajax_query = new WP_Query($args);

    if ( $ajax_query->have_posts() ) :
        while ( $ajax_query->have_posts() ) : $ajax_query->the_post();

            // Lấy ngày hiển thị (sử dụng meta key động)
            $custom_date = get_post_meta(get_the_ID(), $date_meta, true);
            $display_date = !empty($custom_date) ? date('d/m/Y', strtotime($custom_date)) : get_the_date('d/m/Y');
            ?>

            <!-- Render HTML Item (Cấu trúc chung cho tất cả CPT slider) -->
            <div class="<?php echo esc_attr($post_type); ?>-row-item news-row-item">
                <div class="news-item-date"><?php echo esc_html($display_date); ?></div>
                <div class="news-item-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </div>
                <div class="news-item-thumb">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail('thumbnail'); ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php endwhile;
        wp_reset_postdata();
    else :
        echo 0; // Hết bài để JS ẩn nút
    endif;

    wp_die();
}

// Truyền các biến toàn cục cần thiết sang file JS (Gồm cả ngôn ngữ hiện tại)
add_action('wp_enqueue_scripts', function() {
    $current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';

    wp_localize_script('adtec-custom-news', 'adtec_ajax_obj', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'lang'     => $current_lang // Truyền ngôn ngữ sang JS để nó bốc đúng bản dịch bài viết khi load more!
    ));
}, 100);


/**
 * ==========================================================================
 * ĐỘNG CƠ DỊCH SLUG ĐỘNG 100% DỰA TRÊN FILE TỪ ĐIỂN LANGUAGES.PHP
 * ==========================================================================
 */

// 1. Tự động dịch link liên kết (Permalink) hiển thị ngoài Frontend
add_filter('post_type_link', 'adtec_auto_translate_cpt_permalinks', 10, 2);
function adtec_auto_translate_cpt_permalinks($post_link, $post) {
    // Tự động lấy danh sách tất cả CPT được đăng ký (loại trừ các post_type mặc định của WP)
    $custom_post_types = get_post_types(array('_builtin' => false));
    
    if (in_array($post->post_type, $custom_post_types)) {
        // Lấy ngôn ngữ hiện tại của bài viết cụ thể này
        $lang = function_exists('pll_get_post_language') ? pll_get_post_language($post->ID) : 'vi';
        
        // Tạo key từ điển tương ứng (Ví dụ: 'slug_tintuc', 'slug_products'...)
        $key = 'slug_' . str_replace('_', '', $post->post_type); 
        
        // Lấy slug gốc (tiếng Việt) và slug ngôn ngữ đích từ languages.php
        $vi_slug = adtec_get_label_by_lang($key, 'vi');
        $translated_slug = adtec_get_label_by_lang($key, $lang);

        // Nếu có slug dịch và nó khác slug mặc định tiếng Việt, tiến hành thay thế trong URL
        if ($vi_slug && $translated_slug && $vi_slug !== $translated_slug && $vi_slug !== $key) {
            $post_link = str_replace('/' . $vi_slug . '/', '/' . $translated_slug . '/', $post_link);
        }
    }
    return $post_link;
}

// 2. Tự động tạo luật Rewrite Rules cho WordPress đọc hiểu link dịch của tất cả CPT
add_action('init', 'adtec_auto_add_cpt_rewrite_rules', 99);
function adtec_auto_add_cpt_rewrite_rules() {
    $custom_post_types = get_post_types(array('_builtin' => false));
    $languages = array('en', 'ja'); // Các ngôn ngữ phụ cần cấu hình URL

    foreach ($custom_post_types as $cpt) {
        $key = 'slug_' . str_replace('_', '', $cpt); 

        foreach ($languages as $lang) {
            $translated_slug = adtec_get_label_by_lang($key, $lang);
            
            // Nếu tìm thấy slug đã dịch trong languages.php, tự động nạp luật Rewrite cho WP
            if ($translated_slug && $translated_slug !== $key) {
                add_rewrite_rule(
                    '^(' . $lang . ')/' . $translated_slug . '/([^/]+)(?:/([0-9]+))?/?$',
                    'index.php?lang=$matches[1]&' . $cpt . '=$matches[2]',
                    'top'
                );
            }
        }
    }
}