<?php
/**
 * Template Name: Giao diện Trang Thông tin tuyển dụng (Job Listing)
 */
get_header();

$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';
//1920x400px
// Query tất cả job đang active (recruitment_date <= hôm nay)
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

// Lọc job đã hết hạn (job_deadline < hôm nay)
$today = date('Y-m-d');
$visible_jobs = array();
if ($jobs_query->have_posts()) :
    while ($jobs_query->have_posts()) : $jobs_query->the_post();
        $jid      = get_the_ID();
        $deadline = rwmb_meta('job_deadline', '', $jid);
        // Chỉ thêm nếu chưa hết hạn hoặc không có deadline
        if (empty($deadline) || $deadline >= $today) {
            $visible_jobs[] = $jid;
        }
    endwhile;
endif;
wp_reset_postdata();

// Re-query với danh sách job hợp lệ
$job_ids_for_query = $visible_jobs;
$total_jobs = count($visible_jobs);

$job_type_labels = array(
    'full_time' => 'Toàn thời gian',
    'part_time' => 'Bán thời gian',
    'seasonal'  => 'Thời vụ',
);
?>

<div class="job-page-wrapper">
    <?php if (function_exists('adv_display_breadcrumb')) { adv_display_breadcrumb(); } ?>
    <h1 class="job-page-title"><?php adtec_label('thong_tin_tuyen_dung'); ?></h1>

<?php
$banner_image = get_theme_mod('career_banner_image', '');
?>

<?php if (!empty($banner_image)) : ?>
    <div class="career-banner">
        <img src="<?php echo esc_url($banner_image); ?>" alt="" class="career-banner-img">
    </div>
<?php endif; ?>

<div class="career-detail-page-container">
    <p class="job-count-text"><strong><?php echo intval($total_jobs); ?></strong> công việc đang chờ bạn tại ADTEC</p>

    <?php
    $site_favicon_url = get_site_icon_url(120);
    ?>

    <div class="job-row-list" id="jobRowList">
        <?php
        $count = 0;
        foreach ($visible_jobs as $jid) :
            setup_postdata(get_post($jid));
            $salary    = rwmb_meta('job_salary', '', $jid);
            $location  = rwmb_meta('job_location', '', $jid);
            $deadline  = rwmb_meta('job_deadline', '', $jid);
            $job_type  = rwmb_meta('job_type', '', $jid);
            $deadline_display = !empty($deadline) ? date('d-m-Y', strtotime($deadline)) : '';
            $job_type_label = isset($job_type_labels[$job_type]) ? $job_type_labels[$job_type] : '';
            $hidden_class = ($count >= 4) ? 'is-hidden' : '';

            $thumb_url = has_post_thumbnail($jid)
                ? get_the_post_thumbnail_url($jid, 'thumbnail')
                : $site_favicon_url;
        ?>
            <div class="job-row-item <?php echo $hidden_class; ?>">
                <?php if ($thumb_url) : ?>
                    <div class="job-row-thumb">
                        <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr(get_the_title($jid)); ?>">
                    </div>
                <?php endif; ?>

                <a href="<?php echo get_permalink($jid); ?>" class="job-row-link">
                    <span class="job-row-title"><?php echo get_the_title($jid); ?></span>

                    <span class="job-row-meta-left">
                        <?php if (!empty($salary)) : ?>
                            <span class="job-row-meta-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v10M9 10a2 2 0 0 1 2-2h1.5a2 2 0 0 1 0 4H10a2 2 0 0 0 0 4h2a2 2 0 0 0 2-2"/></svg>
                                Lương: <span class="highlight"><?php echo esc_html($salary); ?></span>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($location)) : ?>
                            <span class="job-row-meta-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <strong>Địa điểm:</strong> <?php echo esc_html($location); ?>
                            </span>
                        <?php endif; ?>
                    </span>

                    <span class="job-row-meta-right">
                        <?php if (!empty($deadline_display)) : ?>
                            <span class="job-row-meta-item job-row-deadline">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 10h18"/></svg>
                                <strong>Hạn nộp hồ sơ:</strong> <?php echo esc_html($deadline_display); ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($job_type_label)) : ?>
                            <span class="job-row-meta-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                <?php echo esc_html($job_type_label); ?>
                            </span>
                        <?php endif; ?>
                    </span>
                </a>

                <button class="job-row-apply-btn btn-open-apply-modal"
                        data-job-id="<?php echo esc_attr($jid); ?>"
                        data-job-title="<?php echo esc_attr(get_the_title($jid)); ?>">
                    Ứng tuyển &gt;&gt;
                </button>
            </div>
        <?php
            $count++;
        endforeach;
        wp_reset_postdata();
        ?>
    </div>

    <?php if ($total_jobs > 4) : ?>
        <div class="load-more-wrapper">
            <button class="load-more-btn btn-toggle-jobs" data-state="more">
                <span class="btn-text" style="font-size: 16px;">Xem thêm</span>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
            </button>
        </div>
    <?php endif; ?>
</div><!-- /.job-row-list -->
</div><!-- /.career-detail-page-container -->

<?php get_template_part('template-parts/apply-modal'); ?>
<?php get_footer(); ?>
