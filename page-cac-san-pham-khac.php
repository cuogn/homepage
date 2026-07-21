<?php
/**
 * Template Name: Giao diện Trang Các Sản Phẩm Khác
 */
get_header(); 

$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';
?>
<!-- 360x240px -->
<div class="other-products-page-container">
    <?php if (function_exists('adv_display_breadcrumb')) { adv_display_breadcrumb(); } ?>

    <div class="other-products-page-header">
        <h1 class="other-products-page-title"><?php the_title(); ?></h1>
    </div>

    <div class="other-products-list">
        <?php
        $args = array(
            'post_type'      => 'cac_san_pham_khac',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'lang'           => $current_lang,
            'orderby'        => 'menu_order date',
            'order'          => 'ASC',
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $post_id   = get_the_ID();
                $thumb_url = get_the_post_thumbnail_url($post_id, 'large');
                $prod_url  = get_post_meta($post_id, 'other_product_url', true);
        ?>
            <div class="other-product-item">
                <div class="other-product-content">
                    <h2 class="other-product-title"><?php the_title(); ?></h2>
                    <div class="other-product-description">
                        <?php the_content(); ?>
                    </div>
                </div>

                <?php if ($thumb_url) : ?>
                    <div class="other-product-image">
                        <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title_attribute(); ?>">
                    </div>
                <?php endif; ?>
            </div>
        <?php 
            endwhile; 
            wp_reset_postdata();
        else :
        ?>
            <p class="no-data-text" style="text-align: center; color: #666; margin: 40px 0;">
                <?php 
                    if ($current_lang === 'en') echo 'Product list is updating...';
                    elseif ($current_lang === 'ja') echo '製品リストを更新中...';
                    else echo 'Đang cập nhật danh sách sản phẩm...';
                ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>