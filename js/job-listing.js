/**
 * Job Listing & Apply Modal JavaScript
 */
(function() {
    'use strict';

    // Chống chạy trùng nếu script bị enqueue/nhúng 2 lần
    if (window.__adtecJobListingInited) return;
    window.__adtecJobListingInited = true;

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Job Listing JS loaded');

        // ===== Load More: dùng chung cho cả 2 ngữ cảnh (danh sách + cột trái trang chi tiết) =====
        var loadMoreBtns = document.querySelectorAll('.btn-toggle-jobs');
        console.log('Load More buttons found:', loadMoreBtns.length);

        loadMoreBtns.forEach(function (toggleBtn) {
            var isInJobDetailList = !!toggleBtn.closest('.job-list-column');
            var itemSelector = isInJobDetailList ? '.job-list-item' : '.job-row-item';
            console.log('Processing button, itemSelector:', itemSelector);
            var BATCH_SIZE = 8;

            toggleBtn.addEventListener('click', function () {
                console.log('Load More clicked');
                var hiddenItems = Array.from(document.querySelectorAll(itemSelector + '.is-hidden'));
                console.log('Hidden items found:', hiddenItems.length);

                // Force repaint trước khi thay đổi class
                requestAnimationFrame(function() {
                    var count = 0;
                    hiddenItems.forEach(function (item) {
                        if (count < BATCH_SIZE) {
                            item.style.display = '';
                            item.classList.remove('is-hidden');
                            item.classList.add('is-revealing');
                            count++;
                        }
                    });

                    var remaining = document.querySelectorAll(itemSelector + '.is-hidden');
                    console.log('Remaining hidden:', remaining.length);
                    if (remaining.length === 0) {
                        toggleBtn.style.display = 'none';
                    }
                });
            });
        });

        // ===== Prefetch job pages on hover =====
        document.querySelectorAll('.job-list-item, .job-row-item').forEach(function(link) {
            link.addEventListener('mouseenter', function() {
                var href = this.getAttribute('href');
                if (href) {
                    var prefetchLink = document.createElement('link');
                    prefetchLink.rel = 'prefetch';
                    prefetchLink.href = href;
                    document.head.appendChild(prefetchLink);
                    setTimeout(function() { prefetchLink.remove(); }, 100);
                }
            });
        });

        // ===== Ngăn click nút Ứng tuyển bubble lên link cha =====
        document.querySelectorAll('.btn-open-apply-modal').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // ===== CV Upload Preview =====
        var cvUploadZone = document.getElementById('cvUploadZone');
        var cvFileInput = document.getElementById('cv_file');
        var cvPreview = document.getElementById('cvPreview');
        var cvRemove = document.getElementById('cvRemove');

        if (cvFileInput && cvUploadZone) {
            cvFileInput.addEventListener('change', function(e) {
                var file = e.target.files[0];
                if (file && file.type.match(/^image\/(png|jpeg|jpg)$/)) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        cvPreview.innerHTML = '<img src="' + e.target.result + '" alt="CV Preview" />';
                        cvPreview.style.display = 'block';
                        cvRemove.style.display = 'flex';
                        cvUploadZone.classList.add('has-file');
                        document.querySelector('.cv-upload-content').style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });

            if (cvRemove) {
                cvRemove.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    cvFileInput.value = '';
                    cvPreview.innerHTML = '';
                    cvPreview.style.display = 'none';
                    cvRemove.style.display = 'none';
                    cvUploadZone.classList.remove('has-file');
                    document.querySelector('.cv-upload-content').style.display = 'flex';
                });
            }

            // Drag and drop support
            cvUploadZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                cvUploadZone.classList.add('dragover');
            });

            cvUploadZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                cvUploadZone.classList.remove('dragover');
            });

            cvUploadZone.addEventListener('drop', function(e) {
                e.preventDefault();
                cvUploadZone.classList.remove('dragover');
                var files = e.dataTransfer.files;
                if (files.length > 0 && files[0].type.match(/^image\/(png|jpeg|jpg)$/)) {
                    cvFileInput.files = files;
                    cvFileInput.dispatchEvent(new Event('change'));
                }
            });
        }

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

                var formContainer = document.getElementById('applyFormContainer');
                var statusModal = document.getElementById('applyStatusModal');
                var statusIcon = document.getElementById('applyStatusIcon');
                var statusTitle = document.getElementById('applyStatusTitle');
                var statusText = document.getElementById('applyStatusText');
                var statusClose = document.getElementById('applyStatusClose');
                var submitBtn = form.querySelector('.btn-submit');
                var backBtn = document.getElementById('applyModalBack');
                var closeBtn = document.getElementById('applyModalClose');

                // Hiện modal loading
                if (formContainer) formContainer.style.display = 'none';
                if (statusModal) {
                    statusIcon.innerHTML = '<div class="apply-spinner-large"></div>';
                    statusIcon.className = 'apply-status-icon loading';
                    statusTitle.textContent = 'Đang gửi hồ sơ...';
                    statusText.textContent = 'Vui lòng chờ trong giây lát';
                    statusModal.style.display = 'flex';
                }
                if (submitBtn) submitBtn.disabled = true;
                if (backBtn) backBtn.style.display = 'none';
                if (closeBtn) closeBtn.style.display = 'none';

                var formData = new FormData(form);

                fetch(adtec_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.success) {
                        if (statusIcon) {
                            statusIcon.innerHTML = '✅';
                            statusIcon.className = 'apply-status-icon success';
                        }
                        if (statusTitle) statusTitle.textContent = 'Nộp hồ sơ thành công!';
                        if (statusText) statusText.textContent = 'Cảm ơn bạn đã ứng tuyển. Chúng tôi sẽ liên hệ sớm nhất có thể.';
                        if (form) form.reset();
                        setTimeout(function() {
                            if (overlay) overlay.style.display = 'none';
                            resetApplyModal();
                        }, 1000);
                    } else {
                        if (statusIcon) {
                            statusIcon.innerHTML = '❌';
                            statusIcon.className = 'apply-status-icon error';
                        }
                        if (statusTitle) statusTitle.textContent = 'Thất bại';
                        if (statusText) statusText.textContent = data.data && data.data.message ? data.data.message : 'Có lỗi xảy ra, vui lòng thử lại.';
                        if (submitBtn) submitBtn.disabled = false;
                        if (backBtn) backBtn.style.display = '';
                        if (closeBtn) closeBtn.style.display = '';
                    }
                })
                .catch(function() {
                    if (statusIcon) {
                        statusIcon.innerHTML = '❌';
                        statusIcon.className = 'apply-status-icon error';
                    }
                    if (statusTitle) statusTitle.textContent = 'Lỗi kết nối';
                    if (statusText) statusText.textContent = 'Vui lòng kiểm tra mạng và thử lại.';
                    if (submitBtn) submitBtn.disabled = false;
                    if (backBtn) backBtn.style.display = '';
                    if (closeBtn) closeBtn.style.display = '';
                });
            });
        }

        // Reset modal về trạng thái ban đầu
        function resetApplyModal() {
            var formContainer = document.getElementById('applyFormContainer');
            var statusModal = document.getElementById('applyStatusModal');
            var backBtn = document.getElementById('applyModalBack');
            var closeBtn = document.getElementById('applyModalClose');
            var submitBtn = form ? form.querySelector('.btn-submit') : null;

            if (formContainer) formContainer.style.display = '';
            if (statusModal) statusModal.style.display = 'none';
            if (backBtn) backBtn.style.display = '';
            if (closeBtn) closeBtn.style.display = '';
            if (submitBtn) submitBtn.disabled = false;

            // Reset CV upload
            if (cvFileInput) cvFileInput.value = '';
            if (cvPreview) {
                cvPreview.innerHTML = '';
                cvPreview.style.display = 'none';
            }
            if (cvRemove) cvRemove.style.display = 'none';
            if (cvUploadZone) {
                cvUploadZone.classList.remove('has-file');
                var uploadContent = cvUploadZone.querySelector('.cv-upload-content');
                if (uploadContent) uploadContent.style.display = 'flex';
            }
        }

        // Đóng status modal và quay lại form
        var statusCloseBtn = document.getElementById('applyStatusClose');
        if (statusCloseBtn) {
            statusCloseBtn.addEventListener('click', function() {
                var statusModal = document.getElementById('applyStatusModal');
                var formContainer = document.getElementById('applyFormContainer');
                if (statusModal) statusModal.style.display = 'none';
                if (formContainer) formContainer.style.display = '';
                var submitBtn = form ? form.querySelector('.btn-submit') : null;
                var backBtn = document.getElementById('applyModalBack');
                var closeBtn = document.getElementById('applyModalClose');
                if (submitBtn) submitBtn.disabled = false;
                if (backBtn) backBtn.style.display = '';
                if (closeBtn) closeBtn.style.display = '';
            });
        }
    });
})();
