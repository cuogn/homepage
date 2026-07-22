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

    // Lưu career_work_type options vào global để sử dụng ở frontend
    global $adtec_career_work_type_options;
    $adtec_career_work_type_options = array(
        'vi_tri_dac_biet' => 'Vị trí đặc biệt',
        'nhan_vien'       => 'Nhân viên',
        'cong_nhan'       => 'Công nhân',
        'ky_thuat_vien'   => 'Kỹ thuật viên',
    );

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
    // BỔ SUNG Ô NHẬP LIỆU CHI TIẾT CHO CPT "QUÁ TRÌNH PHÁT TRIỂN" (cot_moc)
    // ==========================================================================
    $meta_boxes[] = [
        'id'         => 'mb_cot_moc_details',
        'title'      => 'Chi Tiết Quá Trình Phát Triển',
        'post_types' => ['cot_moc'],
        'fields'     => [
            [
                'name'       => 'Thời gian cụ thể (Ngày/Tháng/Năm)',
                'id'         => 'cot_moc_date',
                'type'       => 'date',
                'desc'       => 'Chọn ngày tháng năm diễn ra sự kiện.',
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
                'name'  => 'Thứ tự hiển thị (Order)',
                'id'    => 'dia_diem_order',
                'type'  => 'number',
                'std'   => 0,
                'desc'  => 'Nhập số (1, 2, 3...). Số nhỏ hơn sẽ hiển thị trước (VD: 1 hiển thị đầu tiên)',
                'clone' => false,
            ],
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
                'clone' => true,
                'desc'  => 'Bấm "+ Add more" để thêm nhiều TEL',
            ],
            [
                'name'  => 'Số Fax (FAX)',
                'id'    => 'dia_diem_fax',
                'type'  => 'text',
                'clone' => true,
                'desc'  => 'Bấm "+ Add more" để thêm nhiều số FAX',
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

    // META BOX CHO CPT CÂU CHUYỆN NHÂN VIÊN (cau_chuyen)
    $meta_boxes[] = [
        'id'         => 'mb_employee_stories_details',
        'title'      => 'Thông Tin Nhân Viên',
        'post_types' => ['cau_chuyen'],
        'fields'     => [
            [
                'name'  => 'Vị trí / Chức danh',
                'id'    => 'employee_role',
                'type'  => 'text',
                'desc'  => 'Ví dụ: Trưởng phòng Kho, Trưởng phòng Điều chỉnh tổng hợp',
                'clone' => false,
            ],
        ],
    ];

    // META BOX CHO CPT TRANG THIẾT BỊ (trang_thiet_bi)
    $meta_boxes[] = [
        'id'         => 'mb_trang_thiet_bi_details',
        'title'      => 'Chọn ảnh & Chọn Vùng',
        'post_types' => ['trang_thiet_bi'],
        'fields'     => [
            [
                'name'             => 'Thư viện ảnh trang thiết bị',
                'id'               => 'trang_thiet_bi_gallery',
                'type'             => 'image_advanced',
                'max_file_uploads' => 30, 
                'force_delete'     => true,
                'clone'            => false,
            ],
        ],
    ];

    // META BOX THƯ VIỆN ẢNH SLIDER CHO TRANG THIẾT BỊ (PAGE)
    $meta_boxes[] = [
        'id'         => 'mb_page_equipment_slider',
        'title'      => 'Thư Viện Ảnh Slider Banner Trên',
        'post_types' => ['page'],
        'show'       => [
            'template' => ['page-trang-thiet-bi.php'],
        ],
        'fields'     => [
            [
                'name'             => 'Album Ảnh Slider',
                'id'               => 'factory_slider_images',
                'type'             => 'image_advanced',
                'max_file_uploads' => 10,
            ],
        ],
    ];

    // META BOX CHO 2 TRANG MÁY NGUỒN CAO TẦN & BỘ PHỐI HỢP TRỞ KHÁNG
    $meta_boxes[] = [
        'id'         => 'mb_may_nguon_cao_tan_details',
        'title'      => 'Thông Tin Chi Tiết Máy Nguồn Cao Tần',
        'post_types' => ['page'],
        'show'       => [
            'template' => [
                'page-may-nguon-cao-tan.php',
                'page-bo-phoi-hop-tro-khang.php',
            ],
        ],
        'fields'     => [
            [
                'name'  => 'Tên đầy đủ / Mã sản phẩm nổi bật',
                'id'    => 'generator_product_title',
                'type'  => 'text',
                'desc'  => 'Ví dụ: 24949 ADTEC RF PLASMA GENERATOR AXR-600III',
                'clone' => false,
            ],
            [
                'name'  => 'Đoạn mô tả giới thiệu thiết bị',
                'id'    => 'generator_description',
                'type'  => 'textarea',
                'rows'  => 4,
                'clone' => false,
            ],
            [
                'name'       => 'Danh sách thông số / Tình trạng máy (Ví dụ: Thương hiệu: ADTEC)',
                'id'         => 'generator_specs_list',
                'type'       => 'text',
                'clone'      => true, // Cho phép thêm nhiều dòng thông số
                'sort_clone' => true,
                'add_button' => '+ Thêm dòng thông số mới',
                'desc'       => 'Nhập theo dạng: "Thương hiệu: ADTEC" hoặc "Mã sản phẩm: AXR-600III"',
            ],
            [
                'name'  => 'Ghi chú / Lưu ý bổ sung (Chữ in nghiêng bên dưới)',
                'id'    => 'generator_note',
                'type'  => 'textarea',
                'rows'  => 2,
                'desc'  => 'Ví dụ: (Linh kiện chưa được kiểm tra và bán nguyên trạng, không bảo hành hoặc đổi trả)',
                'clone' => false,
            ],
        ],
    ];
    // 2. Meta Box cho CPT "Các sản phẩm khác"
    $meta_boxes[] = [
        'id'         => 'mb_other_products_details',
        'title'      => 'Danh Sách Các Sản Phẩm Phụ Trợ / Khác',
        'post_types' => ['cac_san_pham_khac'],
        'fields'     => [
            [
                'name' => 'Đường dẫn sản phẩm',
                'id'   => 'other_product_url',
                'type' => 'url',
            ],
        ],
    ];

    // Ô NHẬP LIỆU CHO CPT "THÔNG TIN TUYỂN DỤNG" (thong_tin_tuyen_dung)
    $meta_boxes[] = [
        'id'         => 'mb_recruitment_details',
        'title'      => 'Chi tiết Thông tin tuyển dụng',
        'post_types' => ['thong_tin_tuyen_dung'],
        'context'    => 'normal',
        'priority'   => 'high',
        'fields'     => [
            [
                'name'       => 'Ngày hiển thị',
                'id'         => 'recruitment_date',
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
                'name'             => 'Thư viện ảnh thông tin tuyển dụng',
                'id'               => 'recruitment_gallery',
                'type'             => 'image_advanced',
                'max_file_uploads' => 30, 
                'force_delete'     => false,
                'clone'            => false,
            ],
        ],
    ];

    return $meta_boxes;
}

//Khai báo các Custom Post Type (CPT-Khung chứa) và Taxonomy cho website
function adv_register_all_custom_elements() {
    
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
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-groups',
        'supports'     => array('title', 'editor', 'thumbnail'),
        'rewrite'      => array(
            'slug'       => 'form-ung-tuyen',
            'with_front' => false,
        ),
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
        'label'=> 'Quá trình phát triển',
        'public'=>true,
        'show_in_rest'=>true,
        'has_archive'=>false,
        'rewrite'      => array(
            'slug'       => 'qua-trinh-phat-trien',
            'with_front' => false,
        ),
        'menu_icon'=>'dashicons-flag',
        'supports'=>array('title','editor','thumbnail'), // title: Năm, editor: Nội dung, thumbnail: Ảnh minh họa
    ));

    //5. Đăng ký CPT địa điểm (Adtec Group)
    register_post_type('dia_diem', array(
        'label'        => 'Adtec Group',
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => false,
        'rewrite'      => array(
            'slug'       => 'adtec-group',
            'with_front' => false,
        ),
        'menu_icon'    => 'dashicons-location',
        'supports'     => array('title', 'editor', 'thumbnail'),
    ));

    //6. Đăng ký CPT môi trường làm việc (Adtec )
    register_post_type('trang_thiet_bi', array(
        'label'        => 'Trang thiết bị',
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-images-alt2',
        'supports'     => array('title', 'editor', 'thumbnail'),
    ));

    //6. Đăng ký CPT Các sản phẩm khác
    register_post_type('cac_san_pham_khac', array(
        'label'        => 'Sản phẩm',
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-products',
        'supports'     => array('title', 'editor', 'thumbnail'),
    ));

    // 7. Đăng ký CPT Thông tin tuyển dụng (Giữ lại theo yêu cầu User)
    register_post_type('thong_tin_tuyen_dung', array(
        'label'        => 'Thông tin tuyển dụng',
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => false,
        'rewrite'             => array(
            'slug'       => 'thong-tin-tuyen-dung',
            'with_front' => false,
            ),
        'menu_icon'    => 'dashicons-groups',
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
    // Đăng ký Taxonomy Năm cho CPT Sự kiện
    register_taxonomy('nam_su_kien', 'su_kien_nam', array(
        'label'        => 'Năm',
        'hierarchical' => true,
        'show_in_rest' => true,
    ));
    // Đăng ký Taxonomy Năm cho CPT Cột mốc
    register_taxonomy('nam_qua_trinh_phat_trien', 'cot_moc', array(
        'label'        => 'Năm',
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => array(
            'slug'       => 'qua-trinh-phat-trien',
            'with_front' => false,
        ),
    ));

    register_taxonomy('quoc_gia', 'dia_diem', array(
        'label'        => 'Quốc gia',
        'hierarchical' => true,
        'show_in_rest' => true,
    ));

    // Đăng ký Taxonomy Loại cho CPT Môi trường làm việc
    register_taxonomy('khu_vuc_nha_may', 'trang_thiet_bi', array(
        'label'        => 'Khu vực',
        'hierarchical' => true,
        'show_in_rest' => true,
    ));
}
add_action('init', 'adv_register_all_taxonomies');

// Filter để sử dụng template riêng cho CPT tuyển dụng
add_filter('single_template', function($template) {
    global $post;
    if ($post && $post->post_type === 'tuyen_dung') {
        $custom_template = locate_template('single-form_ung_tuyen.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    return $template;
});

/**
 * Lấy options cho career_work_type từ global variable
 */
function adtec_get_career_work_type_options() {
    global $adtec_career_work_type_options;
    
    if (isset($adtec_career_work_type_options) && is_array($adtec_career_work_type_options)) {
        return $adtec_career_work_type_options;
    }
    
    // Fallback nếu global chưa được set
    return array(
        'nhan_vien'       => 'Nhân viên',
        'cong_nhan'       => 'Công nhân',
        'ky_thuat_vien'   => 'Kỹ thuật viên',
    );
}

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
/**
 * 1. ÉP LINK TRÊN HEADER SWITCHER: Khi ở bài viết Form ứng tuyển, tất cả nút cờ/ENG/JA trên Header đều bị ép trỏ về link Tiếng Việt hiện tại
 */
add_filter('pll_the_language_link', 'adtec_force_form_ung_tuyen_lang_link', 99, 3);
function adtec_force_form_ung_tuyen_lang_link($url, $slug, $locale) {
    if (( is_singular( array('tuyen_dung', 'form_ung_tuyen') ) || is_page('form-ung-tuyen') || is_page_template('page-tuyen-dung.php') )) {
        // Luôn trả về permalink bản Tiếng Việt của bài viết/trang hiện tại
        return get_permalink(get_the_ID());
    }
    return $url;
}

/**
 * 2. CHUYỂN HƯỚNG CỨNG (REDIRECT): Nếu gõ URL /en/ hoặc /ja/ trên trình duyệt thì tự động đá về Tiếng Việt
 */
add_action('template_redirect', 'adtec_redirect_careers_to_vi', 1);
function adtec_redirect_careers_to_vi() {
    if ( is_admin() ) return;

    $is_single = is_singular( array( 'tuyen_dung', 'form_ung_tuyen' ) ); 
    $is_page   = is_page('form-ung-tuyen') || is_page('tuyen-dung') || is_page_template('page-tuyen-dung.php');

    if ( $is_single || $is_page ) {
        $current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';

        // Nếu phát hiện đang ở ngôn ngữ EN hoặc JA
        if ( $current_lang !== 'vi' ) {
            
            if ( $is_single ) {
                $post_id = get_the_ID();
                // Tìm ID bài viết bản Tiếng Việt gốc
                $vi_post_id = function_exists('pll_get_post') ? pll_get_post($post_id, 'vi') : $post_id;
                $redirect_url = get_permalink($vi_post_id ?: $post_id);
            } else {
                $redirect_url = home_url('/form-ung-tuyen/');
            }

            wp_safe_redirect($redirect_url, 301);
            exit;
        }
    }
}

/**
 * 3. HỖ TRỢ VÔ HIỆU HÓA/ẨN NÚT ĐỔI NGÔN NGỮ KHI XEM TRANG NÀY (BẰNG CSS)
 */
add_action('wp_head', 'adtec_disable_lang_switcher_css', 999);
function adtec_disable_lang_switcher_css() {
    if ( is_singular( array('tuyen_dung', 'form_ung_tuyen') ) || is_page('form-ung-tuyen') || is_page_template('page-tuyen-dung.php') ) {
        ?>
        <style id="disable-lang-switcher-style">
            /* Làm chìm hoặc khóa bấm nút ngôn ngữ trên Header khi ở Tuyển dụng */
            .lang-switcher, 
            .polylang-switcher,
            .header-lang-dropdown,
            .mGlobalNaviLang,
            .pll-parent-menu-item {
                pointer-events: none !important; /* Vô hiệu hóa hành động click */
                opacity: 0.6 !important; /* Làm mờ nhẹ báo hiệu không đổi được */
                cursor: not-allowed !important;
            }
        </style>
        <?php
    }
}


/**
 * TỰ ĐỘNG CHẶN TRUY CẬP TRANG CHI TIẾT TUYỂN DỤNG KHI ĐÃ QUÁ HẠN HỒ SƠ
 */
add_action('template_redirect', 'adtec_block_expired_careers');
function adtec_block_expired_careers() {
    if ( is_admin() ) return;

    // Chỉ check khi vào bài viết chi tiết Tuyển dụng hoặc Form ứng tuyển
    if ( is_singular( array('tuyen_dung', 'form_ung_tuyen') ) ) {
        $post_id = get_the_ID();
        
        // 1. Lấy ngày hạn nộp từ Meta Box (Định dạng Y-m-d hoặc Ymd)
        $deadline_raw = get_post_meta($post_id, 'career_deadline', true); // Hoặc 'events_date' tùy key Meta Box của ông

        if ( ! empty($deadline_raw) ) {
            $today = date('Y-m-d');
            $deadline = date('Y-m-d', strtotime($deadline_raw));

            // 2. So sánh: Nếu Hạn nộp < Ngày hiện tại -> Đã quá hạn!
            if ( $deadline < $today ) {
                
                // Cách A: Chuyển hướng về trang danh sách tuyển dụng kèm thông báo
                $redirect_url = home_url('/form-ung-tuyen/');
                wp_safe_redirect($redirect_url, 302);
                exit;

                /* 
                // Cách B: Nếu muốn bắn ra trang 404 luôn thì dùng code dưới này (thay cho Cách A)
                global $wp_query;
                $wp_query->set_404();
                status_header(404);
                nocache_headers();
                include( get_query_template( '404' ) );
                exit;
                */
            }
        }
    }
}

// Banner Câu chuyện nhân viên
function adtec_customize_register_employee_banner( $wp_customize ) {
    // 1. Tạo Section "Banner Câu chuyện nhân viên"
    $wp_customize->add_section( 'adtec_employee_story_banner_section', array(
        'title'    => __( 'Banner Câu chuyện nhân viên', 'adtec' ),
        'priority' => 32,
    ) );

    // 2. Tạo Setting lưu đường dẫn ảnh
    $wp_customize->add_setting( 'adtec_employee_story_banner_img', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    // 3. Tạo Control chọn ảnh từ Media
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'adtec_employee_story_banner_img_control', array(
        'label'    => __( 'Ảnh Banner Câu chuyện nhân viên', 'adtec' ),
        'section'  => 'adtec_employee_story_banner_section',
        'settings' => 'adtec_employee_story_banner_img',
    ) ) );
}
add_action( 'customize_register', 'adtec_customize_register_employee_banner' );

// TỰ ĐỘNG ĐIỀU HƯỚNG HIỂN THỊ METABOX THEO PAGE TEMPLATE (Classic & Gutenberg)
add_action( 'admin_footer', function() {
    $screen = get_current_screen();
    // Chỉ chạy trong trang soạn thảo Page
    if ( ! $screen || $screen->base !== 'post' || $screen->post_type !== 'page' ) {
        return;
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Bản đồ liên kết: "Tên file Template" => [ "ID của Meta Box" ]
        const templateMetaMap = {
            'page-trang-thiet-bi.php': ['#mb_page_equipment_slider'],
            'page-may-nguon-cao-tan.php': ['#mb_may_nguon_cao_tan_details'],
            'page-bo-phoi-hop-tro-khang.php': ['#mb_may_nguon_cao_tan_details']
        };

        function handleTemplateMetaBoxes(currentTemplate) {
            // 1. Mặc định ẩn tất cả các Metabox đặc thù
            Object.values(templateMetaMap).forEach(function(selectors) {
                selectors.forEach(function(selector) {
                    $(selector).hide();
                });
            });

            // 2. Chỉ hiển thị metabox thuộc về Template đang được chọn
            if (templateMetaMap[currentTemplate]) {
                templateMetaMap[currentTemplate].forEach(function(selector) {
                    $(selector).show();
                });
            }
        }

        // --- HỖ TRỢ TRÌNH SOẠN THẢO CLASSIC EDITOR ---
        if ($('#page_template').length) {
            handleTemplateMetaBoxes($('#page_template').val());
            $('#page_template').on('change', function() {
                handleTemplateMetaBoxes($(this).val());
            });
        }

        // --- HỖ TRỢ TRÌNH SOẠN THẢO GUTEBERG (BLOCK EDITOR) REAL-TIME ---
        if (typeof wp !== 'undefined' && wp.data && wp.data.subscribe) {
            let lastTemplate = null;
            wp.data.subscribe(function() {
                const editor = wp.data.select('core/editor');
                if (editor) {
                    const currentTemplate = editor.getEditedPostAttribute('template');
                    if (currentTemplate !== lastTemplate) {
                        lastTemplate = currentTemplate;
                        handleTemplateMetaBoxes(currentTemplate);
                    }
                }
            });
        }
    });
    </script>
    <?php
} );

// METABOX BANNER SLIDER TRANG CHỦ
function adtec_home_slider_metabox() {
    add_meta_box(
        'adtec_home_slider_mb',
        'Quản Lý Banner Slider Trang Chủ',
        'adtec_render_home_slider_mb',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'adtec_home_slider_metabox');

function adtec_render_home_slider_mb($post) {
    wp_enqueue_media();
    $slider_ids = get_post_meta($post->ID, '_adtec_home_slider_ids', true);
    $slider_ids_arr = $slider_ids ? explode(',', $slider_ids) : array();
    wp_nonce_field('adtec_save_home_slider', 'adtec_home_slider_nonce');
    ?>
    <div class="sec-field-group">
        <label style="font-weight:bold; display:block; margin-bottom:8px;">Chọn danh sách ảnh Banner Slider:</label>
        <input type="hidden" id="home_slider_ids" name="home_slider_ids" value="<?php echo esc_attr($slider_ids); ?>">
        <button type="button" class="button button-primary" id="btn-upload-slider">📸 Chọn / Upload Nhiều Ảnh Slider</button>
        
        <div id="slider-preview-list" style="display:flex; gap:10px; margin-top:15px; flex-wrap:wrap;">
            <?php 
            if (!empty($slider_ids_arr)) {
                foreach ($slider_ids_arr as $img_id) {
                    $url = wp_get_attachment_image_url($img_id, 'thumbnail');
                    if ($url) {
                        echo '<div style="position:relative;" data-id="'.$img_id.'">
                                <img src="'.esc_url($url).'" style="width:100px; height:70px; object-fit:cover; border:1px solid #ccc; border-radius:3px;" />
                              </div>';
                    }
                }
            }
            ?>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#btn-upload-slider').click(function(e) {
            e.preventDefault();
            var frame = wp.media({
                title: 'Chọn các ảnh Banner cho Trang Chủ',
                button: { text: 'Thêm vào Slider' },
                multiple: true
            });

            frame.on('select', function() {
                var selection = frame.state().get('selection');
                var ids = [];
                $('#slider-preview-list').html('');
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    ids.push(attachment.id);
                    $('#slider-preview-list').append('<div style="position:relative;"><img src="' + attachment.url + '" style="width:100px; height:70px; object-fit:cover; border:1px solid #ccc; border-radius:3px;" /></div>');
                });
                $('#home_slider_ids').val(ids.join(','));
            });

            frame.open();
        });
    });
    </script>
    <?php
}

