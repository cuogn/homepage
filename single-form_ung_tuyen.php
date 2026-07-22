<?php
/**
 * Template Name: Single Tuyển dụng / Form Ứng tuyển
 */
get_header();

$post_id = get_the_ID();

// Get custom metabox fields
$requirements = get_post_meta($post_id, 'career_requirements', true);
$details      = get_post_meta($post_id, 'career_details', true);
$process      = get_post_meta($post_id, 'career_process', true);
$form_link    = get_post_meta($post_id, 'career_form_link', true);

// Get current language
$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';

// Breadcrumb URL & Slugs
$lang_home_url = function_exists('pll_home_url') ? pll_home_url() : home_url('/');
$form_ung_tuyen_slug = function_exists('adtec_get_label') ? adtec_get_label('slug_formungtuyen') : 'form-ung-tuyen';
$lang_form_ung_tuyen_url = function_exists('pll_home_url') ? pll_home_url() . $form_ung_tuyen_slug . '/' : home_url('/' . $form_ung_tuyen_slug . '/');

$custom_breadcrumbs = array(
    array(
        'title' => function_exists('adtec_get_label') ? adtec_get_label('home') : 'Trang chủ',
        'url'   => $lang_home_url
    ),
    array(
        'title' => function_exists('adtec_get_label') ? adtec_get_label('tuyen_dung') : 'Tuyển dụng',
        'url'   => $lang_form_ung_tuyen_url
    ),
    array(
        'title' => function_exists('adtec_get_label') ? adtec_get_label('form_ung_tuyen') : 'Form ứng tuyển',
        'url'   => ''
    )
);
?>

<div class="career-single-container">
    
    <!-- BREADCRUMB -->
    <?php
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

    <!-- PAGE TITLE (TÊN VỊ TRÍ TUYỂN DỤNG) -->
    <div class="career-detail-header">
        <h1 class="career-detail-title"><?php the_title(); ?></h1>
    </div>

    <div class="career-detail-body">
        
        <!-- NỘI DUNG MẶC ĐỊNH BÀI VIẾT (NẾU CÓ) -->
        <?php
        if (have_posts()) : while (have_posts()) : the_post();
            if (get_the_content()) :
                echo '<div class="career-default-content">' . apply_filters('the_content', get_the_content()) . '</div>';
            endif;
        endwhile; endif;
        ?>

        <!-- 1. SECTION: YÊU CẦU CHUNG -->
        <?php if (!empty($requirements)) : ?>
        <div class="career-detail-section">
            <h2 class="career-section-heading">
                <?php 
                    if ($current_lang === 'en') echo 'General Requirements';
                    elseif ($current_lang === 'ja') echo '一般要件';
                    else echo 'Yêu cầu chung';
                ?>
            </h2>
            <div class="career-section-line"></div>
            <div class="career-section-content">
                <?php echo wpautop(esc_html($requirements)); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 2. SECTION: CHI TIẾT -->
        <?php if (!empty($details)) : ?>
        <div class="career-detail-section">
            <h2 class="career-section-heading">
                <?php 
                    if ($current_lang === 'en') echo 'Details';
                    elseif ($current_lang === 'ja') echo '詳細';
                    else echo 'Chi tiết';
                ?>
            </h2>
            <div class="career-section-line"></div>
            <div class="career-section-content">
                <?php echo wpautop(esc_html($details)); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- 3. SECTION: QUY TRÌNH ỨNG TUYỂN & CÁC THÔNG TIN KHÁC -->
        <?php if (!empty($process) || !empty($form_link)) : ?>
        <div class="career-detail-section">
            <h2 class="career-section-heading">
                <?php 
                    if ($current_lang === 'en') echo 'Application Process';
                    elseif ($current_lang === 'ja') echo '選考プロセス';
                    else echo 'Quy trình ứng tuyển';
                ?>
            </h2>
            <div class="career-section-line"></div>
            <div class="career-section-content">
                
                <!-- NỘI DUNG QUY TRÌNH ỨNG TUYỂN -->
                <?php if (!empty($process)) : ?>
                    <div class="career-process-text">
                        <?php echo wpautop(esc_html($process)); ?>
                    </div>
                <?php endif; ?>

                <!-- LINK ĐĂNG KÝ THI TUYỂN/ỨNG TUYỂN -->
                <?php if (!empty($form_link)) : ?>
                <div class="career-form-link-row">
                    <span class="career-form-label">
                        <?php 
                            if ($current_lang === 'en') echo 'Application link: ';
                            elseif ($current_lang === 'ja') echo '応募リンク: ';
                            else echo 'Link đăng ký ứng tuyển: ';
                        ?>
                    </span>
                    <a href="<?php echo esc_url($form_link); ?>" class="career-form-link-anchor" target="_blank" rel="noopener">
                        <?php 
                            if ($current_lang === 'en') echo 'REGISTER NOW';
                            elseif ($current_lang === 'ja') echo '今すぐ応募';
                            else echo 'ĐĂNG KÝ THI TUYỂN';
                        ?>
                    </a>
                </div>
                <?php endif; ?>

            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>