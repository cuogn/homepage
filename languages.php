<?php
// languages.php
if ( ! defined( 'ABSPATH' ) ) { exit; }

// ==========================================================================
// CẤU HÌNH TRUNG TÂM CHO CÁC TRANG SLIDER (TIN TỨC, SỰ KIỆN, V.V.)
// Thêm CPT mới vào đây để tự động hỗ trợ slider, AJAX, và swiper
// ==========================================================================
function adtec_get_slider_cpts_config() {
    return array(
        'tin_tuc' => array(
            'slug_key'   => 'slug_tintuc',
            'date_meta'  => 'news_date',
            'gallery_meta' => 'news_gallery',
            'labels'     => array(
                'singular'   => 'tin_tuc',
                'plural'     => 'tin_tuc_moi',
                'breadcrumb' => 'tin_tuc_su_kien',
            ),
        ),
        'su_kien_nam' => array(
            'slug_key'   => 'slug_sukiennam',
            'date_meta'  => 'events_date',
            'gallery_meta' => 'events_gallery',
            'labels'     => array(
                'singular'   => 'su_kien_nam',
                'plural'     => 'su_kien_moi',
                'breadcrumb' => 'tin_tuc_su_kien',
            ),
        ),
        // --------------------------------------------------
        // HƯỚNG DẪN THÊM CPT MỚI:
        // 1. Copy block trên và đổi 'tin_tuc' thành tên CPT mới
        // 2. Điền slug_key: key trong dictionary cho slug CPT
        // 3. Điền date_meta, gallery_meta: tên meta field
        // 4. Điền labels: các key trong dictionary
        // 5. Thêm slug_key vào dictionary (phần dưới)
        // --------------------------------------------------
    );
}

