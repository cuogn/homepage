</div><!-- #content -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var header = document.getElementById('masthead');
        var headerTopRow = header.querySelector('.header-top-row');
        var lastScrollY = window.scrollY;

        function handleStickyHeader() {
            var currentScrollY = window.scrollY;

            if (currentScrollY > 50) {
                header.classList.add('sticky-header');
            } else {
                header.classList.remove('sticky-header');
            }

            lastScrollY = currentScrollY;
        }

        window.addEventListener('scroll', handleStickyHeader, { passive: true });

        handleStickyHeader();
    });
    </script>

    <footer id="colophon" class="site-footer">
        <div class="footer-container">
            
            <!-- Cột 1: Logo, Slogan và Icon Mạng xã hội -->
            <div class="footer-company-info">
                <!-- Sử dụng logo dạng ảnh của theme, nếu không có sẽ dùng text tạm -->
                <div class="footer-logo">
                    <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/logo-footer.png" alt="<?php bloginfo( 'name' ); ?> Footer Logo">
                </div>
                <p class="footer-slogan">
                    <?php 
                    if ( function_exists('pll_current_language') ) {
                        $current_lang = pll_current_language();
                        
                        if ( $current_lang == 'en' ) {
                            echo "Vietnam's leading supplier of RF generators and impedance matching networks";
                        } elseif ( $current_lang == 'ja' ) {
                            echo "ベトナムを代表する高周波電源およびRFマッチングボックスのサプライヤー";
                        } else {
                            // Mặc định tiếng Việt
                            echo "Nhà cung cấp máy nguồn cao tần và bộ phối hợp trở kháng RF hàng đầu Việt Nam";
                        }
                    } else {
                        echo "Nhà cung cấp máy nguồn cao tần và bộ phối hợp trở kháng RF hàng đầu Việt Nam";
                    }
                    ?>
                </p>
                <!-- Khối Icon Mạng xã hội -->
                <div class="footer-socials">
                    <a href="#" class="social-icon" target="_blank" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                    </a>
                    <a href="#" class="social-icon" target="_blank" aria-label="Youtube">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                    </a>
                </div>
            </div>

            <!-- Cột 2 & Cột 3: Các liên kết chính sách chia cột dạng Grid -->
            <div class="footer-links-grid">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-2',
                        'container'      => false, // Không dùng div bọc ngoài menu
                        'menu_class'     => 'adtec-footer-menu', // Class CSS riêng cho menu footer
                        'fallback_cb'    => '__return_false', // Nếu chưa tạo menu trong admin thì không hiển thị gì
                    )
                );
                ?>
            </div>
            
        </div>

        <!-- Bản quyền website dưới đáy -->
        <div class="site-info">
            <div class="site-info-container">
                <p>&copy; <?php echo date('Y'); ?> ADTEC Plasma Technology Vietnam. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?> <!-- Hàm bắt buộc để WordPress nạp script trước khi đóng thẻ body -->
</body>
</html>
