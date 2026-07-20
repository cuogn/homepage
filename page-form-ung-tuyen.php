<?php
/**
 * Template Name: Giao diện Trang Tuyển dụng
 */
get_header();

// Get banner settings
$banner_image   = get_theme_mod('career_banner_image', '');
$banner_title   = get_theme_mod('career_banner_title', 'TUYỂN DỤNG');
$banner_height  = get_theme_mod('career_banner_height', '400px');
$banner_overlay = get_theme_mod('career_banner_overlay_opacity', '0.4');

// Get current language & Today Date
$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';
$today        = date('Y-m-d'); // Ngày hiện tại để so sánh hạn nộp
?>

<div class="career-page-container">
    <?php if (function_exists('adv_display_breadcrumb')) { adv_display_breadcrumb(); } ?>

    <!-- ========================================== -->
    <!-- PAGE TITLE                                 -->
    <!-- ========================================== -->
    <div class="career-page-header">
        <h1 class="career-page-title"><?php adtec_label('form_ung_tuyen'); ?></h1>
    </div>

    <!-- ========================================== -->
    <!-- BANNER (Customizer)                        -->
    <!-- ========================================== -->
    <?php if ($banner_image) : ?>
    <div class="career-hero-banner" style="background-image: url(<?php echo esc_url($banner_image); ?>); height: <?php echo esc_attr($banner_height); ?>;">
        <div class="career-banner-overlay" style="opacity: <?php echo esc_attr($banner_overlay); ?>;"></div>
    </div>
    <?php endif; ?>

    <!-- ========================================== -->
    <!-- FEATURED BANNER (Featured Post)            -->
    <!-- ========================================== -->
    <?php
    // Query featured job (career_featured = 1, status = dangtuyen, deadline >= today)
    $featured_args = array(
        'post_type'      => 'tuyen_dung',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'lang'           => 'vi',
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'     => 'career_featured',
                'value'   => '1',
                'compare' => '='
            ),
            array(
                'key'     => 'career_status',
                'value'   => 'dangtuyen',
                'compare' => '='
            ),
            // ĐIỀU KIỆN MỚI: Hạn nộp phải chưa nhập HOẶC >= Ngày hôm nay
            array(
                'relation' => 'OR',
                array(
                    'key'     => 'career_deadline',
                    'compare' => 'NOT EXISTS'
                ),
                array(
                    'key'     => 'career_deadline',
                    'value'   => $today,
                    'compare' => '>=',
                    'type'    => 'DATE'
                )
            )
        ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $featured_query = new WP_Query($featured_args);

    if ($featured_query->have_posts()) :
        while ($featured_query->have_posts()) : $featured_query->the_post();
            $post_id          = get_the_ID();
            $deadline         = get_post_meta($post_id, 'career_deadline', true);
            $display_deadline = !empty($deadline) ? date('d/m/Y', strtotime($deadline)) : '';
            $post_date        = get_the_date('d/m/Y');
    ?>
    <div class="career-special-section">
        <div class="career-special-header">
            <h2 class="career-special-title">TUYỂN DỤNG ĐẶC BIỆT</h2>
        </div>
        <div class="career-special-position">
            VỊ TRÍ: <span class="position-title"><?php the_title(); ?></span>
        </div>
        <div class="career-special-divider"></div>
        <div class="career-special-job-desc">
            <span class="job-desc-label">JOB DESCRIPTION:</span>
            <a href="<?php the_permalink(); ?>" class="job-desc-title"><?php the_title(); ?></a>
        </div>
        <div class="career-special-divider"></div>
    </div>
    <?php 
        endwhile;
        wp_reset_postdata();
    endif;
    ?>

    <!-- ========================================== -->
    <!-- JOB LIST BY WORK TYPE (career_work_type)   -->
    <!-- ========================================== -->
    <div class="career-jobs-by-department">
        
        <?php
        // Mapping work type value to label
        $work_type_labels = array(
            'nhan_vien'       => 'Nhân viên',
            'cong_nhan'       => 'Công nhân',
            'ky_thuat_vien'   => 'Kỹ thuật viên',
        );

        // Get all jobs to group by career_work_type
        $all_jobs_args = array(
            'post_type'      => 'tuyen_dung',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'lang'           => 'vi',
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => 'career_status',
                    'value'   => 'dangtuyen',
                    'compare' => '='
                ),
                // ĐIỀU KIỆN MỚI: Chỉ lấy các bài viết còn hạn nộp hồ sơ
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'career_deadline',
                        'compare' => 'NOT EXISTS'
                    ),
                    array(
                        'key'     => 'career_deadline',
                        'value'   => $today,
                        'compare' => '>=',
                        'type'    => 'DATE'
                    )
                )
            ),
            'orderby' => 'date',
            'order'   => 'DESC',
        );

        $all_jobs_query = new WP_Query($all_jobs_args);

        // Group jobs by career_work_type
        $jobs_by_work_type = array();
        if ($all_jobs_query->have_posts()) :
            while ($all_jobs_query->have_posts()) : $all_jobs_query->the_post();
                $job_id    = get_the_ID();
                $work_type = get_post_meta($job_id, 'career_work_type', true);
                
                if (empty($work_type)) {
                    $work_type = 'nhan_vien'; // Default fallback
                }
                
                if (!isset($jobs_by_work_type[$work_type])) {
                    $jobs_by_work_type[$work_type] = array();
                }
                $jobs_by_work_type[$work_type][] = $job_id;
            endwhile;
            wp_reset_postdata();
        endif;

        // Define display order
        $work_type_order = array('nhan_vien', 'ky_thuat_vien', 'cong_nhan');

        // Output grouped jobs
        foreach ($work_type_order as $work_type) :
            if (!isset($jobs_by_work_type[$work_type]) || empty($jobs_by_work_type[$work_type])) {
                continue;
            }

            $label = isset($work_type_labels[$work_type]) ? $work_type_labels[$work_type] : ucfirst(str_replace('_', ' ', $work_type));
        ?>
        
        <div class="career-department-section">
            <h3 class="career-department-title"><?php echo esc_html($label); ?></h3>
            
            <div class="career-job-table">
                <?php foreach ($jobs_by_work_type[$work_type] as $job_id) : ?>
                    <?php
                    $job_post = get_post($job_id);
                    setup_postdata($GLOBALS['post'] =& $job_post);
                    
                    $job_deadline     = get_post_meta($job_id, 'career_deadline', true);
                    $display_deadline = !empty($job_deadline) ? date('d/m/Y', strtotime($job_deadline)) : '';
                    $post_date        = get_the_date('d/m/Y', $job_id);
                    ?>
                    <div class="career-job-row">
                        <div class="career-job-date">
                            <span class="career-date-range">
                                <?php 
                                echo esc_html($post_date);
                                if (!empty($display_deadline)) {
                                    echo ' - ' . esc_html($display_deadline);
                                }
                                ?>
                            </span>
                        </div>
                        <div class="career-job-link">
                            <a href="<?php echo get_permalink($job_id); ?>">
                                <?php echo esc_html($job_post->post_title); ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
        </div>
        
        <?php endforeach; ?>

        <?php if (empty($jobs_by_work_type)) : ?>
        
        <!-- Fallback: List all jobs without grouping -->
        <div class="career-department-section">
            <h3 class="career-department-title"><?php adtec_label('tuyen_dung'); ?></h3>
            
            <?php
            $fallback_args = array(
                'post_type'      => 'tuyen_dung',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'lang'           => 'vi',
                'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'career_status',
                        'value'   => 'dangtuyen',
                        'compare' => '='
                    ),
                    // ĐIỀU KIỆN MỚI: Check quá hạn cho query fallback
                    array(
                        'relation' => 'OR',
                        array(
                            'key'     => 'career_deadline',
                            'compare' => 'NOT EXISTS'
                        ),
                        array(
                            'key'     => 'career_deadline',
                            'value'   => $today,
                            'compare' => '>=',
                            'type'    => 'DATE'
                        )
                    )
                ),
                'orderby' => 'date',
                'order'   => 'DESC',
            );

            $fallback_query = new WP_Query($fallback_args);

            if ($fallback_query->have_posts()) :
            ?>
            <div class="career-job-table">
                <?php while ($fallback_query->have_posts()) : $fallback_query->the_post(); ?>
                    <?php
                    $job_id           = get_the_ID();
                    $job_deadline     = get_post_meta($job_id, 'career_deadline', true);
                    $display_deadline = !empty($job_deadline) ? date('d/m/Y', strtotime($job_deadline)) : '';
                    $post_date        = get_the_date('d/m/Y');
                    ?>
                    <div class="career-job-row">
                        <div class="career-job-date">
                            <span class="career-date-range">
                                <?php 
                                echo esc_html($post_date);
                                if (!empty($display_deadline)) {
                                    echo ' - ' . esc_html($display_deadline);
                                }
                                ?>
                            </span>
                        </div>
                        <div class="career-job-link">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <?php endif; ?>
        </div>
        
        <?php endif; ?>

    </div>

</div>

<?php get_footer(); ?>