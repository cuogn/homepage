</div><!-- #content -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var header = document.getElementById('masthead');
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

<?php
/**
 * Helper: lấy giá trị footer theo ngôn ngữ hiện tại (Polylang-aware).
 */
function adtec_get_footer_lang_mod($base_key) {
    $lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';
    $val = get_theme_mod($base_key . '_' . $lang, '');
    if (empty($val)) {
        $val = get_theme_mod($base_key . '_vi', '');
    }
    return $val;
}

/**
 * Helper: lấy footer socials array từ theme mod.
 */
function adtec_get_footer_socials() {
    $raw = get_theme_mod('footer_socials', '');
    if (empty($raw)) return array();
    $decoded = json_decode(urldecode($raw), true);
    return is_array($decoded) ? $decoded : array();
}

/**
 * Helper: map platform → SVG icon.
 */
function adtec_get_social_icon($platform) {
    $icons = array(
        'facebook'  => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
        'youtube'   => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>',
        'linkedin'  => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
        'twitter'   => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4l11.733 16H20L8.267 4zM4 20l6.768-6.768m2.46-2.46L20 4"></path></svg>',
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
        'tiktok'    => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path></svg>',
        'zalo'      => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M8 11h8M8 14h5"></path></svg>',
    );
    return isset($icons[$platform]) ? $icons[$platform] : '';
}

// Lấy dữ liệu
$footer_logo    = get_theme_mod('footer_logo', get_template_directory_uri() . '/images/logo-footer.png');
$footer_slogan = adtec_get_footer_lang_mod('footer_slogan');
$footer_address = adtec_get_footer_lang_mod('footer_address');
$footer_phone  = get_theme_mod('footer_phone', '');
$footer_email  = get_theme_mod('footer_email', '');
$footer_hours  = adtec_get_footer_lang_mod('footer_working_hours');
$footer_copyright = adtec_get_footer_lang_mod('footer_copyright');
$footer_show_socials = get_theme_mod('footer_show_socials', 1);
$footer_socials = $footer_show_socials ? adtec_get_footer_socials() : array();

// Label liên hệ theo ngôn ngữ
$lang = function_exists('pll_current_language') ? pll_current_language() : 'vi';
$contact_labels = array(
    'vi' => 'Liên hệ',
    'en' => 'Contact',
    'ja' => '連絡先',
);
$contact_label = isset($contact_labels[$lang]) ? $contact_labels[$lang] : 'Liên hệ';
?>

    <footer id="colophon" class="site-footer">
        <div class="footer-container">

            <!-- Cột 1: Logo, Slogan, Mạng xã hội -->
            <div class="footer-company-info">
                <?php if ($footer_logo) : ?>
                <div class="footer-logo">
                    <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php bloginfo('name'); ?> Footer Logo">
                </div>
                <?php endif; ?>

                <?php if ($footer_slogan) : ?>
                <p class="footer-slogan"><?php echo nl2br(esc_html($footer_slogan)); ?></p>
                <?php endif; ?>

                <?php if (!empty($footer_socials)) : ?>
                <div class="footer-socials">
                    <?php foreach ($footer_socials as $item) : ?>
                    <a href="<?php echo esc_url($item['url'] ?? '#'); ?>"
                       class="social-icon"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="<?php echo esc_attr(ucfirst($item['platform'] ?? 'Social')); ?>">
                        <?php echo adtec_get_social_icon($item['platform'] ?? ''); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Cột 3: Menu liên kết (dynamic via menu-2) -->
            <div class="footer-links-grid">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-2',
                        'container'      => false,
                        'menu_class'     => 'adtec-footer-menu',
                        'fallback_cb'    => '__return_false',
                    )
                );
                ?>
            </div>

        </div>

        <!-- Bản quyền -->
        <div class="site-info">
            <div class="site-info-container">
                <?php if ($footer_copyright) : ?>
                <p><?php
                    $year = date('Y');
                    echo str_replace('{year}', $year, $footer_copyright);
                ?></p>
                <?php else : ?>
                <p>&copy; <?php echo date('Y'); ?> ADTEC Plasma Technology Vietnam. All rights reserved.</p>
                <?php endif; ?>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