function adtec_save_home_slider_data($post_id) {
    if (!isset($_POST['adtec_home_slider_nonce']) || !wp_verify_nonce($_POST['adtec_home_slider_nonce'], 'adtec_save_home_slider')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['home_slider_ids'])) {
        update_post_meta($post_id, '_adtec_home_slider_ids', sanitize_text_field($_POST['home_slider_ids']));
    }
}
add_action('save_post', 'adtec_save_home_slider_data');

// METABOX REPEATER TRANG CHỦ - BÓC TOÀN BỘ CÁC MỤC MENU DỄ DÀNG
function adtec_add_home_sections_metabox() {
    add_meta_box(
        'adtec_home_sections_mb',
        'Quản Lý Các Section Trang Chủ',
        'adtec_render_home_sections_mb',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'adtec_add_home_sections_metabox');

function adtec_render_home_sections_mb($post) {
    wp_enqueue_media();

    $sections = get_post_meta($post->ID, '_adtec_home_sections_data', true);
    if (!is_array($sections)) $sections = [];
    wp_nonce_field('adtec_save_home_sections', 'adtec_home_sections_nonce');

    // LẤY TẤT CẢ CÁC MENU ITEM CẤP 1 TỪ TẤT CẢ CÁC MENU TRONG WEBSITE
    $parent_menus = array();
    $all_menus    = wp_get_nav_menus();

    if (!empty($all_menus)) {
        foreach ($all_menus as $menu_obj) {
            $items = wp_get_nav_menu_items($menu_obj->term_id);
            if ($items) {
                foreach ($items as $item) {
                    // Lấy các menu cha (cấp 1)
                    if (empty($item->menu_item_parent) || $item->menu_item_parent == '0') {
                        $parent_menus[$item->ID] = '[' . $menu_obj->name . '] ' . $item->title;
                    }
                }
            }
        }
    }
    ?>
    <style>
        .sec-repeater-box { border: 1px solid #ccd0d4; background: #fff; margin-bottom: 15px; padding: 15px; border-radius: 4px; }
        .sec-repeater-header { font-weight: bold; font-size: 14px; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; }
        .sec-field-group { margin-bottom: 12px; }
        .sec-field-group label { display: block; font-weight: 600; margin-bottom: 5px; color: #222; }
        .sec-field-group select, .sec-field-group input[type="text"] { width: 100%; max-width: 600px; height: 36px; }
        .btn-remove-sec { color: #d63638; cursor: pointer; text-decoration: underline; font-size: 13px; }
        .img-preview-box { margin-top: 8px; display: inline-block; }
        .img-preview-box img { max-width: 180px; height: auto; border: 1px solid #ddd; padding: 3px; border-radius: 3px; display: block; }
    </style>

    <div id="sec-repeater-wrapper">
        <?php foreach ($sections as $index => $sec) : 
            $selected_menu = isset($sec['menu_item_id']) ? $sec['menu_item_id'] : '';
            $subtitle      = isset($sec['subtitle']) ? $sec['subtitle'] : '';
            $bg_img_id     = isset($sec['bg_img_id']) ? $sec['bg_img_id'] : '';
            $bg_img_url    = $bg_img_id ? wp_get_attachment_image_url($bg_img_id, 'medium') : '';
        ?>
            <div class="sec-repeater-box">
                <div class="sec-repeater-header">
                    <span>Section #<?php echo $index + 1; ?></span>
                    <span class="btn-remove-sec" onclick="jQuery(this).closest('.sec-repeater-box').remove();">Xóa Section</span>
                </div>

                <!-- 1. CHỌN MENU TƯƠNG ỨNG -->
                <div class="sec-field-group">
                    <label>Chọn Mục Menu tương ứng (Lấy Tiêu đề & Submenu tự động):</label>
                    <select name="home_sec[<?php echo $index; ?>][menu_item_id]">
                        <option value="">-- Chọn danh mục Menu --</option>
                        <?php foreach ($parent_menus as $m_id => $m_title) : ?>
                            <option value="<?php echo $m_id; ?>" <?php selected($selected_menu, $m_id); ?>>
                                <?php echo esc_html($m_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- 2. SLOGAN -->
                <div class="sec-field-group">
                    <label>Slogan / Mô tả ngắn phụ trợ:</label>
                    <input type="text" name="home_sec[<?php echo $index; ?>][subtitle]" value="<?php echo esc_attr($subtitle); ?>" placeholder="VD: Đổi mới - Sáng tạo - Hiệu quả">
                </div>

                <!-- 3. CHỌN ẢNH TỪ MEDIA -->
                <div class="sec-field-group">
                    <label>Ảnh nền Section (Cột bên phải):</label>
                    <input type="hidden" class="img-id-input" name="home_sec[<?php echo $index; ?>][bg_img_id]" value="<?php echo esc_attr($bg_img_id); ?>">
                    <button type="button" class="button btn-upload-img">📸 Chọn / Tải ảnh lên</button>
                    <button type="button" class="button btn-remove-img" style="<?php echo $bg_img_id ? '' : 'display:none;'; ?>">Xóa ảnh</button>
                    <div class="img-preview-box">
                        <?php if ($bg_img_url) : ?>
                            <img src="<?php echo esc_url($bg_img_url); ?>" alt="Preview">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="button" class="button button-primary" id="add-new-sec-btn">+ Thêm Section Mới</button>

    <script>
    jQuery(document).ready(function($) {
        // 1. SỰ KIỆN UPLOAD ẢNH BẰNG MEDIA POPUP
        $(document).on('click', '.btn-upload-img', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var $box = $btn.closest('.sec-field-group');
            var $input = $box.find('.img-id-input');
            var $preview = $box.find('.img-preview-box');
            var $removeBtn = $box.find('.btn-remove-img');

            var frame = wp.media({
                title: 'Chọn ảnh nền cho Section',
                button: { text: 'Dùng ảnh này' },
                multiple: false
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                $input.val(attachment.id);
                $preview.html('<img src="' + attachment.url + '" style="max-width:180px;height:auto;" />');
                $removeBtn.show();
            });

            frame.open();
        });

        // 2. SỰ KIỆN XÓA ẢNH
        $(document).on('click', '.btn-remove-img', function(e) {
            e.preventDefault();
            var $box = $(this).closest('.sec-field-group');
            $box.find('.img-id-input').val('');
            $box.find('.img-preview-box').html('');
            $(this).hide();
        });

        // 3. THÊM SECTION MỚI - TỰ ĐỘNG LẤY OPTIONS MENU DỄ DÀNG
        $('#add-new-sec-btn').click(function() {
            var count = $('#sec-repeater-wrapper .sec-repeater-box').length;
            var menuOptions = `<?php 
                echo '<option value="">-- Chọn danh mục Menu --</option>';
                foreach ($parent_menus as $m_id => $m_title) {
                    echo '<option value="' . $m_id . '">' . esc_js($m_title) . '</option>';
                }
            ?>`;

            var html = `
                <div class="sec-repeater-box">
                    <div class="sec-repeater-header">
                        <span>Section Mới</span>
                        <span class="btn-remove-sec" onclick="$(this).closest('.sec-repeater-box').remove();">Xóa Section</span>
                    </div>
                    <div class="sec-field-group">
                        <label>Chọn Mục Menu tương ứng:</label>
                        <select name="home_sec[${count}][menu_item_id]">
                            ${menuOptions}
                        </select>
                    </div>
                    <div class="sec-field-group">
                        <label>Slogan / Mô tả ngắn phụ trợ:</label>
                        <input type="text" name="home_sec[${count}][subtitle]" placeholder="VD: Đổi mới - Sáng tạo - Hiệu quả">
                    </div>
                    <div class="sec-field-group">
                        <label>Ảnh nền Section (Cột bên phải):</label>
                        <input type="hidden" class="img-id-input" name="home_sec[${count}][bg_img_id]">
                        <button type="button" class="button btn-upload-img">📸 Chọn / Tải ảnh lên</button>
                        <button type="button" class="button btn-remove-img" style="display:none;">Xóa ảnh</button>
                        <div class="img-preview-box"></div>
                    </div>
                </div>`;
            $('#sec-repeater-wrapper').append(html);
        });
    });
    </script>
    <?php
}

// LƯU DỮ LIỆU
function adtec_save_home_sections_data($post_id) {
    if (!isset($_POST['adtec_home_sections_nonce']) || !wp_verify_nonce($_POST['adtec_home_sections_nonce'], 'adtec_save_home_sections')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['home_sec']) && is_array($_POST['home_sec'])) {
        $clean_data = array_values($_POST['home_sec']);
        update_post_meta($post_id, '_adtec_home_sections_data', $clean_data);
    } else {
        delete_post_meta($post_id, '_adtec_home_sections_data');
    }
}
add_action('save_post', 'adtec_save_home_sections_data');

// TỰ ĐỘNG ẨN METABOX BẰNG ADMIN CSS NẾU TRANG KHÔNG PHẢI LÀ TRANG CHỦ
function adtec_hide_metabox_on_other_pages() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'page') {
        $post_id = isset($_GET['post']) ? (int)$_GET['post'] : 0;
        $front_page_id = (int) get_option('page_on_front');
        
        $is_homepage = false;
        if ($post_id && $front_page_id) {
            // Check trùng ID trang chủ hoặc bản dịch Polylang
            if ($post_id === $front_page_id) $is_homepage = true;
            if (function_exists('pll_get_post')) {
                foreach (array('en', 'ja', 'vi') as $lang) {
                    if ($post_id === pll_get_post($front_page_id, $lang)) $is_homepage = true;
                }
            }
        }

        // NẾU KHÔNG PHẢI TRANG CHỦ -> HÀM CSS ẨN CẢ 2 METABOX
        if (!$is_homepage) {
            echo '<style>
                #adtec_home_slider_mb, 
                #adtec_home_sections_mb { 
                    display: none !important; 
                }
            </style>';
        }
    }
}
add_action('admin_head', 'adtec_hide_metabox_on_other_pages');