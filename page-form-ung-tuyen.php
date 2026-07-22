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
        'lang'           => $current_lang,
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
            // Hạn nộp phải chưa nhập HOẶC >= Ngày hôm nay
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
            <h2 class="career-special-title">
                <?php 
                    if ($current_lang === 'en') echo 'SPECIAL RECRUITMENT';
                    elseif ($current_lang === 'ja') echo ' me 特別採用';
                    else echo 'TUYỂN DỤNG ĐẶC BIỆT';
                ?>
            </h2>
        </div>
        <div class="career-special-position">
            <?php echo ($current_lang === 'en') ? 'POSITION:' : (($current_lang === 'ja') ? '職種:' : 'VỊ TRÍ:'); ?> 
            <span class="position-title"><?php the_title(); ?></span>
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
            'nhan_vien'       => ($current_lang === 'en' ? 'Staff' : ($current_lang === 'ja' ? '正社員' : 'Nhân viên')),
            'cong_nhan'       => ($current_lang === 'en' ? 'Worker' : ($current_lang === 'ja' ? '作業員' : 'Công nhân')),
            'ky_thuat_vien'   => ($current_lang === 'en' ? 'Technician' : ($current_lang === 'ja' ? '技術者' : 'Kỹ thuật viên')),
        );

        // Get all jobs to group by career_work_type
        $all_jobs_args = array(
            'post_type'      => 'tuyen_dung',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'lang'           => $current_lang,
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => 'career_status',
                    'value'   => 'dangtuyen',
                    'compare' => '='
                ),
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
        $has_jobs_rendered = false;

        // Output grouped jobs
        if (!empty($jobs_by_work_type)) :
            foreach ($work_type_order as $work_type) :
                if (!isset($jobs_by_work_type[$work_type]) || empty($jobs_by_work_type[$work_type])) {
                    continue;
                }
                $has_jobs_rendered = true;
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
        <?php endif; ?>

        <!-- ========================================== -->
        <!-- BÁO TRỐNG: NẾU KHÔNG CÓ BÀI VIẾT NÀO       -->
        <!-- ========================================== -->
        <?php if (!$has_jobs_rendered && empty($jobs_by_work_type)) : ?>
            <div class="career-no-jobs-box" style="padding: 40px 20px; text-align: center; background: #f9f9f9; border-radius: 6px; margin: 30px 0;">
                <p style="font-size: 16px; color: #555; margin: 0; font-weight: 500;">
                    <?php 
                        if ($current_lang === 'en') {
                            echo 'Currently, the company personnel is stable, there are no new job openings.';
                        } elseif ($current_lang === 'ja') {
                            echo '現在、人員は安定しており、新しい求人はありません。';
                        } else {
                            echo 'Hiện tại công ty đang ổn định nhân sự, chưa có vị trí tuyển dụng mới.';
                        }
                    ?>
                </p>
            </div>
        <?php endif; ?>

    </div>

</div>

<?php get_footer(); ?>