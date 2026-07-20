<?php
/**
 * Template Name: Giao diện Trang Thiết Bị / Nhà Máy
 */
get_header(); 

// 1. LẤY ID TRANG GỐC TIẾNG VIỆT ĐỂ DÙNG CHUNG SLIDER CHO CẢ 3 NGÔN NGỮ
$current_page_id = get_the_ID();
$default_lang    = function_exists('pll_default_language') ? pll_default_language() : 'vi';

if ( function_exists('pll_get_post') ) {
    $vi_page_id = pll_get_post($current_page_id, $default_lang);
    $slider_page_id = $vi_page_id ? $vi_page_id : $current_page_id;
} else {
    $slider_page_id = $current_page_id;
}

// 2. LẤY ALBUM SLIDER TỪ TRANG GỐC
$slider_img_ids = array();
if (function_exists('rwmb_meta')) {
    $slider_imgs = rwmb_meta('factory_slider_images', array('size' => 'full'), $slider_page_id);
    if (!empty($slider_imgs) && is_array($slider_imgs)) {
        foreach ($slider_imgs as $img) {
            if (isset($img['ID'])) {
                $slider_img_ids[] = $img['ID'];
            }
        }
    }
}

// 3. FALLBACK: NẾU CHƯA UP ALBUM SLIDER THÌ LẤY FEATURED IMAGE CỦA TRANG HIỆN TẠI HOẶC TRANG GỐC
if (empty($slider_img_ids)) {
    $thumb_id = get_post_thumbnail_id($slider_page_id) ? get_post_thumbnail_id($slider_page_id) : get_post_thumbnail_id($current_page_id);
    if ($thumb_id) {
        $slider_img_ids[] = $thumb_id;
    }
}
?>

<!-- ĐƯỜNG DẪN BẮT BUỘC SWIPER CSS & JS (Thêm vào nếu theme chưa có) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<div class="factory-page-container">
    <?php if (function_exists('adv_display_breadcrumb')) { adv_display_breadcrumb(); } ?>

    <!-- 1. TIÊU ĐỀ TRANG -->
    <div class="factory-page-header">
        <h1 class="factory-page-title"><?php adtec_label('trang_thiet_bi'); ?></h1>
    </div>
