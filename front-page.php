<?php
/**
 * Template Name: Homepage
 */
get_header(); 
// 1260×540px
$current_page_id = get_queried_object_id();
if (!$current_page_id) {
    $current_page_id = get_the_ID();
}

$current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';

// 1. LẤY DỮ LIỆU BANNER CỦA TRANG HIỆN TẠI
$slider_ids_str = get_post_meta($current_page_id, '_adtec_home_slider_ids', true);

// 2. NẾU TRANG HIỆN TẠI CHƯA CÓ BANNER (RỖNG) -> TÌM BANNER CỦA TRANG TIẾNG VIỆT
if (empty($slider_ids_str)) {
    $vi_page_id = 0;
    
    // Cách A: Lấy ID bài viết dịch tiếng Việt thông qua Polylang
    if (function_exists('pll_get_post')) {
        $vi_page_id = pll_get_post($current_page_id, 'vi');
    }

    // Cách B: Fallback nếu Polylang chưa link bài -> Lấy trang có slug 'trang-chu' hoặc 'homepage'
    if (!$vi_page_id) {
        $vi_page = get_page_by_path('trang-chu') ?: get_page_by_path('homepage');
        if ($vi_page) {
            $vi_page_id = $vi_page->ID;
        }
    }

    // Nếu tìm thấy ID trang Tiếng Việt -> Lấy meta banner của trang đó
    if ($vi_page_id && $vi_page_id != $current_page_id) {
        $slider_ids_str = get_post_meta($vi_page_id, '_adtec_home_slider_ids', true);
    }
}

// Chuyển chuỗi ID thành mảng
$slider_ids = $slider_ids_str ? explode(',', $slider_ids_str) : array();

// 2. LẤY DỮ LIỆU TIN TỨC MỚI NHẤT (3 BÀI)
$news_args = array(
    'post_type'      => 'tin_tuc',
    'posts_per_page' => 3,
    'lang'           => $current_lang,
    'meta_key'       => 'news_date',
    'orderby'        => 'meta_value',
    'order'          => 'DESC',
    'post_status'    => 'publish',
);
$news_query = new WP_Query($news_args);
$news_page_url = '#';
$news_page = get_page_by_path('tin-tuc');

if ($news_page) {
    $news_page_id = $news_page->ID;
    
    if (function_exists('pll_get_post')) {
        $translated_news_id = pll_get_post($news_page_id, $current_lang);
        if ($translated_news_id) {
            $news_page_id = $translated_news_id;
        }
    }
    
    $news_page_url = get_permalink($news_page_id);
}

// 3. LẤY DỮ LIỆU SECTIONS & MENU
$sections       = get_post_meta($current_page_id, '_adtec_home_sections_data', true);
$all_menus      = wp_get_nav_menus();
$all_menu_items = array();

if (!empty($all_menus)) {
    foreach ($all_menus as $m_obj) {
        $m_lang = function_exists('pll_get_term_language') ? pll_get_term_language($m_obj->term_id) : '';
        if ($m_lang && $m_lang === $current_lang) {
            $items = wp_get_nav_menu_items($m_obj->term_id);
            if (!empty($items)) $all_menu_items = array_merge($all_menu_items, $items);
        }
    }
    if (empty($all_menu_items)) {
        foreach ($all_menus as $m_obj) {
            $items = wp_get_nav_menu_items($m_obj->term_id);
            if (!empty($items)) $all_menu_items = array_merge($all_menu_items, $items);
        }
    }
}
?>

