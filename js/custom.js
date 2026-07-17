document.addEventListener("DOMContentLoaded", function () {

    // --- 2. LOGIC CHO SLIDER TRANG TIN TỨC ---
    if (document.querySelector(".featuredNewsSwiper")) {
        const featuredSwiper = new Swiper(".featuredNewsSwiper", {
            loop: true,
            speed: 800,
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

        // Nút Play/Pause nhỏ ở góc phải slider tin tức
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

// --- 3. XỬ LÝ SỰ KIỆN CLICK NÚT LOAD MORE ---
const loadMoreBtn = document.getElementById("btn-load-more");
const newsContainer = document.getElementById("ajax-news-container");

if (loadMoreBtn && newsContainer) {
    loadMoreBtn.addEventListener("click", function () {
        // Lấy số trang hiện tại đang lưu trữ ở thuộc tính data-page
        let currentPage = parseInt(loadMoreBtn.getAttribute("data-page"));

        // Thay đổi trạng thái nút khi đang tải dữ liệu
        const btnText = loadMoreBtn.querySelector("span");
        const originalText = btnText.textContent;
        btnText.textContent = "LOADING...";
        loadMoreBtn.style.pointerEvents = "none"; // Khóa click tạm thời

        // Khởi tạo đối tượng FormData để gửi dữ liệu lên WP
        let formData = new FormData();
        formData.append("action", "load_more_news");
        formData.append("page", currentPage);

        // Gửi request AJAX
        fetch(adtec_ajax_obj.ajax_url, {
            method: "POST",
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                // Nếu server trả về dữ liệu trống (bằng 0) -> Hết bài để tải
                if (data.trim() === "0" || data.trim() === "") {
                    btnText.textContent = "NO MORE NEWS";
                    loadMoreBtn.style.display = "none"; // Ẩn luôn nút Load More
                } else {
                    // Chèn đống bài viết mới vào cuối danh sách hiện tại
                    newsContainer.insertAdjacentHTML("beforeend", data);

                    // Tăng số trang hiện tại lên 1 đơn vị
                    loadMoreBtn.setAttribute("data-page", currentPage + 1);

                    // Khôi phục trạng thái nút bấm ban đầu
                    btnText.textContent = originalText;
                    loadMoreBtn.style.pointerEvents = "auto";
                }
            })
            .catch(error => {
                console.error("Error loading more news:", error);
                btnText.textContent = originalText;
                loadMoreBtn.style.pointerEvents = "auto";
            });
    });
}