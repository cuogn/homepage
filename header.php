<doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?> <!-- wp + plugin nạp css, js, meta, ... -->
</head>
<body <?php body_class(); ?>> <!-- body_class() -> thêm class vào thẻ body -->
<?php wp_body_open(); ?> <!-- wp_body_open() -> hook để biết bắt đầu body -->

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
        <div class="header-language-switch">
            <?php
            if ( function_exists( 'pll_the_languages' ) ) {
                $languages = pll_the_languages( array( 'raw' => 1 ) );
                
                if ( ! empty( $languages ) ) {
                    $current_index = -1;
                    $lang_list = array_values($languages);
                    $total_langs = count($lang_list);

                    foreach ( $lang_list as $index => $lang ) {
                        if ( $lang['current_lang'] ) {
                            $current_index = $index;
                            break;
                        }
                    }

                    // Nếu là ngôn ngữ cuối cùng thì quay lại ngôn ngữ đầu tiên
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
            } else {
                // Fallback tĩnh
                ?>
                <span class="lang-label">ENG</span>
                <div class="lang-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                </div>
                <?php
            }
            ?>
        </div>

        </div>
        <div class="header-navigation-row">
            <nav id="site-navigation" class="main-navigation container-header">
            <!-- Khu vực hiển thị Menu chính -->
            <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-1',
                        'menu_id'        => 'primary-menu',
                        'container'       => false, // Loại bỏ thẻ div bao quanh menu
                        'menu_class'      => 'adtec-mega-menu', // Thêm class cho thẻ ul của menu
                        'walker'         => new Adtec_Mega_Menu_Walker(),
                    )
                );
                ?>
            </nav><!-- #site-navigation -->
        </div>
    </header><!-- #masthead -->

    <div id="content" class="site-content">