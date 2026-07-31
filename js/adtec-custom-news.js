document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector(".featuredNewsSwiper")) {
        // Khởi tạo Swiper cho slider tin tức
        const featuredSwiper = new Swiper(".featuredNewsSwiper", {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination-featured",
                clickable: true,
            },
            navigation: {
                nextEl: ".arrow-next",
                prevEl: ".arrow-prev",
            },
        });

        // Xử lý nút Play/Pause nhỏ màu đỏ ở dưới
        const playPauseBtn = document.querySelector(".btn-play-pause");
        if (playPauseBtn) {
            const iconPlay = playPauseBtn.querySelector(".icon-play");
            const iconPause = playPauseBtn.querySelector(".icon-pause");

            playPauseBtn.addEventListener("click", function () {
                const isPlaying = playPauseBtn.getAttribute("data-playing") === "true";
                if (isPlaying) {
                    featuredSwiper.autoplay.stop();
                    playPauseBtn.setAttribute("data-playing", "false");
                    iconPause.style.display = "none";
                    iconPlay.style.display = "block";
                } else {
                    featuredSwiper.autoplay.start();
                    playPauseBtn.setAttribute("data-playing", "true");
                    iconPlay.style.display = "none";
                    iconPause.style.display = "block";
                }
            });
        }
    }
});

jQuery(document).ready(function ($) {    
    // Lắng nghe sự kiện click trên class chung btn-toggle-posts
    // Hỗ trợ tất cả các trang slider (Tin tức, Sự kiện, v.v.)
    $('.btn-toggle-posts').on('click', function () {
        var btn = $(this);
        // Tìm container chứa danh sách bài viết (dùng .news-page-container làm cha chung)
        var container = btn.closest('.news-page-container').find('.news-list-rows');
        var state = btn.attr('data-state');

        // console.log('DEBUG click: state=', state, 'container=', container.length, 'hidden=', container.find('.is-hidden').length);

        // Lấy nhãn dịch đa ngôn ngữ từ thuộc tính data-
        var textMore = btn.attr('data-text-more');
        var textLess = btn.attr('data-text-less');

        if (state === 'closed') {
            // Hiển thị các items bị ẩn - xóa class is-hidden để CSS hiện ra
            container.find('.is-hidden').removeClass('is-hidden').hide().fadeIn(300);
            container.addClass('is-expanded');
            btn.attr('data-state', 'open');
            btn.find('.btn-text').text(textLess);
            btn.find('.btn-icon').css('transform', 'rotate(180deg)');
        } else {
            // Thu gọn lại - thêm class is-hidden để CSS ẩn đi
            container.find('.news-row-item').not(':nth-child(-n+4)').addClass('is-hidden');
            container.removeClass('is-expanded');
            btn.attr('data-state', 'closed');
            btn.find('.btn-text').text(textMore);
            btn.find('.btn-icon').css('transform', 'rotate(0deg)');
        }
    });

});