<?php
/**
 * Template Name: Single Tuyển dụng
 */
get_header();

// Get form link from meta
$post_id = get_the_ID();
$form_link = get_post_meta($post_id, 'career_form_link', true);

// Breadcrumb
$lang_home_url = function_exists('pll_home_url') ? pll_home_url() : home_url('/');
$form_ung_tuyen_slug = adtec_get_label('slug_formungtuyen');
$lang_form_ung_tuyen_url = function_exists('pll_home_url') ? pll_home_url() . $form_ung_tuyen_slug . '/' : home_url('/' . $form_ung_tuyen_slug . '/');

$custom_breadcrumbs = array(
    array(
        'title' => adtec_get_label('home'),
        'url'   => $lang_home_url
    ),
    array(
        'title' => adtec_get_label('tuyen_dung'),
        'url'   => $lang_form_ung_tuyen_url
    ),
    array(
        'title' => adtec_get_label('form_ung_tuyen'),
        'url'   => ''
    )
);
?>

<div class="career-single-container">
    <?php
    // Render breadcrumb
    if (!empty($custom_breadcrumbs)) {
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
    ?>

    <!-- PAGE TITLE -->
    <div class="career-detail-header">
        <h1 class="career-detail-title"><?php the_title(); ?></h1>
    </div>

    <!-- CONTENT -->
    <?php
    if (have_posts()) : while (have_posts()) : the_post();
        the_content();
    endwhile; endif;
    ?>

    <!-- FORM LINK -->
    <?php if (!empty($form_link)) : ?>
    <div class="career-form-link-wrapper">
        <h3 class="career-form-link-label">Link đăng ký ứng tuyển:</h3>
        <a href="<?php echo esc_url($form_link); ?>" class="career-form-link-btn" target="_blank" rel="noopener">
            ĐĂNG KÝ ỨNG TUYỂN
        </a>
    </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
