<?php
/**
 * Template Name: Giao diện Trang Câu Chuyện Nhân Viên
 */
get_header(); 

$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';

// 1. Lấy ảnh Banner từ Customizer
$customizer_banner = get_theme_mod('adtec_employee_story_banner_img');

// 2. Nếu Customizer không có thì lấy Featured Image của Page
$page_id   = get_the_ID();
$banner_id = get_post_thumbnail_id($page_id);
$page_banner_url = $banner_id ? wp_get_attachment_image_url($banner_id, 'full') : '';

$final_banner_url = !empty($customizer_banner) ? $customizer_banner : $page_banner_url;
?>

<div class="employee-stories-page-container">
    <?php if (function_exists('adv_display_breadcrumb')) { adv_display_breadcrumb(); } ?>

    <!-- 1. TIÊU ĐỀ TRANG -->
    <div class="employee-page-header">
        <h1 class="employee-page-title"><?php adtec_label('cau_chuyen_nhan_vien'); ?></h1>
    </div>

    <!-- 2. BANNER TRANG (HIỂN THỊ TỪ CUSTOMIZER BÊN TAY TRÁI) -->
    <?php if ( ! empty($final_banner_url) ) : ?>
        <div class="employee-page-banner">
            <img src="<?php echo esc_url($final_banner_url); ?>" alt="<?php adtec_label('cau_chuyen_nhan_vien'); ?>">
        </div>
    <?php endif; ?>

    <!-- 3. TIÊU ĐỀ PHỤ / SLOGAN -->
    <div class="employee-section-subtitle">
        <h2><?php adtec_label('share_story'); ?></h2>
    </div>

    <!-- 4. DANH SÁCH CÂU CHUYỆN NHÂN VIÊN -->
    <div class="employee-stories-list">
        <?php
        $story_args = array(
            'post_type'      => 'cau_chuyen',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'lang'           => $current_lang,
            'orderby'        => array(
                'menu_order' => 'ASC',
                'date'       => 'DESC',
            ),
        );

        $story_query = new WP_Query($story_args);
        $index = 0;

        if ($story_query->have_posts()) :
            while ($story_query->have_posts()) : $story_query->the_post();
                $post_id   = get_the_ID();
                $role      = get_post_meta($post_id, 'employee_role', true);
                $thumb_id  = get_post_thumbnail_id($post_id);

                $align_class = ($index % 2 === 0) ? 'img-right' : 'img-left';
                $index++;
        ?>
            <!-- MỘT ITEM CÂU CHUYỆN -->
            <div class="employee-story-row <?php echo esc_attr($align_class); ?>">
                <!-- KHỐI CHỮ NỀN XANH NHẠT -->
                <div class="employee-text-box">
                    <h3 class="employee-name"><?php the_title(); ?></h3>
                    <?php if ($role) : ?>
                        <div class="employee-role"><?php echo esc_html($role); ?></div>
                    <?php endif; ?>

                    <div class="employee-description">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- KHỐI ẢNH CHÂN DUNG -->
                <div class="employee-image-box">
                    <?php if ( $thumb_id ) : ?>
                        <?php echo wp_get_attachment_image($thumb_id, 'medium_large'); ?>
                    <?php else : ?>
                        <img src="https://via.placeholder.com/600x400" alt="<?php the_title(); ?>">
                    <?php endif; ?>
                </div>
            </div>
        <?php 
            endwhile; 
            wp_reset_postdata();
        else :
        ?>
            <p class="no-data-text" style="text-align: center; color: #666; margin: 40px 0;">Đang cập nhật nội dung...</p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>