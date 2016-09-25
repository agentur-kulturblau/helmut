<?php
/**
 * Template Name: Full Width
 * Description: A full-width site template
 *
 * @package Baylys
 * @since Baylys 1.0
 */

get_header(); ?>

	<div id="main-wrap">
		<div id="content" class="fullwidth">

		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

			<?php comments_template( '', true ); ?>

		<?php endwhile; // end of the loop. ?>

		</div><!-- end #content .fullwidth -->
	</div><!-- end #main-wrap -->

<?php get_footer(); ?>