<div class="homepage-container">

    <!-- ==========================================
         KHỐI 1: BANNER SLIDER
         ========================================== -->
    <?php if (!empty($slider_ids)) : ?>
        <div class="home-slider-wrapper">
            <div class="home-slider-container">
                <?php foreach ($slider_ids as $index => $img_id) : 
                    $img_url = wp_get_attachment_image_url($img_id, 'full');
                    if ($img_url) :
                ?>
                    <div class="slide-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo esc_url($img_url); ?>" alt="Banner Adtec">
                    </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
            
            <!-- THANH ĐIỀU HƯỚNG SLIDER (PAGINATION & NAV BUTTONS) -->
            <div class="slider-controls-bar">
                <div class="slider-indicators">
                    <?php foreach ($slider_ids as $index => $img_id) : ?>
                        <span class="indicator-dash <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></span>
                    <?php endforeach; ?>
                </div>
                <div class="slider-arrow-btns">
                    <button class="slider-btn prev-btn" type="button" aria-label="Previous Slide">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 17L10 12L15 7" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button class="slider-btn next-btn" type="button" aria-label="Next Slide">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 7L15 12L10 17" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- KHỐI TIN TỨC NỔI BẬT (BỌC FULL ĐIỀU KIỆN - RỖNG LÀ BẤT HOẠT CẢ KHỐI XANH) -->
<?php if ($news_query->have_posts()) : ?>
    <div class="home-news-section">
        <div class="home-section-row">
            <!-- CỘT TRÁI: TIÊU ĐỀ TIN TỨC -->
            <div class="section-left-box news-left-bg">
                <div class="section-left-content">
                    <h2 class="section-main-title">
                        <?php 
                            if ($current_lang === 'en') echo 'News';
                            elseif ($current_lang === 'ja') echo 'ニュース';
                            else echo 'Tin tức';
                        ?>
                    </h2>
                    <div class="section-divider"></div>
                    <a href="<?php echo esc_url($news_page_url); ?>" class="section-btn-more">
                        <span>
                            <?php 
                                if ($current_lang === 'en') echo 'SEE DETAILS';
                                elseif ($current_lang === 'ja') echo '詳細を見る';
                                else echo 'XEM CHI TIẾT';
                            ?>
                        </span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 7L15 12L10 17" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- CỘT PHẢI: 3 CARD TIN TỨC -->
            <div class="news-cards-container">
                <div class="news-cards-grid">
                    <?php while ($news_query->have_posts()) : $news_query->the_post(); 
                        $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: 'https://via.placeholder.com/300x200';
                    ?>
                        <a href="<?php the_permalink(); ?>" class="news-card-item">
                            <div class="news-card-thumb">
                                <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>">
                            </div>
                            <div class="news-card-info">
                                <span class="news-card-date"><?php echo get_the_date('j/n/Y'); ?></span>
                                <h3 class="news-card-title"><?php the_title(); ?></h3>
                            </div>
                        </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

    <!-- ==========================================
         KHỐI 3: CÁC SECTION DYNAMIC (VỀ ADV, SẢN PHẨM...)
         ========================================== -->
    <div class="home-dynamic-sections">
        <?php if (!empty($sections) && is_array($sections)) : ?>
            <?php foreach ($sections as $sec) : 
                $menu_item_id = isset($sec['menu_item_id']) ? $sec['menu_item_id'] : 0;
                $subtitle     = isset($sec['subtitle']) ? $sec['subtitle'] : '';
                $bg_img_id    = isset($sec['bg_img_id']) ? $sec['bg_img_id'] : 0;
                $bg_url       = $bg_img_id ? wp_get_attachment_image_url($bg_img_id, 'full') : '';

                if (!$menu_item_id) continue;

                $parent_item = null;
                $submenus    = array();

                if (!empty($all_menu_items)) {
                    foreach ($all_menu_items as $item) {
                        if ($item->ID == $menu_item_id) {
                            $parent_item = $item;
                        }
                        if ($item->menu_item_parent == $menu_item_id) {
                            $submenus[] = array(
                                'title' => $item->title,
                                'url'   => $item->url,
                            );
                        }
                    }
                }

                if (!$parent_item) continue;

                $first_detail_link = !empty($submenus) ? $submenus[0]['url'] : $parent_item->url;
                $title_text        = $parent_item->title;
            ?>
                <div class="home-section-row">
                    <div class="section-left-box">
                        <div class="section-left-content">
                            <h2 class="section-main-title"><?php echo esc_html($title_text); ?></h2>
                            <div class="section-divider"></div>
                            
                            <?php if ($subtitle) : ?>
                                <p class="section-subtitle"><?php echo esc_html($subtitle); ?></p>
                            <?php endif; ?>

                            <a href="<?php echo esc_url($first_detail_link); ?>" class="section-btn-more">
                            <span>
                                <?php 
                                    if ($current_lang === 'en') echo 'SEE DETAILS';
                                    elseif ($current_lang === 'ja') echo '詳細を見る';
                                    else echo 'XEM CHI TIẾT';
                                ?>
                            </span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 7L15 12L10 17" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        </div>
                    </div>

                    <div class="section-right-box" style="background-image: url('<?php echo esc_url($bg_url); ?>');">
                        <?php if (!empty($submenus)) : ?>
                            <div class="section-submenu-overlay">
                                <ul class="section-submenu-list">
                                    <?php foreach ($submenus as $sub) : ?>
                                        <li>
                                            <a href="<?php echo esc_url($sub['url']); ?>">
                                                <span><?php echo esc_html($sub['title']); ?></span>
                                                <i class="arrow-icon">&rsaquo;</i>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<!-- SCRIPT CHẠY SLIDER ẢNH ĐƠN GIẢN NGUYÊN BẢN (KHÔNG CẦN SWIPER) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide-item');
    const dashes = document.querySelectorAll('.indicator-dash');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    let currentIndex = 0;

    if (!slides.length) return;

    function showSlide(index) {
        // Xóa active cũ
        slides.forEach(s => {
            s.classList.remove('active');
            s.style.zIndex = '1';
        });
        dashes.forEach(d => d.classList.remove('active'));

        currentIndex = (index + slides.length) % slides.length;

        // Bật active mới và đẩy z-index lên trên
        slides[currentIndex].classList.add('active');
        slides[currentIndex].style.zIndex = '2';

        if (dashes[currentIndex]) {
            dashes[currentIndex].classList.add('active');
        }
    }

    if (nextBtn) nextBtn.addEventListener('click', () => showSlide(currentIndex + 1));
    if (prevBtn) prevBtn.addEventListener('click', () => showSlide(currentIndex - 1));

    dashes.forEach((dash, i) => {
        dash.addEventListener('click', () => showSlide(i));
    });

    // KÍCH HOẠT SLIDE ĐẦU TIÊN NGAY KHI VỪA VÀO TRANG
    showSlide(0);

    // Tự động chuyển slide sau 5s
    setInterval(() => showSlide(currentIndex + 1), 5000);
});
</script>

