/**
 * Job Listing & Apply Modal JavaScript
 */
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        // ===== Toggle "Xem thêm / Thu gọn" =====
        var toggleBtn = document.querySelector('.btn-toggle-jobs');
        if (toggleBtn) {
            var hiddenItems = document.querySelectorAll('.job-row-item.is-hidden');
            var textEl = toggleBtn.querySelector('.btn-text');

            toggleBtn.addEventListener('click', function () {
                var expand = toggleBtn.dataset.state === 'closed';

                hiddenItems.forEach(function (item) {
                    item.classList.toggle('is-hidden', !expand);
                });

                toggleBtn.dataset.state = expand ? 'open' : 'closed';
                textEl.textContent = expand ? toggleBtn.dataset.textLess : toggleBtn.dataset.textMore;
                toggleBtn.classList.toggle('is-open', expand);
            });
        }

        // ===== Ngăn click nút Ứng tuyển bubble lên link cha =====
        var applyBtns = document.querySelectorAll('.btn-open-apply-modal');
        applyBtns.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // ===== Modal Apply =====
        var overlay = document.getElementById('applyModalOverlay');
        if (!overlay) return;

        var jobTitleEl = document.getElementById('applyModalJobTitle');
        var jobIdInput = document.getElementById('applyJobId');
        var form = document.getElementById('applyForm');

        // Mở modal khi click nút "Ứng tuyển"
        var openBtns = document.querySelectorAll('.btn-open-apply-modal');
        openBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var jobTitle = this.dataset.jobTitle || '';
                var jobId = this.dataset.jobId || '';
                
                if (jobTitleEl) jobTitleEl.textContent = jobTitle;
                if (jobIdInput) jobIdInput.value = jobId;
                
                overlay.style.display = 'flex';
            });
        });

        // Đóng modal
        var closeBtn = document.getElementById('applyModalClose');
        var backBtn = document.getElementById('applyModalBack');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                overlay.style.display = 'none';
            });
        }
        
        if (backBtn) {
            backBtn.addEventListener('click', function() {
                overlay.style.display = 'none';
            });
        }

        // Đóng modal khi click ra ngoài
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                overlay.style.display = 'none';
            }
        });

        // Submit form
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                var msgBox = form.querySelector('.apply-form-message');
                if (!msgBox) return;
                
                msgBox.textContent = 'Đang gửi...';
                msgBox.className = 'apply-form-message';
                
                var formData = new FormData(form);

                fetch(adtec_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.success) {
                        msgBox.textContent = 'Nộp hồ sơ thành công! Cảm ơn bạn đã ứng tuyển.';
                        msgBox.className = 'apply-form-message success';
                        form.reset();
                        setTimeout(function() {
                            overlay.style.display = 'none';
                        }, 2000);
                    } else {
                        msgBox.textContent = data.data && data.data.message ? data.data.message : 'Có lỗi xảy ra, vui lòng thử lại.';
                        msgBox.className = 'apply-form-message error';
                    }
                })
                .catch(function() {
                    msgBox.textContent = 'Lỗi kết nối, vui lòng thử lại.';
                    msgBox.className = 'apply-form-message error';
                });
            });
        }
    });
})();
