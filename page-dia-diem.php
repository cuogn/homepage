<?php
/**
 * Template Name: Giao diện Trang Adtec Group
 */
get_header(); 

// Lấy ảnh Banner từ Customizer
$map_banner = get_theme_mod('adtec_group_map_banner', '');
?>

<div class="adtec-group-container">
    <?php if (function_exists('adv_display_breadcrumb')) { adv_display_breadcrumb(); } ?>

    <!-- 1. TIÊU ĐỀ TRANG -->
    <div class="adtec-group-header">
        <h1 class="adtec-group-title"><?php the_title(); ?></h1>
    </div>

    <!-- 2. BANNER BẢN ĐỒ TOÀN CẦU (Thay đổi trực tiếp từ Customizer) -->
    <?php if ( ! empty($map_banner) ) : ?>
        <div class="adtec-group-banner">
            <img src="<?php echo esc_url($map_banner); ?>" alt="Adtec Group Global Network Map">
        </div>
    <?php endif; ?>

    <!-- 3. DANH SÁCH MẠNG LƯỚI ĐỊA ĐIỂM CHI TIẾT -->
    <div class="adtec-group-content">
        <?php
        // Lấy toàn bộ Taxonomy Quốc gia
        $quoc_gia_terms = get_terms(array(
            'taxonomy'   => 'quoc_gia',
            'hide_empty' => true,
            'orderby'    => 'term_order',
            'order'      => 'ASC',
        ));

        if ( ! is_wp_error($quoc_gia_terms) && ! empty($quoc_gia_terms) ) :
            foreach ( $quoc_gia_terms as $term ) :
                // Lấy slug quốc gia làm Prefix (VD: jp -> JP, vi -> VN)
                // $country_code = strtoupper(substr($term->slug, 0, 2));
        ?>
            <!-- BẮT ĐẦU MỘT KHU VỰC QUỐC GIA -->
            <div class="adtec-country-section">
                <h2 class="adtec-country-title">
                    <!-- <span class="country-code"><?php echo esc_html($country_code); ?></span> -->
                    <span class="country-name"><?php echo esc_html($term->name); ?></span>
                </h2>

                <div class="adtec-location-grid">
                    <?php
                    // Query các địa điểm thuộc Quốc gia
                    $location_args = array(
                        'post_type'      => 'dia_diem',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'quoc_gia',
                                'field'    => 'term_id',
                                'terms'    => $term->term_id,
                            ),
                        ),
                        // 'meta_query'     => array(
                        //     'relation' => 'OR',
                        //     array(
                        //         'key'     => 'dia_diem_order',
                        //         'compare' => 'EXISTS',
                        //     ),
                        //     array(
                        //         'key'     => 'dia_diem_order',
                        //         'compare' => 'NOT EXISTS',
                        //     ),
                        // ),
                        'orderby'        => array(
                            'meta_value_num' => 'ASC',
                            'date'           => 'DESC'
                        ),
                    );
                    $location_query = new WP_Query($location_args);

                    if ($location_query->have_posts()) :
                        while ($location_query->have_posts()) : $location_query->the_post();
                            
                            $post_id = get_the_ID();
                            // Meta Box values
                            $type    = get_post_meta($post_id, 'dia_diem_type', true);
                            $address = get_post_meta($post_id, 'dia_diem_address', true);
                            $tel     = get_post_meta($post_id, 'dia_diem_tel', true);
                            $fax     = get_post_meta($post_id, 'dia_diem_fax', true);
                            $url     = get_post_meta($post_id, 'dia_diem_url', true);
                            
                            // Map nhãn loại địa điểm
                            $type_labels = array(
                                'hq'      => 'Headquarters & R&D',
                                'sale'    => 'Sales Office',
                                'factory' => 'Factory',
                                'branch'  => 'Branch Office',
                            );
                            $display_type = isset($type_labels[$type]) ? $type_labels[$type] : '';
                    ?>
                        <!-- MỘT THẺ CARD ĐỊA ĐIỂM -->
                        <div class="adtec-location-card">
                            <div class="location-card-thumb">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail('medium_large'); ?>
                                <?php else : ?>
                                    <img src="https://via.placeholder.com/600x400" alt="Adtec Location">
                                <?php endif; ?>
                            </div>
                            
                            <div class="location-card-body">
                                <?php if ($display_type) : ?>
                                    <span class="location-type-badge"><?php echo esc_html($display_type); ?></span>
                                <?php endif; ?>
                                
                                <h3 class="location-card-title"><?php the_title(); ?></h3>
                                
                                <div class="location-card-info">
                                    <?php if ($address) : ?>
                                        <p><strong><?php adtec_label('address'); ?></strong> <?php echo esc_html($address); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    // Xử lý Lấy nhiều Số điện thoại
                                    $tels = get_post_meta($post_id, 'dia_diem_tel');
                                    // Nếu chỉ lưu 1 chuỗi hoặc lưu dưới dạng mảng thì gộp lại
                                    if ( is_array($tels) ) {
                                        $tels = array_filter(array_merge(...array_map(function($v) { return (array)$v; }, $tels)));
                                    }
                                    if ( ! empty($tels) ) : 
                                        $tel_string = implode(' / ', $tels); // Nối các số bằng dấu " / "
                                    ?>
                                        <p><strong>TEL:</strong> <?php echo esc_html($tel_string); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    // Xử lý Lấy nhiều Số FAX
                                    $faxes = get_post_meta($post_id, 'dia_diem_fax');
                                    if ( is_array($faxes) ) {
                                        $faxes = array_filter(array_merge(...array_map(function($v) { return (array)$v; }, $faxes)));
                                    }
                                    if ( ! empty($faxes) ) : 
                                        $fax_string = implode(' / ', $faxes); // Nối các số FAX bằng dấu " / "
                                    ?>
                                        <p><strong>FAX:</strong> <?php echo esc_html($fax_string); ?></p>
                                    <?php endif; ?>

                                    <?php if ($url) : ?>
                                        <p><strong>URL:</strong> <a href="<?php echo esc_url($url); ?>" target="_blank" class="location-url-link"><?php echo esc_html($url); ?></a></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endwhile; 
                        wp_reset_postdata();
                    endif; 
                    ?>
                </div> <!-- End .adtec-location-grid -->
            </div>
        <?php 
            endforeach; 
        endif; 
        ?>
    </div>
</div>

<?php get_footer(); ?>