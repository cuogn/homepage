<?php
/**
 * Template Name: Giao diện Trang Tuyển dụng
 */
get_header();

// Get banner settings
$banner_image = get_theme_mod('career_banner_image', '');
$banner_title = get_theme_mod('career_banner_title', 'TUYỂN DỤNG');
$banner_height = get_theme_mod('career_banner_height', '400px');
$banner_overlay = get_theme_mod('career_banner_overlay_opacity', '0.4');

// Get current language
$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';
?>

<div class="career-page-container">
    <?php if (function_exists('adv_display_breadcrumb')) { adv_display_breadcrumb(); } ?>

    <!-- ========================================== -->
    <!-- PAGE TITLE                                -->
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
        <div class="career-banner-content">
            <h1 class="career-banner-title"><?php echo esc_html($banner_title); ?></h1>
        </div>
    </div>
    <?php endif; ?>

    <!-- ========================================== -->
    <!-- FEATURED BANNER (Featured Post)           -->
    <!-- ========================================== -->
    <?php
    // Query featured job (career_featured = true, status = dangtuyen)
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
            )
        )
    );

    $featured_query = new WP_Query($featured_args);

    if ($featured_query->have_posts()) :
        while ($featured_query->have_posts()) : $featured_query->the_post();
            $post_id = get_the_ID();
            $company = get_post_meta($post_id, 'career_company', true);
            $deadline = get_post_meta($post_id, 'career_deadline', true);
            $featured_image = get_the_post_thumbnail_url($post_id, 'large');
            
            if (empty($company)) {
                $company = 'Công ty CP Điện tử ADTEC';
            }
            
            $display_deadline = !empty($deadline) ? date('d/m/Y', strtotime($deadline)) : '';
            
            // Fallback background if no featured image
            $bg_style = $featured_image 
                ? 'background-image: url(' . esc_url($featured_image) . ');' 
                : 'background: linear-gradient(135deg, #C41E3A 0%, #8B0000 100%);';
    ?>
    <div class="career-featured-banner" style="<?php echo $bg_style; ?>">
        <div class="career-featured-overlay"></div>
        <div class="career-featured-content">
            <div class="career-featured-date-badge">
                <?php 
                $post_date = get_the_date('d/m/Y');
                echo esc_html($post_date);
                if (!empty($display_deadline)) {
                    echo ' - ' . esc_html($display_deadline);
                }
                ?>
            </div>
            <h2 class="career-featured-company"><?php echo esc_html($company); ?></h2>
            <h3 class="career-featured-position">VỊ TRÍ: <?php the_title(); ?></h3>
            <a href="<?php the_permalink(); ?>" class="career-featured-job-desc-btn">
                JOB DESCRIPTION - <?php the_title(); ?>
            </a>
        </div>
    </div>
    <?php 
        endwhile;
        wp_reset_postdata();
    endif;
    ?>

    <!-- ========================================== -->
    <!-- JOB LIST BY POSITION TYPE (Loại vị trí)    -->
    <!-- ========================================== -->
    <div class="career-jobs-by-department">
        
        <?php
        // Get all position types with jobs
        $position_types = get_terms(array(
            'taxonomy'   => 'loai_vi_tri',
            'hide_empty' => true,
            'lang'       => $current_lang,
        ));

        if (!empty($position_types) && !is_wp_error($position_types)) :
            foreach ($position_types as $position_type) :
                // Query jobs by position type
                $job_args = array(
                    'post_type'      => 'tuyen_dung',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'lang'           => $current_lang,
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'loai_vi_tri',
                            'field'    => 'term_id',
                            'terms'    => $position_type->term_id,
                        )
                    ),
                    'meta_query'     => array(
                        array(
                            'key'     => 'career_status',
                            'value'   => 'dangtuyen',
                            'compare' => '='
                        )
                    ),
                    'orderby' => 'date',
                    'order'   => 'DESC',
                );

                $job_query = new WP_Query($job_args);

                if ($job_query->have_posts()) :
        ?>
        
        <div class="career-department-section">
            <h3 class="career-department-title"><?php echo esc_html($position_type->name); ?></h3>
            
            <div class="career-job-table">
                <?php while ($job_query->have_posts()) : $job_query->the_post(); ?>
                    <?php
                    $job_id = get_the_ID();
                    $job_deadline = get_post_meta($job_id, 'career_deadline', true);
                    $display_deadline = !empty($job_deadline) ? date('d/m/Y', strtotime($job_deadline)) : '';
                    $post_date = get_the_date('d/m/Y');
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
        </div>
        
        <?php 
                endif;
            endforeach;
        else :
        ?>
        
        <!-- Fallback: List all jobs without position type grouping -->
        <div class="career-department-section">
            <h3 class="career-department-title"><?php adtec_label('tuyen_dung'); ?></h3>
            
            <?php
            $all_jobs_args = array(
                'post_type'      => 'tuyen_dung',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'lang'           => $current_lang,
                'meta_query'     => array(
                    array(
                        'key'     => 'career_status',
                        'value'   => 'dangtuyen',
                        'compare' => '='
                    )
                ),
                'orderby' => 'date',
                'order'   => 'DESC',
            );

            $all_jobs_query = new WP_Query($all_jobs_args);

            if ($all_jobs_query->have_posts()) :
            ?>
            <div class="career-job-table">
                <?php while ($all_jobs_query->have_posts()) : $all_jobs_query->the_post(); ?>
                    <?php
                    $job_id = get_the_ID();
                    $job_deadline = get_post_meta($job_id, 'career_deadline', true);
                    $display_deadline = !empty($job_deadline) ? date('d/m/Y', strtotime($job_deadline)) : '';
                    $post_date = get_the_date('d/m/Y');
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
