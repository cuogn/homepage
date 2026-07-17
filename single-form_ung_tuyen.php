<?php
/**
 * Template Name: Single Tuyển dụng (Giao diện mới)
 */
get_header();

// Get meta values
$form_link = get_post_meta(get_the_ID(), 'career_form_link', true);

$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';
?>

<div class="career-detail-page-container">
    <?php if (function_exists('adv_display_breadcrumb')) { adv_display_breadcrumb(); } ?>

    <!-- ========================================== -->
    <!-- PAGE TITLE                                -->
    <!-- ========================================== -->
    <div class="career-detail-header">
        <h1 class="career-detail-title"><?php the_title(); ?></h1>
    </div>

    <!-- ========================================== -->
    <!-- JOB CONTENT (Editor)                       -->
    <!-- ========================================== -->
    <div class="career-detail-content">
        <?php 
        if (have_posts()) : while (have_posts()) : the_post();
            the_content();
        endwhile; endif;
        ?>
    </div>

    <!-- ========================================== -->
    <!-- APPLY LINK                                -->
    <!-- ========================================== -->
    <?php if (!empty($form_link)) : ?>
    <div class="career-apply-link-wrapper">
        <p class="career-apply-link-label">Link đăng kí ứng tuyển:</p>
        <a href="<?php echo esc_url($form_link); ?>" class="career-apply-link" target="_blank">
            ĐĂNG KÝ THI TUYỂN
        </a>
    </div>
    <?php endif; ?>

</div>

<?php get_footer(); ?>
