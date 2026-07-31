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
                <label for="applicant_name">Họ và tên (*)</label>
                <input type="text" id="applicant_name" name="applicant_name" required />
            </div>

            <div class="form-row">
                <label for="applicant_phone">Số điện thoại (*)</label>
                <input type="tel" id="applicant_phone" name="applicant_phone" required />
            </div>

            <div class="form-row">
                <label for="applicant_email">Email</label>
                <input type="email" id="applicant_email" name="applicant_email" />
            </div>

            <div class="form-row">
                <label for="cv_file">Tải lên CV (*)</label>
                <input type="file" id="cv_file" name="cv_file" accept=".png,.jpg,.jpeg" required />
                <small class="form-hint">Hỗ trợ định dạng .png, .jpg, .jpeg có kích thước dưới 10MB</small>
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
                        <a href="/chinh-sach-bao-ve-du-lieu-ca-nhan/" target="_blank">Chính sách bảo vệ dữ liệu cá nhân</a>. (*)
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