<!-- SCRIPT TAP-TO-OPEN CHO SUBMENU TRÊN MOBILE -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isMobile = () => window.innerWidth < 768;

    document.querySelectorAll('.home-dynamic-sections .home-section-row').forEach(row => {
        row.addEventListener('click', function (e) {
            if (!isMobile()) return;

            // Nếu bấm trúng link con trong submenu, cho đi thẳng
            if (e.target.closest('.section-submenu-list a')) return;

            // Nếu bấm trúng nút "XEM CHI TIẾT" trong section-left-box
            if (e.target.closest('.section-btn-more')) return;

            const alreadyOpen = row.classList.contains('is-open');

            // Đóng các row khác đang mở (chỉ cho phép 1 submenu mở tại 1 thời điểm)
            document.querySelectorAll('.home-dynamic-sections .home-section-row.is-open')
                .forEach(r => r.classList.remove('is-open'));

            if (!alreadyOpen) {
                row.classList.add('is-open');
            }
        });
    });

    // Đóng submenu khi resize ra khỏi mobile
    window.addEventListener('resize', function() {
        if (!isMobile()) {
            document.querySelectorAll('.home-dynamic-sections .home-section-row.is-open')
                .forEach(r => r.classList.remove('is-open'));
        }
    });
});
</script>

<?php get_footer(); ?>