<!--Kích cỡ banner 1920x600px -->
    <!-- 2. KHỐI SLIDER BANNER PHÍA TRÊN CHUẨN MOCKUP -->
    <?php if ( ! empty($slider_img_ids) ) : ?>
        <div class="factory-slider-wrapper">
            <div class="swiper factorySwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($slider_img_ids as $s_id) : 
                        $s_url = wp_get_attachment_image_url($s_id, 'full');
                    ?>
                        <div class="swiper-slide">
                            <div class="factory-slide-img">
                                <img src="<?php echo esc_url($s_url); ?>" alt="<?php adtec_label('trang_thiet_bi'); ?>">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- THANH ĐIỀU HƯỚNG VÀ NÚT MŨI TÊN CHUẨN MẸO THIẾT KẾ -->
            <div class="factory-slider-controls">
                <div class="swiper-pagination factory-pagination"></div>
                <div class="factory-nav-btns">
                    <button type="button" class="factory-prev-btn" aria-label="Previous">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 17L10 12L15 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button type="button" class="factory-next-btn" aria-label="Next">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 7L15 12L10 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- ... GIỮ NGUYÊN PHẦN 3 VÀ 4 CỦA CÁC TAB VÀ GALLERY BÊN DƯỚI ... -->
    
    <?php
    $current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';

    $terms = get_terms(array(
        'taxonomy'   => 'khu_vuc_nha_may',
        'hide_empty' => false,
        'orderby'    => 'term_id',
        'order'      => 'ASC',
        'lang'       => $current_lang
    ));

    if (empty($terms) || is_wp_error($terms)) {
        $terms = get_terms(array(
            'taxonomy'   => 'khu_vuc_nha_may',
            'hide_empty' => false,
            'orderby'    => 'term_id',
            'order'      => 'ASC'
        ));
    }
    ?>

    <?php if ( ! empty($terms) && ! is_wp_error($terms) ) : ?>
        <!-- 3. NÚT TABS CHỌN KHU VỰC -->
        <div class="factory-tabs-wrapper">
            <ul class="factory-tabs-list">
                <?php 
                $tab_index = 0;
                foreach ($terms as $term) : 
                    $active_class = ($tab_index === 0) ? 'active' : '';
                ?>
                    <li class="tab-item <?php echo esc_attr($active_class); ?>" data-target="category-<?php echo esc_attr($term->term_id); ?>">
                        <button type="button"><?php echo esc_html($term->name); ?></button>
                    </li>
                <?php 
                    $tab_index++;
                endforeach; 
                ?>
            </ul>
        </div>

        <!-- 4. LƯỚI ALBUM ẢNH THIẾT BỊ -->
        <div class="factory-gallery-container">
            <?php 
            $content_index = 0;
            foreach ($terms as $term) : 
                $display_style = ($content_index === 0) ? 'display: grid;' : 'display: none;';

                $equipment_args = array(
                    'post_type'      => 'trang_thiet_bi',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'khu_vuc_nha_may',
                            'field'    => 'term_id',
                            'terms'    => $term->term_id,
                        )
                    ),
                );

                $equipment_query = new WP_Query($equipment_args);
                $all_gallery_image_ids = array();

                if ($equipment_query->have_posts()) {
                    while ($equipment_query->have_posts()) {
                        $equipment_query->the_post();
                        $post_id = get_the_ID();

                        if ( function_exists('rwmb_meta') ) {
                            $images = rwmb_meta('trang_thiet_bi_gallery', array('size' => 'full'), $post_id);
                            if (!empty($images) && is_array($images)) {
                                foreach ($images as $img) {
                                    if (isset($img['ID'])) {
                                        $all_gallery_image_ids[] = $img['ID'];
                                    }
                                }
                            }
                        } 
                        
                        if (empty($all_gallery_image_ids)) {
                            $raw_meta = get_post_meta($post_id, 'trang_thiet_bi_gallery', true);
                            if (is_array($raw_meta)) {
                                $all_gallery_image_ids = array_merge($all_gallery_image_ids, $raw_meta);
                            } else if (!empty($raw_meta)) {
                                $all_gallery_image_ids = array_merge($all_gallery_image_ids, explode(',', $raw_meta));
                            }
                        }
                    }
                    wp_reset_postdata();
                }

                $all_gallery_image_ids = array_unique(array_filter($all_gallery_image_ids));
            ?>
                <div class="factory-gallery-grid tab-content" id="category-<?php echo esc_attr($term->term_id); ?>" style="<?php echo $display_style; ?>">
                    <?php if ( ! empty($all_gallery_image_ids) ) : ?>
                        <?php foreach ( $all_gallery_image_ids as $img_id ) : 
                            $img_full_url  = wp_get_attachment_image_url($img_id, 'full');
                            $img_thumb_url = wp_get_attachment_image_url($img_id, 'large');
                            $img_alt       = get_post_meta($img_id, '_wp_attachment_image_alt', true);
                        ?>
                            <div class="gallery-item-card">
                                <div class="gallery-img-holder">
                                    <a href="<?php echo esc_url($img_full_url); ?>" class="lightbox-link" target="_blank">
                                        <img src="<?php echo esc_url($img_thumb_url); ?>" alt="<?php echo esc_attr($img_alt); ?>">
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="no-data-text" style="grid-column: 1/-1; text-align: center; color: #777;">Chưa có hình ảnh thiết bị nào trong khu vực này.</p>
                    <?php endif; ?>
                </div>
            <?php 
                $content_index++;
            endforeach; 
            ?>
        </div>
    <?php endif; ?>
</div>

<!-- SCRIPT KÍCH HOẠT SWIPER SLIDER VÀ NÚT TABS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Khởi tạo Swiper Slider
    if (typeof Swiper !== 'undefined' && document.querySelector('.factorySwiper')) {
        new Swiper('.factorySwiper', {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.factory-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.factory-next-btn',
                prevEl: '.factory-prev-btn',
            },
        });
    }

    // 2. Chuyển Tab Khu vực
    const tabs = document.querySelectorAll('.factory-tabs-list .tab-item');
    const contents = document.querySelectorAll('.factory-gallery-grid.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.style.display = 'none');

            this.classList.add('active');
            const targetId = this.getAttribute('data-target');
            const targetEl = document.getElementById(targetId);
            if (targetEl) {
                targetEl.style.display = 'grid';
            }
        });
    });
});
</script>

<?php get_footer(); ?>