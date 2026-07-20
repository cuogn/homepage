<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package adtec
 */

get_header();
?>

<main id="primary" class="site-main error-404-container">
    <div class="error-404-wrapper">
        <!-- 1. SỐ 404 KHỔNG LỒ -->
        <div class="error-404-code">404</div>

        <!-- 2. TIÊU ĐỀ & MÔ TẢ ĐA NGÔN NGỮ -->
        <h1 class="error-404-title">
            <?php adtec_label('404_title'); ?>
        </h1>

        <p class="error-404-desc">
            <?php adtec_label('404_desc'); ?>
        </p>

        <!-- 4. NÚT VỀ TRANG CHỦ THEO ĐÚNG NGÔN NGỮ HIỆN TẠI -->
        <div class="error-404-actions">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-home-back">
                &larr; <?php adtec_label('404_back_home'); ?>
            </a>
        </div>
    </div>
</main>

<?php
get_footer();