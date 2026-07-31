<?php
/**
 * Template part hiển thị layout Job List bên trái + Job Detail bên phải
 *
 * @param array $job_ids   : Mảng các ID job cần hiển thị
 * @param int   $active_id : ID job đang được chọn (hiển thị ở cột phải)
 */

// Lấy biến từ get_template_part
$job_ids   = isset($args['job_ids']) ? $args['job_ids'] : array();
$active_id = isset($args['active_id']) ? intval($args['active_id']) : 0;

// Nếu không có active_id, lấy job đầu tiên
if (!$active_id && !empty($job_ids)) {
    $active_id = intval($job_ids[0]);
}

// Job type options để hiển thị label
$job_type_labels = array(
    'full_time' => 'Toàn thời gian',
    'part_time' => 'Bán thời gian',
    'seasonal'  => 'Thời vụ',
);

// Favicon site làm ảnh fallback
$site_favicon_url = get_site_icon_url(120);

$total_jobs = count($job_ids);
?>

<div class="job-listing-wrapper">
    <!-- ========================== -->
    <!-- CỘT TRÁI: DANH SÁCH JOB -->
    <!-- ========================== -->
    <div class="job-list-column">
        <h2 class="job-list-header"><?php adtec_label('thong_tin_tuyen_dung'); ?></h2>
        <div class="job-list-items">
            <?php if (!empty($job_ids)) : ?>
                <?php foreach ($job_ids as $index => $jid) :
                    $is_active = ($jid == $active_id);
                    $hidden_class = ($index >= 4) ? 'is-hidden' : '';
                    $salary    = rwmb_meta('job_salary', '', $jid);
                    $location  = rwmb_meta('job_location', '', $jid);
                    $deadline  = rwmb_meta('job_deadline', '', $jid);
                    $job_type  = rwmb_meta('job_type', '', $jid);
                    $job_title = get_the_title($jid);

                    // Format deadline
                    $deadline_display = !empty($deadline) ? date('d/m/Y', strtotime($deadline)) : '';

                    $thumb_url = has_post_thumbnail($jid)
                        ? get_the_post_thumbnail_url($jid, 'thumbnail')
                        : $site_favicon_url;
                ?>
                    <a href="<?php echo esc_url(get_permalink($jid)); ?>"
                       class="job-list-item <?php echo $is_active ? 'is-active' : ''; ?> <?php echo $hidden_class; ?>">
                        <?php if ($thumb_url) : ?>
                            <div class="job-item-thumb">
                                <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($job_title); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="job-item-info">
                            <h3 class="job-item-title"><?php echo esc_html($job_title); ?></h3>
                            <div class="job-item-meta">
                                <?php if (!empty($salary)) : ?>
                                    <span class="meta-salary">Lương: <?php echo esc_html($salary); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($deadline_display)) : ?>
                                    <span class="meta-deadline">Hạn nộp hồ sơ: <?php echo esc_html($deadline_display); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="job-list-empty"><?php adtec_label('khong_co_tin'); ?></p>
            <?php endif; ?>

            <?php if ($total_jobs > 4) : ?>
                <div class="load-more-wrapper">
                    <button class="load-more-btn btn-toggle-jobs" data-state="more">
                        <span class="btn-text" style="font-size: 16px;">Xem thêm</span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ========================== -->
    <!-- CỘT PHẢI: CHI TIẾT JOB -->
    <!-- ========================== -->
    <div class="job-detail-column">
        <?php if ($active_id && get_post_status($active_id) === 'publish') :
            $title     = get_the_title($active_id);
            $salary    = rwmb_meta('job_salary', '', $active_id);
            $location  = rwmb_meta('job_location', '', $active_id);
            $deadline  = rwmb_meta('job_deadline', '', $active_id);
            $job_type  = rwmb_meta('job_type', '', $active_id);
            $desc      = rwmb_meta('job_description', '', $active_id);

            // Format deadline
            $deadline_display = !empty($deadline) ? date('d/m/Y', strtotime($deadline)) : '';

            // Job type label
            $job_type_label = isset($job_type_labels[$job_type]) ? $job_type_labels[$job_type] : '';
        ?>
            <div class="job-detail-header">
                <?php
                $detail_thumb_url = has_post_thumbnail($active_id)
                    ? get_the_post_thumbnail_url($active_id, 'thumbnail')
                    : $site_favicon_url;
                ?>
                <div class="job-detail-header-content">
                    <?php if ($detail_thumb_url) : ?>
                        <div class="job-detail-thumb">
                            <img src="<?php echo esc_url($detail_thumb_url); ?>" alt="<?php echo esc_attr($title); ?>">
                        </div>
                    <?php endif; ?>

                    <div class="job-detail-header-text">
                        <h2 class="job-detail-title"><?php echo esc_html($title); ?></h2>

                        <div class="job-detail-top-row">
                            <div class="job-detail-meta-row">
                                <div class="job-detail-meta-left">
                                    <?php if (!empty($salary)) : ?>
                                        <span class="job-detail-meta-item">
                                            <strong>Lương: </strong> <?php echo esc_html($salary); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($location)) : ?>
                                        <span class="job-detail-meta-item">
                                            <strong>Địa điểm: </strong> <?php echo esc_html($location); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="job-detail-meta-right">
                                    <?php if (!empty($deadline_display)) : ?>
                                        <span class="job-detail-meta-item">
                                            <strong>Hạn nộp: </strong> <?php echo esc_html($deadline_display); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($job_type_label)) : ?>
                                        <span class="job-detail-meta-item">
                                            <strong>Hình thức: </strong> <?php echo esc_html($job_type_label); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <button class="btn-open-apply-modal"
                                    data-job-id="<?php echo esc_attr($active_id); ?>"
                                    data-job-title="<?php echo esc_attr($title); ?>">
                                Ứng tuyển &gt;&gt;
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="job-detail-description">
                <?php echo wp_kses_post($desc); ?>
            </div>
        <?php else : ?>
            <div class="job-detail-empty">
                <p>Vui lòng chọn một vị trí tuyển dụng để xem chi tiết.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
