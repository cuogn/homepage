<?php
/**
 * Template Name: Giao diện Trang Tin Tức
 */
get_header(); ?>

<div class="news-page-container">
    <?php if (function_exists('adv_display_breadcrumb')) { adv_display_breadcrumb(); } ?>
    <h1 class="news-page-title"><?php adtec_label('tin_tuc'); ?></h1>

    <!-- ========================================== -->
    <!-- PHẦN 1: SLIDER TIN NỔI BẬT (CPT: tin_tuc)  -->
    <!-- ========================================== -->
    <?php
    $current_lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';

    $featured_query = new WP_Query(array(
        'post_type'      => 'tin_tuc',
        'posts_per_page' => 9,
        'lang'           => $current_lang,
        'meta_key'       => 'news_date', 
        'orderby'        => 'meta_value', 
        'order'          => 'DESC', 
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'news_date',
                'value'   => date('Y-m-d'),
                'compare' => '<=',
                'type'    => 'DATE',
            ),
        ),
    ));
    
    if ( $featured_query->have_posts() ) : ?>
        <div class="featured-news-slider-wrapper">
            <div class="swiper featuredNewsSwiper">
                <div class="swiper-wrapper">
                    <?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
                        <div class="swiper-slide">
                            <div class="featured-slide-content">
                                <div class="slide-left-img">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <?php the_post_thumbnail('large'); ?>
                                    <?php else : ?>
                                        <img src="https://picsum.photos/600/400" alt="Default Image">
                                    <?php endif; ?>
                                </div>
                                <div class="slide-right-info">
                                    <?php 
                                    $custom_date = get_post_meta(get_the_ID(), 'news_date', true);
                                    $display_date = !empty($custom_date) ? date('d/m/Y', strtotime($custom_date)) : get_the_date('d/m/Y');
                                    ?>
                                    <span class="slide-date"><?php echo esc_html($display_date); ?></span>
                                    <h3 class="slide-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>

            <!-- Nút điều hướng -->
            <button class="slider-arrow arrow-prev" aria-label="Previous">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button class="slider-arrow arrow-next" aria-label="Next">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>

            <!-- Thanh điều khiển -->
            <div class="slider-bottom-controls">
                <div class="swiper-pagination-featured"></div>
                <button class="btn-play-pause" data-playing="true">
                    <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>
                    <svg class="icon-play" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"></path></svg>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <!-- ========================================== -->
    <!-- PHẦN 2: DANH SÁCH TIN TỨC MỚI (CPT: tin_tuc)-->
    <!-- ========================================== -->
    <div class="latest-news-section">
        <h2 class="section-news-title"><?php adtec_label('tin_tuc_moi'); ?></h2>

        <div class="news-list-rows" id="local-news-container">
            <?php
            // Lấy toàn bộ bài viết ra ngay từ đầu (-1)
            $news_query = new WP_Query(array(
                'post_type'      => 'tin_tuc',
                'posts_per_page' => -1, // <--- Lấy sạch sẽ toàn bộ bài viết
                'meta_key'       => 'news_date',
                'orderby'        => 'meta_value',
                'order'          => 'DESC',
                'lang'           => $current_lang,
                'post_status'    => 'publish',
                'meta_query'     => array(
                    array(
                        'key'     => 'news_date',
                        'value'   => date('Y-m-d'),
                        'compare' => '<=',
                        'type'    => 'DATE',
                    ),
                ),
            ));
            
            // DEBUG: Log số lượng bài viết (sau khi init $count)
            $count = 0;
            error_log('tin_tuc: total=' . $news_query->found_posts . ', count_var=' . $count);
            ?>
            <?php if ( $news_query->have_posts() ) :
                while ( $news_query->have_posts() ) : $news_query->the_post(); 
                    $custom_date = get_post_meta(get_the_ID(), 'news_date', true);
                    $display_date = !empty($custom_date) ? date('d/m/Y', strtotime($custom_date)) : get_the_date('d/m/Y');
                    
                    // Nếu là bài viết thứ 3 trở đi ($count >= 2), thêm class "is-hidden" để giấu đi
                    $hidden_class = ($count >= 4) ? 'is-hidden' : '';
                    ?>
                    <div class="news-row-item <?php echo $hidden_class; ?>">
                        <div class="news-item-date"><?php echo esc_html($display_date); ?></div>
                        <div class="news-item-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </div>
                        <div class="news-item-thumb">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail('thumbnail'); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php 
                    $count++;
                endwhile; 
            endif; 
            ?>
        </div>

        <?php if ( $count > 4 ) : ?>
            <div class="load-more-wrapper">
                <button class="load-more-btn btn-toggle-posts"
                        data-state="closed"
                        data-text-more="<?php echo esc_attr(adtec_get_label('load_more')); ?>"
                        data-text-less="<?php echo esc_attr(adtec_get_label('collapse')); // Nhớ định nghĩa key 'collapse' trong languages.php ?>">
                    <span class="btn-text"><?php adtec_label('load_more'); ?></span>
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
            </div>
        <?php endif; wp_reset_postdata(); ?>
    </div>

</div>

<?php get_footer(); ?>