<?php
/**
 * Template Name: Giao diện Trang Quá Trình Phát Triển
 */
get_header(); 

global $wpdb;
$years_in_db = $wpdb->get_col("
    SELECT DISTINCT YEAR(meta_value) 
    FROM {$wpdb->postmeta} 
    WHERE meta_key = 'cot_moc_date' AND meta_value != '' 
    ORDER BY meta_value DESC
");

//Số năm / trang
$years_per_page = 5; 
$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);

$total_years = count($years_in_db);
$total_pages = ceil($total_years / $years_per_page);

// Cắt lấy danh sách Năm của trang hiện tại
$current_years = array_slice($years_in_db, ($paged - 1) * $years_per_page, $years_per_page);
?>

<div class="history-page-container">
    <?php if (function_exists('adv_display_breadcrumb')) { adv_display_breadcrumb(); } ?>

    <!-- 1. TIÊU ĐỀ TRANG -->
    <div class="history-page-header">
        <h1 class="history-page-title"><?php adtec_label('qua_trinh_phat_trien'); ?></h1>
    </div>

    <!-- 2. NÚT ĐIỀU HƯỚNG TRÊN (NEXT) -->
    <div class="history-nav-top">
        <?php if ($paged > 1) : ?>
            <a href="<?php echo esc_url(get_pagenum_link($paged - 1)); ?>" class="history-nav-btn prev-btn">&lt; PREV</a>
        <?php endif; ?>
        
        <?php if ($paged < $total_pages) : ?>
            <a href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>" class="history-nav-btn next-btn">NEXT &gt;</a>
        <?php endif; ?>
    </div>

    <!-- 3. TRỤC THỜI GIAN TIMELINE THẲNG HÀNG NĂM -->
    <div class="history-timeline-wrapper" id="history-timeline">
        <div class="timeline-center-axis"></div>

        <?php
        if ( ! empty($current_years) ) :
            foreach ( $current_years as $year ) :
                // Query các bài viết thuộc năm này
                $history_args = array(
                    'post_type'      => 'cot_moc',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'meta_query'     => array(
                        array(
                            'key'     => 'cot_moc_date',
                            'value'   => array($year . '-01-01', $year . '-12-31'),
                            'compare' => 'BETWEEN',
                            'type'    => 'DATE'
                        )
                    ),
                    'orderby'        => 'meta_value',
                    'order'          => 'DESC'
                );
                $history_query = new WP_Query($history_args);

                $post_ids = array();
                if ($history_query->have_posts()) {
                    while ($history_query->have_posts()) {
                        $history_query->the_post();
                        $post_ids[] = get_the_ID();
                    }
                    wp_reset_postdata();
                }

                if ( ! empty($post_ids) ) :
                    $chunks = array_chunk($post_ids, 2);
                    $is_first_pair = true;
        ?>
            <!-- KHỐI 1 NĂM -->
            <div class="timeline-year-group">
                <?php foreach ($chunks as $pair) : 
                    $left_event_id  = isset($pair[0]) ? $pair[0] : null;
                    $right_event_id = isset($pair[1]) ? $pair[1] : null;
                ?>
                    <div class="timeline-row-grid">
                        <!-- CỘT BÊN TRÁI -->
                        <div class="timeline-col-left">
                            <?php if ($left_event_id) : 
                                $post_left = get_post($left_event_id);
                                $date_left_raw = get_post_meta($left_event_id, 'cot_moc_date', true);
                                $formatted_date_left = !empty($date_left_raw) ? date('[m/Y]', strtotime($date_left_raw)) : get_the_date('[m/Y]', $left_event_id);
                                $thumb_left_id = get_post_thumbnail_id($left_event_id);
                                $img_left_title = $thumb_left_id ? get_the_title($thumb_left_id) : '';
                            ?>
                                <div class="event-content-box">
                                    <div class="event-title-row">
                                        <span class="event-blue-bar"></span>
                                        <span class="event-date"><?php echo esc_html($formatted_date_left); ?></span>
                                        <span class="event-title">- <?php echo esc_html($post_left->post_title); ?></span>
                                    </div>
                                    <?php if ($thumb_left_id) : ?>
                                        <div class="event-image-holder">
                                            <?php echo wp_get_attachment_image($thumb_left_id, 'medium_large'); ?>
                                        </div>
                                        <?php if (!empty($img_left_title)) : ?>
                                            <span class="event-caption"><?php echo esc_html($img_left_title); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- CỘT TRỤC GIỮA -->
                        <div class="timeline-col-center">
                            <?php if ($is_first_pair) : ?>
                                <span class="timeline-year-badge"><?php echo esc_html($year); ?></span>
                                <?php $is_first_pair = false; ?>
                            <?php endif; ?>
                        </div>

                        <!-- CỘT BÊN PHẢI -->
                        <div class="timeline-col-right">
                            <?php if ($right_event_id) : 
                                $post_right = get_post($right_event_id);
                                $date_right_raw = get_post_meta($right_event_id, 'cot_moc_date', true);
                                $formatted_date_right = !empty($date_right_raw) ? date('[m/Y]', strtotime($date_right_raw)) : get_the_date('[m/Y]', $right_event_id);
                                $thumb_right_id = get_post_thumbnail_id($right_event_id);
                                $img_right_title = $thumb_right_id ? get_the_title($thumb_right_id) : '';
                            ?>
                                <div class="event-content-box">
                                    <div class="event-title-row">
                                        <span class="event-blue-bar"></span>
                                        <span class="event-date"><?php echo esc_html($formatted_date_right); ?></span>
                                        <span class="event-title">- <?php echo esc_html($post_right->post_title); ?></span>
                                    </div>
                                    <?php if ($thumb_right_id) : ?>
                                        <div class="event-image-holder">
                                            <?php echo wp_get_attachment_image($thumb_right_id, 'medium_large'); ?>
                                        </div>
                                        <?php if (!empty($img_right_title)) : ?>
                                            <span class="event-caption"><?php echo esc_html($img_right_title); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php 
                endif;
            endforeach;
        endif; 
        ?>
    </div>

    <!-- NÚT ĐIỀU HƯỚNG DƯỚI (PREV / NEXT) -->
    <div class="history-nav-bottom">
        <?php if ($paged > 1) : ?>
            <a href="<?php echo esc_url(get_pagenum_link($paged - 1)); ?>" class="history-nav-btn prev-btn">&lt; PREV</a>
        <?php endif; ?>

        <?php if ($paged < $total_pages) : ?>
            <a href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>" class="history-nav-btn next-btn">NEXT &gt;</a>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>