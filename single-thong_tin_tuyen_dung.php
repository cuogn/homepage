<?php
/**
 * Template hiển thị chi tiết bài viết Thông tin tuyển dụng (CPT: thong_tin_tuyen_dung)
 * Sử dụng layout list trái + chi tiết phải
 */
get_header();

$current_job_id = get_the_ID();

// 1. Tự động lấy link Trang chủ theo ngôn ngữ hiện tại của Polylang
$lang_home_url = function_exists('pll_home_url') ? pll_home_url() : home_url('/');

// 2. Tự động lấy slug trang Sự kiện năm theo ngôn ngữ hiện tại từ từ điển languages.php
$recruitment_slug = adtec_get_label('slug_thongtintuyendung');

// 3. Tạo link trang Thông tin tuyển dụng chuẩn đa ngôn ngữ (Ví dụ: .../en/recruitment/ hoặc .../ja/recruitment-ja/)
$lang_recruitment_url = function_exists('pll_home_url') ? pll_home_url() . $recruitment_slug . '/' : home_url('/' . $recruitment_slug . '/');

$custom_breadcrumbs = array(
    array(
        'title' => adtec_get_label('home'),
        'url'   => $lang_home_url
    ),
    array(
        'title' => adtec_get_label('thong_tin_tuyen_dung'),
        'url'   => $lang_recruitment_url
    ),
    array(
        'title' => get_the_title($current_job_id),
        'url'   => ''
    )
);

// Render ra HTML chuẩn cấu trúc của theme
if ( ! empty($custom_breadcrumbs) ) {
    echo '<div class="adv-breadcrumb-row">';
    echo '<div class="adv-breadcrumb-container">';
    echo '<nav class="adv-breadcrumb" aria-label="Breadcrumb">';
    
    $count = count($custom_breadcrumbs);
    foreach ($custom_breadcrumbs as $index => $crumb) {
        if ($index === $count - 1) {
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

$banner_image = get_theme_mod('career_banner_image', '');
?>

<h1 class="job-page-title"><?php adtec_label('thong_tin_tuyen_dung'); ?></h1>

<?php if (!empty($banner_image)) : ?>
    <div class="career-banner">
        <img src="<?php echo esc_url($banner_image); ?>" alt="" class="career-banner-img">
    </div>
<?php endif; ?>

<div class="career-detail-page-container">
<?php
// Query lại toàn bộ list job để hiện cột trái (cùng logic với trang danh sách)
$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';

$jobs_query = new WP_Query(array(
    'post_type'      => 'thong_tin_tuyen_dung',
    'posts_per_page' => -1,
    'lang'           => $current_lang,
    'meta_key'       => 'recruitment_date',
    'orderby'        => 'meta_value',
    'order'          => 'DESC',
    'post_status'    => 'publish',
    'meta_query'     => array(
        array(
            'key'     => 'recruitment_date',
            'value'   => date('Y-m-d'),
            'compare' => '<=',
            'type'    => 'DATE',
        ),
    ),
));

$job_ids = array();
if ($jobs_query->have_posts()) {
    $job_ids = wp_list_pluck($jobs_query->posts, 'ID');
}
wp_reset_postdata();

// Load template part với job hiện tại là active
get_template_part('template-parts/job-list-detail', null, array(
    'job_ids'   => $job_ids,
    'active_id' => $current_job_id,
));
?>
</div><!-- /.career-detail-page-container -->

<?php get_template_part('template-parts/apply-modal'); ?>
<?php get_footer(); ?>