// 1. NƠI DUY NHẤT ĐỊNH NGHĨA TOÀN BỘ CHỮ NGHĨA & SLUG TRÊN WEBSITE
function adtec_get_dictionary() {
    return array(
        // --- ĐỊNH NGHĨA SLUGS CÁC CUSTOM POST TYPE (Bắt đầu bằng slug_) ---
        // Lưu ý: Key phải đặt theo chuẩn 'slug_' + 'tên_post_type_bỏ_gạch_dưới'
        'slug_tintuc' => array(
            'vi' => 'tin-tuc',
            'en' => 'news',
            'ja' => 'news-ja'
        ),
        'slug_products' => array(
            'vi' => 'san-pham',
            'en' => 'products',
            'ja' => 'products-ja'
        ),
        'slug_tuyendung' => array(
            'vi' => 'tuyen-dung',
            'en' => 'careers',
            'ja' => 'careers-ja'
        ),

        // --- ĐỊNH NGHĨA CÁC NHÃN CHỮ (LABELS / TITLES) ---
        'home' => array(
            'vi' => 'Trang chủ',
            'en' => 'Home',
            'ja' => 'ホーム'
        ),
        'tin_tuc' => array(
            'vi' => 'Tin tức',
            'en' => 'News',
            'ja' => 'ニュース'
        ),
        'tin_tuc_moi' => array(
            'vi' => 'Tin tức mới',
            'en' => 'Latest News',
            'ja' => '最新ニュース'
        ),
        'load_more' => array(
            'vi' => 'TẢI THÊM',
            'en' => 'LOAD MORE',
            'ja' => 'もっと見る'
        ),
        'product_code_label' => array(
            'vi' => 'Mã sản phẩm',
            'en' => 'Product Code',
            'ja' => '製品コード'
        ),
        'specifications' => array(
            'vi' => 'Thông số kỹ thuật',
            'en' => 'Specifications',
            'ja' => '主な仕様'
        ),
        'download_catalog' => array(
            'vi' => 'Tải Catalog (PDF)',
            'en' => 'Download Catalog (PDF)',
            'ja' => 'カタログダウンロード (PDF)'
        ),
        'related_products_label' => array(
            'vi' => 'Sản phẩm liên quan',
            'en' => 'Related Products',
            'ja' => '関連製品'
        ),
        'tin_tuc_su_kien' => array(
            'vi' => 'Tin tức & Sự kiện',
            'en' => 'News & Events',
            'ja' => 'ニュース・イベント',
        ),
        // --- TỪ ĐIỂN CHO PHẦN SỰ KIỆN THƯỜNG NIÊN ---
        'slug_sukiennam' => array(
            'vi' => 'su-kien-nam',
            'en' => 'annual-events',
            'ja' => 'annual-events-ja'
        ),
        'su_kien_nam' => array(
            'vi' => 'Sự kiện năm',
            'en' => 'Annual Events',
            'ja' => '年間イベント'
        ),
        'su_kien_moi' => array(
            'vi' => 'Sự kiện mới',
            'en' => 'Latest Annual Events',
            'ja' => '最新の年間行事'
        ),
        'collapse' => array('vi' => 'Thu gọn', 'en' => 'Collapse', 'ja' => '折りたたむ'),

        // --- TỪ ĐIỂN CHO PHẦN TUYỂN DỤNG ---
        'tuyen_dung' => array(
            'vi' => 'Tuyển dụng',
            'en' => 'Careers',
            'ja' => '採用'
        ),
        'job_description' => array(
            'vi' => 'Mô tả công việc',
            'en' => 'Job Description',
            'ja' => '仕事概要'
        ),
        'tuyen_dung_dac_biet' => array(
            'vi' => 'Tuyển dụng đặc biệt',
            'en' => 'Special Recruitment',
            'ja' => '特別採用'
        ),
        'nhan_vien' => array(
            'vi' => 'Nhân viên',
            'en' => 'Staff',
            'ja' => 'スタッフ'
        ),
        'cong_nhan' => array(
            'vi' => 'Công nhân',
            'en' => 'Workers',
            'ja' => '労働者'
        ),
        'view_details' => array(
            'vi' => 'Xem chi tiết',
            'en' => 'View details',
            'ja' => '詳細を見る'
        ),
        'deadline' => array(
            'vi' => 'Hạn nộp',
            'en' => 'Deadline',
            'ja' => '締切'
        ),
        'location' => array(
            'vi' => 'Địa điểm',
            'en' => 'Location',
            'ja' => '勤務地'
        ),
        'apply_now' => array(
            'vi' => 'ĐĂNG KÝ ỨNG TUYỂN',
            'en' => 'APPLY NOW',
            'ja' => '今すぐ応募'
        ),
        'job_details' => array(
            'vi' => 'Chi tiết',
            'en' => 'Details',
            'ja' => '詳細'
        ),
        'recruitment_process' => array(
            'vi' => 'Quy trình',
            'en' => 'Process',
            'ja' => '流れ'
        ),
        'view_recruitment_process' => array(
            'vi' => 'Xem quy trình ứng tuyển tại ADTEC',
            'en' => 'View recruitment process at ADTEC',
            'ja' => 'ADTECでの採用プロセスを見る'
        ),
        'other_positions' => array(
            'vi' => 'Các vị trí khác',
            'en' => 'Other Positions',
            'ja' => '他のポジション'
        ),
        'back_to_list' => array(
            'vi' => 'Quay lại danh sách',
            'en' => 'Back to list',
            'ja' => '一覧に戻る'
        ),
        'slug_quytrinhtuyendung' => array(
            'vi' => 'quy-trinh-tuyen-dung',
            'en' => 'recruitment-process',
            'ja' => 'recruitment-process-ja'
        ),
        'slug_formungtuyen' => array(
            'vi' => 'form-ung-tuyen',
            'en' => 'application-form',
            'ja' => 'application-form-ja'
        ),
        'featured' => array(
            'vi' => 'NỔI BẬT',
            'en' => 'FEATURED',
            'ja' => '注目'
        ),
        'noi_bat' => array(
            'vi' => 'Nổi bật',
            'en' => 'Featured',
            'ja' => '注目'
        ),
        'form_ung_tuyen' => array(
            'vi' => 'Form ứng tuyển',
            'en' => 'Application Form',
            'ja' => '応募フォーム'
        ),
        'dia_diem' => array(
            'vi' => 'Địa điểm',
            'en' => 'Location',
            'ja' => '勤務地'
        ),
        'vi_tri' => array(
            'vi' => 'vị trí',
            'en' => 'position',
            'ja' => 'ポジション'
        ),
        'han_nop' => array(
            'vi' => 'Hạn nộp',
            'en' => 'Deadline',
            'ja' => '締切'
        ),
        'ung_tuyen_ngay' => array(
            'vi' => ' Quan tâm đến vị trí này?',
            'en' => ' Interested in this position?',
            'ja' => 'このポジションに興味がありますか？'
        ),
        'dang_ky_ung_tuyen' => array(
            'vi' => 'ĐĂNG KÝ ỨNG TUYỂN NGAY',
            'en' => 'APPLY NOW',
            'ja' => '今すぐ応募'
        ),
    );
}

