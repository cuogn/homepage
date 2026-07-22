<doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Tách phần ngôn ngữ ra biến để dùng lại được 2 nơi (desktop top-row & mobile menu)
ob_start();
if ( function_exists( 'pll_the_languages' ) ) {
    $languages = pll_the_languages( array( 'raw' => 1 ) );
    if ( ! empty( $languages ) ) {
        $current_index = -1;
        $lang_list = array_values($languages);
        $total_langs = count($lang_list);
        foreach ( $lang_list as $index => $lang ) {
            if ( $lang['current_lang'] ) { $current_index = $index; break; }
        }
        $next_index = ($current_index + 1) % $total_langs;
        $target_lang = $lang_list[$next_index];
        if ( $target_lang ) : ?>
            <a href="<?php echo esc_url( $target_lang['url'] ); ?>" class="lang-switch-link" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <span class="lang-label"><?php echo esc_html( strtoupper( $target_lang['slug'] ) ); ?></span>
                <div class="lang-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                </div>
            </a>
        <?php endif;
    }
} else { ?>
    <span class="lang-label">ENG</span>
    <div class="lang-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
    </div>
<?php }
$lang_switch_html = ob_get_clean();
?>

<div id="page" class="site">
    <header id="masthead" class="site-header">
        <div class="header-top-row container-header">
            <div class="site-branding">
                <?php if ( has_custom_logo() ) : ?>
                    <div class="site-logo"><?php the_custom_logo(); ?></div>
                <?php else : ?>
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <?php endif; ?>
            </div><!-- .site-branding -->

            <!-- Desktop: hiện ngôn ngữ -->
            <div class="header-language-switch">
                <?php echo $lang_switch_html; ?>
            </div>

            <!-- Mobile: hiện hamburger thay vào đúng vị trí này -->
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="menu-toggle-bar bar-1"></span>
                <span class="menu-toggle-bar bar-2"></span>
                <span class="menu-toggle-bar bar-3"></span>
                <span class="screen-reader-text">Menu</span>
            </button>
        </div>
        <div class="header-navigation-row">
            <nav id="site-navigation" class="main-navigation container-header">
                <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'menu-1',
                            'menu_id'        => 'primary-menu',
                            'container'       => false,
                            'menu_class'      => 'adtec-mega-menu',
                            'walker'         => new Adtec_Mega_Menu_Walker(),
                        )
                    );
                ?>
                <!-- Mobile: ngôn ngữ nằm trong menu xổ xuống -->
                <div class="mobile-lang-switch">
                    <?php echo $lang_switch_html; ?>
                </div>
            </nav><!-- #site-navigation -->
        </div>
    </header><!-- #masthead -->

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.querySelector('.menu-toggle');
        const nav = document.querySelector('.main-navigation');

        if (toggle && nav) {
            toggle.addEventListener('click', function () {
                const isOpen = nav.classList.toggle('toggled');
                toggle.classList.toggle('is-active', isOpen);
                toggle.setAttribute('aria-expanded', String(isOpen));
            });
        }

        document.querySelectorAll('.main-navigation li.menu-item-has-children > a').forEach(link => {
            link.addEventListener('click', function (e) {
                if (window.innerWidth >= 1085) return;
                e.preventDefault();

                const parentLi = this.parentElement;
                const isOpen = parentLi.classList.contains('submenu-open');

                // Đóng tất cả submenu đang mở trước khi mở cái mới
                document.querySelectorAll('.main-navigation li.submenu-open')
                    .forEach(li => li.classList.remove('submenu-open'));

                if (!isOpen) {
                    parentLi.classList.add('submenu-open');
                }
            });
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768 && nav) {
                nav.classList.remove('toggled');
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
                toggle.classList.remove('is-active');
                document.querySelectorAll('.main-navigation li.submenu-open')
                    .forEach(li => li.classList.remove('submenu-open'));
            }
        });
    });
    </script>

    <div id="content" class="site-content">