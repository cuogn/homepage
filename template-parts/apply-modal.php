<?php
/**
 * Template part: Modal Form Ứng tuyển
 * Dùng chung cho cả trang danh sách và trang chi tiết
 */
?>
<div class="apply-modal-overlay" id="applyModalOverlay" style="display:none;">
    <div class="apply-modal-box" id="applyFormContainer">
        <div class="apply-modal-header">
            <span>BẠN ĐANG ỨNG TUYỂN CHO VỊ TRÍ <strong id="applyModalJobTitle"></strong></span>
            <button type="button" class="apply-modal-close" id="applyModalClose">&times;</button>
        </div>
        <form id="applyForm" enctype="multipart/form-data">
            <input type="hidden" name="job_id" id="applyJobId" value="" />
            <input type="hidden" name="action" value="submit_job_application" />
            <?php wp_nonce_field('job_application_nonce', 'job_application_nonce_field'); ?>

            <div class="form-row">
                <label for="applicant_name">Họ và tên <span class="required-mark">(*)</span></label>
                <input type="text" id="applicant_name" name="applicant_name" required />
            </div>

            <div class="form-row">
                <label for="applicant_phone">Số điện thoại <span class="required-mark">(*)</span></label>
                <input type="tel" id="applicant_phone" name="applicant_phone" required />
            </div>

            <div class="form-row">
                <label for="applicant_email">Email</label>
                <input type="email" id="applicant_email" name="applicant_email" />
            </div>

            <div class="form-row">
                <label for="cv_file">Tải lên CV <span class="required-mark">(*)</span></label>
                <div class="cv-upload-zone">
                    <div class="cv-upload-content">
                        <div class="cv-upload-icon-wrapper">
                            <svg class="cv-upload-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 15V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17 8L12 3L7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 3V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <p class="cv-upload-text">Tải lên ảnh CV, CCCD hoặc giấy tờ cá nhân</p>
                        <p class="cv-upload-hint">Hỗ trợ định dạng .png, .jpg, .jpeg có kích thước dưới 10Mb</p>
                        <button type="button" class="cv-select-btn">Chọn CV</button>
                    </div>
                    <input type="file" id="cv_file" name="cv_file" accept=".png,.jpg,.jpeg" required />
                    <div class="cv-file-info" style="display:none;">
                        <span class="cv-file-name"></span>
                        <span class="cv-remove-btn">×</span>
                    </div>
                </div>
            </div>

            <fieldset class="referral-section">
                <legend>Giới thiệu ứng viên (nếu có)</legend>

                <div class="form-row">
                    <label for="referral_name">Họ và tên người giới thiệu</label>
                    <input type="text" id="referral_name" name="referral_name" />
                </div>

                <div class="form-row">
                    <label for="referral_employee_code">Mã nhân viên</label>
                    <input type="text" id="referral_employee_code" name="referral_employee_code" />
                </div>

                <div class="form-row">
                    <label for="referral_email">Email công ty</label>
                    <input type="email" id="referral_email" name="referral_email" />
                </div>
            </fieldset>

            <div class="form-row consent-row">
                <label class="apply-consent">
                    <input type="checkbox" name="consent_given" required />
                    <span>Tôi đồng ý cho phép thu thập, xử lý và sử dụng dữ liệu cá nhân theo
                        <a href="/chinh-sach-bao-ve-du-lieu-ca-nhan/" target="_blank">Chính sách bảo vệ dữ liệu cá nhân</a>. <span class="required-mark">(*)</span>
                    </span>
                </label>
            </div>

            <div class="apply-modal-actions">
                <button type="button" class="btn-back" id="applyModalBack">← Quay lại</button>
                <button type="submit" class="btn-submit">✓ Nộp hồ sơ</button>
            </div>
        </form>
    </div>

    <!-- Loading / Result Modal -->
    <div class="apply-status-modal" id="applyStatusModal" style="display:none;">
        <div class="apply-status-box">
            <div class="apply-status-icon" id="applyStatusIcon"></div>
            <h3 class="apply-status-title" id="applyStatusTitle"></h3>
            <p class="apply-status-text" id="applyStatusText"></p>
            <button type="button" class="btn-apply-status-close" id="applyStatusClose">Đóng</button>
        </div>
    </div>
</div>
