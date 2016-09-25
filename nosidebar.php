<?php
/**
 * Template Name: Without Sidebar
 * Description: A page template without sidebar
 *
 * @package Baylys
 * @since Baylys 1.0
 */

get_header(); ?>

	<div id="main-wrap">

	<div id="content" class="nosidebar">

		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

			<?php comments_template( '', true ); ?>

		<?php endwhile; // end of the loop. ?>

		</div><!-- end #content .nosidebar -->
	</div><!-- end #main-wrap -->

<?php get_footer(); ?>