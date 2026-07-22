<?php
/**
 * Template hiển thị chi tiết bài viết Thông tin tuyển dụng (CPT: thong_tin_tuyen_dung)
 */
get_header(); ?>

<div class="news-detail-wrapper">
    <?php 
    // 1. Tự động lấy link Trang chủ theo ngôn ngữ hiện tại của Polylang
    $lang_home_url = function_exists('pll_home_url') ? pll_home_url() : home_url('/');

    // 2. Tự động lấy slug trang Sự kiện năm theo ngôn ngữ hiện tại từ từ điển languages.php
    $recruitment_slug = adtec_get_label('slug_thongtintuyendung');

    // 3. Tạo link trang Thông tin tuyển dụng chuẩn đa ngôn ngữ (Ví dụ: .../en/recruitment/ hoặc .../ja/recruitment-ja/)
    $lang_recruitment_url = function_exists('pll_home_url') ? pll_home_url() . $recruitment_slug . '/' : home_url('/' . $recruitment_slug . '/');

    $custom_breadcrumbs = array(
        array(
            'title' => adtec_get_label('home'),
            'url'   => $lang_home_url
        ),
        array(
            'title' => adtec_get_label('tuyen_dung'), // Nếu có key này trong languages.php
            'url'   => $lang_recruitment_url
        ),
        array(
            'title' => adtec_get_label('thong_tin_tuyen_dung'),
            'url'   => ''
        )
    );

    // Render ra HTML chuẩn cấu trúc của theme
    if ( ! empty($custom_breadcrumbs) ) {
        echo '<div class="adv-breadcrumb-row">';
        echo '<div class="adv-breadcrumb-container">';
        echo '<nav class="adv-breadcrumb" aria-label="Breadcrumb">';
        
        $count = count($custom_breadcrumbs);
        foreach ($custom_breadcrumbs as $index => $crumb) {
            if ($index === $count - 1) {
                // Mục cuối cùng
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

    <!-- Tiêu đề lớn (Đã dọn dẹp sạch sẽ đống if/else lằng nhằng bằng từ điển) -->
    <h1 class="news-detail-main-header">
        <?php adtec_label('thong_tin_tuyen_dung'); ?>
    </h1>

    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post(); 
            
            // Lấy ngày tháng hiển thị từ Meta Box
            $custom_date = get_post_meta(get_the_ID(), 'recruitment_date', true);
            $display_date = !empty($custom_date) ? date('d/m/Y', strtotime($custom_date)) : get_the_date('d/m/Y');
            
            // Lấy album ảnh từ Meta Box
            // Lấy album ảnh gốc từ Meta Box (Mảng chứa các ID ảnh hoặc thông tin ảnh gốc)
            $raw_gallery = rwmb_meta( 'recruitment_gallery', array( 'size' => 'large' ) );
            $gallery_images = array();

            if ( ! empty( $raw_gallery ) ) {
                foreach ( $raw_gallery as $img_id => $image_data ) {
                    $final_img_id = $img_id;

                    // BẮT BUỘC: Nếu có Polylang, chuyển đổi ID ảnh gốc sang ID ảnh đã dịch tương ứng
                    if ( function_exists('pll_get_post') ) {
                        $translated_img_id = pll_get_post($img_id);
                        if ( $translated_img_id ) {
                            $final_img_id = $translated_img_id;
                        }
                    }

                    // Lấy lại thông tin Title, Alt, Caption chuẩn theo ID ảnh đã dịch
                    $gallery_images[] = array(
                        'url'     => wp_get_attachment_image_url($final_img_id, 'large'),
                        'alt'     => get_post_meta($final_img_id, '_wp_attachment_image_alt', true),
                        'title'   => get_the_title($final_img_id), // Lấy đúng Title ảnh đã dịch
                        'caption' => wp_get_attachment_caption($final_img_id)
                    );
                }
            }
            ?>

            <article id="post-<?php the_ID(); ?>" class="news-detail-article-block">
                
                <!-- Ngày hiển thị - Sát góc phải -->
                <div class="news-detail-meta-date"><?php echo esc_html($display_date); ?></div>

                <!-- Tiêu đề bài viết -->
                <h2 class="news-detail-article-title"><?php the_title(); ?></h2>

                <!-- Vùng nội dung xen kẽ ảnh tự động -->
                <div class="news-detail-body-text">
                    <?php 
                    // 1. Lấy nội dung văn bản từ editor và lọc qua filter chuẩn của WP
                    $content = apply_filters('the_content', get_the_content());
                    
                    if ( ! empty($gallery_images) ) {
                        // 2. Sử dụng DOMDocument để bóc tách các thẻ <p> (đoạn văn) trong nội dung
                        $dom = new DOMDocument();
                        // Tránh lỗi hiển thị ký tự UTF-8 tiếng Việt
                        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                        
                        $paragraphs = $dom->getElementsByTagName('p');
                        $p_list = array();
                        foreach ($paragraphs as $p) {
                            $p_list[] = $dom->saveHTML($p);
                        }

                        // Cấu hình: Sau đoạn văn thì chèn 1 hàng 2 ảnh từ album vào
                        $paragraphs_interval = 2; 
                        $image_index = 0;
                        $total_images = count($gallery_images);

                        // Lặp qua từng đoạn văn để in ra và chèn ảnh xen kẽ
                        foreach ($p_list as $index => $paragraph_html) {
                            echo $paragraph_html;


                            if ( ($index + 1) % $paragraphs_interval == 0 && $image_index < $total_images ) {
                                echo '<div class="news-detail-gallery-grid inline-gallery">';
                                
                                // Chèn tối đa 2 tấm ảnh cho mỗi hàng
                                for ($i = 0; $i < 2; $i++) {
                                    if ( isset($gallery_images[$image_index]) ) {
                                        $image = $gallery_images[$image_index];
                                        ?>
                                        <div class="gallery-image-wrapper">
                                            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="gallery-main-img">
                                            <div class="gallery-image-meta">
                                                <?php if ( ! empty($image['title']) && ! preg_match('/^[a-zA-Z0-9_\-\s]+\.(jpg|jpeg|png|gif|webp)$/i', $image['title']) ) : ?>
                                                    <h4 class="gallery-image-title"><?php echo esc_html($image['title']); ?></h4>
                                                <?php endif; ?>
                                                <?php if ( ! empty($image['caption']) ) : ?>
                                                    <p class="gallery-image-caption"><?php echo esc_html($image['caption']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php
                                        $image_index++;
                                    }
                                }
                                echo '</div>';
                            }
                        }

                        // Nếu in hết chữ rồi mà vẫn còn thừa ảnh trong album, dồn toàn bộ ảnh thừa xuống cuối bài
                        if ( $image_index < $total_images ) {
                            echo '<div class="news-detail-gallery-grid inline-gallery remaining-gallery">';
                            while ( $image_index < $total_images ) {
                                $image = $gallery_images[$image_index];
                                ?>
                                <div class="gallery-image-wrapper">
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="gallery-main-img">
                                    <div class="gallery-image-meta">
                                        <?php if ( ! empty($image['title']) && ! preg_match('/^[a-zA-Z0-9_\-\s]+\.(jpg|jpeg|png|gif|webp)$/i', $image['title']) ) : ?>
                                            <h4 class="gallery-image-title"><?php echo esc_html($image['title']); ?></h4>
                                        <?php endif; ?>
                                        <?php if ( ! empty($image['caption']) ) : ?>
                                            <p class="gallery-image-caption"><?php echo esc_html($image['caption']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php
                                $image_index++;
                            }
                            echo '</div>';
                        }

                    } else {
                        // Nếu bài viết hoàn toàn không có album ảnh Meta Box, chỉ hiển thị chữ thuần túy
                        echo $content;
                    }
                    ?>
                </div>

            </article>

        <?php 
        endwhile; 
    endif; 
    ?>
</div>

<?php get_footer(); ?>