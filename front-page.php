<?php get_header(); ?>

    <main id="primary" class="site-main">
        <?php 
        while ( have_posts() ) : the_post();
        the_content(); // Hiển thị nội dung của trang chủ
        endwhile;
        ?>
    </main><!-- #primary -->
    <?php get_footer(); ?>