// 2. Lấy nhãn dịch dựa theo ngôn ngữ hiện tại của trang
function adtec_get_label( $key ) {
    $lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';
    return adtec_get_label_by_lang($key, $lang);
}

// 3. Lấy nhãn dịch dựa theo một ngôn ngữ chỉ định cụ thể
function adtec_get_label_by_lang($key, $lang) {
    $dictionary = adtec_get_dictionary();
    
    if ( isset( $dictionary[$key][$lang] ) ) {
        return $dictionary[$key][$lang];
    }
    if ( isset( $dictionary[$key]['vi'] ) ) {
        return $dictionary[$key]['vi'];
    }
    return $key;
}

// 4. Hàm in nhanh ra màn hình
function adtec_label( $key ) {
    echo esc_html( adtec_get_label( $key ) );
}

// ==========================================================================
// HELPER FUNCTIONS CHO SLIDER CPT CONFIG
// ==========================================================================

/**
 * Lấy config của một CPT slider cụ thể
 * @param string $cpt_name Tên CPT (vd: 'tin_tuc', 'su_kien_nam')
 * @return array|false Config của CPT hoặc false nếu không tìm thấy
 */
function adtec_get_cpt_config( $cpt_name ) {
    $config = adtec_get_slider_cpts_config();
    return isset( $config[ $cpt_name ] ) ? $config[ $cpt_name ] : false;
}

/**
 * Lấy danh sách tất cả CPT có slider
 * @return array Mảng tên các CPT
 */
function adtec_get_all_slider_cpts() {
    return array_keys( adtec_get_slider_cpts_config() );
}

/**
 * Lấy tất cả slugs của các trang slider (theo ngôn ngữ hiện tại)
 * @return array Mảng slugs
 */
function adtec_get_all_slider_page_slugs() {
    $slugs = array();
    foreach ( adtec_get_slider_cpts_config() as $cpt_name => $config ) {
        $slug = adtec_get_label( $config['slug_key'] );
        if ( $slug && $slug !== $config['slug_key'] ) {
            $slugs[] = $slug;
        }
    }
    return $slugs;
}

/**
 * Kiểm tra xem trang hiện tại có phải là trang slider không
 * @return bool
 */
function adtec_is_slider_page() {
    if ( ! is_page() ) {
        return false;
    }
    $current_slug = get_post_field( 'post_name', get_the_ID() );
    $slider_slugs = adtec_get_all_slider_page_slugs();
    return in_array( $current_slug, $slider_slugs, true );
}

/**
 * Lấy meta key ngày của một CPT
 * @param string $cpt_name Tên CPT
 * @return string Meta key ngày
 */
function adtec_get_cpt_date_meta( $cpt_name ) {
    $config = adtec_get_cpt_config( $cpt_name );
    return $config ? $config['date_meta'] : 'news_date';
}

/**
 * Lấy meta key gallery của một CPT
 * @param string $cpt_name Tên CPT
 * @return string Meta key gallery
 */
function adtec_get_cpt_gallery_meta( $cpt_name ) {
    $config = adtec_get_cpt_config( $cpt_name );
    return $config ? $config['gallery_meta'] : 'news_gallery';
}