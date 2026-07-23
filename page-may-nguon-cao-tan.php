<?php
/**
 * Template Name: Giao diện Máy Nguồn Cao Tần
 */
get_header(); 

$current_page_id = get_the_ID();
$default_lang    = function_exists('pll_default_language') ? pll_default_language() : 'vi';

// 1. Lấy ID trang gốc tiếng Việt để fallback dữ liệu nếu trang ngôn ngữ phụ chưa nhập
if ( function_exists('pll_get_post') ) {
    $vi_page_id = pll_get_post($current_page_id, $default_lang);
    $data_page_id = $vi_page_id ? $vi_page_id : $current_page_id;
} else {
    $data_page_id = $current_page_id;
}

// 2. Ưu tiên lấy dữ liệu Meta Box từ trang hiện tại, nếu rỗng thì lấy từ trang gốc tiếng Việt
$prod_title = get_post_meta($current_page_id, 'generator_product_title', true);
if (empty($prod_title)) {
    $prod_title = get_post_meta($data_page_id, 'generator_product_title', true);
}

$prod_desc = get_post_meta($current_page_id, 'generator_description', true);
if (empty($prod_desc)) {
    $prod_desc = get_post_meta($data_page_id, 'generator_description', true);
}

$prod_note = get_post_meta($current_page_id, 'generator_note', true);
if (empty($prod_note)) {
    $prod_note = get_post_meta($data_page_id, 'generator_note', true);
}

// 3. Lấy Ảnh đại diện
$thumb_url = get_the_post_thumbnail_url($current_page_id, 'full');
if (empty($thumb_url)) {
    $thumb_url = get_the_post_thumbnail_url($data_page_id, 'full');
}

// 4. Lấy danh sách Thông số kỹ thuật (Cloned Field)
if (function_exists('rwmb_meta')) {
    $specs_list = rwmb_meta('generator_specs_list', array(), $current_page_id);
    if (empty($specs_list)) {
        $specs_list = rwmb_meta('generator_specs_list', array(), $data_page_id);
    }
} else {
    $specs_list = get_post_meta($current_page_id, 'generator_specs_list', false);
    if (empty($specs_list)) {
        $specs_list = get_post_meta($data_page_id, 'generator_specs_list', false);
    }
}

$display_title = !empty($prod_title) ? $prod_title : get_the_title();
?>

<div class="generator-page-container">
    <?php if (function_exists('adv_display_breadcrumb')) { adv_display_breadcrumb(); } ?>

    <!-- 1. TIÊU ĐỀ TRANG -->
    <div class="generator-page-header">
        <h1 class="generator-page-title"><?php the_title(); ?></h1>
    </div>

    <!-- 2. ẢNH ĐẠI DIỆN THIẾT BỊ NỔI BẬT -->
    <?php if ($thumb_url) : ?>
        <div class="generator-main-image">
            <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($display_title); ?>">
        </div>
    <?php endif; ?>

    <!-- 3. KHỐI THÔNG TIN CHI TIẾT SẢN PHẨM -->
    <div class="generator-content-box">
        <!-- TIÊU ĐỀ TÊN MÁY -->

        <!-- ĐOẠN MÔ TẢ GIỚI THIỆU -->
        <?php if ($prod_desc) : ?>
            <div class="generator-description">
                <p><?php echo nl2br(esc_html($prod_desc)); ?></p>
            </div>
        <?php endif; ?>

        <!-- DANH SÁCH BULLET POINTS THÔNG SỐ -->
        <?php if (!empty($specs_list) && is_array($specs_list)) : ?>
            <ul class="generator-specs-list">
                <?php foreach ($specs_list as $spec_item) : 
                    if (is_array($spec_item)) {
                        $spec_text = implode(': ', array_filter($spec_item));
                    } else {
                        $spec_text = (string) $spec_item;
                    }

                    $spec_text = trim($spec_text);

                    if (!empty($spec_text)) :
                        $parts = explode(':', $spec_text, 2);
                ?>
                    <li>
                        <?php if (count($parts) > 1) : ?>
                            <strong><?php echo esc_html(trim($parts[0])); ?>:</strong> <?php echo esc_html(trim($parts[1])); ?>
                        <?php else : ?>
                            <?php echo esc_html($spec_text); ?>
                        <?php endif; ?>
                    </li>
                <?php 
                    endif;
                endforeach; 
                ?>
            </ul>
        <?php endif; ?>

        <!-- GHI CHÚ BỔ SUNG -->
        <?php if ($prod_note) : ?>
            <div class="generator-note">
                <p><strong><?php echo esc_html($prod_note); ?></strong></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>