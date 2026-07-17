<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package adtec
 */

get_header();
?>
<?php if (function_exists('adv_display_breadcrumb')) { adv_display_breadcrumb(); } ?>
	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			if( class_exists( '\\Elementor\\Plugin' ) && \Elementor\Plugin::$instance->db->is_built_with_elementor( get_the_ID() ) ) {
				// Nếu trang được xây dựng bằng Elementor, hiển thị nội dung của Elementor
				the_content();
			} else {
				?>
				<header class="entry-header" style="max-width: 1200px; margin: 0 auto; padding: 20px 0;">
                	<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>
				<div class="entry-content" style="max-width: 1200px; margin: 0 auto;">
					<?php the_content(); ?>
				</div>
				<?php
			}

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_footer();
