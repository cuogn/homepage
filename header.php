<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Tách phần ngôn ngữ ra biến (Chỉ hiện các ngôn ngữ KHÁC ngôn ngữ hiện tại)
ob_start();
if ( function_exists( 'pll_the_languages' ) ) {
    $languages = pll_the_languages( array( 'raw' => 1 ) );
    if ( ! empty( $languages ) ) : ?>
        <div class="adtec-lang-flags-wrapper">
            <?php foreach ( $languages as $lang ) : 
                // Nếu là ngôn ngữ hiện tại -> Bỏ qua (không in ra cờ)
                if ( $lang['current_lang'] ) continue; 
            ?>
                <a href="<?php echo esc_url( $lang['url'] ); ?>" 
                   class="lang-flag-item" 
                   title="<?php echo esc_attr( $lang['name'] ); ?>">
                    <img src="<?php echo esc_url( $lang['flag'] ); ?>" alt="<?php echo esc_attr( $lang['name'] ); ?>" width="24" height="16" />
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif;
}
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

            <!-- Desktop: Hiện 3 lá cờ góc trên bên phải -->
            <div class="header-language-switch">
                <?php echo $lang_switch_html; ?>
            </div>

            <!-- Mobile: Nút hamburger -->
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
                            'container'      => false,
                            'menu_class'     => 'adtec-mega-menu',
                            'walker'         => new Adtec_Mega_Menu_Walker(),
                        )
                    );
                ?>
                <!-- Mobile: 3 lá cờ nằm trong menu xổ xuống -->
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
                if (window.innerWidth >= 1499) return;
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